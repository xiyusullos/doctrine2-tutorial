<?php
/**
 * Created by PhpStorm.
 * User: xiyusullos
 * Date: 2017/3/11
 * Time: 10:33
 */

// create_product.php
require_once "bootstrap.php";

$newProductName = $argv[1];

$product = new Product();
$product->setName($newProductName);

$entityManager->persist($product);
$entityManager->flush();

echo "Created Product with ID " . $product->getId() . "\n";