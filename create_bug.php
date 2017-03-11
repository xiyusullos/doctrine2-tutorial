<?php
/**
 * Created by PhpStorm.
 * User: xiyusullos
 * Date: 2017/3/11
 * Time: 10:39
 */

// create_bug.php
require_once 'bootstrap.php';

$theReportedId = $argv[1];
$theDefaultEngineerId = $argv[2];
$productIds = explode(',', $argv[3]);

$reporter = $entityManager->find('User', $theReportedId);
$engineer = $entityManager->find('User', $theDefaultEngineerId);
if (!$reporter || !$engineer) {
    echo "No reporter and/or engineer found for the inpupt.\n";
    exit(1);
}

$bug = new Bug();
$bug->setDescription('Somethins does not work!');
$bug->setCreated(new DateTime('now'));
$bug->setStatus('OPEN');

foreach ($productIds as $productId) {
    $product = $entityManager->find('Product', $productId);
    $bug->assignedToProduct($product);
}

$bug->setReporter($reporter);
$bug->setEngineer($engineer);

$entityManager->persist($bug);
$entityManager->flush();

echo "Your new Bug Id: {$bug->getId()}\n";