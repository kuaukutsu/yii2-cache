<?php

declare(strict_types=1);

namespace kuaukutsu\cache;

use Closure;
use yii\caching\CacheInterface;
use yii\caching\TagDependency;

/**
 * Декоратор механизма кеширования.
 * Цель:
 * - унифицировать процесс записи и инвалидации кеша
 *
 * @example:
 * Model cache
 * ```php
 *  $model = $this->db->cache(
 *      fn() => Model::findOne(['one' => IDENTITY, 'two' => IDENTITY2, 'three' => IDENTITY3]),
 *      86400,
 *      $this->cache->generateTagDependency(IDENTITY, IDENTITY2, IDENTITY3)
 *  );
 * ```
 * Value cache
 * ```php
 * $cache->getCacheInterface()->getOrSet(
 *      $cache->generateKey(1,'key'),
 *      fn() => 1, // Closure query
 *      $cache->getDuration(),
 *      $cache->generateTagDependency(1,'tag')
 * )
 * ```
 *
 * Short
 * ```php
 * $value = $cache->getOrSet(
 *      fn() => 11,
 *      [11, 'tag']
 * ):
 * ```
 *
 * Cache invalidate:
 * ```
 * $this->cache->invalidate(IDENTITY, IDENTITY2, IDENTITY3);
 * ```
 *
 * Cache flush:
 * ```
 * $this->cache->flush();
 * ```
 */
interface CacheDecorator
{
    /**
     * Обёртка над CacheInterface::getOrSet
     *
     * @param Closure $callable
     * @param scalar[] $conditions
     * @return mixed
     */
    public function getOrSet(Closure $callable, array $conditions);

    /**
     * Обёртка над CacheInterface::get
     *
     * @param string $key a key identifying the value to be cached. This can be a simple string.
     * @return mixed|false
     */
    public function get(string $key);

    /**
     * Обёртка над CacheInterface::set
     *
     * @param string $key a key identifying the value to be cached. This can be a simple string.
     * @param mixed $value the value to be cached
     * @return bool
     */
    public function set(string $key, $value): bool;

    /**
     * Обёртка над CacheInterface::delete
     *
     * @param string $key a key identifying the value to be cached. This can be a simple string.
     */
    public function delete(string $key): void;

    /**
     * @return int Default value: 3600 second
     */
    public function getDuration(): int;

    /**
     * @return CacheInterface
     * @see https://www.yiiframework.com/doc/guide/2.0/en/caching-data
     */
    public function getCacheInterface(): CacheInterface;

    /**
     * Генерация ключа для кеша на основе входящих аргументов
     *
     * @param scalar ...$args
     * @return string
     */
    public function generateKey(...$args): string;

    /**
     * Генерация зависимости для кеша на основе TagDependency
     *
     * @param scalar ...$args
     * @return TagDependency
     */
    public function generateTagDependency(...$args): TagDependency;

    /**
     * Инвалидация ключа по параметрам
     *
     * @param scalar ...$args
     */
    public function invalidate(...$args): void;

    /**
     * Полная инвалидация всех ключей для рабочей модели.
     */
    public function flush(): void;
}
