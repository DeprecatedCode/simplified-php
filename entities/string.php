<?php

/**
 * Simplified PHP
 * @author Nate Ferrero
 */

/**
 * String
 */
class String extends Entity {

    protected $value = '';

    public function __construct($val = '') {
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
