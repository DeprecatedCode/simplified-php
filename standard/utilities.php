<?php

/**
 * Simple Notation :: PHP
 * @author Nate Ferrero
 */

/**
 * Included Entities
 */
class IncludedEntitiesNativeProperty extends NativeProperty {

    public function __invoke($self) {
        return new Entity($self->getIncludedEntities());
    }

}

/**
 * Entity Prototype
 */
class EntityPrototypeNativeProperty extends NativeProperty {

    public function __invoke($self) {
        return $self->getEntityPrototype();
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