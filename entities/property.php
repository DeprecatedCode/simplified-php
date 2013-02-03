<?php

/**
 * Simple Notation :: PHP
 * @author Nate Ferrero
 */

/**
 * Property
 */
class Property extends Entity {

}

/**
 * NativeProperty
 */
class NativeProperty extends Property {

    /**
     * Actual Invocation must be defined in subclass.
     */
    public function __invoke() {
        throw new Exception('Invoke not defined on NativeProperty');
    }

}
