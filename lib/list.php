<?php

/**
 * List Constructor
 */
proto(ListType)->{Constructor} = function() {
    return array();
};

/**
 * List Print
 */
proto(ListType)->__string__ = function(&$context) {
    $out = array();
    foreach($context as $item) {
        $out[] = property($item, '__string__');
    }
    $out = implode(", ", $out);
    return "($out)";
};

/**
 * List Length
 */
proto(ListType)->length = function(&$context) {
    return count($context);
};

/**
 * List HTML
 */
proto(ListType)->__html__ = proto(EntityType)->__html__;
