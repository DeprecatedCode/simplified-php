<?php

S::$lib->Number = new stdClass;

/**
 * Number Constructor
 */
S::$lib->Number->{S::CONSTRUCTOR} = function($context) {
    return 0;
};

/**
 * Number Print
 */
S::$lib->Number->print = function($context) {
    echo $context;
};

/**
 * Number HTML
 */
S::$lib->Number->__html__ = function($context) {
    return '<span class="number">' . $context . '</span>';
};
