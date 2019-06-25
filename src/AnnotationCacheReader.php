<?php

namespace yii\annotations;

use Doctrine\Common\Annotations\Reader;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

/**
 * Class AnnotationCacheReader.
 */
final class AnnotationCacheReader implements Reader
{
    /**
     * @var Reader
     */
    private $delegate;

    /**
     * @var AnnotationCacheInterface
     */
    private $cache;

    /**
     * @var bool
     */
    private $debug;

    /**
     * @var array
     */
    private $loadedAnnotations = [];

    /**
     * Constructor.
     *
     * @param Reader                   $reader
     * @param AnnotationCacheInterface $cache
     * @param bool                     $debug
     */
    public function __construct(Reader $reader, AnnotationCacheInterface $cache, $debug = false)
    {
        $this->delegate = $reader;
        $this->cache = $cache;
        $this->debug = (bool) $debug;
    }

    /**
     * {@inheritdoc}
     */
    public function getClassAnnotations(ReflectionClass $class)
    {
        $cacheKey = $class->getName();

        if (isset($this->loadedAnnotations[$cacheKey])) {
            return $this->loadedAnnotations[$cacheKey];
        }

        if (false === ($annots = $this->fetchFromCache($cacheKey, $class))) {
            $annots = $this->delegate->getClassAnnotations($class);
            $this->saveToCache($cacheKey, $annots);
        }

        return $this->loadedAnnotations[$cacheKey] = $annots;
    }

    /**
     * {@inheritdoc}
     */
    public function getClassAnnotation(ReflectionClass $class, $annotationName)
    {
        foreach ($this->getClassAnnotations($class) as $annot) {
            if ($annot instanceof $annotationName) {
                return $annot;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPropertyAnnotations(ReflectionProperty $property)
    {
        $class = $property->getDeclaringClass();
        $cacheKey = $class->getName().'$'.$property->getName();

        if (isset($this->loadedAnnotations[$cacheKey])) {
            return $this->loadedAnnotations[$cacheKey];
        }

        if (false === ($annots = $this->fetchFromCache($cacheKey, $class))) {
            $annots = $this->delegate->getPropertyAnnotations($property);
            $this->saveToCache($cacheKey, $annots);
        }

        return $this->loadedAnnotations[$cacheKey] = $annots;
    }

    /**
     * {@inheritdoc}
     */
    public function getPropertyAnnotation(ReflectionProperty $property, $annotationName)
    {
        foreach ($this->getPropertyAnnotations($property) as $annot) {
            if ($annot instanceof $annotationName) {
                return $annot;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getMethodAnnotations(ReflectionMethod $method)
    {
        $class = $method->getDeclaringClass();
        $cacheKey = $class->getName().'#'.$method->getName();

        if (isset($this->loadedAnnotations[$cacheKey])) {
            return $this->loadedAnnotations[$cacheKey];
        }

        if (false === ($annots = $this->fetchFromCache($cacheKey, $class))) {
            $annots = $this->delegate->getMethodAnnotations($method);
            $this->saveToCache($cacheKey, $annots);
        }

        return $this->loadedAnnotations[$cacheKey] = $annots;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethodAnnotation(ReflectionMethod $method, $annotationName)
    {
        foreach ($this->getMethodAnnotations($method) as $annot) {
            if ($annot instanceof $annotationName) {
                return $annot;
            }
        }
    }

    /**
     * Clears loaded annotations.
     *
     * @return void
     */
    public function clearLoadedAnnotations(): void
    {
        $this->loadedAnnotations = [];
    }

    /**
     * Fetches a value from the cache.
     *
     * @param string          $cacheKey The cache key.
     * @param ReflectionClass $class    The related class.
     *
     * @return mixed The cached value or false when the value is not in cache.
     */
    private function fetchFromCache($cacheKey, ReflectionClass $class)
    {
        if ((($data = $this->cache->fetch($cacheKey)) !== false) && $this->isCanReturnCacheData($cacheKey, $class)) {
            return $data;
        }

        return false;
    }

    /**
     * @param $cacheKey
     * @param ReflectionClass $class
     *
     * @return bool
     */
    private function isCanReturnCacheData($cacheKey, ReflectionClass $class): bool
    {
        return !$this->debug || $this->isCacheFresh($cacheKey, $class);
    }

    /**
     * Saves a value to the cache.
     *
     * @param string $cacheKey The cache key.
     * @param mixed  $value    The value.
     *
     * @return void
     */
    private function saveToCache($cacheKey, $value): void
    {
        $this->cache->save($cacheKey, $value);
        if ($this->debug) {
            $this->cache->save('[C]'.$cacheKey, time());
        }
    }

    /**
     * Checks if the cache is fresh.
     *
     * @param string          $cacheKey
     * @param ReflectionClass $class
     *
     * @return bool
     */
    private function isCacheFresh($cacheKey, ReflectionClass $class): bool
    {
        $lastModification = $this->getLastModification($class);
        if (null === $lastModification) {
            return true;
        }

        return $this->cache->fetch('[C]'.$cacheKey) >= $lastModification;
    }

    /**
     * Returns the time the class was last modified, testing traits and parents.
     *
     * @param ReflectionClass $class
     *
     * @return mixed
     */
    private function getLastModification(ReflectionClass $class)
    {
        $filename = $class->getFileName();
        $parent = $class->getParentClass();

        return max(array_merge(
            [$filename ? filemtime($filename) : 0],
            array_map([$this, 'getTraitLastModificationTime'], $class->getTraits()),
            array_map([$this, 'getLastModification'], $class->getInterfaces()),
            $parent ? [$this->getLastModification($parent)] : []
        ));
    }

    /**
     * @param ReflectionClass $reflectionTrait
     *
     * @return mixed
     */
    private function getTraitLastModificationTime(ReflectionClass $reflectionTrait)
    {
        $fileName = $reflectionTrait->getFileName();

        return max(array_merge(
            [$fileName ? filemtime($fileName) : 0],
            array_map([$this, 'getTraitLastModificationTime'], $reflectionTrait->getTraits())
        ));
    }
}
