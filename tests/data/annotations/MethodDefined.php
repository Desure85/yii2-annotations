<?php

namespace yii\annotations\tests\data\annotations;

/**
 * Class MethodDefined
 * @Annotation
 * @Target({"METHOD"})
 * @package yii\annotations\tests\data\annotations
 */
class MethodDefined
{
    /**
     * @var boolean
     */
    public $check;

    /**
     * @var AnnotationDefined
     */
    public $checkAnnotation;

    /**
     * @var AnnotationFeatures
     */
    public $checkAll;
}
