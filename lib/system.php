<?php

S::$lib->System = array();
$X = &S::$lib->System;

/**
 * System Constructor
 */
$X[S::CONSTRUCTOR] = function(&$context) {
    if(!is_array($context)) {
        $context = array();
    }
    $context[S::TYPE] = S::$lib->System[S::TYPE];
    return $context;
};

/**
 * Debug CSS
 */
$X['__css__'] = function(&$context) {
    return <<<EOF
table.simplified-php-html {
    font-family: Monaco, "Droid Sans Mono", monospace;
    font-size: 13px;
    padding: 0;
    border-collapse: collapse;
    border: none;
    margin: 0;
    white-space: pre;
    margin: -4px;
    background: none;
}
table.simplified-php-html tr {
    background: white;
}
table.simplified-php-html tr:hover {
    background: rgba(255, 255, 127, 0.25);
}
table.simplified-php-html td {
    padding: 4px;
    vertical-align: top;
    border-bottom: 2px solid #ccc;
}
table.simplified-php-html tr:last-child > td {
    border-bottom: none;
}
table.simplified-php-html td.key {
    text-align: right;
    border-right: 2px solid #ccc;
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
table.simplified-php-html span.string:before {
    content: '"';
}
table.simplified-php-html span.string:after {
    content: '"';
}
EOF;
};
