<?php

/**
 * File List
 */
proto(FileType)->__apply__ = function($context) {
    return function($item) use($context) {
        if(!is_string($item)) {
            throw new Exception("File argument must be a string");
        }
        if($item !== '' && $item[0] !== '/') {
            $item = getcwd() . "/$item";
        }
        $file = construct(FileType);
        $file->path = $item;
        return $file;
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
 * Lines
 */
proto(FileType)->lines = function($context) {
    if(!isset($context->path)) {
        throw new Exception("No file selected");
    }
    $context->string = file_get_contents($context->path);
    return explode("\n", $context->string);
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

/**
 * Run code
 */
proto(FileType)->run = function($context) {
    if(!isset($context->path)) {
        throw new Exception("No file selected");
    }
    $code = construct(CodeType);
    $code->code = property($context, 'string');
    $code->label = $context->path;
    property($code, 'run');
    return $code->entity;
};
