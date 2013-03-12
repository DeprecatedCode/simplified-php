<?php

S::$lib->String = array();
$X = &S::$lib->String;

/**
 * String Constructor
 */
$X[S::CONSTRUCTOR] = function(&$context) {
    return "";
};

/**
 * String Length
 */
$X['length'] = function(&$context) {
    return strlen($context);
};

/**
 * String Print
 */
$X['print'] = function(&$context) {
    echo $context;
};

/**
 * String HTML
 */
$X['__html__'] = function(&$context) {
    return '<span class="string">' . htmlspecialchars($context) . '</span>';
};
