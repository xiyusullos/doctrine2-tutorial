<?php
/**
 * Created by PhpStorm.
 * User: xiyusullos
 * Date: 2017/3/9
 * Time: 16:49
 */

// list_products.php
require_once 'bootstrap.php';

$productRepository = $entityManager->getRepository('Product');
$products = $productRepository->findAll();

foreach ($products as $product) {
    echo sprintf("-%s\n", $product->getName());
}