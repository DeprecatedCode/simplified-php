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
        foreach($context->groups as $group) {
            if (_code_reduce_value($group->condition, $entity)) {
                return _code_reduce_value($group->stack, $entity);
            }
        }
    }
    return function($entity) use($context) {
        foreach($context->groups as $group) {
            if (_code_reduce_value($group->condition, $entity)) {
                return _code_reduce_value($group->stack, $entity);
            }
        }
    };
};

/**
 * Expression HTML
 */
proto(ExpressionType)->__html__ = function($context) {
    if($context instanceof Closure) {
        return '[Native Expression]';
    }
    $html = '{' . (isset($context->{Immediate}) && $context->{Immediate} ?
        '<span class="sphp-operator">!</span>' : '');
    if(isset($context->stack)) {
        $stack = $context->stack;
        $lines = _code_flatten_stack($stack);
        foreach($stack as $item) {
            $html .= '<span class="sphp-' . $item->type . '">' . 
                htmlspecialchars($item->{'#raw'}) . '</span>';
        }
    }
    return $html . '}';
};