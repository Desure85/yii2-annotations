<?php

namespace yii\annotations\tests\Unit;

use Yii;
use yii\annotations\tests\data\AppHelper;
use yii\base\InvalidConfigException;
use yii\web\Application as WebApplication;

/**
 * Class AnnotationsTest.
 */
class AnnotationsWebAppTest extends AnnotationsTest
{
    /**
     * @throws InvalidConfigException
     */
    protected function setUp()
    {
        parent::setUp();
        AppHelper::testWebApplication();
    }

    public function testWebInit(): void
    {
        $this->assertInstanceOf(WebApplication::class, Yii::$app);
    }
}
