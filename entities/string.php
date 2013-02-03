<?php

/**
 * Simple Notation :: PHP
 * @author Nate Ferrero
 */

/**
 * String
 */
class String extends Entity {

    private $value = '';

    public function __construct($val = '') {
        $this->value = $val;
    }

    public function getValue() {
        return $this->value;
    }

    public function setValue() {
        $this->value = $val;
    }

}
