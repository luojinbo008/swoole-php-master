<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/15
 * Time: 14:57
 */

namespace FPHP\Foundation\Coroutine;

class EventChain
{
    private $beforeMap = [];
    private $afterMap = [];
    private $event = null;

    public function __construct(Event $event)
    {
        $this->beforeMap = [];
        $this->afterMap = [];
        $this->event = $event;
    }

    /**
     * @param $beforeEvt
     * @param $afterEvt
     */
    public function breakChain($beforeEvt, $afterEvt)
    {
        $this->crackAfterChain($beforeEvt, $afterEvt);
        $this->crackBeforeChain($beforeEvt, $afterEvt);
    }

    /**
     * @param $beforeEvt
     * @param $afterEvt
     * @return bool
     */
    public function after($beforeEvt, $afterEvt)
    {
        if (!isset($this->afterMap[$beforeEvt])) {
            $this->afterMap[$beforeEvt] = [$afterEvt => 1];
            return true;
        }
        $this->afterMap[$beforeEvt][$afterEvt] = 1;
    }

    /**
     * @param $beforeEvt
     * @param $afterEvt
     * @return bool
     */
    public function before($beforeEvt, $afterEvt)
    {
        $this->after($beforeEvt, $afterEvt);
        if (!isset($this->beforeMap[$afterEvt])) {
            $this->beforeMap[$afterEvt] = [$beforeEvt => 0];
            return true;
        }
        $this->beforeMap[$afterEvt][$beforeEvt] = 0;
    }

    /**
     * @param $evtName
     * @return bool
     */
    public function fireEventChain($evtName)
    {
        if (!isset($this->afterMap[$evtName]) || !$this->afterMap[$evtName]) {
            return false;
        }
        foreach ($this->afterMap[$evtName] as $afterEvt => $count) {
            $this->fireAfterEvent($evtName, $afterEvt);
        }
        return true;
    }

    /**
     * @param $beforeEvt
     * @param $afterEvt
     * @return bool
     */
    private function fireAfterEvent($beforeEvt, $afterEvt)
    {
        $this->fireBeforeEvent($beforeEvt, $afterEvt);
        if (true !== $this->isBeforeEventFired($afterEvt)) {
            return false;
        }
        $this->event->fire($afterEvt);
        $this->clearBeforeEventBind($afterEvt);
    }

    /**
     * @param $beforeEvt
     * @param $afterEvt
     * @return bool
     */
    private function fireBeforeEvent($beforeEvt, $afterEvt)
    {
        if (!isset($this->beforeMap[$afterEvt])) {
            return false;
        }
        if (!isset($this->beforeMap[$afterEvt][$beforeEvt])) {
            return false;
        }
        $this->beforeMap[$afterEvt][$beforeEvt]++;
    }

    /**
     * @param $afterEvt
     * @return bool
     */
    private function clearBeforeEventBind($afterEvt)
    {
        if (!isset($this->beforeMap[$afterEvt])) {
            return false;
        }
        $decrease = function (&$v) {
            return $v--;
        };
        array_walk($this->beforeMap[$afterEvt], $decrease);
    }

    /**
     * @param $afterEvt
     * @return bool
     */
    private function isBeforeEventFired($afterEvt)
    {
        if (!isset($this->beforeMap[$afterEvt])) {
            return true;
        }
        foreach ($this->beforeMap[$afterEvt] as $count) {
            if ($count < 1) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param $beforeEvt
     * @param $afterEvt
     * @return bool
     */
    private function crackAfterChain($beforeEvt, $afterEvt)
    {
        if (!isset($this->afterMap[$beforeEvt])) {
            return false;
        }
        if (!isset($this->afterMap[$beforeEvt][$afterEvt])) {
            return false;
        }
        unset($this->afterMap[$beforeEvt][$afterEvt]);
        return true;
    }

    /**
     * @param $beforeEvt
     * @param $afterEvt
     * @return bool
     */
    private function crackBeforeChain($beforeEvt, $afterEvt)
    {
        if (!isset($this->beforeMap[$afterEvt])) {
            return false;
        }
        if (!isset($this->beforeMap[$afterEvt][$beforeEvt])) {
            return false;
        }
        unset($this->beforeMap[$afterEvt][$beforeEvt]);
        return true;
    }
}