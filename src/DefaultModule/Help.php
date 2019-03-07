<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2019-02-25
 * Time: 16:15
 */

namespace EasySwoole\Console\DefaultModule;


use EasySwoole\Console\ConsoleModuleContainer;
use EasySwoole\Console\ModuleInterface;
use EasySwoole\Socket\Bean\Caller;
use EasySwoole\Socket\Bean\Response;

class Help implements ModuleInterface
{
    function moduleName(): string
    {
        // TODO: Implement moduleName() method.
        return 'help';
    }

    public function exec(Caller $caller, Response $response)
    {
        $args = $caller->getArgs();
        if (!isset($args[0])) {
            $this->help($caller, $response);
        } else {
            $actionName = $args[0];
            $call = ConsoleModuleContainer::getInstance()->get($actionName);
            if ($call instanceof ModuleInterface) {
                $call->help($caller, $response);
            } else {
                $response->setMessage("no help message for command {$actionName} was found.");
            }
        }
    }

    public function help(Caller $caller, Response $response)
    {
        $allCommand = implode(PHP_EOL, ConsoleModuleContainer::getInstance()->getCommandList());
        $help = <<<HELP

欢迎使用EASYSWOOLE远程控制台!
用法: 命令 [命令参数]

请使用 help [命令名称] 获取某个命令的使用帮助，当前已注册的命令:

{$allCommand}

HELP;
        $response->setMessage($help);
    }
}