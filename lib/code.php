<?php

require_once(__DIR__ . '/code/helpers.php');

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
    $request = S::construct('Request');
    $entity = S::construct('Entity');
    if(isset($request->args['!'])) {
        switch($request->args['!']) {
            case 'stack':
                S::dump($context);
                return;
            case 'request':
                S::dump($request);
                return;
            case 'entity':
                _code_apply_stack($context->stack, $entity);
                S::dump($entity);
                return;
        }
    }
    
    /**
     * Actually process the code
     */
    _code_apply_stack($context->stack, $entity);
    return $entity;
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
    $column = 0;
    $ql = 1;
    $qc = 0;

    $stack = new stdClass;
    $stack->nest     = true;
    $stack->children = array();
    $stack->super    = null;
    $stack->{'#line'}     = $line;
    $stack->{'#column'}   = $column;

    $length = strlen($context->code);
    $queue = '';

    /**
     * Main Parse Loop
     */
    for($pos = 0; $pos < $length; $pos++) {

        if($context->code[$pos] == "\r") {
            ;
        } else if($context->code[$pos] == "\n") {
            $column = 0;
            $line++;
        } else {
            $column++;
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
                if($queue !== '') {
                    _code_parse_expression($queue, $stack, $ql, $qc);
                    $queue = '';
                }
                if($stack->super === null) {
                    _code_clean_stack($stack);
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
                    if($queue !== '') {
                        _code_parse_expression($queue, $stack, $ql, $qc);
                        $queue = '';
                    }

                    $new = new stdClass;
                    $new->token    = $chars;
                    $new->stop     = $syntax[$chars];
                    $new->nest     = isset($nest[$chars]);
                    $new->children = array();
                    $new->super    = $stack;
                    $new->{'#line'}     = $line;
                    $new->{'#column'}   = $column;

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
        if($queue === '') {
            /**
             * Note: If the queue is empty and the first character
             * is a newline, $line has already been incremented
             * above. We need to account for that and subtract 1.
             */
            $ql = $line - ($context->code[$pos] === "\n" ? 1 : 0);
            $qc = $column;
        }
        $queue .= $context->code[$pos];
    }
    _code_parse_expression($queue, $stack, $ql, $qc);
    $queue = '';
    if($stack->super !== null) {
        $sline = $stack->{'#line'};
        $scolumn = $stack->{'#column'};
        throw new Exception("Unclosed block starting with `$stack->token` " .
            "at line $sline column $scolumn in $context[label]");
    }

    _code_clean_stack($stack);
    return $stack->children;
};
