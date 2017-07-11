<?php
namespace Hutong\Cache\Drive;

use HuTong\Cache\Contract;

class File implements Contract
{
    private $config;
    private $prefix = '';

    public function __construct($config)
    {
        $this->config = $config;

        $this->setPrefix($this->config['prefix'] ?: 'default');
    }

    public function get($key)
    {
        $path = $this->getkeyPath($key);

        if (!is_file($path))
        {
            return null;
        }

        return $this->getContent($key)['data'] ?: null;
    }

    public function set($key, $val, $expires = 0)
    {
        $path = $this->getkeyPath($key);
        $this->createDir($path);

        $content = $this->expiration($expires).serialize($val);

        return (bool)file_put_contents($path, $content);
    }

    public function del($key)
    {
        $path = $this->getkeyPath($key);
        @unlink($path);

        return true;
    }

    public function increment($key, $value = 1)
    {
        $info = $this->getContent($key);
        $val = (int)$info['data'] + $value;

        return $this->set($key, $val, $info['time']) ? $val : false;
    }

    public function decrement($key, $value = 1)
    {
        $info = $this->getContent($key);
        $val = (int)$info['data'] - $value;

        return $this->set($key, $val, $info['time']) ? $val : false;
    }

    public function flush()
    {
        $this->delPath($this->config['path'].$this->prefix);

        return true;
    }

    private function delPath($directory)
    {
        if (!is_dir($directory))
        {
            return false;
        }

        $items = new \FilesystemIterator($directory);

        foreach ($items as $item)
        {
            if ($item->isDir() && !$item->isLink())
            {
                $this->delPath($item->getPathname());
            } else {
                @unlink($item->getPathname());
            }
        }

        @rmdir($directory);

        return true;
    }

    private function getkeyPath($key)
    {
        $parts = array_slice(str_split($hash = sha1($key), 2), 0, 2);
        $path = $this->config['path'].$this->prefix.'/'.implode('/', $parts).'/'.$hash;

        return $path;
    }

    private function createDir($path)
    {
        $dir = dirname($path);
        if (!is_dir($dir))
        {
            mkdir($dir, 0777, true);
        }
    }

    private function expiration($minutes)
    {
        $time = time() + (int)($minutes * 60);

        return ($minutes === 0 || $time > 9999999999) ? 9999999999 : (int)$time;
    }

    private function getContent($key)
    {
        $path = $this->getkeyPath($key);

        if (!is_file($path))
        {
            return array('data'=>null, 'time'=>0);
        }

        $contents = file_get_contents($path);

        $expire = (int)substr($contents, 0, 10);

        if ($expire > time())
        {
            $data = unserialize(substr($contents, 10));

            return array('data'=>$data, 'time'=>$expire);
        } else {
            unlink($path);

            return array('data'=>null, 'time'=>0);
        }
    }

    private function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }
}
