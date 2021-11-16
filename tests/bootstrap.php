<?php

// ensure we get report on all possible php errors
error_reporting(E_ALL);

define('YII_ENV', 'test');

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

Yii::setAlias('@yiiunit', __DIR__);

if (getenv('TEST_RUNTIME_PATH')) {
    Yii::setAlias('@yiiunit/runtime', getenv('TEST_RUNTIME_PATH'));
    Yii::setAlias('@runtime', getenv('TEST_RUNTIME_PATH'));
}

require_once __DIR__ . '/YiiTestCase.php';
