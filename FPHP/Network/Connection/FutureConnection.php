<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/21
 * Time: 15:59
 */

namespace FPHP\Network\Connection;

use FPHP\Foundation\Contract\Async;
use FPHP\Foundation\Coroutine\Task;
use FPHP\Foundation\Exception\System\InvalidArgumentException;
use FPHP\Foundation\Core\Event;

class FutureConnection implements Async
{
    private $connKey = '';
    private $timeout = 0;
    private $taskCallback = null;
    private $connectionManager = null;

    public function __construct($connectionManager, $connKey, $timeout = 0)
    {
        if (!is_int($timeout)) {
            throw new InvalidArgumentException('invalid timeout for Future[Connection]');
        }
        $this->connectionManager = $connectionManager;
        $this->connKey = $connKey;
        $this->timeout = $timeout;
        $this->init();
    }

    public function execute(callable $callback)
    {
        $this->taskCallback = $callback;
    }

    private function init()
    {
        $evtName = $this->connKey . '_free';
        Event::once($evtName, [$this, 'getConnection']);
    }

    public function getConnection()
    {
        Task::execute($this->doGeting());
    }

    public function doGeting()
    {
        $conn = (yield $this->connectionManager->get($this->connKey));
        call_user_func($this->taskCallback, $conn);
    }
}