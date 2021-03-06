<?php
namespace Hutong\Cache;

interface Contract
{
    /**
     * 获取缓存数据
     * @param  string $key
     * @return string|int|array|null
     *
     * @author hutong
     * @date   2017-07-11
     */
    public function get($key);

    /**
     * 添加、修改缓存数据
     * @param  string $key
     * @param  string|int|array $val
     * @param  int $expires
     * @return boolval
     *
     * @author hutong
     * @date   2017-07-11
     */
    public function set($key, $val, $expires);

    /**
     * 设置key的过期时间
     * @param  string $key [description]
     * @param  int $expire [description]
     * @param  int $type 0 按分钟1按秒
     * @return boolval
     *
     * @author hutong
     * @date   2018-05-11
     */
    public function expire($key, $expires, $type = 0);

    /**
     * 删除缓存
     * @param  string $key
     * @return boolval
     *
     * @author hutong
     * @date   2017-07-11
     */
    public function del($key);

    /**
     * 递增缓存数据
     * @param  string $key
     * @param  int $value
     * @return int|false
     *
     * @author hutong
     * @date   2017-07-11
     */
    public function increment($key, $value);

    /**
     * 递减缓存数据
     * @param  string $key
     * @param  int $value
     * @return int|false
     *
     * @author hutong
     * @date   2017-07-11
     */
    public function decrement($key, $value);

    /**
     * 清除缓存数据
     * @return boolval
     *
     * @author hutong
     * @date   2017-07-11
     */
    public function flush();
}
