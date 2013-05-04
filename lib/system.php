<?php

require_once(__DIR__ . '/system/evaluate.php');

/**
 * Evaluate
 */
proto(SystemType)->evaluate = function($context) {
    _system_evaluate();
};

/**
 * Operators
 */
proto(SystemType)->operators = new stdClass;
require_once(__DIR__ . '/system/operators.php');

/**
 * Debug CSS
 */
proto(SystemType)->__css__ = function($context) {
    return file_get_contents(__DIR__ . '/system/style.css');
};
