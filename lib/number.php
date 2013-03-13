<?php

S::$lib->Number = array();
$X = &S::$lib->Number;

/**
 * Number Constructor
 */
S::$lib->Number[S::CONSTRUCTOR] = function(&$context) {
    return 0;
};

/**
 * Number Print
 */
$X['print'] = function(&$context) {
    echo $context;
};

/**
 * Number HTML
 */
$X['__html__'] = function(&$context) {
    return '<span class="number">' . $context . '</span>';
};
