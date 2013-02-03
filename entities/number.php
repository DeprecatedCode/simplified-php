<?php

/**
 * Simple Notation :: PHP
 * @author Nate Ferrero
 */

/**
 * Number
 */
class Number extends Entity {

    private $value = 0;

    public function __construct($val = 0) {
        $this->value = $val;
    }

    public function getValue() {
        return $this->value;
    }

    public function setValue() {
        $this->value = $val;
    }

}
