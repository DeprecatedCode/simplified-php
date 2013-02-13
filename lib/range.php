<?php

S::$lib->Range = S::$lib->Entity;

/**
 * Range Constructor
 */
S::$lib->Range[S::CONSTRUCTOR] = function(&$context) {
    $context[S::TYPE] = S::$lib->Range[S::TYPE];
    return $context;
};
