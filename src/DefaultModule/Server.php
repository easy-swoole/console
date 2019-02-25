<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2019-02-25
 * Time: 16:16
 */

namespace EasySwoole\Console\DefaultModule;


use EasySwoole\Console\Console;
use EasySwoole\Console\ModuleInterface;
use EasySwoole\Socket\Bean\Caller;
use EasySwoole\Socket\Bean\Response;
use EasySwoole\Utility\ArrayToTextTable;


class Server implements ModuleInterface
{
    function moduleName(): string
    {
        // TODO: Implement moduleName() method.
        return 'server';
    }

    public function exec(Caller $caller, Response $response)
    {
        $args = $caller->getArgs();
        if (empty($args)) {
            $this->status($caller, $response);
        } else {
            $actionName = array_shift($args);
            $caller->setArgs($args);
            switch ($actionName) {
                case 'status': $this->status($caller, $response); break;
                case 'hostIp': $this->hostIp($caller, $response); break;
                case 'reload': $this->reload($caller, $response); break;
                case 'shutdown': $this->shutdown($caller, $response); break;
                case 'close': $this->close($caller, $response); break;
                case 'clientInfo': $this->clientInfo($caller, $response); break;
                default :
                    $response->setMessage("action {$actionName} not supported!");
            }
        }
    }

    public function help(Caller $caller, Response $response)
    {
        $help = <<<HELP
进行服务端的管理

用法: 命令 [命令参数]

server status                    | 查看服务当前的状态
server hostIp                    | 显示服务当前的IP地址
server reload                    | 重载服务端
server shutdown                  | 关闭服务端
server clientInfo [fd]           | 查看某个链接的信息
HELP;
        $response->setMessage($help);
    }

    private function status(Caller $caller, Response $response)
    {
        $stats = Console::getInstance()->getSwooleServer()->stats();
        $message = new ArrayToTextTable([ $stats ]);
        $response->setMessage($message);
    }

    private function hostIp(Caller $caller, Response $response)
    {
        $list = swoole_get_local_ip();
        $message = new ArrayToTextTable([ $list ]);
        $response->setMessage($message);
    }

    private function reload(Caller $caller, Response $response)
    {
        Console::getInstance()->getSwooleServer()->reload();
        $response->setMessage('reload at' . time());
    }

    private function shutdown(Caller $caller, Response $response)
    {
        Console::getInstance()->getSwooleServer()->shutdown();
        $response->setMessage('shutdown at' . time());
    }

    private function clientInfo(Caller $caller, Response $response)
    {
        $args = $caller->getArgs();
        $fd = array_shift($args);
        if (!empty($fd)) {
            $info = Console::getInstance()->getSwooleServer()->getClientInfo($fd);
            $info = new ArrayToTextTable([ $info ]);
        } else {
            $info = 'missing parameter usage: server clientInfo fd';
        }
        $response->setMessage($info);
    }
}