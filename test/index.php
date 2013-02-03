<?php

require_once('../simplified.php');

$main->a = new String('boo');

$main->b = new Number(5.4321);

$main->c = $main->Void;

$main->d = $main->Entity;

$main->a_length = $main->a->length;

$main->included = $main->includedEntities;

$main->string_prototype = $main->String->entityPrototype;

/**
 * Represent Main
 */
echo $main->toString->getValue();
