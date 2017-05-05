<?php

/** @var Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__.'/../vendor/autoload.php';
$loader->setPsr4('Chubbyphp\Tests\Model\\', __DIR__);

$chubbyphpModelResourceDir = __DIR__.'/Resources';

$loader->addClassMap([
    \MyProject\Model\MyModel::class => $chubbyphpModelResourceDir.'/Model/MyModel.php',
    \MyProject\Model\MyEmbeddedModel::class => $chubbyphpModelResourceDir.'/Model/MyEmbeddedModel.php',
    \MyProject\Repository\AbstractRepository::class => $chubbyphpModelResourceDir.'/Repository/AbstractRepository.php',
    \MyProject\Repository\MyEmbeddedRepository::class => $chubbyphpModelResourceDir.'/Repository/MyEmbeddedRepository.php',
    \MyProject\Repository\MyModelRepository::class => $chubbyphpModelResourceDir.'/Repository/MyModelRepository.php',
]);
