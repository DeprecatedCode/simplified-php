<?php

/**
 * Simple Notation :: PHP
 * @author Nate Ferrero
 */

/**
 * Entity String Representation
 */
class IncludedEntitiesNativeProperty extends NativeProperty {

    public function __invoke($self) {
        return new Entity($self->getIncludedEntities());
    }

}

/**
 * String Length
 */
class StringLengthNativeProperty extends NativeProperty {

    public function __invoke($self) {
        return new Number(strlen($self->getValue()));
    }

}