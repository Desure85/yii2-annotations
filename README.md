**Yii2 Annotations extension**

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
            
            'cache' => '\yii\caching\FileCache' (Optional. Container with cache component defined
                        in configuration or cache class. By default 'cache'),
                        
            'path' => '@runtime/annotations' (Optional. Annotations cache dir for \yii\caching\FileCache),
            
            'debug' => false (Optional. Debug enable)
        ]
    ],
...
````  

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

More info [doctrine/annotations custom annotations](https://www.doctrine-project.org/projects/doctrine-annotations/en/latest/custom.html#custom-annotation-classes)