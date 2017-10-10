<?php

require_once 'vendor/autoload.php';

use Papertowel\Papertowel;
use Papertowel\Request\Request;

/**
 * Returns application instance
 *
 * @param Papertowel $newInstance
 * @return Papertowel
 */
function Papertowel(Papertowel $newInstance = null)
{
    static $instance;

    if ($newInstance !== null) {
        $instance = $newInstance;

        return $instance;
    }

    if ($instance === null) {
        die('An error occurred');
    }

    return $instance;
}

//
///**
// * PHP Class Autoloading based on Namespace
// * @param $classId
// */
//function autoload($classId)
//{
//    $classIdParts = explode("\\", $classId);
//    $classIdLength = count($classIdParts);
//    $className = $classIdParts[$classIdLength - 1];
//    $namespace = $classIdParts[0];
//
//    for ($i = 1; $i < $classIdLength - 1; $i++) {
//        $namespace .= '/' . $classIdParts[$i];
//    }
//
//    if (file_exists(dirname(__FILE__))
//        . '/' . $namespace
//        . '/' . $className
//        . '.class.php'
//    ) {
//        include $namespace . '/' . $className . '.php';
//    }
//}

Papertowel(new Papertowel());

try {
    Papertowel()->Router()->handle(new Request());
} catch (\Papertowel\Exceptions\LangNotFoundException $e) {
    Papertowel()->View()->renderErrorPage($e->getCode(), $e->getMessage());
}
