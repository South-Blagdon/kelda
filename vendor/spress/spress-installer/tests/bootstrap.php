<?php

$loader = require __DIR__.'/../vendor/autoload.php';
$loader->add('Composer\Test', __DIR__.'/../vendor/composer/composer/tests');
$loader->add('Yosymfony\Spress\Composer', __DIR__);
