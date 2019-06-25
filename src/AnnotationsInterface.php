<?php

namespace yii\annotations;

use Doctrine\Common\Annotations\AnnotationException;

/**
 * Interface AnnotationsInterface.
 */
interface AnnotationsInterface
{
    /**
     * @throws AnnotationException
     *
     * @return AnnotationCacheReader
     */
    public function getReader(): AnnotationCacheReader;
}
