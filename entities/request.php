<?php

/**
 * Simple Notation :: PHP
 * @author Nate Ferrero
 */

/**
 * Request
 */
class Request extends Entity {

    public function __construct() {
        parent::__construct(array(
            'address'   => isset($_SERVER['REMOTE_ADDR']) ?
                                 $_SERVER['REMOTE_ADDR'] : null,

            'args'      => $_GET,
            'cookies'   => $_COOKIE,
            'domain'    => isset($_SERVER['HTTP_HOST']) ?
                                 $_SERVER['HTTP_HOST'] : null,

            'files'     => $_FILES,
            'form'      => $_POST,
            'method'    => isset($_SERVER['REQUEST_METHOD']) ?
                                 $_SERVER['REQUEST_METHOD'] : null,

            'path'      => array_shift(explode('?', isset($_SERVER['REQUEST_URI']) ?
                                 $_SERVER['REQUEST_URI'] : '')),

            'query'     => isset($_SERVER['QUERY_STRING']) ?
                                 $_SERVER['QUERY_STRING'] : null,

            'referer'   => isset($_SERVER['HTTP_REFERER']) ?
                                 $_SERVER['HTTP_REFERER'] : null,

            'url'       => isset($_SERVER['REQUEST_URI']) ?
                                 $_SERVER['REQUEST_URI'] : null,

            'userAgent' => isset($_SERVER['HTTP_USER_AGENT']) ?
                                 $_SERVER['HTTP_USER_AGENT'] : null

        ));
    }

}
