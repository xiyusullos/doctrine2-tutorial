<?php
/**
 * Created by PhpStorm.
 * User: xiyusullos
 * Date: 2017/3/11
 * Time: 12:06
 */

// products.php
require_once "bootstrap.php";

$dql = "SELECT p.id, p.name, count(b.id) AS openBugs FROM Bug b "
    ."JOIN b.products p WHERE b.status = 'OPEN' GROUP BY p.id";
$productBugs = $entityManager->createQuery($dql)->getScalarResult();

foreach ($productBugs as $productBug) {
    echo $productBug['name']." has " . $productBug['openBugs'] . " open bugs!\n";
}