<?php

namespace yii\annotations;

use Doctrine\Common\Annotations\DocParser;

/**
 * Interface AnnotationsInterface.
 */
interface ParserInterface
{
    /**
     * @param array $importAnnotations ['nameAnnotation' => AnnotationClass::class]
     *
     * @return DocParser
     */
    public function getParser(array $importAnnotations): DocParser;
}
