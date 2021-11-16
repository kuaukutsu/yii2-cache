<?php

declare(strict_types=1);

namespace kuaukutsu\cache\tests;

use PHPUnit\Framework\TestCase;
use yii\helpers\ArrayHelper;
use yii\web\Application;

abstract class YiiTestCase extends TestCase
{
    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->mockWebApplication();
    }

    /**
     * @param array $config
     * @param string $appClass
     */
    protected function mockWebApplication(array $config = [], string $appClass = Application::class): void
    {
        new $appClass(
            ArrayHelper::merge(
                [
                    'id' => 'testapp-cache',
                    'basePath' => __DIR__,
                    'vendorPath' => $this->getVendorPath(),
                    'language' => 'ru_RU',
                    'timeZone' => 'Europe/Moscow',
                    // singletons & definitions
                    'container' => require __DIR__ . '/container.php',
                ],
                $config
            )
        );
    }

    /**
     * @return string
     */
    protected function getVendorPath(): string
    {
        $vendor = dirname(__DIR__, 2) . '/vendor';
        if (is_dir($vendor) === false) {
            $vendor = dirname(__DIR__, 4);
        }

        return $vendor;
    }
}
