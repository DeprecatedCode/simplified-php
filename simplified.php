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
    
    /**
     * Debug params
     */
    $request = S::construct('Request');
    if(isset($request->args['!'])) {
        switch($request->args['!']) {
            case 'tests':
                $test = S::construct('Test');
                #$apply = S::property($test, '__apply_list__');
                #$apply(__DIR__ . '/tests');
                #$html = S::property($test, '__html__');
                $html = "<i>Testing Coming Soon</i>";
                $system = S::construct('System');
                $style = S::property($system, '__css__');
                $style = '<style>' . $style . '</style>';
                _system_inspect('stack');
                S::property($style, 'print');
                S::property($html, 'print');
                return;
            case 'stack':
                _system_inspect('stack');
                $context->stack = S::property($code, 'parse');
                S::dump($context);
                return;
            case 'code':
                _system_inspect('code');
                echo "<pre>";
                echo htmlspecialchars($code->code);
                echo "</pre>";
                return;
            case 'request':
                _system_inspect('request');
                S::dump($request);
                return;
            case 'entity':
                _system_inspect('entity');
                $entity = new stdClass;
                $context->stack = S::property($code, 'parse');
                ob_start();
                _code_apply_stack($context->stack, $entity);
                ob_end_clean();
                S::dump($entity);
                return;
            case 'output':
                _system_inspect('output');
                ob_start();
                S::property($code, 'run');
                $str = ob_get_clean();
                echo "<pre>";
                echo htmlspecialchars($str);
                echo "</pre>";
                return;
            case 'render':
                _system_inspect('render');
                S::property($code, 'run');
                return;
        }
    }
    
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
    
    const VERSION = '0.0.1';

    const CONSTRUCTOR = '#constructor';
    const IMMEDIATE = '#immediate';
    const COMMENT = '#comment';
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
        "Test",
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
    public $Test;
    public $Void;

    /**
     * Dump
     */
    public static function dump($context) {
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
        if(is_null($context)) {
            $context = new stdClass;
        }
        return $method($context);
    }
    
    /**
     * Test for a match
     */
    public static function is(&$context, $type) {
        if(is_string($context)) {
            return $type === 'String';
        } else if(is_integer($context) || is_float($context)) {
            return $type === 'Number';
        } else if(is_array($context)) {
            return $type === 'List';
        } else if(is_null($context)) {
            return $type === 'Void';
        } else if(is_bool($context)) {
            return $type === 'Boolean';
        } else if($context instanceof stdClass) {
            if(isset($context->{S::TYPE})) {
                return $type === $context->{S::TYPE};
            }
            return $type === 'Entity';
        }
    }
    
    /**
     * Describe Type
     */
    public static function type(&$context) {
        if(is_string($context)) {
            return 'String';
        } else if(is_integer($context) || is_float($context)) {
            return 'Number';
        } else if(is_array($context)) {
            return 'List';
        } else if(is_null($context)) {
            return 'Void';
        } else if(is_bool($context)) {
            return 'Boolean';
        } else if($context instanceof stdClass) {
            if(isset($context->{S::TYPE})) {
                return $context->{S::TYPE};
            }
            return 'Entity';
        }
        return 'Unknown';
    }

    /**
     * Get a property of an Entity
     * seek: Whether to travel up the scope chain.
     */
    public static function property(&$context, $key, $seek = false) {
        if($context instanceof stdClass) {

            /**
             * Handle Standard Values
             */
            if(isset($context->$key)) {
                $value = $context->$key;

                /**
                 * Immediately Execute
                 */
                if($value instanceof stdClass && isset($value->{S::IMMEDIATE})) {
                    $method = $value->{S::IMMEDIATE};
                    if($method === true) {
                        $run = S::property($value, 'run');
                        return $run($context);
                    }
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
            if($seek) {
                /**
                 * Return Global Entities
                 */
                if(isset(S::$lib->$key)) {
                    return S::construct($key);
                }
            }
            /**
             * Todo
             */
            var_dump($context);
            throw new Exception("Property '$key' Not Found on $type" . 
                ($seek ? " or it's scope" : ''));
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
            if($key === 'print') {
                if($context instanceof Closure) {
                    $repr = print_r($context, true);
                    $repr = substr($repr, strpos($repr, "[parameter]") + 29);
                    $match = preg_match_all(";\[(.+?)\];", $repr, $groups);
                    if($match) {
                        $args = $groups[1];
                    } else {
                        $args = array();
                    }
                    $x = implode(', ', $args);
                    echo "{native}[$x]";
                }
                else {
                    echo "[native]";
                }
                return;
            }
            throw new Exception("No valid type found for PHP.$key:" . print_r($context, true));
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
    
    /**
     * String Value
     */
    if(!isset(S::$lib->$entity->__string__)) {
        S::$lib->$entity->__string__ = function($context) use($entity) {
            echo $entity;
        };
    }
}

/**
 * Capture Code
 */
ob_start();
