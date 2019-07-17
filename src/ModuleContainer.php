<?php


namespace EasySwoole\Console;


class ModuleContainer
{
    private $container = [];

    public function set(ModuleInterface $command)
    {
        $this->container[strtolower($command->moduleName())] = $command;
    }
    function get($key): ?ModuleInterface
    {
        $key = strtolower($key);
        if (isset($this->container[$key])) {
            return $this->container[$key];
        } else {
            return null;
        }
    }
    function getCommandList()
    {
        return array_keys($this->container);
    }
}