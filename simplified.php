<?php

/**
 * Simplified PHP
 * @author Nate Ferrero
 */

/**
 * Include Entities
 */
$entities = explode(' ', 'Entity Expression File Network Number Property' .
    ' ' . 'Request String Void');

foreach($entities as $file) {
    require_once(__DIR__ . strtolower("/entities/$file.php"));
}

/**
 * Setup standard scope which will automatically be included
 * on Entity creation.
 */
Entity::$standard = new Entity;

/**
 * Standard Entities
 */
foreach($entities as $type) {
    Entity::$standard->$type = new $type;
}

/**
 * Standard Library Includes
 */
foreach(explode(' ', 'repr entity grammar http sparse string util') as $file) {
    require_once(__DIR__ . "/lib/lib-$file.php");
}

/**
 * Main Entity
 */
$main = new Entity;
