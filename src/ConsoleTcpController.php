<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2019-02-25
 * Time: 15:55
 */

namespace EasySwoole\Console;


use EasySwoole\Socket\AbstractInterface\Controller;

class ConsoleTcpController extends Controller
{
    protected function onRequest(?string $actionName): bool
    {
        $events = OnRequestEvent::getInstance()->list();
        foreach ($events as $event){
            if(call_user_func($event,$this->caller()) === false){
                return false;
            }
        }
        return true;
    }

    protected function actionNotFound(?string $actionName)
    {
        ModuleContainer::getInstance()->hook($actionName, $this->caller(), $this->response());
    }
}