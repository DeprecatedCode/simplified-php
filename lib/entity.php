<?php

S::$lib->Entity = array();
$X = &S::$lib->Entity;

/**
 * Entity Constructor
 */
$X[S::CONSTRUCTOR] = function(&$context) {
    return $context;
};

/**
 * Entity Length
 */
$X['length'] = function(&$context) {
    return count($context) - (int) isset($context[S::TYPE]);
};
