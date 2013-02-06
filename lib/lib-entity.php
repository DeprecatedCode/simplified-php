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
 * Entity Length
 */
class EntityLengthNativeProperty extends NativeProperty {

    public function __invoke($self) {
        return new Number(count($self->getPrivate()));
    }

}

/**
 * Entity Each
 */
class EntityEachNativeExpression extends NativeExpression {

    public function __invoke($self) {
        /* TODO: Iterate through all items.
         *       When reaching a range, iterate through the range.
         *       Key does not advance during range iteration, `it` does!
         */
    }

}

/**
 * Standard Methods
 */
Entity::$standard->each = new EntityEachNativeExpression;
# Entity::$standard->filter = new EntityFilterNativeExpression;  # .filter{it.name == "Nate"}
# Entity::$standard->get = new EntityGetNativeExpression;        # .get[1, 2]    .get("name")
Entity::$standard->length = new EntityLengthNativeProperty;
Entity::$standard->toString = new EntityStringRepresentationNativeProperty;
Entity::$standard->includedEntities = new IncludedEntitiesNativeProperty;
Entity::$standard->entityPrototype = new EntityPrototypeNativeProperty;
