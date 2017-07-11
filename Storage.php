<?php
namespace Hutong\Cache;

/**
 * @desc 数据存储
 */
class Storage
{
    private $config;
    private $instance;

    public function __construct($config)
    {
        $this->config = $config;
    }

    private function getInstance($name = null)
    {
        if (!isset($this->config[$name]) || empty($this->config[$name]))
        {
            throw new \Exception('连接的类型不存在');
        }

        $config = $this->config[$name];

        if (!isset($this->instance[$name]))
        {
            if (isset($config['type']))
            {
                $class = "HuTong\Cache\Drive\\".$config['type'];
            } else {
                throw new \Exception('连接的类型不存在');
            }

            $this->instance[$name] = new $class($config);
        }
        
        return $this->instance[$name];
    }

    public function store($name = 'default')
    {
        return $this->getInstance($name);
    }
}
