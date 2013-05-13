<?php

/**
 * String Constructor
 */
proto(StringType)->{Constructor} = function() {
    return "";
};

/**
 * String Length
 */
proto(StringType)->length = function($context) {
    return strlen($context);
};

/**
 * String Number
 */
proto(StringType)->number = function($context) {
    return (float) $context;
};

/**
 * String Reverse
 */
proto(StringType)->reverse = function($context) {
    return strrev($context);
};

/**
 * String Split
 */
proto(StringType)->split = function($context) {
    return function($arg) use($context) {
        if(is_string($arg)) {
            if(strlen($arg)) {
                return explode($arg, $context);
            } else {
                return str_split($context);
            }
        } else if(is_numeric($arg)) {
            return str_split($context, $arg);
        }
        return explode($arg, $context);
    };
};

/**
 * String Replace
 */
proto(StringType)->replace = function($context) {
    return function($arg) use($context) {
        foreach($arg as $key => $value) {
            $context = str_replace($key, $value, $context);
        }
        return $context;
    };
};

/**
 * String Uppercase
 */
proto(StringType)->upper = function($context) {
    return strtoupper($context);
};

/**
 * String Lowercase
 */
proto(StringType)->lower = function($context) {
    return strtolower($context);
};

/**
 * String Apply
 */
proto(StringType)->__apply__ = function(&$context) {
    return function(&$arg) use($context) {

        /**
         * If arg is expression, get callable
         */
        if(is_object($arg) && type($arg) === ExpressionType) {
            $arg = property($arg, 'run');   
        }

        /**
         * Iterate over String Characters
         */        
        if(is_callable($arg)) {
            $out = array();
            foreach(str_split($context) as $char) {
                $entity = new stdClass;
                $entity->it = $char;
                $out[] = $arg($entity);
            }
            return $out;
        }
        
        /**
         * Range Access
         */
        else if(is_object($arg) && type($arg) === RangeType) {
            print_r($arg);
            return substr($context, $arg->start, $arg->end - $arg->start);
        }
        
        /**
         * String Format
         */
        else if(is_object($arg)) {
            foreach($arg as $key => $value) {
                if(strlen($key) > 0 && $key[0] === '#') {
                    continue;
                }
                if(!is_string($value)) {
                    $value = property($value, '__string__');
                }
                $context = str_replace('#{'.$key.'}', $value, $context);
            }
            return $context;
        }
        
        /**
         * Index Access
         */
        else if(is_numeric($arg)) {
            if($arg >= strlen($context)) {
                return null;
            }
            return substr($context, $arg, 1);
        }
        
        /**
         * Standard String Concatenate
         */
        return $context . $arg;
    };
};

/**
 * String to Code
 */
proto(StringType)->code = function($context) {
    $code = S::construct('Code');
};

/**
 * String Print
 */
proto(StringType)->print = function($context) {
    echo $context;
};

/**
 * String HTML
 */
proto(StringType)->html = function($context) {
    return str_replace("\n", "<br/>\n", $context);
};

/**
 * String Lines
 */
proto(StringType)->lines = function($context) {
    return explode("\n", $context);
};

/**
 * String String
 */
proto(StringType)->__string__ = function($context) {
    return $context;
};

/**
 * String HTML
 */
proto(StringType)->__html__ = function($context) {
    return '<span class="sphp-string">&quot;' . htmlspecialchars($context) . '&quot;</span>';
};
