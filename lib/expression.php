<?php

S::$lib->Expression = clone S::$lib->Entity;

/**
 * Expression Constructor
 */
S::$lib->Expression->{S::CONSTRUCTOR} = function($context) {
    $context->{S::TYPE} = S::$lib->Expression->{S::TYPE};
    return $context;
};

/**
 * Run expression
 */
S::$lib->Expression->run = function($context) {
    return function($entity) use($context) {
        if($context->{S::IMMEDIATE}) {
            return _code_reduce_value($context->stack, $entity);
        }
    };
};