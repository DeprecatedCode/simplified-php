<?php

S::$lib->Property = clone S::$lib->Entity;

/**
 * Property Constructor
 */
S::$lib->Property->{S::CONSTRUCTOR} = function($context) {
    $context->{S::TYPE} = S::$lib->Property->{S::TYPE};
    return $context;
};
