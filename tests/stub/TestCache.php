<?php

declare(strict_types=1);

namespace kuaukutsu\cache\tests\stub;

use kuaukutsu\cache\CacheDecoratorBase;

final class TestCache extends CacheDecoratorBase
{
    public function getDuration(): int
    {
        return 60;
    }

    protected function generateUniqueKey(array $conditions): string
    {
        return hash('crc32b', self::class . implode(':', $conditions));
    }

    protected function generateShareKey(): string
    {
        return hash('crc32b', self::class);
    }
}
