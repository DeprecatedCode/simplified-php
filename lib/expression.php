<?php

S::$lib->Expression = clone S::$lib->Entity;

/**
 * Expression Constructor
 */
S::$lib->Expression->{S::CONSTRUCTOR} = function($context) {
    $context->{S::TYPE} = S::$lib->Expression->{S::TYPE};
    return $context;
};
