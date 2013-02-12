<?php

/**
 * Simplified PHP
 * @author Nate Ferrero
 */

/**
 * Entity
 */
class Entity {

    public function __construct($clone = null) {
        if(!is_null($clone)) {
            foreach($clone->keys() as $key) {
                $this->$key = $clone->$key;
            }
        }
    }

    public $scope = array();

    public function __get($var) {
        if isset($this->scope[$var]) {
            return $this->scope[$var];
        }
    }

    public function __set($var, $value) {
        $this->scope[$var] = $value;
    }

    public function keys() {
        return array_keys($this->scope);
    }

}

/**
 * Simplified Class
 */
class S {
    public static $prototype;
}

S::$prototype = new Entity;

/**
 * All Prototypes
 */
foreach(explode(' ', 'entity code expression file network ' .
    'number property range request router string') as $path) {
    require_once(__DIR__ . "/lib/$file.php");
}
