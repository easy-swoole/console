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
    /**
     * 控制器本身不再处理任何实体action
     * 全部转发给对应注册的命令处理器进行处理
     * @param null|string $actionName
     * @author: eValor < master@evalor.cn >
     */
    protected function actionNotFound(?string $actionName)
    {
        ModuleContainer::getInstance()->hook($actionName, $this->caller(), $this->response());
    }
}