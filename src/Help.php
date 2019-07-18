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
        if (!isset($args[0])) {
            return $this->help($arg, $fd,$console);
        } else {
            $actionName = $arg[0];
            $call = $console->moduleContainer()->get($actionName);
            if ($call instanceof ModuleInterface) {
                array_shift($arg);
                return $call->help($arg,$fd, $console);
            } else {
                return "no help message for command {$actionName} was found.";
            }
        }
    }

    public function help(array $arg, int $fd, Console $console)
    {
        $allCommand = implode(PHP_EOL, $console->moduleContainer()->getCommandList());
        $help = <<<HELP
Welcome to EasySwoole remote console
Usage: command [action] [...arg] 
For help: help [command] [...arg]
Current command list:
{$allCommand}
HELP;
        return $help;

    }

}