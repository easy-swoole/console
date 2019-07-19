<?php


namespace EasySwoole\Console;


use Swoole\Table;

class Console
{
    protected $serverName;
    protected $authKey;
    /** @var \swoole_server */
    protected $server;
    protected $table;
    protected $moduleContainer;

    function __construct(string $serverName = 'easyswoole',string $authKey = 'easyswoole')
    {
        $this->serverName = $serverName;
        $this->authKey = $authKey;
        $this->table = new Table(1024);
        $this->table->column('fd',Table::TYPE_INT,8);
        $this->table->column('isAuth',Table::TYPE_INT,1);
        $this->table->create();
        $this->moduleContainer = new ModuleContainer();
        $this->moduleContainer->set(new Help());
    }

    function attachToServer(\swoole_server $server):Console
    {
        $this->server = $server;
        return $this;
    }

    function moduleContainer():ModuleContainer
    {
        return $this->moduleContainer;
    }


    /**
     * @param \swoole_server|\swoole_server_port $server
     * @return Console
     */
    function protocolSet($server):Console
    {
        $server->set(array(
            "open_eof_split" => false,
            'open_eof_check'=>true,
            'package_eof' => "\r\n",
        ));

        $server->on('receive', function (\swoole_server $server, $fd, $reactor_id, $data){
            $data = trim($data);
            /*
             * ctl+c
             */
            if('fff4fffd06' === bin2hex(substr($data,0,5))){
                $this->send($fd,'Bye Bye !!!');
                $this->server->close($fd);
                return;
            }
            $data = trim($data);
            $arr = explode(" ",$data);
            $action = array_shift($arr);
            $args = $arr;
            $call = null;
            switch ($action){
                case 'help':{
                    $call = $this->moduleContainer->get($action);
                    try{
                        $msg = $call->exec($args,$fd,$this);
                    }catch (\Throwable $throwable){
                        $msg = "Error: {$throwable->getMessage()} at file {$throwable->getFile()} line {$throwable->getLine()}";
                    }
                    $this->send($fd,$msg);
                    return;
                }
                case 'auth':{
                    $password = array_shift($args);
                    if($password == $this->authKey){
                        $this->table->set($fd,[
                            'fd'=>$fd,
                            'isAuth'=>1
                        ]);
                        $msg = 'auth success';
                    }else{
                        $msg = 'auth fail';
                    }
                    $this->send($fd,$msg);
                    return;
                }
                case 'exit':{
                    $this->send($fd,'Bye Bye !!!');
                    $this->server->close($fd);
                    return;
                }
                default:{
                    $call = $this->moduleContainer->get($action);
                    if(!$this->table->get($fd)){
                        $msg = 'please auth !!!';
                        $this->send($fd,$msg);
                        return;
                    }
                    if($call){
                        try{
                            $msg = $call->exec($args,$fd,$this);
                        }catch (\Throwable $throwable){
                            $msg = "Error: {$throwable->getMessage()} at file {$throwable->getFile()} line {$throwable->getLine()}";
                        }
                    }else{
                        $msg = "{$action} not a register command";
                    }
                    $this->send($fd,$msg);
                    return;
                }
            }
        });

        $server->on('connect', function (\swoole_server $server, int $fd, int $reactorId){
            $hello = "Welcome to {$this->serverName} , please auth !!!";
            $this->send($fd,$hello);
        });

        $server->on('close',function ($server, int $fd){
            $this->table->del($fd);
        });

        return $this;
    }

    public function send(int $fd,string $msg)
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