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
 */
class Annotations extends Component
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
        if (method_exists(AnnotationRegistry::class, 'registerLoader')) {
            AnnotationRegistry::registerLoader('class_exists');
        }
        $cacheComponent = is_string($this->cache) ? Instance::ensure($this->cache) : $this->cache;
        if (!$cacheComponent) {
            $cacheComponent = new FileCache();
        }
        if ($cacheComponent instanceof FileCache && $this->path !== null) {
            $cacheComponent->cachePath = Yii::getAlias($this->path);
            $cacheComponent->cacheFileSuffix = static::CACHE_PREFIX;
        }
        $this->reader = new AnnotationCacheReader(
            new AnnotationReader(),
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
}
