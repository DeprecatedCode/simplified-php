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
 * String Print
 */
S::$lib->String->print = function($context) {
    echo $context;
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
