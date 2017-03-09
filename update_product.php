<?php
/**
 * Created by PhpStorm.
 * User: xiyusullos
 * Date: 2017/3/9
 * Time: 16:57
 */
// update_product.php <id> <new-name>
require_once 'bootstrap.php';

$id = $argv[1];
$newName = $argv[2];
$product = $entityManager->find('Product', $id);

if (null === $product) {
    echo sprintf("Product $id does not exist.\n");
    exit(1);
}

$product->setName($newName);

$entityManager->flush();
