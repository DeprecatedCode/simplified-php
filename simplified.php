<?php

/**
 * Simplified PHP
 * @author Nate Ferrero
 */

/**
 * Complete Process
 */
if(is_object(S::$lib)) {
    $context = new stdClass;
    $context->code = ob_get_clean();
    $context->label = $_SERVER['SCRIPT_FILENAME'];

    /**
     * Code object
     */
    $code = S::construct('Code', $context);
    S::property($code, 'run');
    exit;
}

/**
 * Error Handler
 */
set_error_handler(function($num, $str, $file, $line) {
    throw new ErrorException($str, $num, 1, $file, $line);
});

/**
 * Exception Handler
 */
set_exception_handler(function($exc) {
    $str = $exc->getMessage();
    $line = $exc->getLine();
    $file = $exc->getFile();
    $type = get_class($exc);
    echo "$type: $str<br/><br/>\nRaised on line $line of $file<br/><br/>\n";
    echo nl2br(htmlspecialchars($exc->getTraceAsString()));
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
        "List",
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
    public $List;
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
        $method = S::$lib->$type->{S::CONSTRUCTOR};
        if(is_null($method)) {
            throw new Exception("No constructor found on $type");
        }
        return $method($context);
    }

    /**
     * Get a property of an Entity
     */
    public static function property(&$context, $key) {
        if($context instanceof stdClass) {

            /**
             * Handle Standard Values
             */
            if(isset($context->$key)) {
                $value = $context->$key;

                /**
                 * Immediately Execute
                 */
                if($value instanceof stdClass && isset($value->${S::IMMEDIATE})) {
                    $method = $value->${S::IMMEDIATE};
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
            $type = S::$lib->Entity->{S::TYPE};
            if(isset($context->{S::TYPE})) {
                $type = $context->{S::TYPE};
            }
            $prototype = &S::$lib->$type;
            if(isset($prototype->$key)) {
                $method = $prototype->$key;
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
            $prototype = S::$lib->String;
        } else if(is_integer($context) || is_float($context)) {
            $prototype = S::$lib->Number;
        } else if(is_array($context)) {
            $prototype = S::$lib->List;
        } else if(is_null($context)) {
            $prototype = S::$lib->Void;
        } else if(is_bool($context)) {
            $prototype = S::$lib->Boolean;
        } else {
            throw new Exception("No valid type found for:" . print_r($context, true));
        }

        /**
         * Look on PHP Type Prototype
         */
        if(isset($prototype->$key)) {
            $method = $prototype->$key;
            if(!is_callable($method)) {
                return $method;
            }
            return $method($context);
        }

        /**
         * Not Found
         */
        $type = $prototype->{S::TYPE};
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
    S::$lib->$entity->{S::TYPE} = $entity;
    
    /**
     * HTML Output of String Value
     */
    if(!isset(S::$lib->$entity->__html__)) {
        S::$lib->$entity->__html__ = function($context) use($entity) {
            $ent = strtolower($entity);
            $str = htmlspecialchars(S::property($context, '__string__'));
            return "<span class=\"$ent\">$str</span>";
        };
    }
    
    /**
     * Print String Value
     */
    if(!isset(S::$lib->$entity->print)) {
        S::$lib->$entity->print = function($context) {
            echo S::property($context, '__string__');
        };
    }
}

/**
 * Capture Code
 */
ob_start();
