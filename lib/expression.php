<?php

/**
 * Run expression
 */
proto(ExpressionType)->run = function($context) {
    return function($entity) use($context) {
        if($context->{Immediate}) {
            return _code_reduce_value($context->stack, $entity);
        }
    };
};
