<?php

/**
 * Simplified PHP
 * @author Nate Ferrero
 */

/**
 * Code
 */
class Code extends Entity {

    protected $value = array();

    public function __construct($val = array()) {
        parent::__construct();
        $this->value = $val;
    }

    public function getValue() {
        return $this->value;
    }

    public function setValue() {
        $this->value = $val;
    }

}
