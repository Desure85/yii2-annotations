<?php

namespace yii\annotations\tests\data\annotations;

/**
 * Class PropertyDefined
 * @Annotation
 * @Target({"PROPERTY"})
 * @package yii\annotations\tests\data\annotations
 */
class PropertyDefined
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
