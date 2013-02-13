<?php

S::$lib->Request = S::$lib->Entity;

/**
 * Request Constructor
 */
S::$lib->Request[S::CONSTRUCTOR] = function(&$context) {
    $context[S::TYPE] = S::$lib->Request[S::TYPE];
    return $context;
};
