<?php


namespace EasySwoole\Console;


class Console
{
    protected $config;
    function __construct(Config $config)
    {
        $this->config = $config;
    }

    function attachToServer(\swoole_server $server)
    {
//        $config = new Un
    }
}