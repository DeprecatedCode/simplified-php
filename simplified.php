<?php

/**
 * Simple Notation :: PHP
 * @author Nate Ferrero
 */

/**
 * Include Entities
 */
foreach(explode(' ', 'entity expression file network number property ' .
    'request string void') as $file) {
    require_once(__DIR__ . "/entities/$file.php");
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
$standard->Entity     = new     Entity;
$standard->Expression = new Expression;
$standard->File       = new       File;
$standard->Network    = new    Network;
$standard->Number     = new     Number;
$standard->Property   = new   Property;
$standard->Request    = new    Request;
$standard->String     = new     String;
$standard->Void       = new       Void;

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