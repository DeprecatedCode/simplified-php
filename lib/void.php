<?php

/**
 * Void Constructor
 */
proto(VoidType)->{Constructor} = function() {
    return null;
};

/**
 * Void String
 */
proto(VoidType)->__string__ = function(&$context) {
    return 'Void';
};

/**
 * Void HTML
 */
proto(VoidType)->__html__ = function(&$context) {
    return '<span class="void">Void</span>';
};
