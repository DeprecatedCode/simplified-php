<?php

S::$lib->File = clone S::$lib->Entity;

/**
 * File Constructor
 */
S::$lib->File->{S::CONSTRUCTOR} = function($context) {
    $context->{S::TYPE} = S::$lib->File->{S::TYPE};
    return $context;
};

/**
 * File List
 */
S::$lib->File->__apply_list__ = function($context) {
    return function($list) use($context) {
        if(S::property($list, 'length') !== 1) {
            throw new Exception("Only one file can be opened at a time");
        }
        $file = $list[0];
        if($file !== '' && $file[0] !== '/') {
            $file = getcwd() . "/$file";
        }
        $x = new stdClass;
        $x->string = file_get_contents($file);
        $x->path = $file;
        return S::construct('File', $x);
    };
};

/**
 * As code
 */
S::$lib->File->code = function($context) {
    if(!isset($context->string)) {
        throw new Exception("No file opened");
    }
    
    $x = new stdClass;
    $x->code = $context->string;
    $x->label = $context->path;
    return S::construct('Code', $x);
};
