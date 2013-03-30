<?php

/**
 * CodeException
 */
class CodeException extends Exception {
    
    public $item;
    
    public function __construct($item, $message) {
        $this->item = $item;
        $pos = 'line ' . $item->{'#line'} .
            ', column ' . $item->{'#column'}; 
        $message = "$message near $pos";
        return parent::__construct($message);
    }
}

/**
 * Utility Method: Apply Stack
 */
function _code_apply_stack($stack, &$entity) {
    
    /**
     * Evaluate Expressions
     */
    if(S::is($entity, 'Expression')) {
        $entity->stack = $stack;
    }
    
    /**
     * Evaluate Lists
     */
    else if(S::is($entity, 'List')) {
        $end = new stdClass;
        $end->break = true;
        $stack[] = $end;
        $queue = array();
        foreach($stack as $item) {
            if(isset($item->comment)) {
                if(!isset($entity['#comment'])) {
                    $entity['#comment'] = $item->comment;
                }
                continue;
            }
            if(isset($item->break) || 
                (isset($item->operator) && $item->operator === ',')) {
                if(count($queue) > 0) {
                    $entity[] = _code_reduce_value($queue, $entity);
                    $queue = array();
                }
            } else {
                $queue[] = $item;
            }
        }
    }
    
    /**
     * Evaluate Entities
     */
    else if(S::is($entity, 'Entity')) {
        static $_NEUTRAL = 0;
        static $_KEY = 1;
        static $_VALUE = 3;
        $state = $_NEUTRAL;
        $end = new stdClass;
        $end->break = true;
        $stack[] = $end;
        $queue = array();
        $key = null;
        foreach($stack as $item) {
            if(isset($item->comment)) {
                if(!isset($entity->{'#comment'})) {
                    $entity->{'#comment'} = $item->comment;
                }
                continue;
            }
            if(isset($item->break) || 
                (isset($item->operator) && $item->operator === ',')) {
                if(count($queue) > 0) {
                    if($state === $_KEY) {
                        /**
                         * Assign Void to key when no value provided
                         */
                        $entity->{_code_reduce_key($queue, $entity)} = null;
                    } else if($state === $_VALUE) {
                        /**
                         * Store the processed value in key
                         */
                        $entity->{$key} = _code_reduce_value($queue, $entity);
                    } else {
                        /**
                         * Just process the code and discard the result
                         */
                        _code_reduce_value($queue, $entity);
                    }
                    $queue = array();
                    $state = $_NEUTRAL;
                }
            } else if(isset($item->operator) && $item->operator === ':') {
                if(count($queue) > 0) {
                    $key = _code_reduce_key($queue, $entity);
                    $queue = array();
                    $state = $_VALUE;
                } else {
                    throw new CodeException($item,
                        "No key provided when parsing Entity");
                }
            } else {
                $queue[] = $item;
            }
        }
    }
    
    /**
     * Cannot process
     */
    else {
        throw new Exception("Cannot run code in context of " .
            S::type($entity));
    }
}

/**
 * Utility Method: Code reduce key
 */
function _code_reduce_key(&$stack, &$context) {
    /**
     * Special case for keys, allowed to be single unquoted
     * identifier. The same would result in an error for values.
     */
    if(count($stack) === 1 && isset($stack[0]->identifier)) {
        return $stack[0]->identifier;
    }
    return _code_reduce_value($stack, $context);
}

/**
 * Utility Method: Code reduce value
 */
function _code_reduce_value(&$stack, &$context) {
    $O = S::$lib->System->operators;
    $operator = '@';
    $operation = $noop = $O->{$operator};
    $value = null;
    foreach($stack as $item) {
        if(isset($item->entity)) {
            $entity = new stdClass;
            _code_apply_stack($item->entity, $entity);
            $value = $operation($value, $entity);
            $operation = $noop;
        } else if(isset($item->expression)) {
            $expression = S::construct('Expression');
            _code_apply_stack($item->expression, $expression);
            $value = $operation($value, $expression);
            $operation = $noop;
        } else if(isset($item->list)) {
            $list = S::construct('List');
            _code_apply_stack($item->list, $list);
            $value = $operation($value, $list);
            $operation = $noop;
        } else if(isset($item->operator)) {
            if(isset($O->{$item->operator})) {
                $operator = $item->operator;
                $operation = $O->{$operator};
            } else {
                $op = $item->operator;
                throw new CodeException($item, "Operator $op is not defined");
            }
        } else if(isset($item->identifier)) {
            try {
                if($operator === '.') {
                    /**
                     * Get property of current value
                     */
                    $value = S::property($value, $item->identifier);
                } else {
                    /**
                     * Get variable in current scope
                     */
                    $x = S::property($context, $item->identifier, true);
                    $value = $operation($value, $x);
                }
            } catch(Exception $e) {
                throw new CodeException($item, $e->getMessage());
            }
            $operation = $noop;
        } else if(isset($item->string)) {
            $value = $operation($value, $item->string);
            $operation = $noop;
        } else {
            throw new CodeException("Not implemented feature found");
        }
    }
    return $value;
}

/**
 * Utility Method: Parse expression
 */
function _code_parse_expression($expr, &$stack, $line, $column) {
    if(!$stack->nest) {
        $stack->children[] = $expr;
        return;
    }
    static $regex = array(
        '[a-zA-Z0-9_]+'     => 'identifier',
        '[^\sa-zA-Z0-9_]+'  => 'operator',
        '\n+'               => 'break',
        '\s+'               => null
    );
    while(strlen($expr) > 0) {
        foreach($regex as $re => $type) {
            $match = preg_match(";^$re;", $expr, $groups);
            if($match) {
                $current = $groups[0];
                $len = strlen($current);
                $expr = substr($expr, $len);
                
                /**
                 * Save to Stack
                 */
                if(!is_null($type)) {
                    $obj = new stdClass;
                    $obj->{'#line'} = $line;
                    $obj->{'#column'} = $column;
                    $obj->$type = $type == 'break' ? true : $current;
                    $stack->children[] = $obj;
                }
                
                /**
                 * Update Line and Column
                 */
                for($i = 0; $i < $len; $i++) {
                    if($current[$i] == "\r") {
                        ;
                    } else if($current[$i] == "\n") {
                        $column = 1;
                        $line++;
                    } else {
                        $column++;
                    }
                }
            }
        }
    }
}

/**
 * Utility Method: Clean stack
 */
function _code_clean_stack(&$obj) {
    unset($obj->super);
    unset($obj->stop);
    unset($obj->nest);
    
    if(isset($obj->children)) {
        foreach($obj->children as $child) {
            if(is_object($child)) {
                _code_clean_stack($child);
            }
        }
    }

    if(isset($obj->token)) {
        /**
         * Clean specific cases
         */
        switch($obj->token) {
            /**
             * Clean strings
             */
            case '"':
            case "'":
            case '"""':
            case "'''":
                $obj->string = implode('', $obj->children);
                break;
            /**
             * Clean comments
             */
            case '#':
            case '/*':
                $comment = implode('', $obj->children);
                if($comment !== '' && $comment[0] == '*') {
                    $comment = substr($comment, 1);
                }
                $comment = preg_replace(";\n\s*\*;", "\n", $comment);
                $obj->comment = trim($comment);
                break;
            /**
             * Clean data structures
             */
            case '(':
                $obj->list = $obj->children;
                break;
            case '[':
                $obj->entity = $obj->children;
                break;
            case '{':
                $obj->expression = $obj->children;
                break;
        }
        
        /**
         * Remove extra information
         */
        unset($obj->token);
        unset($obj->children);
    }
}
