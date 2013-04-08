<?php

proto(RequestType)->{Proto} = proto(EntityType);

/**
 * Request Constructor
 */
proto(RequestType)->{Constructor} = function() {
    if(!isset(proto(RequestType)->__instance__)) {
        $headers = getallheaders();
        $location = $_SERVER['REQUEST_URI'];
        $path = explode('?', $location, 2);
        $path = $path[0];
        $host = $_SERVER['HTTP_HOST'];
        $root = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ?
            'https://' : 'http://') . $host;
        $base_url = $root . $path;
        $url = $root . $location;
        $url_root = $root . substr($path, 0, strrpos($path, '/') + 1);
        $Request = new stdClass;
        $Request->{Type} = proto(RequestType)->{Type};
        $Request->{Proto} = proto(EntityType);
        $Request->args = entity($_GET);
        $Request->base_url = $base_url;
        $Request->cookies = entity($_COOKIE);
        $Request->data = file_get_contents("php://input");
        $Request->files = entity($_FILES);
        $Request->form = entity($_POST);
        $Request->headers = $headers;
        $Request->host = $_SERVER['HTTP_HOST'];
        $Request->location = $location;
        $Request->method = $_SERVER['REQUEST_METHOD'];
        $Request->path = $path;
        $Request->protocol = $_SERVER['SERVER_PROTOCOL'];
        $Request->query = $_SERVER['QUERY_STRING'];
        $Request->remote_addr = $_SERVER['REMOTE_ADDR'];
        $Request->remote_port = $_SERVER['REMOTE_PORT'];
        $Request->root = $root;
        $Request->script = $_SERVER['SCRIPT_FILENAME'];
        $Request->time = $_SERVER['REQUEST_TIME_FLOAT'];
        $Request->url = $url;
        $Request->url_root = $url_root;
        proto(RequestType)->__instance__ = $Request;
    }
    return proto(RequestType)->__instance__;
};
