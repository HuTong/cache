<?php
namespace Hutong\Cache\Drive;

use HuTong\Cache\Contract;

class Redis implements Contract
{
    private $config;
    private $container;
    private $prefix = '';

    public function __construct($config)
    {
        $this->config = $config;

        $this->container = new \Redis();
        $this->container->connect($this->config['host'], $this->config['port']);

        if (isset($this->config['password']) && $this->config['password'])
        {
            $this->container->auth($this->config['password']);
        }

        if(isset($this->config['db']) && $this->config['db'])
        {
            $this->container->select($this->config['db']);
        }

        $this->setPrefix($this->config['prefix'] ?: 'default');
    }

    public function get($key)
    {
        $value = $this->container->get($this->getKeyPath($key));

        return ! is_null($value) ? $this->unserialize($value) : null;
    }

    public function set($key, $val, $expires = 0)
    {
        if ($expires)
        {
            return (bool)$this->container->setex($this->getKeyPath($key), max(1, $expires * 60), $this->serialize($val));
        } else {
            return (bool)$this->container->set($this->getKeyPath($key), $this->serialize($val));
        }
    }

    public function del($key)
    {
        $this->container->delete($this->getKeyPath($key));

        return true;
    }

    public function increment($key, $value = 1)
    {
        return $this->container->incrby($this->getKeyPath($key), $value);
    }

    public function decrement($key, $value = 1)
    {
        return $this->container->decrby($this->getKeyPath($key), $value);
    }

    public function flush()
    {
        $this->container->flushdb();

        return true;
    }

    private function serialize($val)
    {
        return is_numeric($val) ? $val : serialize($val);
    }

    private function unserialize($val)
    {
        return is_numeric($val) ? $val : unserialize($val);
    }

    private function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    private function getKeyPath($key)
    {
        return $this->prefix.$key;
    }

    /**
     * redis其它方法
     *
     * @author hutong
     * @date   2017-07-11
     */
    public function __call($method, $parameters)
    {
        return $this->container->$method(...$parameters);
    }
}
