<?php

declare(strict_types=1);

namespace Zing\Yii2PsrSimpleCache;

use Psr\SimpleCache\CacheInterface;
use yii\caching\CacheInterface as YiiCache;

class DynamicCache implements CacheInterface
{
    private ?Cache $cache = null;

    private ?YiiCache $yiiCache = null;

    private function getCache(): Cache
    {
        if (! ($this->cache instanceof Cache && $this->yiiCache instanceof YiiCache) || \Yii::$app->getCache() !== $this->yiiCache) {
            $this->yiiCache = \Yii::$app->getCache();
            $this->cache = new Cache(\Yii::$app->getCache());
        }

        return $this->cache;
    }

    /**
     * @param string $key
     * @param mixed $default
     */
    public function get($key, $default = null): mixed
    {
        return $this->getCache()
            ->get($key, $default);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param int|\DateInterval|null $ttl
     */
    public function set($key, $value, $ttl = null): bool
    {
        return $this->getCache()
            ->set($key, $value, $ttl);
    }

    /**
     * @param string $key
     */
    public function delete($key): bool
    {
        return $this->getCache()
            ->delete($key);
    }

    public function clear(): bool
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
    public function getMultiple($keys, $default = null): iterable
    {
        return $this->getCache()
            ->getMultiple($keys, $default);
    }

    /**
     * @param iterable<string, mixed> $values
     * @param int|\DateInterval|null $ttl
     */
    public function setMultiple($values, $ttl = null): bool
    {
        return $this->getCache()
            ->setMultiple($values, $ttl);
    }

    /**
     * @param iterable<string> $keys
     */
    public function deleteMultiple($keys): bool
    {
        return $this->getCache()
            ->deleteMultiple($keys);
    }

    /**
     * @param string $key
     */
    public function has($key): bool
    {
        return $this->getCache()
            ->has($key);
    }
}
