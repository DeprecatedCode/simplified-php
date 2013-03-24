<?php

S::$lib->Entity = new stdClass;

/**
 * Entity Constructor
 */
S::$lib->Entity->${S::CONSTRUCTOR} = function($context) {
    return $context;
};

/**
 * Entity Length
 */
S::$lib->Entity->length = function($context) {
    return count($context) - (int) isset($context->{S::TYPE});
};

/**
 * Entity HTML
 */
S::$lib->Entity->__html__ = function($context) {
    static $depth = 0;
    $html = '';
    $html .= '<table class="simplified-php-html">';
    foreach($context as $key => &$value) {
        $str = is_string($key) ? 'string' : 'number';
        $html .= '<tr><td class="key"><span class="key '. $str .'">' . $key . '</span></td><td>';
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
    $html .= '</table>';
    return $html;
};
