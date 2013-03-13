<?php

S::$lib->Boolean = array();
$X = &S::$lib->Boolean;

/**
 * Boolean Constructor
 */
$X[S::CONSTRUCTOR] = function(&$context) {
    return true;
};

/**
 * Boolean HTML
 */
$X['__html__'] = function(&$context) {
    return '<span class="boolean">' . ($context ? 'True' : 'False') . '</span>';
};
