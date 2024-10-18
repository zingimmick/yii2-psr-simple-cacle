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

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->getCache()
            ->get($key, $default);
    }

    public function set(string $key, mixed $value, null|\DateInterval|int $ttl = null): bool
    {
        return $this->getCache()
            ->set($key, $value, $ttl);
    }

    public function delete(string $key): bool
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
     *
     * @return iterable<string, mixed>
     */
    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        return $this->getCache()
            ->getMultiple($keys, $default);
    }

    /**
     * @param iterable<string, mixed> $values
     */
    public function setMultiple(iterable $values, null|\DateInterval|int $ttl = null): bool
    {
        return $this->getCache()
            ->setMultiple($values, $ttl);
    }

    /**
     * @param iterable<string> $keys
     */
    public function deleteMultiple(iterable $keys): bool
    {
        return $this->getCache()
            ->deleteMultiple($keys);
    }

    public function has(string $key): bool
    {
        return $this->getCache()
            ->has($key);
    }
}
