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
 * String Execute
 */
class StringExecuteNativeProperty extends NativeProperty {

    public function __invoke($self) {
        return Grammar::getCode($self->getValue())->execute;
    }

}

/**
 * String Replace Native Expression
 */
class StringReplaceNativeExpression extends NativeExpression {

    public function __invoke($self, $entity) {
        $values = $entity->getPrivate();
        $from = array();
        $to = array();
        foreach($values as $key => $value) {
            $from[] = $key;
            if($value instanceof String || $value instanceof Number) {
                $to[] = $value->getValue();
            } else {
                throw new Exception("Replacement for $key must be a String or Number");
            }
        }
        return new String(str_replace($from, $to, $self->getValue()));
    }

}

/**
 * String Methods
 */
$string = Entity::$standard->String->getEntityPrototype();
$string->length = new StringLengthNativeProperty;
$string->execute = new StringExecuteNativeProperty;
$string->replace = new StringReplaceNativeExpression;
