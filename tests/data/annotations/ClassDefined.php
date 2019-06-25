<?php

namespace yii\annotations\tests\data\annotations;

use yii\annotations\tests\data\annotations\AnnotationDefined as AnnotationDefinedAlias;
use yii\annotations\tests\data\annotations\AnnotationFeatures as AnnotationFeaturesAlias;

/**
 * Class ClassDefined.
 *
 * @Annotation
 * @Target({"CLASS"})
 */
class ClassDefined
{
    /**
     * @var bool
     */
    public $check;

    /**
     * @var AnnotationDefinedAlias
     */
    public $checkAnnotation;

    /**
     * @var AnnotationFeaturesAlias
     */
    public $checkAll;
}
