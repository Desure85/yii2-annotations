<?php

namespace yii\annotations\tests\Unit;

use Yii;
use yii\annotations\tests\data\AppHelper;
use yii\base\InvalidConfigException;
use yii\console\Application as ConsoleApplication;

/**
 * Class AnnotationsTest.
 */
class AnnotationsConsoleAppTest extends AnnotationsTest
{
    /**
     * @throws InvalidConfigException
     */
    protected function setUp()
    {
        parent::setUp();
        AppHelper::testConsoleApplication();
    }

    public function testConsoleInit(): void
    {
        $this->assertInstanceOf(ConsoleApplication::class, Yii::$app);
    }
}
