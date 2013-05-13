<?php

/**
 * List Constructor
 */
proto(ListType)->{Constructor} = function() {
    return array();
};

/**
 * List Get
 */
proto(ListType)->get = function(&$context) {
    return function(&$arg) use($context) {

        /**
         * If arg is expression, get callable
         */
        if(is_object($arg) && type($arg) === ExpressionType) {
            $arg = property($arg, 'run');   
        }
        
        /**
         * List position access
         */
        if(is_numeric($arg)) {
            if(isset($context[$arg])) {
                return $context[$arg];
            }
            return null;
        }
        
        throw new Exception("Cannot get " . type($arg) . " from a list");
    };
};

/**
 * List Join
 */
proto(ListType)->join = function(&$context) {
    return implode('', $context);
};

/**
 * List Glue
 */
proto(ListType)->glue = function(&$context) {
    return function(&$glue) use($context) {
        return implode($glue, $context);
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
 * List Sum
 */
proto(ListType)->sum = function(&$context) {
    return array_sum($context);
};

/**
 * List HTML
 */
proto(ListType)->__html__ = proto(EntityType)->__html__;
