<?php

namespace yii\annotations\tests\Unit;

use ReflectionClass;
use ReflectionException;
use Yii;
use yii\annotations\AnnotationCacheReader;
use yii\annotations\Annotations;
use yii\annotations\tests\data\AnnotatedClass;
use yii\annotations\tests\data\annotations\ClassDefined;
use yii\annotations\tests\data\annotations\MethodDefined;
use yii\annotations\tests\data\annotations\PropertyDefined;
use yii\annotations\tests\data\AppHelper;
use yii\annotations\tests\TestCase;
use yii\base\InvalidConfigException;
use yii\caching\DummyCache;
use yii\di\Instance;

/**
 * Class AnnotationCacheReaderTest
 * @package yii\annotations\test\Unit
 */
class AnnotationCacheReaderTest extends TestCase
{
    /**
     * @var AnnotationCacheReader
     */
    private $cacheReader;

    /**
     * @throws InvalidConfigException
     */
    protected function setUp()
    {
        parent::setUp();
        AppHelper::testWebApplication();
        Yii::$app->set('annotation', ['class' => Annotations::class, 'cache' => DummyCache::class]);
        $this->cacheReader = Instance::ensure('annotation')
            ->getReader();
    }

    /**
     * @throws ReflectionException
     */
    public function testGetMethodAnnotations(): void
    {
        /** @var MethodDefined[] $annotations */
        $annotations = $this
            ->cacheReader
            ->getMethodAnnotations((new ReflectionClass(AnnotatedClass::class))
                ->getMethod('methodAnnotation'));
        $this->assertTrue($annotations[0]->check);
    }

    /**
     * @throws ReflectionException
     */
    public function testClearLoadedAnnotations(): void
    {
        $annotations = $this
            ->cacheReader
            ->getMethodAnnotations((new ReflectionClass(AnnotatedClass::class))
                ->getMethod('methodAnnotation'));
        $this->assertTrue($annotations[0]->check);
        $this
            ->cacheReader
            ->clearLoadedAnnotations();
        $annotations = $this
            ->cacheReader
            ->getMethodAnnotations((new ReflectionClass(AnnotatedClass::class))
                ->getMethod('methodAnnotation'));
        $this->assertTrue($annotations[0]->check);
    }

    public function testGetClassAnnotations(): void
    {
        $annotations = $this
            ->cacheReader
            ->getClassAnnotations(new ReflectionClass(AnnotatedClass::class));
        $this->assertTrue($annotations[0]->check);
    }

    public function testGetClassAnnotation(): void
    {
        $annotation = $this
            ->cacheReader
            ->getClassAnnotation(new ReflectionClass(AnnotatedClass::class), ClassDefined::class);
        $this->assertTrue($annotation->check);
    }

    /**
     * @throws ReflectionException
     */
    public function testGetMethodAnnotation(): void
    {
        $annotation = $this
            ->cacheReader
            ->getMethodAnnotation(
                (new ReflectionClass(AnnotatedClass::class))
                    ->getMethod('methodAnnotation'),
                MethodDefined::class
            );
        $this->assertTrue($annotation->check);
    }

    /**
     * @throws ReflectionException
     */
    public function testGetPropertyAnnotation(): void
    {
        $annotations = $this
            ->cacheReader
            ->getPropertyAnnotations(
                (new ReflectionClass(AnnotatedClass::class))->getProperty('propertyAnnotation')
            );
        $this->assertTrue($annotations[0]->check);
    }

    /**
     * @throws ReflectionException
     */
    public function testGetPropertyAnnotations(): void
    {
        $annotations = $this
            ->cacheReader
            ->getPropertyAnnotation(
                (new ReflectionClass(AnnotatedClass::class))->getProperty('propertyAnnotation'),
                PropertyDefined::class
            );
        $this->assertTrue($annotations->check);
    }
}
