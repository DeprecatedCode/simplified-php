<?php

S::$lib->Code = S::$lib->Entity;
$X = &S::$lib->Code;

/**
 * Code Constructor
 */
$X[S::CONSTRUCTOR] = function(&$context) {
    $X = &S::$lib->Code;
    $context[S::TYPE] = $X[S::TYPE];
    return $context;
};

/**
 * Code Run
 */
$X['run'] = function(&$context) {
    if(!isset($context['stack'])) {
        $context['stack'] = S::property($context, 'parse');
    }
    S::dump($context);
};

/**
 * Code Syntax
 */
$X['syntax'] = array(
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
$X['nest'] = array(
    '(' => 1,
    '[' => 1,
    '{' => 1
);

/**
 * Code Parse
 */
$X['parse'] = function(&$context) {
    $syntax = S::property($context, 'syntax');
    $nest = S::property($context, 'nest');

    $stack = array(
        'token'    => '|#-#|',
        'stop'     => '|#-#|',
        'nest'     => true,
        'children' => array(),
        'parent'   => null
    );

    $length = strlen($context['code']);
    $queue = '';

    /**
     * Main Parse Loop
     */
    for($pos = 0; $pos < $length; $pos++) {

        /**
         * First, check for the current stop.
         * If found and null parent, return.
         */
        $slen = strlen($stack['stop']);
        $chars = substr(
            $context['code'], $pos, $slen
        );
        if($chars === $stack['stop']) {
            if(strlen($queue) > 0) {
                $stack['children'][] = $queue;
                $queue = '';
            }
            if($stack['parent'] === null) {
                return $stack;
            }
            $stack =& $stack['parent'];
            $pos += $slen - 1;
            continue;
        }
        
        /**
         * Search for matching characters, from 3 to 1
         */
        if($stack['nest']) {
            for($blen = 3; $blen >= 1; $blen--) {
                $chars = substr(
                    $context['code'], $pos, $blen
                );
                if(isset($syntax[$chars])) {
                    if(strlen($queue) > 0) {
                        $stack['children'][] = $queue;
                        $queue = '';
                    }
                    $new = array(
                        'token'     => $syntax[$chars],
                        'stop'     => $syntax[$chars],
                        'nest'     => isset($nest[$chars]),
                        'children' => array(),
                        'parent'   => &$stack
                    );
                    $stack['children'][] = &$new;
                    $stack = &$new;
                    $pos += $blen - 1;
                    continue 2;
                }
            }
        }

        /**
         * No match, add to queue and continue
         */
        $queue .= $context['code'][$pos];
    }
    $stack['children'][] = $queue;
    if($stack['parent'] !== null) {
        throw new Exception("Unclosed block starting with $stack[token]");
    }

    remove_all_parents($stack);

    $queue = '';
    return $stack;
};

/**
 * Utility Method: Remove all parents
 */
function remove_all_parents(&$arr) {
    unset($arr['parent']);
    foreach($arr['children'] as &$child) {
        if(is_array($child)) {
            remove_all_parents($child);
        }
    }
}
