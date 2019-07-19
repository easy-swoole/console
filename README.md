## 安装
```
composer require easyswoole/console
```
## Server
```
use EasySwoole\Console\Console;
use EasySwoole\Console\ModuleInterface;
$http = new swoole_http_server("127.0.0.1", 9501);

$http->on("request", function ($request, $response) {
    $response->header("Content-Type", "text/plain");
    $response->end("Hello World\n");
});

/*
 * 开一个tcp端口给console 用
 */
$tcp = $http->addlistener('0.0.0.0',9600,SWOOLE_TCP);
/*
 * 实例化一个控制台，设置密码为123456
 */
$console = new Console('myConsole','123456');

/*
 * 定义自己的一个命令
 */

class Test implements ModuleInterface
{

    public function moduleName(): string
    {
        return 'test';
    }

    public function exec(array $arg, int $fd, Console $console)
    {
       return 'this is test exec';
    }

    public function help(array $arg, int $fd, Console $console)
    {
        return 'this is test help';
    }
}

/*
 * 命令注册
 */

$console->moduleContainer()->set(new Test());
/*
 * 依附给server
 */
$console->protocolSet($tcp)->attachToServer($http);

$http->start();

```

## Client 
```
telnet 127.0.0.1 9600
```

### 鉴权

```
auth {PASSWORD}
```

### 执行命令

```
{MODULE} {ARG1} {ARG2}
```