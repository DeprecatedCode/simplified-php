<?php

S::$lib->Void = new stdClass;

/**
 * Void Constructor
 */
S::$lib->Void->{S::CONSTRUCTOR} = function($context) {
    return null;
};

/**
 * Void HTML
 */
S::$lib->Void->__html__ = function($context) {
    return '<span class="void">Void</span>';
};
