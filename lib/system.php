<?php

require_once(__DIR__ . '/system/evaluate.php');

/**
 * Evaluate
 */
proto(SystemType)->evaluate = function($context) {
    _system_evaluate();
};

/**
 * Operators
 */
proto(SystemType)->operators = new stdClass;
require_once(__DIR__ . '/system/operators.php');

/**
 * Debug CSS
 */
proto(SystemType)->__css__ = function($context) {
    return <<<EOF
h4.sphp-info {
    margin: 1em 0;
    padding: 0 10px;
}
table.simplified-php-html, table.simplified-php-html pre, h4.sphp-info {
    font-family: Consolas, "Liberation Mono", Courier, monospace;
    font-size: 12px;
    line-height: 1.4;
}
table.simplified-php-html pre {
    padding: 10px;
    margin: -4px;
    background-color: #fff;
}
table.simplified-php-html {
    padding: 0;
    margin: -5px;
    border-collapse: collapse;
    border: none;
    white-space: pre;
    background: none;
    box-shadow: 0 0 1em rgba(0, 0, 0, 0.2);
}
table.simplified-php-html pre.sphp-lines {
    color: #aaa;
    font-weight: bold;
    background-color: #ececec;
    color: #aaa;
    padding: 10px 6px;
    text-align: right;
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
span.sphp-list {
    color: #333;
}
span.sphp-entity {
    color: #333;
}
span.sphp-expression {
    color: #333;
}
span.sphp-comment {
    color: #998;
    font-style: italic;
}
span.sphp-string {
    color: #d14;
}
span.sphp-operator {
    color: #333;
    font-weight: bold;
}
span.sphp-identifier {
    color: #333;
}
EOF;
};
