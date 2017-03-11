<?php

require_once 'bootstrap.php';

$productName = 'DBAL';

$product = $entityManager->getRepository('Product')
                         ->findOneBy(array('name' => $productName));

var_dump($product);

$bugs = $entityManager->getRepository('Bug')
    ->findBy(array('status' => 'CLOSED'));

foreach ($bugs as $bug) {
    // do stuff
}