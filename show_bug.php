<?php
/**
 * Created by PhpStorm.
 * User: xiyusullos
 * Date: 2017/3/11
 * Time: 11:42
 */

// show_bug.php
require_once 'bootstrap.php';

$theBugId = $argv[1];

$bug = $entityManager->find('Bug', (int) $theBugId);

echo "Bug: {$bug->getDescription()}\n";
echo "Engineer: {$bug->getEngineer()->getName()}\n";