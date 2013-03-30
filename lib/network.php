<?php

S::$lib->Network = clone S::$lib->Entity;

/**
 * Network Constructor
 */
S::$lib->Network->{S::CONSTRUCTOR} = function($context) {
    $context->{S::TYPE} = S::$lib->Network->{S::TYPE};
    return $context;
};
