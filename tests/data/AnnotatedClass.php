<?php

namespace yii\annotations\tests\data;

use yii\annotations\tests\data\annotations\AnnotationFeatures;
use yii\annotations\tests\data\annotations\AnnotationRequired;
use yii\annotations\tests\data\annotations\ClassDefined;
use yii\annotations\tests\data\annotations\MethodDefined;
use yii\annotations\tests\data\annotations\PropertyDefined;

/**
 * Class AnnotatedClass.
 *
 * @ClassDefined(check = true)
 * @AnnotationFeatures(check=true)
 */
class AnnotatedClass
{
    public const CHECK_CONST = true;

    /**
     * @PropertyDefined(check = true)
     */
    public $propertyAnnotation;

    /**
     * @MethodDefined(check = true)
     * @AnnotationFeatures(check = true)
     */
    public function methodAnnotation(): void
    {
    }

    /**
     * @AnnotationFeatures(check = true, id = 1)
     * @AnnotationFeatures(check = true, id = 2)
     * @AnnotationFeatures(check = true, id = 3)
     */
    public function multipleAnnotations(): void
    {
    }

    /**
     * @AnnotationFeatures(array = {"1", "2", "3", "4", "5"})
     * @AnnotationFeatures(array = {1, 2, 3, 4, 5})
     */
    public function arrayPropertyAnnotation(): void
    {
    }

    /**
     * @AnnotationFeatures(string = "true")
     */
    public function stringPropertyAnnotation(): void
    {
    }

    /**
     * @AnnotationFeatures(boolean = true)
     */
    public function booleanPropertyAnnotation(): void
    {
    }

    /**
     * @AnnotationFeatures(integer = 1)
     */
    public function integerPropertyAnnotation(): void
    {
    }

    /**
     * @AnnotationFeatures(const = AnnotatedClass::CHECK_CONST)
     */
    public function constPropertyAnnotation(): void
    {
    }

    /**
     * @AnnotationRequired()
     */
    public function requiredPropertyAnnotation(): void
    {
    }
}
