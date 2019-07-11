<?php


namespace EasySwoole\Console;


use EasySwoole\Socket\Bean\Caller;
use EasySwoole\Socket\Bean\Response;

interface ModuleInterface
{
    public function moduleName():string;
    public function exec(Caller $caller,Response $response);
    public function help(Caller $caller,Response $response);
}