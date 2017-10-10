<?php

require_once 'vendor/autoload.php';

use Papertowel\Core\Database;

$database = new Database();

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($database->getEntityManager());