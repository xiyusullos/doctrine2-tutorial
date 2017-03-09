<?php
/**
 * Created by PhpStorm.
 * User: xiyusullos
 * Date: 2017/3/9
 * Time: 16:52
 */
// show_product.php <id>
require_once 'bootstrap.php';

$id = $argv[1];
$product = $entityManager->find('Product', $id);

if (null === $product) {
    echo "No product found.\n";
    exit(1);
}

echo sprintf("-%s\n", $product->getName());