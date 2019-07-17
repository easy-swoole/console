<?php


namespace EasySwoole\Console;



class Help implements ModuleInterface
{
    public function moduleName(): string
    {
        return 'help';
    }

    public function exec(array $arg, int $fd, Console $console)
    {
        // TODO: Implement exec() method.
    }

    public function help(array $arg, int $fd, Console $console)
    {
        // TODO: Implement help() method.
    }

}