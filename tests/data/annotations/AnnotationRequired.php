<?php

namespace yii\annotations\tests\data\annotations;

use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class AnnotationDefined
 * @Annotation
 * @Target({"METHOD"})
 * @package yii\annotations\tests\data\annotations
 */
class AnnotationRequired
{
    /**
     * @Required
     * @var boolean
     */
    public $check;
}
