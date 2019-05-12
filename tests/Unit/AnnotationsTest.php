<?php

namespace yii\annotations\tests\Unit;

use Yii;
use yii\annotations\AnnotationCacheReader;
use yii\annotations\Annotations;
use yii\annotations\tests\TestCase;
use yii\base\InvalidConfigException;
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
}
