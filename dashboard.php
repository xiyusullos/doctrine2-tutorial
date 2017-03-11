<?php
/**
 * Created by PhpStorm.
 * User: xiyusullos
 * Date: 2017/3/11
 * Time: 11:50
 */

// dashboard.php
require_once "bootstrap.php";

$theUserId = $argv[1];

$dql = "SELECT b, e, r FROM Bug b JOIN b.engineer e JOIN b.reporter r "
    ."WHERE b.status = 'OPEN' AND (e.id = ?1 OR r.id = ?1) ORDER BY b.created DESC";

$myBugs = $entityManager->createQuery($dql)
    ->setParameter(1, $theUserId)
    ->setMaxResults(15)
    ->getResult();

echo "You have created or assigned to " .count($myBugs) ." open bugs:\n\n";

foreach ($myBugs as $bug) {
    echo "{$bug->getId()} - {$bug->getDescription()}\n";
}