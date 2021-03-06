<?php

namespace yii\annotations;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\DocParser;
use Exception;
use Yii;
use yii\base\Component;
use yii\caching\CacheInterface;
use yii\caching\FileCache;
use yii\di\Instance;

/**
 * Class Annotations
 * @property CacheInterface|object $cacheComponent
 * @property FileCache $defaultCache
 */
class Annotations extends Component implements AnnotationsInterface, ParserInterface
{
    /**
     * @var string|CacheInterface
     */
    public $cache = 'cache';

    /**
     * @var bool
     */
    public $debug = false;

    /**
     * @var string
     */
    public $path;

    /**
     * @var AnnotationReader
     */
    private $reader;

    /**
     * @var array
     */
    public $ignoreAnnotations = [];

    /**
     * @var CacheInterface
     */
    private $cacheComponent;

    /**
     * @var FileCache
     */
    private const DEFAULT_CACHE = FileCache::class;

    /**
     * @var string
     */
    protected const CACHE_PREFIX = '.annotations';

    /**
     */
    public function init(): void
    {
        parent::init();
        $this->registerLoader();
        $this->enableCacheComponent();
    }

    /** {@inheritDoc} */
    public function getReader(): AnnotationCacheReader
    {
        $this->enableNewReader();
        return new AnnotationCacheReader(
            $this->reader,
            new AnnotationCache($this->cacheComponent),
            $this->debug
        );
    }

    /**
     * @return void
     */
    private function enableCacheComponent(): void
    {
        $this->cacheComponent = (is_string($this->cache)
                ? $this->tryInstantiateCache()
                : $this->cache) ?? $this->getDefaultCache();
        $this->configurationCache();
    }

    /**
     * @return object|CacheInterface|null
     */
    private function tryInstantiateCache()
    {
        try {
            return Instance::ensure($this->cache);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Return default cache
     */
    private function getDefaultCache()
    {
        $defaultCache = static::DEFAULT_CACHE;
        return new $defaultCache();
    }

    /**
     * @return void
     */
    private function configurationCache(): void
    {
        $this->trySetCachePath();
        $this->trySetCacheFileSuffix();
    }

    /**
     * @return void
     */
    private function trySetCachePath(): void
    {
        if (property_exists($this->cacheComponent, 'cachePath') && $this->path !== null) {
            $this->cacheComponent->cachePath = Yii::getAlias($this->path);
        }
    }

    /**
     * @return void
     */
    private function trySetCacheFileSuffix(): void
    {
        if (property_exists($this->cacheComponent, 'cacheFileSuffix')) {
            $this->cacheComponent->cacheFileSuffix = static::CACHE_PREFIX;
        }
    }

    /**
     *
     */
    private function registerLoader()
    {
        if (method_exists(AnnotationRegistry::class, 'registerLoader')) {
            /** @scrutinizer ignore-deprecated */
            /** @noinspection PhpDeprecationInspection */
            AnnotationRegistry::registerLoader('class_exists');
        }
    }

    /**
     * @return void
     * @throws AnnotationException
     */
    private function enableNewReader(): void
    {
        $this->reader = new AnnotationReader();
        foreach ($this->ignoreAnnotations as $annotation) {
            $this->reader::addGlobalIgnoredName($annotation);
        }
    }

    /** {@inheritDoc} */
    public function getParser(array $importAnnotations = []): DocParser
    {
        $parser = new DocParser();
        $parser->setImports($importAnnotations);
        $parser->setIgnoreNotImportedAnnotations(true);
        return $parser;
    }
}
