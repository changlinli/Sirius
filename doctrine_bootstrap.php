<?php
/**
 * doctrine_bootstrap.php Bootstrap file required for using the Doctrine ORM. 
 * This file sets up the entity manager which is Doctrine's way of keeping track 
 * of which classes correspond to which databases and files. In effect it is the 
 * heart of how Doctrine maps back and forth between classes and relational 
 * databases.
 */

namespace sirius\doctrine_bootstrap;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once('vendor/autoload.php');
require_once('base/config.php');

$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__.'/base/doctrineModels', __DIR__.'/../app/doctrineModels/'), $isDevMode);

$conn = array(
    'driver' => 'pdo_mysql',
    'user' => \DB_USER,
    'password' => \DB_PASS,
    'dbname' => \DB_NAME
);

$entityManager = EntityManager::create($conn, $config);
$GLOBALS['entityManager'] = $entityManager;
