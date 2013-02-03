<?php

/**
 * Simple Notation :: PHP
 * @author Nate Ferrero
 */

/**
 * Number
 */
class Number extends Entity {

    protected $value = 0;

    public function __construct($val = 0) {
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
