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

/**
 * Expression HTML
 */
proto(ExpressionType)->__html__ = function($context) {
    $html = '{' . ($context->{Immediate} ?
        '<span class="sphp-operator">!</span>' : '');
    $stack = $context->stack;
    $lines = _code_flatten_stack($stack);
    foreach($stack as $item) {
        $html .= '<span class="sphp-' . $item->type . '">' . 
            htmlspecialchars($item->{'#raw'}) . '</span>';
    }
    return $html . '}';
};