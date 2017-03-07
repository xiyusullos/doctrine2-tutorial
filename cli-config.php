<?php
/**
 * Created by PhpStorm.
 * User: xiyusullos
 * Date: 2017/3/7
 * Time: 22:10
 */

// cli-config.php
require_once 'bootstrap.php';

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);