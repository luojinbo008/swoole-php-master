<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/21
 * Time: 16:37
 */

namespace FPHP\Foundation\Core;

use FPHP\Foundation\Contract\PooledObject;
use FPHP\Foundation\Contract\PooledObjectFactory;
use FPHP\Foundation\Exception\System\InvalidArgumentException;
use FPHP\Util\DesignPattern\Singleton;

class ObjectPool
{
    protected $initialNum = 1;
    protected $maxNum = 10;
    protected $factory = null;

    protected $pool = [];
    protected $usedObjectMap = null;

    use Singleton;

    private function __construct()
    {
        $this->initialNum = 1;
        $this->maxNum = 10;

        $this->pool = [];
        $this->usedObjectMap = new \SplObjectStorage();
    }

    public function get()
    {

    }

    public function release(PooledObject $object)
    {

    }

    public function getUsedObjects()
    {

    }

    public function getFreeObjects()
    {

    }

    public function init(PooledObjectFactory $factory, $maxNum, $initalNum = 0)
    {
        $this->factory = $factory;
        $this->setMaxNum($maxNum);
        $this->setInitalNum($initalNum);
        $this->initPool();

        return $this;
    }

    protected function setInitalNum($num)
    {
        if (!is_int($num)) {
            throw new InvalidArgumentException('invalid initialNum (not int) for ObjectPool');
        }

        if ($num < 0) {
            throw new InvalidArgumentException('invalid initialNum (less than 0) for ObjectPool');
        }

        $this->initialNum = $num;
    }

    protected function setMaxNum($num)
    {
        if (!is_int($num)) {
            throw new InvalidArgumentException('invalid maxNum (not int) for ObjectPool');
        }

        if ($num < 1) {
            throw new InvalidArgumentException('invalid maxNum (less than 1) for ObjectPool');
        }

        $this->maxNum = $num;
    }

    protected function initPool()
    {
        if ($this->initialNum < 1) {
            return false;
        }

        for ($i = 0; $i < $this->initialNum; $i++) {
            $this->pool[] = $this->factory->create();
        }
    }
}