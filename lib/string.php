<?php

S::$lib->String = new stdClass;

/**
 * String Constructor
 */
S::$lib->String->{S::CONSTRUCTOR} = function($context) {
    return "";
};

/**
 * String Length
 */
S::$lib->String->length = function($context) {
    return strlen($context);
};

/**
 * String Apply
 */
S::$lib->String->__apply_string__ = function(&$context) {
    return function(&$string) use($context) {
        return $context . $string;
    };
};

/**
 * String to Code
 */
S::$lib->String->code = function($context) {
    $code = S::construct('Code');
};

/**
 * String Print
 */
S::$lib->String->print = function($context) {
    echo $context;
};

/**
 * String HTML
 */
S::$lib->String->html = function($context) {
    return str_replace("\n", "<br/>\n", $context);
};

/**
 * String Lines
 */
S::$lib->String->lines = function($context) {
    return explode("\n", $context);
};

/**
 * String String
 */
S::$lib->String->__string__ = function($context) {
    return $context;
};

/**
 * String HTML
 */
S::$lib->String->__html__ = function($context) {
    return '<span class="string">&quot;' . htmlspecialchars($context) . '&quot;</span>';
};
