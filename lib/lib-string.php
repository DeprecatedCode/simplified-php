<?php

/**
 * Simplified PHP Library: String
 * @author Nate Ferrero
 */

/**
 * String Length
 */
class StringLengthNativeProperty extends NativeProperty {

    public function __invoke($self) {
        return new Number(strlen($self->getValue()));
    }

}

/**
 * String Methods
 */
$string = Entity::$standard->String->getEntityPrototype();
$string->length = new StringLengthNativeProperty;
