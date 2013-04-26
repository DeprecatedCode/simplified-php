<?php

/**
 * List Constructor
 */
proto(ListType)->{Constructor} = function() {
    return array();
};

/**
 * List Apply
 */
proto(ListType)->__apply__ = function(&$context) {
    return function(&$item) use($context) {
        if(!is_string($item)) {
            throw new Exception("Only strings can be applied to lists");
        }
        return implode($item, $context);
    };
};

/**
 * List String
 */
proto(ListType)->__string__ = function(&$context) {
    $out = array();
    foreach($context as $item) {
        $out[] = property($item, '__string__');
    }
    return implode("", $out);
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
