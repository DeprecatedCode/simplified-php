<?php

/**
 * Number Print
 */
proto(RangeType)->__string__ = function($context) {
    return "$context->start..$context->end";
};

/**
 * Range HTML
 */
proto(RangeType)->__html__ = function($context) {
    return '<span class="number">' . $context->start . '..' . $context->end . '</span>';
};
