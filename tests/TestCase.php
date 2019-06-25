<?php

namespace yii\annotations\tests;

use /* @noinspection PhpUndefinedClassInspection */
    PHPUnit\Framework\TestCase as BaseTestCase;
use ReflectionClass;
use ReflectionException;
use Yii;
use yii\di\Container;

/** @noinspection PhpUndefinedClassInspection */

/**
 * This is the base class for all yii framework unit tests.
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * @var array
     */
    public static $params;

    /**
     * Clean up after tests in test class.
     * By default the application created with [[testApplication]] will be destroyed.
     */
    public static function tearDownAfterClass()
    {
        /* @noinspection PhpUndefinedClassInspection */
        parent::tearDownAfterClass();
        self::destroyApplication();
    }

    /**
     * Returns a test configuration param from /data/config.php.
     *
     * @param string $name    params name
     * @param mixed  $default default value to use when param is not set.
     *
     * @return mixed the value of the configuration param
     */
    public static function getParam($name, $default = null)
    {
        return static::$params[$name] ?? $default;
    }

    /**
     * Destroys application in Yii::$app by setting it to null.
     */
    protected static function destroyApplication(): void
    {
        Yii::$app = null;
        Yii::$container = new Container();
    }

    /**
     * Invokes object method, even if it is private or protected.
     *
     * @param object $object object.
     * @param string $method method name.
     * @param array  $args   method arguments
     *
     * @throws ReflectionException
     *
     * @return mixed method result
     */
    protected function invoke($object, $method, array $args = [])
    {
        $classReflection = new ReflectionClass(get_class($object));
        $methodReflection = $classReflection->getMethod($method);
        $methodReflection->setAccessible(true);
        $result = $methodReflection->invokeArgs($object, $args);
        $methodReflection->setAccessible(false);

        return $result;
    }

    /**
     * @param $object
     * @param $property
     *
     * @throws ReflectionException
     *
     * @return mixed
     */
    protected function getProtectedProperty($object, $property)
    {
        $reflection = new ReflectionClass($object);
        $reflection_property = $reflection->getProperty($property);
        $reflection_property->setAccessible(true);
        $property = $reflection_property->getValue($object);
        $reflection_property->setAccessible(false);

        return $property;
    }
}
