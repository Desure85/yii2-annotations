<?php

namespace yii\annotations\tests\Unit;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;
use ReflectionException;
use Yii;
use yii\annotations\AnnotationCacheReader;
use yii\annotations\Annotations;
use yii\annotations\tests\data\IgnoreAnnotatedClass;
use yii\annotations\tests\TestCase;
use yii\base\InvalidConfigException;
use yii\caching\DummyCache;
use yii\caching\FileCache;
use yii\di\Instance;

/**
 * Class AnnotationsTest
 * @package yii\annotations\test\Unit
 */
abstract class AnnotationsTest extends TestCase
{
    /**
     * @throws InvalidConfigException
     */
    public function testGetReader(): void
    {
        $this->assertInstanceOf(AnnotationCacheReader::class, Instance::ensure('annotation')->getReader());
    }

    /**
     * @throws InvalidConfigException
     */
    public function testSetComponent(): void
    {
        $this->assertInstanceOf(Annotations::class, Instance::ensure('annotation'));
    }

    /**
     * @throws InvalidConfigException
     * @throws ReflectionException
     */
    public function testSetComponentWithCacheDefinedComponent(): void
    {
        Yii::$app->set('cache', ['class' => DummyCache::class]);
        Yii::$app->set('annotation', ['class' => Annotations::class, 'cache' => 'cache']);
        $this->assertInstanceOf(
            DummyCache::class,
            $this->getProtectedProperty(
                Instance::ensure('annotation'),
                'cacheComponent'
            )
        );
    }

    /**
     * @throws InvalidConfigException
     * @throws ReflectionException
     */
    public function testSetComponentWithCacheClass(): void
    {
        Yii::$app->set('cache', ['class' => DummyCache::class]);
        Yii::$app->set('annotation', ['class' => Annotations::class, 'cache' => FileCache::class]);
        $this->assertInstanceOf(
            FileCache::class,
            $this->getProtectedProperty(
                Instance::ensure('annotation'),
                'cacheComponent'
            )
        );
    }

    /**
     * @throws InvalidConfigException
     * @throws ReflectionException
     * @throws AnnotationException
     */
    public function testIgnoreAnnotation(): void
    {
        AnnotationReader::addGlobalIgnoredName('checkIgnore');
        $this->assertContains(
            'checkIgnore',
            $this->getProtectedProperty(new AnnotationReader(), 'globalIgnoredNames')
        );
        Instance::ensure('annotation')
            ->getReader()
            ->getClassAnnotations(new ReflectionClass(IgnoreAnnotatedClass::class));
        Instance::ensure('annotation')
            ->getReader()
            ->getPropertyAnnotations((new ReflectionClass(IgnoreAnnotatedClass::class))->getProperty('testProperty'));
        Instance::ensure('annotation')
            ->getReader()
            ->getMethodAnnotations((new ReflectionClass(IgnoreAnnotatedClass::class))->getMethod('testMethod'));
    }
}
