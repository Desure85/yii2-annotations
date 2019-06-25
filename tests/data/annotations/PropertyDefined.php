<?php

namespace yii\annotations\tests\data\annotations;

/**
 * Class PropertyDefined.
 *
 * @Annotation
 * @Target({"PROPERTY"})
 */
class PropertyDefined
{
    /**
     * @var bool
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
