<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/21
 * Time: 15:05
 */
/*
 *    Copyright 2012-2016 Youzan, Inc.
 *
 *    Licensed under the Apache License, Version 2.0 (the "License");
 *    you may not use this file except in compliance with the License.
 *    You may obtain a copy of the License at
 *
 *        http://www.apache.org/licenses/LICENSE-2.0
 *
 *    Unless required by applicable law or agreed to in writing, software
 *    distributed under the License is distributed on an "AS IS" BASIS,
 *    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *    See the License for the specific language governing permissions and
 *    limitations under the License.
 */
namespace FPHP\Store\NoSQL\Redis;

/**
 * Class RedisClient
 * @package FPHP\Store\NoSQL\Redis
 */
class RedisClient
{
    public $host;
    public $port;
    public $debug = false;

    /**
     * 空闲连接池
     * @var array
     */
    public $pool = [];

    public function __construct($host = 'localhost', $port = 6379, $timeout = 0.1)
    {
        $this->host = $host;
        $this->port = $port;
    }

    public function trace($msg)
    {
        echo "-----------------------------------------\n".trim($msg)."\n-----------------------------------------\n";
    }

    public function stats()
    {
        $stats = "Idle connection: ".count($this->pool)."<br />\n";
        return $stats;
    }

    public function hmset($key, array $value, $callback)
    {
        $lines[] = "hmset";
        $lines[] = $key;
        foreach($value as $k => $v) {
            $lines[] = $k;
            $lines[] = $v;
        }
        $connection = $this->getConnection();
        $cmd = $this->parseRequest($lines);
        $connection->command($cmd, $callback);
    }

    public function hmget($key, array $value, $callback)
    {
        $connection = $this->getConnection();
        $connection->fields = $value;

        array_unshift($value, "hmget", $key);
        $cmd = $this->parseRequest($value);
        $connection->command($cmd, $callback);
    }

    public function parseRequest($array)
    {
        $cmd = '*' . count($array) . "\r\n";
        foreach ($array as $item) {
            $cmd .= '$' . strlen($item) . "\r\n" . $item . "\r\n";
        }
        return $cmd;
    }

    public function __call($method, array $args)
    {
        $callback = array_pop($args);
        array_unshift($args, $method);
        $cmd = $this->parseRequest($args);
        $connection = $this->getConnection();
        $connection->command($cmd, $callback);
    }

    /**
     * 从连接池中取出一个连接资源
     * @return RedisConnection
     */
    protected function getConnection()
    {
        if (count($this->pool) > 0) {
            /**
             * @var $connection RedisConnection
             */
            foreach($this->pool as $k => $connection) {
                unset($this->pool[$k]);
                break;
            }
            return $connection;
        } else {
            return new RedisConnection($this);
        }
    }

    public function lockConnection($id)
    {
        unset($this->pool[$id]);
    }

    public function freeConnection($id, RedisConnection $connection)
    {
        $this->pool[$id] = $connection;
    }
}

class RedisConnection
{
    /**
     * @var RedisClient
     */
    protected $redis;
    protected $buffer = '';

    /**
     * @var \swoole_client
     */
    protected $client;
    protected $callback;

    /**
     * 等待发送的数据
     */
    protected $wait_send = false;
    protected $wait_recv = false;
    protected $multi_line = false;
    public $fields;

    public function __construct(RedisClient $redis)
    {
        $client = new \swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);
        $client->on('connect', array($this, 'onConnect'));
        $client->on('error', array($this, 'onError'));
        $client->on('receive', array($this, 'onReceive'));
        $client->on('close', array($this, 'onClose'));
        $client->connect($redis->host, $redis->port);
        $this->client = $client;
        $redis->pool[$client->sock] = $this;
        $this->redis = $redis;
    }

    /**
     * 清理数据
     */
    public function clean()
    {
        $this->buffer = '';
        $this->callback;
        $this->wait_send = false;
        $this->wait_recv = false;
        $this->multi_line = false;
        $this->fields = [];
    }

    /**
     * 执行redis指令
     * @param $cmd
     * @param $callback
     */
    public function command($cmd, $callback)
    {
        if ($this->client->isConnected()) { // 如果已经连接，直接发送数据
            $this->client->send($cmd);
        } else {                            // 未连接，等待连接成功后发送数据
            $this->wait_send = $cmd;
        }

        $this->callback = $callback;

        // 从空闲连接池中移除，避免被其他任务使用
        $this->redis->lockConnection($this->client->sock);
    }

    /**
     * @param \swoole_client $client
     */
    public function onConnect(\swoole_client $client)
    {
        if ($this->wait_send) {
            $client->send($this->wait_send);
            $this->wait_send = '';
        }
    }

    public function onError()
    {
        echo "连接redis服务器失败\n";
    }

    /**
     * @param $cli
     * @param $data
     */
    public function onReceive($cli, $data)
    {
        $success = true;
        if ($this->redis->debug) {
            $this->redis->trace($data);
        }
        if ($this->wait_recv) {
            $this->buffer .= $data;
            if ($this->multi_line) {
                $require_line_n = $this->multi_line * 2 + 1 - substr_count($data, "$-1\r\n");
                if (substr_count($this->buffer, "\r\n") - 1 == $require_line_n) {
                    goto parse_multi_line;
                } else {
                    return;
                }
            } else {

                // 就绪
                if (strlen($this->buffer) >= $this->wait_recv) {
                    $result = rtrim($this->buffer, "\r\n");
                    goto ready;
                } else {
                    return;
                }
            }
        }

        $lines = explode("\r\n", $data, 2);
        $type = $lines[0][0];
        if ($type == '-') {
            $success = false;
            $result = substr($lines[0], 1);
        } elseif ($type == '+') {
            $result = substr($lines[0], 1);;
        } elseif ($type == '$') { // 只有一行数据
            $len = intval(substr($lines[0], 1));
            if ($len > strlen($lines[1])) {
                $this->wait_recv = $len;
                $this->buffer = $lines[1];
                $this->multi_line = false;
                return;
            }
            $result = $lines[1];
        } elseif ($type == '*') { // 多行数据
            parse_multi_line:
            $data_line_num = intval(substr($lines[0], 1));
            $data_lines = explode("\r\n", $lines[1]);
            $require_line_n = $data_line_num * 2 - substr_count($data, "$-1\r\n");
            $lines_n = count($data_lines) - 1;

            if ($lines_n == $require_line_n) {
                $result = [];
                $key_n = 0;
                for ($i = 0; $i < $lines_n; $i++) {

                    // not exists
                    if (substr($data_lines[$i], 1, 2) === '-1') {
                        $value = false;
                    } else {
                        $value = $data_lines[$i + 1];
                        $i++;
                    }
                    if ($this->fields) {
                        $result[$this->fields[$key_n]] = $value;
                    } else {
                        $result[] = $value;
                    }
                    $key_n  ++;
                }
                goto ready;
            } else { // 数据不足，需要缓存
                $this->multi_line = $data_line_num;
                $this->buffer = $lines[1];
                $this->wait_recv = true;
                return;
            }
        } elseif ($type == ':') {
            $result = intval(substr($lines[0], 1));
            goto ready;
        } else {
            echo "Response is not a redis result. String:\n$data\n";
            return;
        }

        ready:
        $this->clean();
        $this->redis->freeConnection($cli->sock, $this);
        call_user_func($this->callback, $result, $success);
    }

    public function onClose(\swoole_client $cli)
    {
        if ($this->wait_send) {
            $this->redis->freeConnection($cli->sock, $this);
            call_user_func($this->callback, "timeout", false);
        }
    }
}