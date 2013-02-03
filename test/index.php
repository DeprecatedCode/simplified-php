<?php

require_once('../sn.php');

$main->a = new String('boo');

$main->b = new Number(5.4321);

$main->c = $main->Void;

$main->d = $main->Entity;

$main->aLength = $main->a->length;

$main->included = $main->includedEntities;

/**
 * Represent Main
 */
echo $main->toString->getValue();