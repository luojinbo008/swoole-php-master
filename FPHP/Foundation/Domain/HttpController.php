<?php

/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2017/2/20
 * Time: 11:02
 */
namespace FPHP\Foundation\Domain;

use FPHP\Network\Http\Response\RedirectResponse;
use FPHP\Network\Http\Response\Response;
use FPHP\Network\Http\Response\JsonResponse;
use FPHP\Contract\Network\Request;
use FPHP\Util\DesignPattern\Context;
use FPHP\Foundation\View\JsVar;
use FPHP\Foundation\View\View;

class HttpController extends Controller
{
    protected $viewData = [];
    protected $jsVar = null;

    public function __construct(Request $request, Context $context)
    {
        parent::__construct($request, $context);
        $this->jsVar = new JsVar();
    }

    public function setJsVar($key, $value)
    {
        $this->jsVar->setBusiness($key, $value);
    }

    public function setShare($cover, $title, $desc)
    {
        $this->jsVar->setShare('cover', trim($cover));
        $this->jsVar->setShare('title', trim($title));
        $this->jsVar->setShare('desc', trim($desc));
    }

    public function setShareData(array $shareItems){
        foreach($shareItems as $key=>$item){
            $this->jsVar->setShare($key, $item);
        }
    }

    public function setDomains(array $domains)
    {
        $this->jsVar->setDomain($domains);
    }

    public function getJsVars()
    {
        return $this->jsVar->get();
    }

    public function output($content)
    {
        return new Response($content);
    }

    public function display($tpl)
    {
        $this->viewData['_js_var'] = $this->getJsVars();
        $content = View::display($tpl, $this->viewData);
        return $this->output($content);
    }

    public function render($tpl)
    {
        $this->viewData['_js_var'] = $this->getJsVars();
        return View::display($tpl, $this->viewData);
    }

    public function assign($key, $value)
    {
        $this->viewData[$key] = $value;
    }

    public function r($code, $msg, $data)
    {
        $data = [
            'code' => $code,
            'msg'  => $msg,
            'data' => $data,
        ];
        return new JsonResponse($data);
    }

    public function redirect($url, $code = 302)
    {
        return RedirectResponse::create($url, $code);
    }

    protected function dispatch($action, $mode = 0)
    {
        switch ($mode) {
        }
    }

}