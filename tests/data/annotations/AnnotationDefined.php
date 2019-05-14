<?php

namespace yii\annotations\tests\data\annotations;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class AnnotationDefined
 * @Annotation
 * @Target({"ANNOTATION"})
 * @package yii\annotations\tests\data\annotations
 */
class AnnotationDefined
{
    /**
     * @var boolean
     */
    public $check;
}
