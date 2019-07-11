<?php


namespace EasySwoole\Console;


use Swoole\Table;

class Console
{
    protected $config;
    protected $server;
    protected $table;

    function __construct(Config $config)
    {
        $this->config = $config;
        $this->table = new Table(1024);
        $this->table->column('fd',Table::TYPE_INT,8);
        $this->table->column('isAuth',Table::TYPE_INT,1);
        $this->table->create();
    }

    function attachToServer(\swoole_server $server):Console
    {
        $this->server = $server;
        return $this;
    }


    /**
     * @param \swoole_server|\swoole_server_port $server
     */
    function protocolSet($server)
    {

    }

    public function send(string $msg,int $fd)
    {

    }

    public function allFd():array
    {
        $ret = [];
        foreach ($this->table as $item){
            $ret[] = $item;
        }
        return $ret;
    }
}