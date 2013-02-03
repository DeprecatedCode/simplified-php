<?php

/**
 * Simple Notation :: PHP
 * @author Nate Ferrero
 */

/**
 * Network HTTP Entity
 */
class NetworkHTTP {
    public static function getEntity() {
        $config = new Entity(array(
            'headers' => new Entity,
            'cookies' => new Entity,
            'timeout' => 60
        ));
        return new Entity(array(
            'get'     => new NetworkHTTPMethod($config, 'GET'),
            'delete'  => new NetworkHTTPMethod($config, 'DELETE'),
            'options' => new NetworkHTTPMethod($config, 'OPTIONS'),
            'post'    => new NetworkHTTPMethod($config, 'POST'),
            'put'     => new NetworkHTTPMethod($config, 'PUT'),
            'config'  => $config
        ));
    }
}

/**
 * Network HTTP Method Expression
 */
class NetworkHTTPMethod extends NativeExpression {

    protected $method;

    public function expressionArgs() {
        $args = new Entity(array('url' => new Void));
        if(in_array($this->method, array('POST', 'PUT'))) {
            $args->data = new Void;
        }
        return $args;
    }

    public function __construct($config, $method) {
        $this->method = $method;
        parent::__construct();
        $this->includeEntity($config);
    }

    public function __invoke($self) {
        ;
    }

}