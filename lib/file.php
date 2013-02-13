<?php

S::$lib->File = S::$lib->Entity;

/**
 * File Constructor
 */
S::$lib->File[S::CONSTRUCTOR] = function(&$context) {
    $context[S::TYPE] = S::$lib->File[S::TYPE];
    return $context;
};
