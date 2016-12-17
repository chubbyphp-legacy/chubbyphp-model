<?php

/** @var Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__.'/../vendor/autoload.php';
$loader->setPsr4('Chubbyphp\Tests\Model\\', __DIR__);
$loader->addClassMap([
    \MyProject\Model\MyModel::class => __DIR__ .'/Resources/Model/MyModel.php',
    \MyProject\Model\MyEmbeddedModel::class => __DIR__ .'/Resources/Model/MyEmbeddedModel.php',
    \MyProject\Repository\AbstractRepository::class => __DIR__ .'/Resources/Repository/AbstractRepository.php',
    \MyProject\Repository\MyEmbeddedRepository::class => __DIR__ .'/Resources/Repository/MyEmbeddedRepository.php',
    \MyProject\Repository\MyModelRepository::class => __DIR__ .'/Resources/Repository/MyModelRepository.php',
]);
