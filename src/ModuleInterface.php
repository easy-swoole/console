<?php


namespace EasySwoole\Console;



interface ModuleInterface
{
    public function moduleName():string;
    public function exec(array $arg,int $fd,Console $console);
    public function help(array $arg,int $fd,Console $console);
}