<?php

S::$lib->Code = clone S::$lib->Entity;

/**
 * Code Constructor
 */
S::$lib->Code->{S::CONSTRUCTOR} = function($context) {
    $context->{S::TYPE} = S::$lib->Code->{S::TYPE};
    return $context;
};

/**
 * Code Run
 */
S::$lib->Code->run = function($context) {
    if(!isset($context->stack)) {
        $context->stack = S::property($context, 'parse');
    }
    S::dump($context);
};

/**
 * Code Syntax
 */
S::$lib->Code->syntax = array(
      '(' => ')'   ,
      '[' => ']'   ,
      '{' => '}'   ,
     '/*' => '*/'  ,
      '#' => "\n"  ,
    '"""' => '"""' ,
    "'''" => "'''" ,
     '"'  =>  '"'  ,
     "'"  =>  "'"
);

/**
 * Syntax Nesting
 */
S::$lib->Code->nest = array(
    '(' => 1,
    '[' => 1,
    '{' => 1
);

/**
 * Code Parse
 */
S::$lib->Code->parse = function($context) {
    $syntax = S::property($context, 'syntax');
    $nest = S::property($context, 'nest');

    $line = 1;
    $col = 0;

    $stack = new stdClass;
    $stack->nest     = true;
    $stack->children = array();
    $stack->super    = null;
    $stack->line     = $line;
    $stack->col      = $col;

    $length = strlen($context->code);
    $queue = '';

    /**
     * Main Parse Loop
     */
    for($pos = 0; $pos < $length; $pos++) {

        $col += 1;
        if($context->code[$pos] == "\n") {
            $col = 0;
            $line ++;
        }

        /**
         * First, check for the current stop.
         * If found and null super, return.
         */
        if(isset($stack->stop)) {
            $slen = strlen($stack->stop);
            $chars = substr(
                $context->code, $pos, $slen
            );
            if($chars === $stack->stop) {
                if(strlen($queue) > 0) {
                    parse_expression($queue, $stack);
                    $queue = '';
                }
                if($stack->super === null) {
                    remove_all_supers($stack);
                    return $stack;
                }
                $stack = $stack->super;
                $pos += $slen - 1;
                continue;
            }
        }
        
        /**
         * Search for matching characters, from 3 to 1
         */
        if($stack->nest) {
            for($blen = 3; $blen >= 1; $blen--) {
                $chars = substr(
                    $context->code, $pos, $blen
                );
                if(isset($syntax[$chars])) {
                    if(strlen($queue) > 0) {
                        parse_expression($queue, $stack);
                        $queue = '';
                    }

                    $new = new stdClass;
                    $new->token    = $chars;
                    $new->stop     = $syntax[$chars];
                    $new->nest     = isset($nest[$chars]);
                    $new->children = array();
                    $new->super    = $stack;
                    $new->line     = $line;
                    $new->col      = $col;

                    $stack->children[] = $new;
                    $stack = $new;
                    $pos += $blen - 1;
                    continue 2;
                }
            }
        }

        /**
         * No match, add to queue and continue
         */
        $queue .= $context->code[$pos];
    }
    parse_expression($queue, $stack);
    $queue = '';
    if($stack->super !== null) {
        throw new Exception("Unclosed block starting with `$stack->token` " .
            "at line $stack->line column $stack->col in $context[label]");
    }

    remove_all_supers($stack);
    return $stack->children;
};

/**
 * Utility Method: Parse expression
 */
function parse_expression($expr, &$stack) {
    if(!$stack->nest) {
        $stack->children[] = $expr;
        return;
    }
    $regex = array(
        '[a-zA-Z0-9_]+' => 'identifier',
        '[^\sa-zA-Z0-9_]+'           => 'operator',
        '\s+'           => null
    );
    while(strlen($expr) > 0) {
        foreach($regex as $re => $type) {
            $match = preg_match(";^$re;", $expr, $groups);
            if($match) {
                $expr = substr($expr, strlen($groups[0]));
                if(!is_null($type)) {
                    $obj = new stdClass;
                    $obj->$type = $groups[0];
                    $stack->children[] = $obj;
                }
            }
        }
    }
}

/**
 * Utility Method: Remove all supers
 */
function remove_all_supers(&$obj) {
    unset($obj->super);
    unset($obj->stop);
    unset($obj->nest);
    foreach($obj->children as $child) {
        if(is_object($child) && isset($child->children)) {
            remove_all_supers($child);
        }
    }
}
