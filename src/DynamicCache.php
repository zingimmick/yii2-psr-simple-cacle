<?php

namespace Zing\Yii2PsrSimpleCache;

use Psr\SimpleCache\CacheInterface;

class DynamicCache implements CacheInterface
{
    /**
     * @var \Zing\Yii2PsrSimpleCache\Cache|null
     */
    private $cache;

    /**
     * @var \yii\caching\CacheInterface|null
     */
    private $yiiCache;

    /**
     * @return \Zing\Yii2PsrSimpleCache\Cache
     */
    private function getCache()
    {
        if (! ($this->cache !== null && $this->yiiCache !== null) || \Yii::$app->getCache() !== $this->yiiCache) {
            $this->yiiCache = \Yii::$app->getCache();
            $this->cache = new Cache(\Yii::$app->getCache());
        }

        return $this->cache;
    }

    /**
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->getCache()
            ->get($key, $default);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param int|\DateInterval|null $ttl
     *
     * @return bool
     */
    public function set($key, $value, $ttl = null)
    {
        return $this->getCache()
            ->set($key, $value, $ttl);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function delete($key)
    {
        return $this->getCache()
            ->delete($key);
    }

    /**
     * @return bool
     */
    public function clear()
    {
        return $this->getCache()
            ->clear();
    }

    /**
     * @param iterable<string> $keys
     * @param mixed $default
     *
     * @return iterable<string, mixed>
     */
    public function getMultiple($keys, $default = null)
    {
        return $this->getCache()
            ->getMultiple($keys, $default);
    }

    /**
     * @param iterable<string, mixed> $values
     * @param int|\DateInterval|null $ttl
     *
     * @return bool
     */
    public function setMultiple($values, $ttl = null)
    {
        return $this->getCache()
            ->setMultiple($values, $ttl);
    }

    /**
     * @param iterable<string> $keys
     *
     * @return bool
     */
    public function deleteMultiple($keys)
    {
        return $this->getCache()
            ->deleteMultiple($keys);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        return $this->getCache()
            ->has($key);
    }
}
