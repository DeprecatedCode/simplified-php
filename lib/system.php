<?php

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
    margin: -9px !important;
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
table.simplified-php-html span.string:before {
    content: '"';
}
table.simplified-php-html span.string:after {
    content: '"';
}
EOF;
};
