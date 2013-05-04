<?php

# Time
define('TimerStart', microtime(true));

# Global Prototypes
const EntityType     = 'Entity';
const BooleanType    = 'Boolean';
const CodeType       = 'Code';
const ExpressionType = 'Expression';
const FileType       = 'File';
const ListType       = 'List';
const NetworkType    = 'Network';
const NumberType     = 'Number';
const PropertyType   = 'Property';
const RangeType      = 'Range';
const RequestType    = 'Request';
const RouterType     = 'Router';
const StringType     = 'String';
const SystemType     = 'System';
const TestType       = 'Test';
const VoidType       = 'Void';

# Global Constansts
const Version        = '0.0.1';

# System Properties
const Comment        = '#comment';
const Constructor    = '#constructor';
const Immediate      = '#immediate';
const Type           = '#type';
const Proto          = '#proto';

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
 * Dump
 */
function dump($context, $style=true) {
    $system = construct(SystemType);
    if($context instanceof Closure) {
        echo htmlspecialchars('[Native Code]');
        return;
    }
    $html = property($context, '__html__');
    if($style) {
        $style = property($system, '__css__');
        $style = '<style>' . $style . '</style>';
        property($style, 'print');
    }
    property($html, 'print');
}

/**
 * Variables
 */
class Engine {
    public static $proto;
    public static $types;
}

Engine::$proto = new stdClass;

Engine::$types = array(
    'Entity', 'Boolean', 'Code', 'Expression', 'File', 'List', 'Network',
    'Number', 'Property', 'Range', 'Request', 'Router', 'String', 'System',
    'Test', 'Void'
);

/**
 * Get the proto for a type
 */
function proto($type) {
    if(!isset(Engine::$proto->$type)) {
        throw new Exception("Invalid type '$type'");
    }
    if(Engine::$proto->$type === true) {
        Engine::$proto->$type = new stdClass;
        Engine::$proto->$type->{Type} = $type;
        require_once(__DIR__ . '/../' . strtolower($type) . '.php');
        
        /**
         * Default Method: HTML Output of String Value
         */
        if(!isset(Engine::$proto->$type->__html__)) {
            Engine::$proto->$type->__html__ = function($context) use($type) {
                $t = strtolower($type);
                $str = htmlspecialchars(property($context, '__string__'));
                return "<span class=\"sphp-$t\">$str</span>";
            };
        }
        
        /**
         * Default Method: Print String Value
         */
        if(!isset(Engine::$proto->$type->print)) {
            Engine::$proto->$type->print = function($context) {
                echo property($context, '__string__');
            };
        }
        
        /**
         * Default Method: String Value
         */
        if(!isset(Engine::$proto->$type->__string__)) {
            Engine::$proto->$type->__string__ = function($context) use($type) {
                return $type;
            };
        }
    }

    return Engine::$proto->$type;
}

/**
 * Initialize Basic Types
 */
foreach(Engine::$types as $type) {
    Engine::$proto->$type = true;
}

/**
 * Construct an Entity
 */
function construct($type) {
    $proto = proto($type);
    if(isset($proto->{Constructor})) {
        $method = $proto->{Constructor};
        return $method();
    } else {
        $entity = new stdClass;
        $entity->{Proto} = $type;
        return $entity;
    }
}

/**
 * Convert Array to Entity
 */
function entity($array) {
    $e = construct(EntityType);
    foreach($array as $key => $value) {
        if(is_array($value)) {
            $value = entity($value);
        }
        $e->$key = $value;
    }
    return $e;
}

/**
 * Describe Type
 */
function type(&$context) {
    if(is_string($context)) {
        return StringType;
    } else if(is_integer($context) || is_float($context)) {
        return NumberType;
    } else if(is_array($context)) {
        return ListType;
    } else if(is_null($context)) {
        return VoidType;
    } else if(is_bool($context)) {
        return BooleanType;
    } else if($context instanceof stdClass) {
        if(isset($context->{Proto}) && is_string($context->{Proto})) {
            return $context->{Proto};
        }
        return property($context, Type);
    } else if(is_callable($context)) {
        return ExpressionType;
    }
    return 'Unknown';
}

/**
 * Get a property of an Entity
 * seek: Whether to travel up the scope chain.
 */
function property(&$context, $key, $seek = false, &$original = null) {
    
    /**
     * Self property
     */
    if($key === '__self__') {
        return $context;
    }
    
    if($context instanceof stdClass) {

        /**
         * Handle Standard Values
         */
        if(isset($context->$key)) {
            $value = $context->$key;

            /**
             * Immediately Execute
             */
            if($value instanceof stdClass && isset($value->{Immediate}) && $value->{Immediate} === true) {
                $value = property($value, 'run');
            }

            # Checking is_callable here results in a bug since the string
            # "Entity" is considered callable, as function entity() exists.
            if(!($value instanceof Closure)) {
                return $value;
            }
            
            if(!is_null($original)) {
                return $value($original);
            }
            return $value($context);
        }

        /**
         * Handle Proto Values
         */
        if(isset($context->{Proto})) {
            $proto = $context->{Proto};
            if(is_string($proto)) {
                $proto = proto($proto);
            }
            return property($proto, $key, $seek, $context);
        }
        
        if(!isset($context->{Type})) {
            $proto = proto(EntityType);
            return property($proto, $key, $seek, $context);
        }
        
        /**
         * Handle Entity Types
         */
        if($key === Type) {
            return EntityType;
        }
        
        /**
         * Search Scope
         */
        if($seek) {
            /**
             * Return Global Entities
             */
            if(isset(Engine::$proto->$key)) {
                return construct($key);
            }
        }
        
        /**
         * Iterate lists
         */
        if(is_array($original)) {
            $out = array();
            foreach($original as $item) {
                $out[] = property($item, $key);
            }
            return $out;
        }

        /**
         * Todo - clean up error messaging
         */
        throw new Exception("Property '$key' Not Found on " . type($context) . 
            ($seek ? " or it's scope" : ''));
    }

    /**
     * Handle PHP Types
     */
    $type = type($context);
    $proto = proto($type);
    return property($proto, $key, $seek, $context);
}
