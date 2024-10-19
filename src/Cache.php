<?php

declare(strict_types=1);

namespace Zing\Yii2PsrSimpleCache;

use Psr\SimpleCache\CacheInterface;
use yii\caching\CacheInterface as YiiCache;

class Cache implements CacheInterface
{
    private YiiCache $yiiCache;

    public function __construct(YiiCache $yiiCache = null)
    {
        if (! isset($yiiCache)) {
            $yiiCache = \Yii::$app->getCache();
        }

        if ($yiiCache === null) {
            throw new \InvalidArgumentException('The cache component must not be null.');
        }

        $this->yiiCache = $yiiCache;
    }

    /**
     * @param string $key
     * @param mixed $default
     */
    public function get($key, $default = null): mixed
    {
        $value = $this->yiiCache->get($key);

        return $value === false ? $default : $value;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param int|\DateInterval|null $ttl
     */
    public function set($key, $value, $ttl = null): bool
    {
        $seconds = $this->getSeconds($ttl);
        if ($seconds === null) {
            return $this->yiiCache->set($key, $value, 0);
        }

        if ($seconds <= 0) {
            return $this->yiiCache->delete($key);
        }

        return $this->yiiCache->set($key, $value, $seconds);
    }

    /**
     * @param string $key
     */
    public function delete($key): bool
    {
        return $this->yiiCache->delete($key);
    }

    public function clear(): bool
    {
        return $this->yiiCache->flush();
    }

    /**
     * @param iterable<string> $keys
     * @param mixed $default
     *
     * @return iterable<string, mixed>
     */
    public function getMultiple($keys, $default = null): iterable
    {
        $values = $this->yiiCache->multiGet(\is_array($keys) ? $keys : iterator_to_array($keys));
        foreach ($values as $key => $value) {
            $values[$key] = $value === false ? $default : $value;
        }

        return $values;
    }

    /**
     * @param iterable<string, mixed> $values
     * @param int|\DateInterval|null $ttl
     */
    public function setMultiple($values, $ttl = null): bool
    {
        $values = \is_array($values) ? $values : iterator_to_array($values);
        $seconds = $this->getSeconds($ttl);
        if ($seconds === null) {
            return $this->yiiCache->multiSet($values, 0) === [];
        }

        if ($seconds <= 0) {
            return $this->deleteMultiple(array_keys($values));
        }

        return $this->yiiCache->multiSet($values, $this->getSeconds($ttl)) === [];
    }

    /**
     * @param iterable<string> $keys
     */
    public function deleteMultiple($keys): bool
    {
        $result = true;
        foreach ($keys as $key) {
            if (! $this->yiiCache->delete($key)) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * @param string $key
     */
    public function has($key): bool
    {
        return $this->yiiCache->exists($key);
    }

    /**
     * @param int|\DateInterval|null $ttl
     *
     * @return int|null
     */
    protected function getSeconds($ttl)
    {
        if ($ttl === null) {
            return $ttl;
        }

        if ($ttl instanceof \DateInterval) {
            $ttl = (int) \DateTime::createFromFormat('U', '0')->add($ttl)->format('U');
        }

        return (int) $ttl > 0 ? $ttl : 0;
    }
}
