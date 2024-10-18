<?php

declare(strict_types=1);

namespace Zing\Yii2PsrSimpleCache\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use yii\helpers\ArrayHelper;

abstract class TestCase extends BaseTestCase
{
    /**
     * @param array<string, mixed> $config
     * @param class-string<\yii\base\Application> $appClass
     *
     * @phpstan-return void
     */
    protected function mockApplication(array $config = [], $appClass = '\yii\console\Application'): void
    {
        new $appClass(ArrayHelper::merge([
            'id' => 'testapp',
            'basePath' => __DIR__,
            'vendorPath' => \dirname(__DIR__) . '/vendor',
        ], $config));
    }
}
