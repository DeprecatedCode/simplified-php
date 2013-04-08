<?php

/**
 * File List
 */
proto(FileType)->__apply_list__ = function($context) {
    return function($list) use($context) {
        $out = array();
        foreach($list as $path) {
            if($path !== '' && $path[0] !== '/') {
                $path = getcwd() . "/$path";
            }
            $file = construct(FileType);
            $file->path = $path;
            $out[] = $file;
        }
        return $out[0];
    };
};

/**
 * String
 */
proto(FileType)->string = function($context) {
    if(!isset($context->path)) {
        throw new Exception("No file selected");
    }
    $context->string = file_get_contents($context->path);
    return $context->string;
};

/**
 * As code
 */
proto(FileType)->code = function($context) {
    if(!isset($context->path)) {
        throw new Exception("No file selected");
    }
    $code = construct(CodeType);
    $code->code = property($context, 'string');
    $code->label = $context->path;
    return $code;
};
