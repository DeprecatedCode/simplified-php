<?php

require_once(__DIR__ . '/code/helpers.php');

/**
 * Code Run
 */
proto(CodeType)->run = function($context) {
    try {
        if(!isset($context->stack)) {
            $context->stack = property($context, 'parse');
        }
        if(!isset($context->entity)) {
            $context->entity = new stdClass;
        }
        
        /**
         * Process the code
         */
        return _code_apply_stack($context->stack, $context->entity);
    } catch(Exception $e) {
        $line = $e->getLine();
        $file = $e->getFile();
        throw new Exception($e->getMessage() . " (from " . $context->label . ") -- thrown on line $line of $file");
    }
};

/**
 * Code Syntax
 */
proto(CodeType)->syntax = array(
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
proto(CodeType)->nest = array(
    '(' => 1,
    '[' => 1,
    '{' => 1
);

/**
 * Code HTML Output
 */
proto(CodeType)->__html__ = function($context) {
    
    $parse = proto(CodeType)->parse;
    $stack = $parse($context, false);
    $lines = _code_flatten_stack($stack);
    
    $html = '<h4 class="sphp-info">Source of ' . $context->label . '</h4>';
    
    
    $html .= '<table class="simplified-php-html">';
    
    $html .= '<tr><td width="0"><pre class="sphp-lines">' . 
        implode("\n", range(1, $lines)) . '</pre></td>';

    $html .= '<td><pre>';
    foreach($stack as $item) {
        $html .= '<span class="sphp-' . $item->type . '">' . 
            htmlspecialchars($item->{'#raw'}) . '</span>';
    }
    $html .= '</pre></td></tr>';
    
    
    return $html . '</table>';
};

/**
 * Code Parse
 */
proto(CodeType)->parse = function($context) {
    $syntax = property($context, 'syntax');
    $nest = property($context, 'nest');

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
            "at line $sline column $scolumn in " . $context->label);
    }

    _code_clean_stack($stack);
    return $stack->children;
};
