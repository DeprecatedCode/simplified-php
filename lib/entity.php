<?php

/**
 * Entity Length
 */
proto(EntityType)->length = function($context) {
    return count($context) 
        - (int) isset($context->{Proto})
        - (int) isset($context->{Comment})
        - (int) isset($context->{Type});
};

/**
 * Entity String
 */
proto(EntityType)->__string__ = function($context) {
    return property($context, Type);
};

/**
 * Entity HTML
 */
proto(EntityType)->__html__ = function($context) {
    static $depth = 0;
    $html = '';
    $html .= '<table class="simplified-php-html">';
    $type = property($context, Type);
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
        $str = is_string($key) ? 'sphp-identifier' : 'sphp-identifier';
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
            if($depth > 15) {
                $show = false;
                $html .= '<i>too deep</i>';
            } else {
                $cache[] = &$value;
            }
        }
        if($show) {
            $html .= property($value, '__html__');
        }
        $html .= '</td></tr>';
    }
    if(!$ran) {
        $html .= '<tr><td colspan="2"><span class="sphp-comment">No Items</span></td></tr>';
    }
    $html .= '</table>';
    return $html;
};
