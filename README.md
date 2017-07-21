# cache
数据缓存

## 配置
### File 缓存
```
include './vendor/autoload.php';

$config = array(
    'default' => array(
        'type' => 'File',
        'path' => '/var/www/test/cache/',
        'prefix' => '',
    ),
    'redis' => array(
        'type' => 'Redis',
    	'host' => '127.0.0.1',
    	'port' => '6379',
    	'password' => '123456',
    	'prefix' => 'web.',
    ),
);

$cache = new HuTong\Cache\Storage($config);

$val = $cache->store()->increment('getVal');
var_dump($val);
$val = $cache->store('redis')->increment('getVal');
var_dump($val);

输出：
int(1)
int(1)
```

```
$val = $cache->store()->set('getVal','33');

$val = $cache->store()->get('getVal');

$val = $cache->store()->del('getVal');

$val = $cache->store()->increment('getVal');

$val = $cache->store()->decrement('getVal');

$val = $cache->store()->flush();
```

# 学习交流群
630730920