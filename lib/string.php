<?php

S::$lib->String = array();

/**
 * String Constructor
 */
S::$lib->String[S::CONSTRUCTOR] = function(&$context) {
    return "";
};

/**
 * String Length
 */
S::$lib->String['length'] = function(&$context) {
    return strlen($context);
};

/**
 * String Print
 */
S::$lib->String['print'] = function(&$context) {
    echo $context;
};
