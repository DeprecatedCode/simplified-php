<?php

S::$lib->Void = array();
$X = &S::$lib->Void;

/**
 * Void Constructor
 */
$X[S::CONSTRUCTOR] = function(&$context) {
    return null;
};

/**
 * Void HTML
 */
$X['__html__'] = function(&$context) {
    return '<span class="void">Void</span>';
};
