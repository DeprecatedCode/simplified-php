<?php

/**
 * Run expression
 */
proto(ExpressionType)->run = function($context) {
    if($context instanceof Closure) {
        return $context;
    }
    if(isset($context->{Immediate}) && $context->{Immediate}) {
        $entity = new stdClass;
        return _code_reduce_value($context->stack, $entity);
    }
    return function($entity) use($context) {
        return _code_reduce_value($context->stack, $entity);
    };
};

/**
 * Expression HTML
 */
proto(ExpressionType)->__html__ = function($context) {
    if($context instanceof Closure) {
        return '[Native Code]';
    }
    $html = '{' . (isset($context->{Immediate}) && $context->{Immediate} ?
        '<span class="sphp-operator">!</span>' : '');
    $stack = $context->stack;
    $lines = _code_flatten_stack($stack);
    foreach($stack as $item) {
        $html .= '<span class="sphp-' . $item->type . '">' . 
            htmlspecialchars($item->{'#raw'}) . '</span>';
    }
    return $html . '}';
};