<?php

namespace yii\annotations\tests\data;

use yii\annotations\Annotations;
use yii\base\InvalidConfigException;
use yii\console\Application as ConsoleApplication;
use yii\helpers\ArrayHelper;
use yii\web\Application as WebApplication;

/**
 * Class AppHelper.
 */
class AppHelper
{
    /**
     * Populates Yii::$app with a new application
     * The application will be destroyed on tearDown() automatically.
     *
     * @param array $config The application configuration, if needed
     *
     * @throws InvalidConfigException
     */
    public static function testWebApplication($config = []): void
    {
        new WebApplication(ArrayHelper::merge(ArrayHelper::merge([
            'id' => 'testWebApp',
        ], static::baseConfig()), $config));
    }

    /**
     * Populates Yii::$app with a new application
     * The application will be destroyed on tearDown() automatically.
     *
     * @param array $config The application configuration, if needed
     *
     * @throws InvalidConfigException
     */
    public static function testConsoleApplication($config = []): void
    {
        new ConsoleApplication(ArrayHelper::merge(ArrayHelper::merge([
            'id' => 'testConsoleApp',
        ], static::baseConfig()), $config));
    }

    /**
     * @return array
     */
    protected static function baseConfig(): array
    {
        return [
            'basePath'   => __DIR__,
            'vendorPath' => dirname(__DIR__).'/vendor',
            'components' => [
                'annotation' => [
                    'class' => Annotations::class,
                ],
            ],
        ];
    }
}
