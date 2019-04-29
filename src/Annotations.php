<?php
namespace yii\annotations;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\CachedReader;
use yii\base\InvalidConfigException;
use yii\caching\CacheInterface;
use yii\caching\FileCache;
use yii\di\Instance;

/**
 * Class Annotations
 */
class Annotations
{
    /**
     * @var string|CacheInterface
     */
    public $cache = false;

    /**
     * @var bool
     */
    public $debug = false;

    /**
     * @var string
     */
    public $path;

    /**
     * @var CachedReader
     */
    private $reader;

    /**
     *
     */
    protected const CACHE_PREFIX = 'annotations.';

    /**
     * Annotations constructor.
     * @throws AnnotationException
     * @throws InvalidConfigException
     */
    public function init(): void
    {
        if (method_exists(AnnotationRegistry::class, 'registerLoader')) {
            AnnotationRegistry::registerLoader('class_exists');
        }
        $cacheComponent = is_string($this->cache) ? Instance::ensure($this->cache) : $this->cache;
        if (!$cacheComponent) {
            $fileCache = new FileCache();
            $fileCache->cacheFileSuffix = static::CACHE_PREFIX;
            if ($this->path !== null) {
                $fileCache->cachePath = $this->path;
            }
            $cacheComponent = new AnnotationCache($fileCache);

        }
        $this->reader = new CachedReader(
            new AnnotationReader(),
            new AnnotationCache($cacheComponent),
            $this->debug
        );
    }

    /**
     * @return CachedReader
     */
    public function getReader(): CachedReader
    {
        return $this->reader;
    }
}