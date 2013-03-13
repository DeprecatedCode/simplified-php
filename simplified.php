<?php

/**
 * Simplified PHP
 * @author Nate Ferrero
 */

/**
 * Complete Process
 */
if(is_object(S::$lib)) {
    $code = S::construct('Code', array(
        'code' => ob_get_clean()
    ));
    S::property($code, 'run');
    exit;
}

/**
 * Error Handler
 */
set_error_handler(function($num, $str, $file, $line) {
    echo "$str on line $line of $file\n\n";
    debug_print_backtrace();
    exit;
});

/**
 * Simplified Class
 */
class S {

    const CONSTRUCTOR = '#constructor';
    const IMMEDIATE = '#immediate';
    const TYPE = '#type';

    public static $entities = array(
        "Entity",
        "Boolean",
        "Code",
        "Expression",
        "File",
        "Network",
        "Number",
        "Property",
        "Range",
        "Request",
        "Router",
        "String",
        "System",
        "Void"
    );

    public static $lib;

    public $Entity;
    public $Boolean;
    public $Code;
    public $Expression;
    public $File;
    public $Network;
    public $Number;
    public $Property;
    public $Range;
    public $Request;
    public $Router;
    public $String;
    public $System;
    public $Void;

    /**
     * Dump
     */
    public static function dump(&$context) {
        $system = S::construct('System');
        $style = S::property($system, '__css__');
        $html = S::property($context, '__html__');
        $style = '<style>' . $style . '</style>';
        S::property($style, 'print');
        S::property($html, 'print');
        exit;
    }

    /**
     * Construct an Entity
     */
    public static function construct($type, $context=null) {
        $ref = &S::$lib->$type;
        $method = $ref[S::CONSTRUCTOR];
        if(is_null($method)) {
            throw new Exception("No constructor found on $type");
        }
        return $method($context);
    }

    /**
     * Get a property of an Entity
     */
    public static function property(&$context, $key) {
        if(is_array($context)) {

            /**
             * Handle Standard Values
             */
            if(isset($context[$key])) {
                $value = $context[$key];

                /**
                 * Immediately Execute
                 */
                if(is_array($value) && isset($value[S::IMMEDIATE])) {
                    $method = $value[S::IMMEDIATE];
                    if(!is_callable($method)) {
                        return $method;
                    }
                    return $method($context);
                }

                return $value;
            }

            /**
             * Handle Prototype Values
             */
            $type = S::$lib->Entity[S::TYPE];
            if(isset($context[S::TYPE])) {
                $type = $context[S::TYPE];
            }
            $prototype = &S::$lib->$type;
            if(isset($prototype[$key])) {
                $method = $prototype[$key];
                if(!is_callable($method)) {
                    return $method;
                }
                return $method($context);
            }
            throw new Exception("Property '$key' Not Found on $type");
        }

        /**
         * Handle PHP Types
         */
        if(is_string($context)) {
            $prototype = &S::$lib->String;
        } else if(is_integer($context) || is_float($context)) {
            $prototype = &S::$lib->Number;
        } else if(is_null($context)) {
            $prototype = &S::$lib->Void;
        } else if(is_bool($context)) {
            $prototype = &S::$lib->Boolean;
        } else {
            throw new Exception("No Valid Type Found");
        }

        /**
         * Look on PHP Type Prototype
         */
        if(isset($prototype[$key])) {
            $method = $prototype[$key];
            if(!is_callable($method)) {
                return $method;
            }
            return $method($context);
        }

        /**
         * Not Found
         */
        $type = $prototype[S::TYPE];
        var_dump($prototype);
        throw new Exception("Property '$key' Not Found on $type");
    }
}

/**
 * Lib
 */
S::$lib = new S();

/**
 * Standard Entities
 */
foreach(S::$entities as $entity) {
    $path = strtolower($entity);
    require_once(__DIR__ . "/lib/$path.php");
    $ref = &S::$lib->$entity;
    $ref[S::TYPE] = $entity;
}

/**
 * Capture Code
 */
ob_start();
