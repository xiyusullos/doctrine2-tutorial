<?php
/**
 * Created by PhpStorm.
 * User: xiyusullos
 * Date: 2017/3/7
 * Time: 21:48
 */

// bootstrap.php
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

require_once "vendor/autoload.php";

// Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration([__DIR__ . '/src'], $isDevMode);
// or if you refer yaml or XML
// $config = Setup::createXMLMetadataConfiguration([__DIR__ .'/src'], $isDevMode);
// $config = Setup::createYAMLMetadataConfiguration([__DIR__ .'/src'], $isDevMode);

// database configuration parameters
$conn = [
    'driver' => 'pdo_sqlite',
    'path' => __DIR__ .'/db.sqlite',
];

// obtaining the entity manager
$entityManager = EntityManager::create($conn, $config);
