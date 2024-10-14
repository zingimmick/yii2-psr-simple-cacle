<?php

namespace Zing\Yii2PsrSimpleCache\Tests;

use Zing\Yii2PsrSimpleCache\Cache;
use Zing\Yii2PsrSimpleCache\DynamicCache;

/**
 * @internal
 */
final class DynamicCacheTest extends TestCase
{
    /**
     * @phpstan-return void
     */
    public function testCacheUsesCurrent()
    {
        \define('YII_ENABLE_ERROR_HANDLER', false);
        $this->mockApplication();
        $mockObject = $this->getMockBuilder('yii\caching\CacheInterface')
            ->getMock();
        $mockObject->expects($this->once())
            ->method('get')
            ->with('test1');

        $mockObject2 = $this->getMockBuilder('yii\caching\CacheInterface')
            ->getMock();
        $mockObject2->expects($this->once())
            ->method('get')
            ->with('test2');

        $dynamicCache = new DynamicCache();
        \Yii::$app->setComponents([
            'cache' => $mockObject,
        ]);

        $dynamicCache->get('test1');

        \Yii::$app->setComponents([
            'cache' => $mockObject2,
        ]);
        $dynamicCache->get('test2');
    }

    /**
     * @phpstan-return void
     */
    public function testGet()
    {
        \define('YII_ENABLE_ERROR_HANDLER', false);
        $this->mockApplication();
        $mockObject = $this->getMockBuilder('yii\caching\CacheInterface')
            ->getMock();
        $mockObject->expects($this->exactly(3))
            ->method('get')
            ->with('test')
            ->willReturn(false, false, 2);

        $dynamicCache = new DynamicCache();
        \Yii::$app->setComponents([
            'cache' => $mockObject,
        ]);

        $this->assertNull($dynamicCache->get('test'));
        $this->assertSame(1, $dynamicCache->get('test', 1));
        $this->assertSame(2, $dynamicCache->get('test', 1));
    }

    /**
     * @phpstan-return void
     */
    public function testSet()
    {
        \define('YII_ENABLE_ERROR_HANDLER', false);
        $this->mockApplication();
        $mockObject = $this->getMockBuilder('yii\caching\CacheInterface')
            ->getMock();
        $mockObject->expects($this->exactly(2))
            ->method('set')
            ->with('test', 'value', 1)
            ->willReturn(true, true);

        $dynamicCache = new DynamicCache();
        \Yii::$app->setComponents([
            'cache' => $mockObject,
        ]);

        $this->assertTrue($dynamicCache->set('test', 'value', 1));
        $this->assertTrue($dynamicCache->set('test', 'value', new \DateInterval('PT1S')));
    }

    /**
     * @phpstan-return void
     */
    public function testSetWithoutTtlValue()
    {
        \define('YII_ENABLE_ERROR_HANDLER', false);
        $this->mockApplication();
        $mockObject = $this->getMockBuilder('yii\caching\CacheInterface')
            ->getMock();
        $mockObject->expects($this->exactly(1))
            ->method('set')
            ->with('test', 'value', 0)
            ->willReturn(true, true);

        $cache = new Cache($mockObject);

        $this->assertTrue($cache->set('test', 'value'));
    }

    /**
     * @phpstan-return void
     */
    public function testSetWithNegativeTtl()
    {
        \define('YII_ENABLE_ERROR_HANDLER', false);
        $this->mockApplication();
        $mockObject = $this->getMockBuilder('yii\caching\CacheInterface')
            ->getMock();
        $mockObject->expects($this->exactly(1))
            ->method('delete')
            ->with('test')
            ->willReturn(true);

        $cache = new Cache($mockObject);

        $this->assertTrue($cache->set('test', 'value', -1));
    }

    /**
     * @phpstan-return void
     */
    /**
     * @phpstan-return void
     */
    public function testDelete()
    {
        \define('YII_ENABLE_ERROR_HANDLER', false);
        $this->mockApplication();
        $mockObject = $this->getMockBuilder('yii\caching\CacheInterface')
            ->getMock();
        $mockObject->expects($this->once())
            ->method('delete')
            ->with('test')
            ->willReturn(true);
        $dynamicCache = new DynamicCache();
        \Yii::$app->setComponents([
            'cache' => $mockObject,
        ]);

        $this->assertTrue($dynamicCache->delete('test'));
    }

    /**
     * @phpstan-return void
     */
    public function testClear()
    {
        \define('YII_ENABLE_ERROR_HANDLER', false);
        $this->mockApplication();
        $mockObject = $this->getMockBuilder('yii\caching\CacheInterface')
            ->getMock();
        $mockObject->expects($this->once())
            ->method('flush')
            ->willReturn(true);
        $dynamicCache = new DynamicCache();
        \Yii::$app->setComponents([
            'cache' => $mockObject,
        ]);

        $this->assertTrue($dynamicCache->clear());
    }

