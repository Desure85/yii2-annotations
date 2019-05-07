<?php

namespace yii\annotations;

/**
 * Interface AnnotationsInterface
 * @package yii\annotations
 */
interface AnnotationsInterface
{
    /**
     * @return AnnotationCacheReader
     */
    public function getReader(): AnnotationCacheReader;
}
