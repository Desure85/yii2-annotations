<?php

namespace yii\annotations\test\Unit;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Exception;
use yii\annotations\AnnotationCache;
use yii\annotations\AnnotationCacheInterface;
use yii\annotations\tests\TestCase;
use yii\caching\FileCache;

/**
 * Class AnnotationCacheTest
 * @package yii\annotations\test\Unit
 */
class AnnotationCacheTest extends TestCase
{
    /**
     * @var AnnotationCacheInterface
     */
    private $cache;

    protected function setUp()
    {
        parent::setUp();
        if (method_exists(AnnotationRegistry::class, 'registerLoader')) {
            /** @scrutinizer ignore-deprecated */
            /** @noinspection PhpDeprecationInspection */
            AnnotationRegistry::registerLoader('class_exists');
        }
        $this->cache = new AnnotationCache(new FileCache([
            'cachePath' => __DIR__ .  '/../data/cache',
            'cacheFileSuffix' => '.annotation'
        ]));
    }

    public function testConstruct(): void
    {
        $this->assertInstanceOf(AnnotationCacheInterface::class, $this->cache);
    }

    public function testSave(): void
    {
        try {
            $this->assertTrue(
                $this->cache->save('test.cache', ['testData' => true])
            );
            $this->assertTrue($this->cache->contains('test.cache'));
            return;
        } catch (Exception $e) {
        }
        $this->fail($e->getMessage());
    }

    public function testFetch(): void
    {
        try {
            $this->assertSame(
                ['testData' => true],
                $this->cache->fetch('test.cache')
            );
            return;
        } catch (Exception $e) {
        }
        $this->fail($e->getMessage() ?? 'Error cache fetched');
    }

    public function testContains(): void
    {
        try {
            $this->assertTrue(
                $this->cache->contains('test.cache')
            );
            return;
        } catch (Exception $e) {
        }
        $this->fail($e->getMessage() ?? 'Error cache contains');
    }

    public function testDelete(): void
    {
        try {
            $this->assertTrue(
                $this->cache->delete('test.cache')
            );
            $this->assertFalse($this->cache->contains('test.cache'));
            return;
        } catch (Exception $e) {
        }
        $this->fail($e->getMessage() ?? 'Error cache contains');
    }



    public function testGetStats()
    {
        $this->assertNull($this->cache->getStats());
    }
}
