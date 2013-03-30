<?php

S::$lib->List = new stdClass;

/**
 * List Constructor
 */
S::$lib->List->{S::CONSTRUCTOR} = function($context) {
    return array();
};

/**
 * List Print
 */
S::$lib->List->__string__ = function(&$context) {
    $out = array();
    foreach($context as $item) {
        $out[] = S::property($item, '__string__');
    }
    $out = implode(", ", $out);
    return "($out)";
};

/**
 * List Length
 */
S::$lib->List->length = function(&$context) {
    return count($context) 
        - (int) isset($context[S::TYPE])
        - (int) isset($context[S::COMMENT]);
};

/**
 * List HTML
 */
S::$lib->List->__html__ = S::$lib->Entity->__html__;
