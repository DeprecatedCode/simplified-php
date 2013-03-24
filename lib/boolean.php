<?php

S::$lib->Boolean = new stdClass;

/**
 * Boolean Constructor
 */
S::$lib->Boolean->{S::CONSTRUCTOR} = function($context) {
    return true;
};

/**
 * Boolean String
 */
S::$lib->Boolean->__string__ = function($context) {
    return $context ? 'True' : 'False';
};
