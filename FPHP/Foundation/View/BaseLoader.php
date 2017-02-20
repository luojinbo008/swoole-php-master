<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/20
 * Time: 11:07
 */

namespace FPHP\Foundation\View;

use FPHP\Foundation\Core\Config;
use FPHP\Foundation\Coroutine\Event;

class BaseLoader
{
    public $blockResQueue = [];
    public $noBlockResQueue = [];
    public $curBlock = '';
    public $event = null;
    public $query_path;

    public function __construct(Event $event)
    {
        $that = $this;
        $this->event = $event;
        $this->event->bind('start_block', function($args) use($that) {
            $that->setCurrentBlock($args);
        });
        $this->event->bind('end_block', function ($args) use($that) {
            $this->setCurrentEndBlock($args);
        });
    }

    public function setCurrentBlock($blockName)
    {
        if (isset($this->blockResQueue[$blockName])) {
            $this->blockResQueue[$blockName] = [];
        }
        $this->curBlock = $blockName;
    }

    public function setCurrentEndBlock($blockName)
    {
        if ($blockName === $this->curBlock) {
            $this->curBlock = '';
        }
    }

    public function getCdnType()
    {
        $cdnMap = Config::get('cdn_whitelist');
        $pageKey = (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $this->query_path;
        if (isset($cdnMap[$pageKey])) {
            return 'new_cdn_static';
        } else {
            return 'up_cdn_static';
        }
    }
}