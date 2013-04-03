<?php


/**
 * System Inspect Switcher
 */
function _system_inspect($selected) {
    echo "<style>#sphp-debug-switch {position: relative; top: -1px; right: -1px;
        padding: 8px 8px 9px; margin: -8px -1px 9px -1px; background: #eee;
        border: 1px solid #ccc; font-size: 11px;
        box-shadow: inset 0 -0.25em 1em #ccc;
        font-family: Verdana, Tahoma, 'Lucida Grande', Arial, Ubuntu, sans-serif;
        color: #bbb;
    }
    #sphp-debug-switch a {color: #666; text-decoration: none; padding: 2px 4px 3px;
        border-radius: 3px;}
    #sphp-debug-switch a:hover {background: #ccc; color: #444;}
    #sphp-debug-switch a.sphp-active {background: #333; color: #fff;}
    </style>";
    $modes = explode(" ", "request code stack entity output render tests close");
    $url = $_SERVER['REQUEST_URI'];
    $a = array();
    $re = ';\!\=[a-z0-9]+;';
    foreach($modes as $mode) {
        $c = strpos($url, '!=' . $mode) > -1 ? 'sphp-active' : '';
        $x = '<a href="' .
            preg_replace($re, $mode == 'close' ? '' : '!=' . $mode, $url) . '" class="'.$c.'">';
        $x .= ucfirst($mode) . '</a>';
        $a[] = $x;
    }
    echo '<div id="sphp-debug-switch">SimplifiedPHP ' . S::VERSION . '&nbsp; &middot; ';
    echo implode(' &middot; ', $a);
    echo '</div>';
}

S::$lib->System = new stdClass;

/**
 * System Constructor
 */
S::$lib->System->{S::CONSTRUCTOR} = function($context) {
    if(!is_object($context)) {
        $context = new stdClass;
    }
    $context->{S::TYPE} = S::$lib->System->{S::TYPE};
    return $context;
};

/**
 * Operators
 */
S::$lib->System->operators = new stdClass;
require_once(__DIR__ . '/system/operators.php');

/**
 * Debug CSS
 */
S::$lib->System->__css__ = function($context) {
    return <<<EOF
table.simplified-php-html {
    font-family: Monaco, "Droid Sans Mono", monospace;
    font-size: 12px;
    padding: 0;
    margin: -5px;
    border-collapse: collapse;
    border: none;
    white-space: pre;
    background: none;
    box-shadow: 0 0 1em rgba(0, 0, 0, 0.2);
}
body > table.simplified-php-html {
    margin: 0px !important;
}
table.simplified-php-html tr {
    background: white;
}
table.simplified-php-html tr:hover {
    background: rgba(255, 255, 127, 0.25);
}
table.simplified-php-html td, table.simplified-php-html th {
    padding: 4px;
    vertical-align: top;
    border: 1px solid #ccc;
}
table.simplified-php-html th {
    padding: 6px;
    background: #eee;
    font-weight: bold;
    font-size: 85%;
    color: #444;
}
table.simplified-php-html td.key {
    text-align: right;
    border-right: 1px solid #ccc;
}
table.simplified-php-html span.string {
    color: darkgreen;
}
table.simplified-php-html span.key.string {
    color: red;
}
table.simplified-php-html span.number {
    color: darkorange;
}
table.simplified-php-html span.boolean {
    color: darkblue;
}
table.simplified-php-html span.info {
    color: gray;
}
span.sphp-list {
    color: #115;
}
span.sphp-entity {
    color: #228;
}
span.sphp-expression {
    color: #33b;
}
span.sphp-comment {
    color: orange;
}
span.sphp-string {
    color: darkgreen;
}
span.sphp-operator {
    color: darkred;
}
EOF;
};
