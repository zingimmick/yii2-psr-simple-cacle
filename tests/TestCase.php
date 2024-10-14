<?php

namespace Zing\Yii2PsrSimpleCache\Tests;

if (class_exists('PHPUnit_Framework_TestCase') && ! class_exists('PHPUnit\Framework\TestCase')) {
    class_alias('PHPUnit_Framework_TestCase', 'PHPUnit\Framework\TestCase');
}

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
    protected function mockApplication(array $config = [], $appClass = '\yii\console\Application')
    {
        new $appClass(ArrayHelper::merge([
            'id' => 'testapp',
            'basePath' => __DIR__,
            'vendorPath' => \dirname(__DIR__) . '/vendor',
        ], $config));
    }
}
