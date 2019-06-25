<?php

namespace yii\annotations\tests\data\annotations;

use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class AnnotationDefined.
 *
 * @Annotation
 * @Target({"METHOD"})
 */
class AnnotationRequired
{
    /**
     * @Required
     *
     * @var bool
     */
    public $check;
}
