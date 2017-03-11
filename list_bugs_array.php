<?php
/**
 * Created by PhpStorm.
 * User: xiyusullos
 * Date: 2017/3/11
 * Time: 11:31
 */

// list_bugs_array.php
require_once 'bootstrap.php';

// $dql = "SELECT b, e, r FROM Bug b JOIN b.engineer e JOIN b.reporter r ORDER BY b.created DESC";
$dql = "SELECT b, e, r, p FROM Bug b JOIN b.engineer e "
    ."JOIN b.reporter r JOIN b.products p ORDER BY b.created DESC";

$query = $entityManager->createQuery($dql);
$query->setMaxResults(30);
// $bugs = $query->getResult();
$bugs = $query->getArrayResult();

foreach ($bugs as $bug) {
    echo "{$bug['description']} - {$bug['created']->format('d.m.Y')}\n";
    echo "  Reported by: {$bug['reporter']['name']}\n";
    echo "  Assigned to: {$bug['engineer']['name']}\n";
    foreach ($bug['products'] as $product) {
        echo "  Platform: {$product['name']}\n";
    }
    echo "\n";
}