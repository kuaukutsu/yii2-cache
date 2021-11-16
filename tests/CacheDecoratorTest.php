<?php

declare(strict_types=1);

namespace kuaukutsu\cache\tests;

use Yii;
use yii\base\InvalidConfigException;
use kuaukutsu\cache\CacheDecorator;
use kuaukutsu\cache\tests\stub\TestCache;

final class CacheDecoratorTest extends YiiTestCase
{
    private TestCache $cache;

    /**
     * @throws InvalidConfigException
     */
    protected function setUp(): void
    {
        parent::setUp();

        /**
         * @var CacheDecorator cache
         * @psalm-suppress UndefinedClass
         * @psalm-suppress PropertyTypeCoercion
         */
        $this->cache = Yii::createObject(TestCache::class);
        $this->cache->getCacheInterface()->flush();
    }

    public function testGenerateKey(): void
    {
        $key = $this->cache->generateKey('test', 23);
        $key2 = $this->cache->generateKey('test', 23);

        self::assertEquals($key, $key2);

        $key3 = $this->cache->generateKey('test', 231);

        self::assertNotEquals($key, $key3);
    }

    public function testGenerateTagDependency(): void
    {
        $key = $this->cache->generateTagDependency('test', 23);
        $key2 = $this->cache->generateTagDependency('test', 23);

        self::assertEquals($key, $key2);

        $key3 = $this->cache->generateTagDependency('test', 231);

        self::assertNotEquals($key, $key3);
    }

    public function testCacheKey(): void
    {
        $value = 'test-value-22';
        $cacheKey = $this->cache->generateKey('test');

        $valueResult = $this->cache->getCacheInterface()
            ->getOrSet(
                $cacheKey,
                fn() => $value,
                $this->cache->getDuration(),
            );

        self::assertEquals($value, $valueResult);

        self::assertTrue($this->cache->getCacheInterface()->exists($cacheKey));
        self::assertEquals($value, $this->cache->getCacheInterface()->get($cacheKey));

        $this->cache->delete('test');

        self::assertFalse($this->cache->getCacheInterface()->exists($cacheKey));
        self::assertFalse($this->cache->get($cacheKey));
    }

    /**
     * exists: that this method does not check whether the dependency associated
     * with the cached data, if there is any, has changed. So a call to [[get]]
     * may return false while exists returns true.
     */
    public function testCacheTagDependency(): void
    {
        $value = 'test-value-22';
        $cacheParams = [22, 'tag'];
        $cacheKey = $this->cache->generateKey(...$cacheParams);

        self::assertFalse($this->cache->getCacheInterface()->get($cacheKey));

        $isWrite = $this->cache->getCacheInterface()
            ->set(
                $cacheKey,
                $value,
                $this->cache->getDuration(),
                $this->cache->generateTagDependency(...$cacheParams)
            );

        self::assertTrue($isWrite);
        self::assertEquals($value, $this->cache->getCacheInterface()->get($cacheKey));

        $this->cache->invalidate(...$cacheParams);

        self::assertFalse($this->cache->get($cacheKey));
    }

    /**
     * exists: that this method does not check whether the dependency associated
     * with the cached data, if there is any, has changed. So a call to [[get]]
     * may return false while exists returns true.
     */
    public function testCacheTagDependencyFlush(): void
    {
        $valueOne = 'test-value-1';
        $cacheParamsOne = [1, 'test', 'tag'];
        $cacheKeyOne = $this->cache->generateKey(...$cacheParamsOne);

        $valueTwo = 'test-value-2';
        $cacheParamsTwo = [2, 'test', 'tag'];
        $cacheKeyTwo = $this->cache->generateKey(...$cacheParamsTwo);

        $this->cache->getCacheInterface()
            ->getOrSet(
                $cacheKeyOne,
                fn() => $valueOne,
                $this->cache->getDuration(),
                $this->cache->generateTagDependency(...$cacheParamsOne)
            );

        $this->cache->getOrSet(fn() => $valueTwo, $cacheParamsTwo);

        self::assertEquals($valueOne, $this->cache->getCacheInterface()->get($cacheKeyOne));
        self::assertEquals($valueTwo, $this->cache->getCacheInterface()->get($cacheKeyTwo));

        $this->cache->flush();

        self::assertFalse($this->cache->get($cacheKeyOne));
        self::assertFalse($this->cache->get($cacheKeyTwo));
    }

    public function testCacheGetOrSet(): void
    {
        $value = time();
        $cacheParams = [1212, __METHOD__];
        $cacheKey = $this->cache->generateKey(...$cacheParams);

        $valueResult = $this->cache->getOrSet(
            fn() => $value,
            $cacheParams
        );

        self::assertEquals($value, $valueResult);
        self::assertEquals($value, $this->cache->getCacheInterface()->get($cacheKey));

        $this->cache->getCacheInterface()->delete($cacheKey);

        self::assertFalse($this->cache->getCacheInterface()->exists($cacheKey));
        self::assertFalse($this->cache->get($cacheKey));
    }
}
