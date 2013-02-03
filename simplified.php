<?php

/**
 * Simple Notation :: PHP
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
$standard = new Entity;
Entity::$standardIncludes[] = $standard;

/**
 * Standard Entities
 */
foreach($entities as $entity) {
    $standard->$entity = new $entity;
}

/**
 * Standard Includes
 */
foreach(explode(' ', 'grammar http representation sparse utilities') as $file) {
    require_once(__DIR__ . "/standard/$file.php");
}

/**
 * Standard Methods
 */
$standard->toString = new EntityStringRepresentationNativeProperty;
$standard->includedEntities = new IncludedEntitiesNativeProperty;
$standard->entityPrototype = new EntityPrototypeNativeProperty;

/**
 * String Methods
 */
$string = $standard->String->getEntityPrototype();
$string->length = new StringLengthNativeProperty;


/**
 * Network Methods
 */
$standard->Network->http = NetworkHTTP::getEntity();

/**
 * Main Entity
 */
$main = new Entity;