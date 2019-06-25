<?php

namespace yii\annotations;

use yii\caching\CacheInterface;

/**
 * Class AnnotationCache.
 */
class AnnotationCache implements AnnotationCacheInterface
{
    /**
     * @var CacheInterface
     */
    private $yiiCache;

    /**
     * AnnotationCache constructor.
     *
     * @param CacheInterface $yiiCache
     */
    public function __construct(CacheInterface $yiiCache)
    {
        $this->yiiCache = $yiiCache;
    }

    /**
     * {@inheritdoc}
     */
    public function fetch($id)
    {
        return $this->yiiCache->get($id);
    }

    /**
     * {@inheritdoc}
     */
    public function contains($id): bool
    {
        return $this->yiiCache->exists($id);
    }

    /**
     * {@inheritdoc}
     */
    public function save($id, $data, $lifeTime = 0): bool
    {
        return $this->yiiCache->set($id, $data, $lifeTime);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id): bool
    {
        return $this->yiiCache->delete($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getStats(): ?array
    {
        return null;
    }
}
