<?php

S::$lib->Request = clone S::$lib->Entity;

/**
 * Request Constructor
 */
S::$lib->Request->{S::CONSTRUCTOR} = function($context) {
    if(!isset(S::$lib->Request->__instance__)) {
        $headers = getallheaders();
        $_GET[S::TYPE] = 'Entity';
        $_COOKIE[S::TYPE] = 'Entity';
        $_FILES[S::TYPE] = 'Entity';
        $_POST[S::TYPE] = 'Entity';
        $headers[S::TYPE] = 'Entity';
        $location = $_SERVER['REQUEST_URI'];
        $path = explode('?', $location, 2);
        $path = $path[0];
        $host = $_SERVER['HTTP_HOST'];
        $root = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ?
            'https://' : 'http://') . $host;
        $base_url = $root . $path;
        $url = $root . $location;
        $url_root = $root . substr($path, 0, strrpos($path, '/') + 1);
        $context->{S::TYPE} = S::$lib->Request->{S::TYPE};
        $context->args = $_GET;
        $context->base_url = $base_url;
        $context->cookies = $_COOKIE;
        $context->data = file_get_contents("php://input");
        $context->files = $_FILES;
        $context->form = $_POST;
        $context->headers = $headers;
        $context->host = $_SERVER['HTTP_HOST'];
        $context->location = $location;
        $context->method = $_SERVER['REQUEST_METHOD'];
        $context->path = $path;
        $context->protocol = $_SERVER['SERVER_PROTOCOL'];
        $context->query = $_SERVER['QUERY_STRING'];
        $context->remote_addr = $_SERVER['REMOTE_ADDR'];
        $context->remote_port = $_SERVER['REMOTE_PORT'];
        $context->root = $root;
        $context->script = $_SERVER['SCRIPT_FILENAME'];
        $context->time = $_SERVER['REQUEST_TIME_FLOAT'];
        $context->url = $url;
        $context->url_root = $url_root;
        S::$lib->Request->__instance__ = $context;
    }
    return S::$lib->Request->__instance__;
};
