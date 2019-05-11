<?php
namespace yii\annotations;

use yii\caching\CacheInterface;

/**
 * Class AnnotationCache
 * @package yii\annotations
 */
class AnnotationCache implements AnnotationCacheInterface
{
    /**
     * @var CacheInterface
     */
    private $yiiCache;

    /**
     * AnnotationCache constructor.
     * @param CacheInterface $yiiCache
     */
    public function __construct(CacheInterface $yiiCache)
    {
        $this->yiiCache = $yiiCache;
    }

    /**
     * {@inheritDoc}
     */
    public function fetch($id)
    {
        return $this->yiiCache->get($id);
    }

    /**
     * {@inheritDoc}
     */
    public function contains($id): bool
    {
        return $this->yiiCache->exists($id);
    }

    /**
     * {@inheritDoc}
     */
    public function save($id, $data, $lifeTime = 0): bool
    {
        return $this->yiiCache->set($id, $data, $lifeTime);
    }

    /**
     * {@inheritDoc}
     */
    public function delete($id): bool
    {
        return $this->yiiCache->delete($id);
    }

    /**
     * {@inheritDoc}
     */
    public function getStats(): ?array
    {
        return null;
    }
}
