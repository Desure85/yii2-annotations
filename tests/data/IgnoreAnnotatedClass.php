<?php

namespace yii\annotations\tests\data;

use Doctrine\Common\Annotations\Annotation\IgnoreAnnotation;

/**
 * Class AnnotatedClass
 * @IgnoreAnnotation("ignoreInline")
 * @checkIgnore
 * @ignoreInline
 */
class IgnoreAnnotatedClass
{
    /**
     * @checkIgnore
     * @ignoreInline
     */
    public $testProperty;

    /**
     * @ignoreInline
     * @checkIgnore
     */
    public function testMethod(): void
    {
    }
}
