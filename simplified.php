<?php

/**
 * Simplified PHP
 * @author Nate Ferrero
 */

/**
 * Include Entities
 */
$entities = explode(' ', 'Entity Code Expression File Network Number' .
    ' ' . 'Property Range Request Router String Void');

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
foreach(explode(' ', 'repr code entity file grammar http '.
    'router sparse string util') as $file) {
    require_once(__DIR__ . "/lib/lib-$file.php");
}

/**
 * Routing
 */
if(isset($_SERVER['REDIRECT_URL'])) {
    Entity::$standard->Router->route(new Entity);
}
