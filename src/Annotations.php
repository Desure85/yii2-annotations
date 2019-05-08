<?php
namespace yii\annotations;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\caching\CacheInterface;
use yii\caching\FileCache;
use yii\di\Instance;

/**
 * Class Annotations
 * @property CacheInterface|object $cacheComponent
 * @property FileCache $defaultCache
 */
class Annotations extends Component implements AnnotationsInterface
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
     * @var AnnotationCacheReader
     */
    private $reader;

    /**
     * @var array
     */
    public $ignoreAnnotations = [];

    /**
     * @var FileCache
     */
    private const DEFAULT_CACHE = FileCache::class;
    /**
     *
     */
    protected const CACHE_PREFIX = '.annotations';

    /**
     * @throws AnnotationException
     * @throws InvalidConfigException
     */
    public function init(): void
    {
        parent::init();
        $this->registerLoader();
        $cacheComponent = $this->getCacheComponent();
        $this->configurationCache($cacheComponent);
        $reader = new AnnotationReader();
        $this->configurationReader($reader);
        $this->reader = new AnnotationCacheReader(
            $reader,
            new AnnotationCache($cacheComponent),
            $this->debug
        );
    }

    /**
     * @return AnnotationCacheReader
     */
    public function getReader(): AnnotationCacheReader
    {
        return $this->reader;
    }

    /**
     * @return object|CacheInterface
     * @throws InvalidConfigException
     */
    private function getCacheComponent(): CacheInterface
    {
        return (is_string($this->cache) ? Instance::ensure($this->cache) : $this->cache) ?? $this->getDefaultCache();
    }

    /**
     * Return default cache
     */
    private function getDefaultCache()
    {
        return self::DEFAULT_CACHE;
    }

    /**
     * @param $cacheComponent
     */
    private function configurationCache(CacheInterface $cacheComponent)
    {
        if (property_exists($cacheComponent, 'cachePath') &&  $this->path !== null) {
            $cacheComponent->cachePath = Yii::getAlias($this->path);
        }
        if (property_exists($cacheComponent, 'cacheFileSuffix')) {
            $cacheComponent->cacheFileSuffix = static::CACHE_PREFIX;
        }
    }

    private function registerLoader()
    {
        if (method_exists(AnnotationRegistry::class, 'registerLoader')) {
            AnnotationRegistry::registerLoader('class_exists');
        }
    }

    /**
     * @param $reader
     */
    private function configurationReader(AnnotationReader $reader)
    {
        foreach ($this->ignoreAnnotations as $annotation) {
            $reader::addGlobalIgnoredName($annotation);
        }
    }
}
