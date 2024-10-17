<?php

namespace Zing\Yii2PsrSimpleCache\Tests;

use Zing\Yii2PsrSimpleCache\Cache;

/**
 * @internal
 */
final class CacheTest extends TestCase
{
    /**
     * @phpstan-return void
     */
    public function testGet()
    {
        \defined('YII_ENABLE_ERROR_HANDLER') || \define('YII_ENABLE_ERROR_HANDLER', false);
        $this->mockApplication();
        $mockObject = $this->getMockBuilder('yii\caching\CacheInterface')
            ->getMock();
        $mockObject->expects($this->once())
            ->method('get')
            ->with('test')
            ->willReturn(2);

        $cache = new Cache($mockObject);
        $this->assertSame(2, $cache->get('test', 1));
    }

    /**
     * @phpstan-return void
     */
    public function testGetWithoutDefault()
    {
        \defined('YII_ENABLE_ERROR_HANDLER') || \define('YII_ENABLE_ERROR_HANDLER', false);
        $this->mockApplication();
        $mockObject = $this->getMockBuilder('yii\caching\CacheInterface')
            ->getMock();
        $mockObject->expects($this->once())
            ->method('get')
            ->with('test')
            ->willReturn(false);

        $cache = new Cache($mockObject);
        $this->assertNull($cache->get('test'));
    }

    /**
     * @phpstan-return void
     */
    public function testGetWithDefault()
    {
        \defined('YII_ENABLE_ERROR_HANDLER') || \define('YII_ENABLE_ERROR_HANDLER', false);
        $this->mockApplication();
        $mockObject = $this->getMockBuilder('yii\caching\CacheInterface')
            ->getMock();
        $mockObject->expects($this->once())
            ->method('get')
            ->with('test')
            ->willReturn(false);

        $cache = new Cache($mockObject);
        $this->assertSame(1, $cache->get('test', 1));
    }

    /**
     * @phpstan-return void
     */
    public function testSet()
    {
        \defined('YII_ENABLE_ERROR_HANDLER') || \define('YII_ENABLE_ERROR_HANDLER', false);
        $this->mockApplication();
        $mockObject = $this->getMockBuilder('yii\caching\CacheInterface')
            ->getMock();
        $mockObject->expects($this->once())
            ->method('set')
            ->with('test', 'value', 1)
            ->willReturn(true);

        $cache = new Cache($mockObject);

        $this->assertTrue($cache->set('test', 'value', 1));
    }

    /**
     * @phpstan-return void
     */
    public function testSetWithDateInterval()
    {
        \defined('YII_ENABLE_ERROR_HANDLER') || \define('YII_ENABLE_ERROR_HANDLER', false);
        $this->mockApplication();
        $mockObject = $this->getMockBuilder('yii\caching\CacheInterface')
            ->getMock();
        $mockObject->expects($this->once())
            ->method('set')
            ->with('test', 'value', 1)
            ->willReturn(true);

        $cache = new Cache($mockObject);

        $this->assertTrue($cache->set('test', 'value', new \DateInterval('PT1S')));
    }

    /**
     * @phpstan-return void
     */
    public function testSetWithoutTtlValue()
    {
        \defined('YII_ENABLE_ERROR_HANDLER') || \define('YII_ENABLE_ERROR_HANDLER', false);
        $this->mockApplication();
        $mockObject = $this->getMockBuilder('yii\caching\CacheInterface')
            ->getMock();
        $mockObject->expects($this->once())
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
        \defined('YII_ENABLE_ERROR_HANDLER') || \define('YII_ENABLE_ERROR_HANDLER', false);
        $this->mockApplication();
        $mockObject = $this->getMockBuilder('yii\caching\CacheInterface')
            ->getMock();
        $mockObject->expects($this->once())
            ->method('delete')
            ->with('test')
            ->willReturn(true);

        $cache = new Cache($mockObject);

        $this->assertTrue($cache->set('test', 'value', -1));
    }

    /**
     * @phpstan-return void
     */
    public function testDelete()
    {
        \defined('YII_ENABLE_ERROR_HANDLER') || \define('YII_ENABLE_ERROR_HANDLER', false);
        $this->mockApplication();
        $mockObject = $this->getMockBuilder('yii\caching\CacheInterface')
            ->getMock();
        $mockObject->expects($this->once())
            ->method('delete')
            ->with('test')
            ->willReturn(true);
        $cache = new Cache($mockObject);

        $this->assertTrue($cache->delete('test'));
    }

    /**
     * @phpstan-return void
     */
    public function testClear()
    {
        \defined('YII_ENABLE_ERROR_HANDLER') || \define('YII_ENABLE_ERROR_HANDLER', false);
        $this->mockApplication();
        $mockObject = $this->getMockBuilder('yii\caching\CacheInterface')
            ->getMock();
        $mockObject->expects($this->once())
            ->method('flush')
            ->willReturn(true);
        $cache = new Cache($mockObject);

        $this->assertTrue($cache->clear());
    }

    /**
     * @phpstan-return void
     */
    public function testGetMultiple()
    {
        \defined('YII_ENABLE_ERROR_HANDLER') || \define('YII_ENABLE_ERROR_HANDLER', false);
        $this->mockApplication();
        $mockObject = $this->getMockBuilder('yii\caching\CacheInterface')
            ->getMock();
        $mockObject->expects($this->once())
            ->method('multiGet')
            ->with(['test1', 'test2'])
            ->willReturn([
                'test1' => 2,
                'test2' => false,
            ]);

        $cache = new Cache($mockObject);

        $this->assertSame([
            'test1' => 2,
            'test2' => null,
        ], $cache->getMultiple(['test1', 'test2']));
    }

