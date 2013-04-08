<?php

/**
 * Boolean String
 */
proto(BooleanType)->__string__ = function(&$context) {
    return $context ? 'True' : 'False';
};
