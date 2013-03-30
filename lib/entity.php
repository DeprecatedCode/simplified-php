<?php

S::$lib->Entity = new stdClass;

/**
 * Entity Constructor
 */
S::$lib->Entity->{S::CONSTRUCTOR} = function($context) {
    return $context;
};

/**
 * Entity Length
 */
S::$lib->Entity->length = function($context) {
    return count($context) 
        - (int) isset($context->{S::TYPE})
        - (int) isset($context->{S::COMMENT});
};

/**
 * Entity String
 */
S::$lib->Entity->__string__ = function($context) {
    return $context->{S::TYPE};
};

/**
 * Entity HTML
 */
S::$lib->Entity->__html__ = function($context) {
    static $depth = 0;
    $html = '';
    $html .= '<table class="simplified-php-html">';
    if(is_array($context)) {
        if(isset($context[S::TYPE])) {
            $type = $context[S::TYPE];
        } else {
            $type = 'List';
        }
    } else {
        $type = isset($context->{S::TYPE}) ? $context->{S::TYPE} : 'Entity';
    }
    if(isset($context->{'#line'})) {
        $type .= " &bull; " . $context->{'#line'};
        if(isset($context->{'#column'})) {
            $type .= ":" . $context->{'#column'};
        }
    }
    $html .= '<tr><th colspan="2">' . $type . '</th></tr>';
    $ran = false;
    foreach($context as $key => &$value) {
        if(is_string($key) && $key[0] == '#') {
            continue;
        }
        $ran = true;
        $str = is_string($key) ? 'string' : 'number';
        if(is_string($key) && !preg_match(';^[a-zA-Z0-9_]+$;', $key)) {
            $q = "&quot;";
        } else {
            $q = '';
        }
        $html .= '<tr><td class="key"><span class="key '. $str .'">' . 
            $q . $key . $q . '</span></td><td>';
        $show = true;
        if(is_array($value)) {
            $depth += 1;
            if($depth > 10) {
                $show = false;
                $html .= '<i>too deep</i>';
            } else {
                $cache[] = &$value;
            }
        }
        if($show) {
            $html .= S::property($value, '__html__');
        }
        $html .= '</td></tr>';
    }
    if(!$ran) {
        $html .= '<tr><td colspan="2"><span class="info">No Items</span></td></tr>';
    }
    $html .= '</table>';
    return $html;
};
