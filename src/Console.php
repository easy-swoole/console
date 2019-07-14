<?php


namespace EasySwoole\Console;


use Swoole\Table;

class Console
{
    protected $config;
    /** @var \swoole_server */
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
        if($server instanceof \swoole_server){
            $this->server = $server;
            $server = $server->addlistener($this->config->getListenAddress(),$this->config->getListenPort(),SWOOLE_TCP);
        }
        $server->set(array(
            "open_eof_split" => true,
            'package_eof' => "\r\n",
        ));
        $server->on('receive', function (\swoole_server $server, $fd, $reactor_id, $data){
            $data = trim($data);
            $arr = explode(" ",$data);
            $action = array_shift($arr);
            $args = $arr;
        });
        $server->on('connect', function (\swoole_server $server, int $fd, int $reactorId)use ($dispatcher) {
            $hello = 'Welcome to ' . $this->config->getName();
            $this->send($fd,$hello);
        });
    }

    public function send(string $msg,int $fd)
    {
        if($this->server->getClientInfo($fd)){
            return $this->server->send($fd,$msg."\r\n");
        }
        return false;
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