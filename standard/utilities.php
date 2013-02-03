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