    /**
     * @phpstan-return void
     */
    public function testGetMultipleWithDefault()
    {
        \defined('YII_ENABLE_ERROR_HANDLER') || \define('YII_ENABLE_ERROR_HANDLER', false);
        $this->mockApplication();
        $mockObject = $this->getMockBuilder('yii\caching\CacheInterface')
            ->getMock();
        $mockObject->expects($this->once())
            ->method('multiGet')
            ->with(['test1', 'test2'])
            ->willReturn([
                'test1' => 2,
                'test2' => false,
            ]);

        $cache = new Cache($mockObject);

        $this->assertSame([
            'test1' => 2,
            'test2' => 1,
        ], $cache->getMultiple(['test1', 'test2'], 1));
    }

    /**
     * @phpstan-return void
     */
    public function testSetMultiple()
    {
        \defined('YII_ENABLE_ERROR_HANDLER') || \define('YII_ENABLE_ERROR_HANDLER', false);
        $this->mockApplication();
        $mockObject = $this->getMockBuilder('yii\caching\CacheInterface')
            ->getMock();
        $mockObject->expects($this->once())
            ->method('multiSet')
            ->with([
                'test' => 'value',
            ], 1, null)
            ->willReturn([]);

        $cache = new Cache($mockObject);

        $this->assertTrue($cache->setMultiple([
            'test' => 'value',
        ], 1));
    }

    /**
     * @phpstan-return void
     */
    public function testSetMultipleWithDateInterval()
    {
        \defined('YII_ENABLE_ERROR_HANDLER') || \define('YII_ENABLE_ERROR_HANDLER', false);
        $this->mockApplication();
        $mockObject = $this->getMockBuilder('yii\caching\CacheInterface')
            ->getMock();
        $mockObject->expects($this->once())
            ->method('multiSet')
            ->with([
                'test' => 'value',
            ], 1, null)
            ->willReturn([]);

        $cache = new Cache($mockObject);

        $this->assertTrue($cache->setMultiple([
            'test' => 'value',
        ], new \DateInterval('PT1S')));
    }

    /**
     * @phpstan-return void
     */
    public function testSetMultipleWithoutTtlValue()
    {
        \defined('YII_ENABLE_ERROR_HANDLER') || \define('YII_ENABLE_ERROR_HANDLER', false);
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
        \defined('YII_ENABLE_ERROR_HANDLER') || \define('YII_ENABLE_ERROR_HANDLER', false);
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
        \defined('YII_ENABLE_ERROR_HANDLER') || \define('YII_ENABLE_ERROR_HANDLER', false);
        $this->mockApplication();
        $mockObject = $this->getMockBuilder('yii\caching\CacheInterface')
            ->getMock();
        $mockObject->expects($this->exactly(3))
            ->method('delete')
            ->withAnyParameters()
            ->willReturn(true);
        $cache = new Cache($mockObject);

        $this->assertTrue($cache->deleteMultiple(['test1', 'test2', 'test3']));
    }

    /**
     * @phpstan-return void
     */
    public function testDeleteMultipleFailed()
    {
        \defined('YII_ENABLE_ERROR_HANDLER') || \define('YII_ENABLE_ERROR_HANDLER', false);
        $this->mockApplication();
        $mockObject = $this->getMockBuilder('yii\caching\CacheInterface')
            ->getMock();
        $mockObject->expects($this->exactly(3))
            ->method('delete')
            ->withAnyParameters()
            ->willReturn(false, false, false);
        $cache = new Cache($mockObject);

        $this->assertFalse($cache->deleteMultiple(['test1', 'test2', 'test3']));
    }

    /**
     * @phpstan-return void
     */
    public function testHas()
    {
        \defined('YII_ENABLE_ERROR_HANDLER') || \define('YII_ENABLE_ERROR_HANDLER', false);
        $this->mockApplication();
        $mockObject = $this->getMockBuilder('yii\caching\CacheInterface')
            ->getMock();
        $mockObject->expects($this->once())
            ->method('exists')
            ->with('test')
            ->willReturn(true);

        $cache = new Cache($mockObject);
        $this->assertTrue($cache->has('test'));
    }

    /**
     * @phpstan-return void
     */
    public function testHasReturnFalse()
    {
        \defined('YII_ENABLE_ERROR_HANDLER') || \define('YII_ENABLE_ERROR_HANDLER', false);
        $this->mockApplication();
        $mockObject = $this->getMockBuilder('yii\caching\CacheInterface')
            ->getMock();
        $mockObject->expects($this->once())
            ->method('exists')
            ->with('test')
            ->willReturn(false);

        $cache = new Cache($mockObject);
        $this->assertFalse($cache->has('test'));
    }

    /**
     * @phpstan-return void
     */
    public function testCreateWithoutCacheComponent()
    {
        \defined('YII_ENABLE_ERROR_HANDLER') || \define('YII_ENABLE_ERROR_HANDLER', false);
        $this->mockApplication();
        if (method_exists($this, 'expectException')) {
            $this->expectException('InvalidArgumentException');
        } else {
            $this->setExpectedException('InvalidArgumentException');
        }

        new Cache();
    }
}
