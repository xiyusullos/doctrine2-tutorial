<?php
/**
 * Created by PhpStorm.
 * User: xiyusullos
 * Date: 2017/3/11
 * Time: 11:57
 */

// close_bug.php
require_once 'bootstrap.php';

$theBugId = $argv[1];

$bug = $entityManager->find('Bug', $theBugId);
$bug->close();

$entityManager->flush();