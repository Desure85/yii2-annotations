<?php

require_once __DIR__.'/debug_config.php';
ini_set('error_reporting', -1);

$_SERVER['SCRIPT_NAME'] = '/'.__FILE__;
$_SERVER['SCRIPT_FILENAME'] = __FILE__;
$_SERVER['REQUEST_URI'] = '/';

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../vendor/yiisoft/yii2/Yii.php';
