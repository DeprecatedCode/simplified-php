<?php

/**
 * Number Constructor
 */
proto(NumberType)->{Constructor} = function() {
    return 0;
};

/**
 * Number Print
 */
proto(NumberType)->print = function($context) {
    echo $context;
};

/**
 * Number HTML
 */
proto(NumberType)->__html__ = function($context) {
    return '<span class="number">' . $context . '</span>';
};
