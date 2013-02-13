<?php

S::$lib->Code = S::$lib->Entity;
$X = &S::$lib->Code;

/**
 * Code Constructor
 */
$X[S::CONSTRUCTOR] = function(&$context) {
    $X = &S::$lib->Code;
    $context[S::TYPE] = $X[S::TYPE];
    return $context;
};

/**
 * Code Run
 */
$X['run'] = function(&$context) {
    $X = &S::$lib->Code;
    if(!isset($context['tree'])) {
        $parse = $X['parse'];
        $parse = $parse($context);
        $context['tree'] = $parse($context['code']);
    }
    print_r($context);
};

/**
 * Code Parse
 */
$X['parse'] = function(&$context) {
    return function($code) {
        $tree = array();

        return $tree;
    };
};
