<?php

namespace yii\annotations\tests\data\annotations;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class AnnotationDefined.
 *
 * @Annotation
 * @Target({"ANNOTATION"})
 */
class AnnotationDefined
{
    /**
     * @var bool
     */
    public $check;
}
