<?php

/**
 * String Constructor
 */
proto(StringType)->{Constructor} = function() {
    return "";
};

/**
 * String Length
 */
proto(StringType)->length = function($context) {
    return strlen($context);
};

/**
 * String Apply
 */
proto(StringType)->__apply_string__ = function(&$context) {
    return function(&$string) use($context) {
        return $context . $string;
    };
};

/**
 * String to Code
 */
proto(StringType)->code = function($context) {
    $code = S::construct('Code');
};

/**
 * String Print
 */
proto(StringType)->print = function($context) {
    echo $context;
};

/**
 * String HTML
 */
proto(StringType)->html = function($context) {
    return str_replace("\n", "<br/>\n", $context);
};

/**
 * String Lines
 */
proto(StringType)->lines = function($context) {
    return explode("\n", $context);
};

/**
 * String String
 */
proto(StringType)->__string__ = function($context) {
    return $context;
};

/**
 * String HTML
 */
proto(StringType)->__html__ = function($context) {
    return '<span class="string">&quot;' . htmlspecialchars($context) . '&quot;</span>';
};
