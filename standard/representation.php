<?php

/**
 * Simple Notation :: PHP
 * @author Nate Ferrero
 */

/**
 * Entity String Representation
 */
class EntityStringRepresentationNativeProperty extends NativeProperty {

    public function __invoke($self) {

        if($self instanceof NativeProperty) {
            return StringRepresentation::nativeProperty($self);
        }

        if($self instanceof NativeExpression) {
            return StringRepresentation::nativeExpression($self);
        }

        if($self instanceof Property) {
            return StringRepresentation::property($self);
        }

        if($self instanceof Expression) {
            return StringRepresentation::expression($self);
        }

        if($self instanceof Number) {
            return StringRepresentation::number($self);
        }

        if($self instanceof String) {
            return StringRepresentation::str($self);
        }

        if($self instanceof Void) {
            return StringRepresentation::void($self);
        }

        if($self instanceof Prototype) {

            $x = array();
            foreach($self->getPrivate() as $var => $val) {
                try {
                    $x[] = (is_string($var) ? "\"$var\": " : "$var: ") . $this($val)->getValue();
                } catch(Exception $e) {
                    throw new Exception("$var: " . $e->getMessage());
                }
            }

            return new String('[' . implode(', ', $x) . ']');
        }

        throw new Exception("Unknown Component");
    }

}

/**
 * Specific String Representation
 */
class StringRepresentation {

    public static function nativeProperty($prop) {
        return new String('{! #native }');
    }

    public static function nativeExpression($expr) {
        $args = $expr->getArgs()->toString;
        return new String('{ #native }' . $args->getValue());
    }

    public static function property($prop) {
        return new String('{!}');
    }

    public static function expression($expr) {
        return new String('{}');
    }

    public static function number($num) {
        $val = $num->getValue();
        return new String("$val");
    }

    public static function str($str) {
        $val = $str->getValue();
        return new String("\"$val\"");
    }

    public static function void($void) {
        return new String("Void");
    }

}