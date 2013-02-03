<?php

/**
 * Simple Notation :: PHP
 * @author Nate Ferrero
 */

/**
 * Expression
 */
class Expression extends Entity {

}

/**
 * NativeExpression convenience method
 */
function native($object, $method, $entity = null) {
    $ref = $object->$method;
    if($ref instanceof NativeProperty) {
        return $ref($object);
    } else if($ref instanceof NativeExpression) {
        if($entity instanceof Entity) {
            return $ref->onEntity($object, $entity);
        } else {
            throw new Exception("No Entity provided to native expression");
        }
    } else {
        throw new Exception("Attempting native() on non-native expression $method");
    }
}

/**
 * NativeExpression
 */
class NativeExpression extends Expression {

    protected $args;

    /**
     * Actual Invocation must be defined in subclass.
     */
    public function __invoke() {
        throw new Exception('Invoke not defined on NativeExpression');
    }

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        if(method_exists($this, 'expressionArgs')) {
            $this->args = $this->expressionArgs();
        } else {
            $this->args = $this->Entity;
        }
    }

    /**
     * Get args
     */
    public function getArgs() {
        return $this->args;
    }

    /**
     * Invoke this NativeExpression
     */
    public function onEntity($self, $entity) {
        $args = array($self);
        foreach($this->args as $arg => $default) {
            $args[] = isset($entity->$arg) ? $entity->$arg : $default;
        }
        return call_user_func_array($this, $args);
    }
}