    /**
     * @phpstan-return void
     */
    public function testGetMultiple()
    {
        \define('YII_ENABLE_ERROR_HANDLER', false);
        $this->mockApplication();
        $mockObject = $this->getMockBuilder('yii\caching\CacheInterface')
            ->getMock();
        $mockObject->expects($this->exactly(2))
            ->method('multiGet')
            ->with(['test1', 'test2'])
            ->willReturn([
                'test1' => 2,
                'test2' => false,
            ], [
                'test1' => 2,
                'test2' => false,
            ]);

        $dynamicCache = new DynamicCache();
        \Yii::$app->setComponents([
            'cache' => $mockObject,
        ]);

        $this->assertSame([
            'test1' => 2,
            'test2' => null,
        ], $dynamicCache->getMultiple(['test1', 'test2']));
        $this->assertSame([
            'test1' => 2,
            'test2' => 1,
        ], $dynamicCache->getMultiple(['test1', 'test2'], 1));
    }

    /**
     * @phpstan-return void
     */
    public function testSetMultiple()
    {
        \define('YII_ENABLE_ERROR_HANDLER', false);
        $this->mockApplication();
        $mockObject = $this->getMockBuilder('yii\caching\CacheInterface')
            ->getMock();
        $mockObject->expects($this->exactly(2))
            ->method('multiSet')
            ->with([
                'test' => 'value',
            ], 1, null)
            ->willReturn([], []);

        $dynamicCache = new DynamicCache();
        \Yii::$app->setComponents([
            'cache' => $mockObject,
        ]);

        $this->assertTrue($dynamicCache->setMultiple([
            'test' => 'value',
        ], 1));
        $this->assertTrue($dynamicCache->setMultiple([
            'test' => 'value',
        ], new \DateInterval('PT1S')));
    }

    /**
     * @phpstan-return void
     */
    public function testSetMultipleWithoutTtlValue()
    {
        \define('YII_ENABLE_ERROR_HANDLER', false);
        $this->mockApplication();
        $mockObject = $this->getMockBuilder('yii\caching\CacheInterface')
            ->getMock();
        $mockObject->expects($this->once())
            ->method('multiSet')
            ->with([
                'test' => 'value',
            ], 0, null)
            ->willReturn([], []);

        $cache = new Cache($mockObject);

        $this->assertTrue($cache->setMultiple([
            'test' => 'value',
        ]));
    }

    /**
     * @phpstan-return void
     */
    public function testSetMultipleWithNegativeTtl()
    {
        \define('YII_ENABLE_ERROR_HANDLER', false);
        $this->mockApplication();
        $mockObject = $this->getMockBuilder('yii\caching\CacheInterface')
            ->getMock();
        $mockObject->expects($this->once())
            ->method('delete')
            ->with('test')
            ->willReturn(true);

        $cache = new Cache($mockObject);

        $this->assertTrue($cache->setMultiple([
            'test' => 'value',
        ], -1));
    }

    /**
     * @phpstan-return void
     */
    public function testDeleteMultiple()
    {
        \define('YII_ENABLE_ERROR_HANDLER', false);
        $this->mockApplication();
        $mockObject = $this->getMockBuilder('yii\caching\CacheInterface')
            ->getMock();
        $mockObject->expects($this->exactly(3))
            ->method('delete')
            ->withAnyParameters()
            ->willReturn(true);
        $dynamicCache = new DynamicCache();
        \Yii::$app->setComponents([
            'cache' => $mockObject,
        ]);

        $this->assertTrue($dynamicCache->deleteMultiple(['test1', 'test2', 'test3']));
    }

    /**
     * @phpstan-return void
     */
    public function testDeleteMultipleFailed()
    {
        \define('YII_ENABLE_ERROR_HANDLER', false);
        $this->mockApplication();
        $mockObject = $this->getMockBuilder('yii\caching\CacheInterface')
            ->getMock();
        $mockObject->expects($this->exactly(3))
            ->method('delete')
            ->withAnyParameters()
            ->willReturn(true, false, true);
        $dynamicCache = new DynamicCache();
        \Yii::$app->setComponents([
            'cache' => $mockObject,
        ]);

        $this->assertFalse($dynamicCache->deleteMultiple(['test1', 'test2', 'test3']));
    }

    /**
     * @phpstan-return void
     */
    public function testHas()
    {
        \define('YII_ENABLE_ERROR_HANDLER', false);
        $this->mockApplication();
        $mockObject = $this->getMockBuilder('yii\caching\CacheInterface')
            ->getMock();
        $mockObject->expects($this->exactly(2))
            ->method('exists')
            ->with('test')
            ->willReturn(false, true);

        $dynamicCache = new DynamicCache();
        \Yii::$app->setComponents([
            'cache' => $mockObject,
        ]);
        $this->assertFalse($dynamicCache->has('test'));
        $this->assertTrue($dynamicCache->has('test'));
    }
}
