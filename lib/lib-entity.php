<?php

/**
 * Simplified PHP Library: Entity
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
 * Entity Prototype
 */
class EntityLengthNativeProperty extends NativeProperty {

    public function __invoke($self) {
        return new Number(count($self->getPrivate()));
    }

}

/**
 * Standard Methods
 */
Entity::$standard->length = new EntityLengthNativeProperty;
Entity::$standard->toString = new EntityStringRepresentationNativeProperty;
Entity::$standard->includedEntities = new IncludedEntitiesNativeProperty;
Entity::$standard->entityPrototype = new EntityPrototypeNativeProperty;
