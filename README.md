**Yii2 Annotations extension**

Master: 
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Desure85/yii2-annotations/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Desure85/yii2-annotations/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/Desure85/yii2-annotations/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Desure85/yii2-annotations/?branch=master)
[![Build Status](https://travis-ci.org/Desure85/yii2-annotations.svg?branch=master)](https://travis-ci.org/Desure85/yii2-annotations)

Develop:
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Desure85/yii2-annotations/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/Desure85/yii2-annotations/?branch=develop)
[![Code Coverage](https://scrutinizer-ci.com/g/Desure85/yii2-annotations/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/Desure85/yii2-annotations/?branch=develop)
[![Build Status](https://travis-ci.org/Desure85/yii2-annotations.svg?branch=develop)](https://travis-ci.org/Desure85/yii2-annotations)

Use doctrine/annotations in Yii2 projects with Yii2 caching drivers

**Install**
````
composer require egorov/yii2-annotations
````
**Configuration**
````
...
    'components' => [
        'annotations' => [
            'class' => 'yii\annotations\Annotations',
            'cache' => '\yii\caching\FileCache',
            'path' => '@runtime/annotations',
            'debug' => false,
            'ignoreAnnotations' => [
                'properties', 'relations', 'property'
            ]
        ]
    ],
...
````  
where :

    class - yii\annotations\Annotations class or class implementing yii\annotations\AnnotationsInterface,
    cache - Optional. Container with cache component defined in configuration or cache class. By default 'cache',
    path -  Optional. Annotations cache dir for \yii\caching\FileCache,
    debug - Optional boolean. Debug enable. True means that the cache will be invalid every time the file is changed, false - the cache will be generated once
    ignoreAnnotations - List of ignored annotations when parsing a file

**Annotation class**

Annotation classes have to contain a class-level docblock with the text @Annotation:
````
/** @Annotation */
class Bar
{
    // some code
}
````

**Inject annotation values**

The annotation parser check if the annotation constructor has arguments, if so then we will pass the value array, otherwise will try to inject values into public properties directly:
````
/**
 * @Annotation
 *
 * Some Annotation using a constructor
 */
class Bar
{
    private $foo;

    public function __construct(array $values)
    {
        $this->foo = $values['foo'];
    }
}

/**
 * @Annotation
 *
 * Some Annotation without a constructor
 */
class Foo
{
    public $bar;
}
````

**Annotation Target**

@Target indicates the kinds of class element to which an annotation type is applicable. Then you could define one or more targets:

CLASS Allowed in the class docblock
PROPERTY Allowed in the property docblock
METHOD Allowed in the method docblock
ALL Allowed in the class, property and method docblock
ANNOTATION Allowed inside other annotations
If the annotations is not allowed in the current context you got an AnnotationException
````
/**
 * @Annotation
 * @Target({"METHOD","PROPERTY"})
 */
class Bar
{
    // some code
}

/**
 * @Annotation
 * @Target("CLASS")
 */
class Foo
{
    // some code
}
````

**Attribute types**

Annotation parser check the given parameters using the phpdoc annotation @var, The data type could be validated using the @var annotation on the annotation properties or using the annotations @Attributes and @Attribute.

If the data type not match you got an AnnotationException
````
/**
 * @Annotation
 * @Target({"METHOD","PROPERTY"})
 */
class Bar
{
    /** @var mixed */
    public $mixed;

    /** @var boolean */
    public $boolean;

    /** @var bool */
    public $bool;

    /** @var float */
    public $float;

    /** @var string */
    public $string;

    /** @var integer */
    public $integer;

    /** @var array */
    public $array;

    /** @var SomeAnnotationClass */
    public $annotation;

    /** @var array<integer> */
    public $arrayOfIntegers;

    /** @var array<SomeAnnotationClass> */
    public $arrayOfAnnotations;
}

/**
 * @Annotation
 * @Target({"METHOD","PROPERTY"})
 * @Attributes({
 *   @Attribute("stringProperty", type = "string"),
 *   @Attribute("annotProperty",  type = "SomeAnnotationClass"),
 * })
 */
class Foo
{
    public function __construct(array $values)
    {
        $this->stringProperty = $values['stringProperty'];
        $this->annotProperty = $values['annotProperty'];
    }

    // some code
}
````
**Annotation Required**

@Required indicates that the field must be specified when the annotation is used. If it is not used you get an AnnotationException stating that this value can not be null.

Declaring a required field:
````
/**
 * @Annotation
 * @Target("ALL")
 */
class Foo
{
    /** @Required */
    public $requiredField;
}
````
Usage:
````

/** @Foo(requiredField="value") */
public $direction;                  // Valid

 /** @Foo */
public $direction;                  // Required field missing, throws an AnnotationException
````
**Enumerated values**

An annotation property marked with @Enum is a field that accept a fixed set of scalar values.
You should use @Enum fields any time you need to represent fixed values.
The annotation parser check the given value and throws an AnnotationException if the value not match.
Declaring an enumerated property:

````
/**
 * @Annotation
 * @Target("ALL")
 */
class Direction
{
    /**
     * @Enum({"NORTH", "SOUTH", "EAST", "WEST"})
     */
    public $value;
}
````
Annotation usage:
````
/** @Direction("NORTH") */
public $direction;                  // Valid value

 /** @Direction("NORTHEAST") */
public $direction;                  // Invalid value, throws an AnnotationException
````
**Constants**

The use of constants and class constants are available on the annotations parser.

The following usage are allowed:
````
use MyCompany\Annotations\Foo;
use MyCompany\Annotations\Bar;
use MyCompany\Entity\SomeClass;

/**
 * @Foo(PHP_EOL)
 * @Bar(Bar::FOO)
 * @Foo({SomeClass::FOO, SomeClass::BAR})
 * @Bar({SomeClass::FOO_KEY = SomeClass::BAR_VALUE})
 */
class User
{
}
````
**_Be careful with constants and the cache !_**

The cached reader will not re-evaluate each time an annotation is loaded from cache. When a constant is changed the cache must be cleaned.

**Usage Example**

Create annotations class
````
<?php
/**
 * @Annotation
 * @Target({"CLASS"})
 */
class ServiceDescription
{
    /** @var string */
    private $description;

    /** @var array<ServiceMethod> */
    private $methods;

    public function __construct(array $values)
    {
        $this->description = $values['description'] ?? [];
        $this->methods = $values['methods'] ?? [];
    }
}
````
````
<?php
/**
 * @Annotation
 * @Target({"CLASS", "ANNOTATION"})
 */
class ServiceMethod
{
    /** @var string */
    private $name;

    /** @var string */
    private $description;

    public function __construct(array $values)
    {
        $this->name = $values['name'];
        $this->description = $values['description'];
    }
}
````
Write annotations in target class
````
...
/**
 * Class ReportDataService
 * @ServiceDescription(
 *      description = "Service description",
 *      methods = {
 *          @ServiceMethod(
 *              name="delete",
 *              description="delete ReportData method description"
 *          ),
 *          @ServiceMethod(
 *              name="getAll",
 *              description="getAll ReportData method description"
 *          ),
 *          @ServiceMethod(
 *              name="update",
 *              description="update ReportData method description"
 *          ),
 *          @ServiceMethod(
 *              name="count",
 *              description="count ReportData method description"
 *          )
 *     }
 * )
 */
class ReportDataService extends BaseModelService
...
````
````
<?php
...
$reader = Yii::$app->annotations->getReader();
...
````

**Reader API**

Access all annotations of a class
````
$reader->getClassAnnotations(\ReflectionClass $class);
````

Access one annotation of a class
````
$reader->getClassAnnotation(\ReflectionClass $class, $annotationName);
````

Access all annotations of a method
````
$reader->getMethodAnnotations(\ReflectionMethod $method);
````

Access one annotation of a method
````
$reader->getMethodAnnotation(\ReflectionMethod $method, $annotationName);
````

Access all annotations of a property
````
$reader->getPropertyAnnotations(\ReflectionProperty $property);
````

Access one annotation of a property
````
$reader->getPropertyAnnotation(\ReflectionProperty $property, $annotationName);
````

**Get parser**
Parse annotation directly from docBlock. It`s not cached
````
<?php
...
$reader = Yii::$app->annotations->getParser([
    'annotationName' => AnnotationClass::class
]);
...
````