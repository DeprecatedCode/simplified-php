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
        if($message instanceof Exception) {
            $message = $message->getMessage() . " [internal error at " .
                $message->getFile() . " line " . $message->getLine() . ']';
        }
        $message = "$message near $pos";
        return parent::__construct($message);
    }
}

/**
 * Flatten Stack
 */
function _code_flatten_stack(&$stack) {
    $new = array();
    while(true) {
        $lines = 0;
        $len = count($stack);
        for($i = 0; $i < $len; $i++) {
            
            $item = is_object($stack[$i]) ? clone $stack[$i] : $stack[$i];
            $stack[$i] = $item;
            
            $lines += substr_count($item->{'#raw'}, "\n");
            
            if(isset($item->list)) {
                $children = $item->list;
                unset($item->list);
                $type = 'list';
            } else if(isset($item->entity)) {
                $children = $item->entity;
                unset($item->entity);
                $type = 'entity';
            } else if(isset($item->expression)) {
                $children = $item->expression;
                unset($item->expression);
                $type = 'expression';
            } else {
                $type = 'other';
                if(isset($item->comment)) {
                    $type = 'comment';
                    unset($item->comment);
                } else if(isset($item->string)) {
                    $type = 'string';
                    unset($item->string);
                } else if(isset($item->operator)) {
                    $type = 'operator';
                    unset($item->operator);
                } else if(isset($item->identifier)) {
                    $type = 'identifier';
                    unset($item->identifier);
                } else if(isset($item->literal)) {
                    $type = 'literal';
                    unset($item->literal);
                } else if(isset($item->break)) {
                    $type = 'break';
                    unset($item->break);
                } else if(isset($item->space)) {
                    $type = 'space';
                    unset($item->space);
                }
                $children = false;
            }
            if(!isset($item->type)) {
                $item->type = $type;
            }
            if(is_array($children) && isset($item->{'#rawStop'})) {
                $new = new stdClass;
                $new->{'#raw'} = $item->{'#rawStop'};
                $new->type = $type;
                $children[] = $new;
                array_splice($stack, $i + 1, 0, $children);
                unset($item->{'#rawStop'});
                continue 2;
            }
        }
        
        // No more children present
        break;
    }
    
    return $lines + 1;
}

/**
 * Utility Method: Apply Stack
 */
function _code_apply_stack($stack, &$entity) {
    
    /**
     * Evaluate Expressions
     */
    $type = type($entity);

    if($type === ExpressionType) {
        if(isset($stack[0]) && isset($stack[0]->operator)
            && $stack[0]->operator == '!') {
            array_shift($stack);
            $entity->{Immediate} = true;
        }
        $entity->stack = $stack;
    }
    
    /**
     * Evaluate Lists
     */
    else if($type === ListType) {
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
                    $parent = new stdClass;
                    $entity[] = _code_reduce_value($queue, $parent);
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
    else if($type === EntityType) {
        static $_NEUTRAL = 0;
        static $_KEY = 1;
        static $_VALUE = 3;
        $state = $_NEUTRAL;
        $end = new stdClass;
        $end->break = true;
        $stack[] = $end;
        $queue = array();
        $key = null;
        $escape = false;
        $last = null;
        foreach($stack as $item) {
            
            if($escape === true) {
                $escape = false;
                continue;
                
                /**
                 * TODO: Handle \literal etc here
                 */
            }
            
            if(isset($item->comment)) {
                if(!isset($entity->{'#comment'})) {
                    $entity->{'#comment'} = $item->comment;
                }
                continue;
            } else if(isset($item->space)) {
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
                        $last = _code_reduce_value($queue, $entity);
                        $entity->{$key} = $last;
                    } else {
                        /**
                         * Just process the code and keep the result
                         */
                        $last = _code_reduce_value($queue, $entity);
                    }
                    $queue = array();
                    $state = $_NEUTRAL;
                }
            } else if(isset($item->operator) && $item->operator === '\\') {
                $escape = true;
                continue;
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
        return $last;
    }
    
    /**
     * Cannot process
     */
    else {
        throw new Exception("Cannot run code in context of " . $type);
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
    $O = proto(SystemType)->operators;
    $operator = '@';
    $operation = $noop = $O->{$operator};
    $value = null;
    foreach($stack as $item) {
        if(isset($item->space)) {
            continue;
        } else if(isset($item->entity)) {
            $entity = new stdClass;
            _code_apply_stack($item->entity, $entity);
            $value = $operation($value, $entity);
            $operation = $noop;
        } else if(isset($item->expression)) {
            $expression = construct(ExpressionType);
            _code_apply_stack($item->expression, $expression);
            $value = $operation($value, $expression);
            $operation = $noop;
        } else if(isset($item->list)) {
            $list = construct(ListType);
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
                    $value = property($value, $item->identifier);
                } else {
                    /**
                     * Get variable in current scope
                     */
                    $x = property($context, $item->identifier, true);
                    $value = $operation($value, $x);
                }
            } catch(Exception $e) {
                throw new CodeException($item, $e);
            }
            $operation = $noop;
        } else if(isset($item->string)) {
            $value = $operation($value, $item->string);
            $operation = $noop;
        } else if(isset($item->literal)) {
            $value = $operation($value, (float) $item->literal);
            $operation = $noop;
        } else {
            $desc = print_r($item, true);
            throw new CodeException($item, "Not implemented feature '$desc' found");
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
        '[+-]?(\d+\.?\d*([eE][+-]?\d+)?)'  => 'literal',
        '[a-zA-Z0-9_]+'     => 'identifier',
        '[^\sa-zA-Z0-9_]+'  => 'operator',
        '\n+'               => 'break',
        '\s+'               => 'space'
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
                    $obj->token = $current;
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
                $obj->{'#raw'} = implode('', $obj->children);
                $obj->string = stripcslashes($obj->{'#raw'});
                break;
            /**
             * Clean comments
             */
            case '#':
            case '/*':
                $comment = implode('', $obj->children);
                $obj->{'#raw'} = $comment;
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
        if(isset($obj->{'#raw'})) {
            $obj->{'#raw'} = $obj->token . $obj->{'#raw'} . $obj->stop;
        } else {
            $obj->{'#raw'} = $obj->token;
            if(isset($obj->stop)) {
                $obj->{'#rawStop'} = $obj->stop;
            }
        }
        unset($obj->token);
        unset($obj->children);
    }
    
    unset($obj->stop);
    unset($obj->nest);
}
