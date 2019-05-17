<?php

namespace yii\annotations;

use Doctrine\Common\Annotations\AnnotationException;

/**
 * Interface AnnotationsInterface
 * @package yii\annotations
 */
interface AnnotationsInterface
{
    /**
     * @return AnnotationCacheReader
     * @throws AnnotationException
     */
    public function getReader(): AnnotationCacheReader;
}
