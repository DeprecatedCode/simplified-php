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
        
        /**
         * Iterate over list
         */        
        if(is_callable($arg)) {
            $out = array();
            foreach($context as $item) {
                $entity = new stdClass;
                $entity->it = &$item;
                $out[] = $arg($entity);
            }
            return $out;
        }
        
        /**
         * Implode with string
         */
        if(is_string($arg)) {
            return implode($arg, $context);
        }
        
        throw new Exception("Cannot use " . type($arg) . " on a list");
    };
};

/**
 * List Join
 */
proto(ListType)->join = function(&$context) {
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
