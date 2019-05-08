<?php
namespace yii\annotations;

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
     * @var CacheInterface
     */
    private $cacheComponent;

    /**
     * @var FileCache
     */
    private const DEFAULT_CACHE = FileCache::class;
    /**
     *
     */
    protected const CACHE_PREFIX = '.annotations';

    /**
     * @throws InvalidConfigException
     */
    public function init(): void
    {
        parent::init();
        $this->registerLoader();
        $this->enableCacheComponent();
    }

    /**
     * @return AnnotationCacheReader
     */
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
     * @throws InvalidConfigException
     */
    private function enableCacheComponent(): void
    {
        $this->cacheComponent = (is_string($this->cache)
                ? Instance::ensure($this->cache)
                : $this->cache) ?? $this->getDefaultCache();
        $this->configurationCache();
    }

    /**
     * Return default cache
     */
    private function getDefaultCache()
    {
        return self::DEFAULT_CACHE;
    }

    /**
     *
     */
    private function configurationCache()
    {
        if (property_exists($this->cacheComponent, 'cachePath') &&  $this->path !== null) {
            $this->cacheComponent->cachePath = Yii::getAlias($this->path);
        }
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
            AnnotationRegistry::registerLoader('class_exists');
        }
    }

    /**
     *
     */
    private function enableNewReader()
    {
        $this->reader = new AnnotationReader();
        foreach ($this->ignoreAnnotations as $annotation) {
            $this->reader::addGlobalIgnoredName($annotation);
        }
    }
}
