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
proto(NumberType)->__string__ = function($context) {
    return "$context";
};

/**
 * Number HTML
 */
proto(NumberType)->__html__ = function($context) {
    return '<span class="number">' . $context . '</span>';
};
