<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/7
 * Time: 17:34
 */

namespace FPHP\Store\Database\Sql;

use FPHP\Foundation\Core\Path;
use FPHP\Util\DesignPattern\Singleton;
use FPHP\Foundation\Core\ConfigLoader;
use FPHP\Store\Database\Sql\Exception\SqlTableException;

class Table
{
    use Singleton;
    private $tables = [];

    public function getDatabase($tableName)
    {
        if (!isset($this->tables[$tableName])) {
            $this->setTables();
            if (!isset($this->tables[$tableName])) {
                throw new SqlTableException('无法获取数' . $tableName . '表所在的数据库配置');
            }
        }
        return $this->tables[$tableName];
    }

    public function init()
    {
        $this->setTables();
    }

    private function setTables()
    {
        if ([] == $this->tables) {
            $tables = ConfigLoader::getInstance()->loadDistinguishBetweenFolderAndFile(Path::getTablePath());
            if (null == $tables || [] == $tables) {
                return;
            }
            foreach ($tables as $table) {
                if (null == $table || [] == $table) {
                    continue;
                }
                $parseTable = $this->parseTable($table);
                if ([] != $parseTable) {
                    $this->tables = array_merge($this->tables, $parseTable);
                }
            }
        }
        return;
    }

    private function parseTable($table)
    {
        $result = [];
        foreach ($table as $db => $tableList) {
            foreach ($tableList as $tableName) {
                $result[$tableName] = $db;
            }
        }
        return $result;
    }

}
