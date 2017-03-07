<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/3/7
 * Time: 17:46
 */

namespace FPHP\Store\Database\Sql;

use FPHP\Util\DesignPattern\Singleton;
use FPHP\Foundation\Core\Path;
use FPHP\Foundation\Core\ConfigLoader;

class SqlMapInit
{
    use Singleton;

    public function init()
    {
        $sqlPath = Path::getSqlPath();
        if (!is_dir($sqlPath)) {
            return false;
        }
        $sqlMaps = ConfigLoader::getInstance()->loadDistinguishBetweenFolderAndFile($sqlPath);
        if (null == $sqlMaps || [] == $sqlMaps) {
            return false;
        }
        foreach ($sqlMaps as $key => $sqlMap) {
            $sqlMaps[$key] = (new SqlParser())->setSqlMap($sqlMap)->parse()->getSqlMap();
        }
        SqlMap::getInstance()->setSqlMaps($sqlMaps);
        return true;
    }
}