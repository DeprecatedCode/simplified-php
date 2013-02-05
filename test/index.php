<?php

require_once('../simplified.php');

$main = new Entity;

$main->a = new String('boo');

$main->b = new Number(5.4321);

$main->c = $main->Void;

$main->d = $main->Entity;

$main->length_4 = $main->length;

$main->a_length = $main->a->length;

$main->included = $main->includedEntities;

$main->string_prototype = $main->String->entityPrototype;

$main->replaced = $main->a->replace(new Entity(array('o' => 'a')));

/**
 * Represent Main
 */
echo $main->toString->getValue();
