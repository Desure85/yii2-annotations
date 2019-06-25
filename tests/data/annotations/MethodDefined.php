<?php

namespace yii\annotations\tests\data\annotations;

/**
 * Class MethodDefined.
 *
 * @Annotation
 * @Target({"METHOD"})
 */
class MethodDefined
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
