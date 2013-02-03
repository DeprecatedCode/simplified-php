<?php

/**
 * Simple Notation :: PHP
 * @author Nate Ferrero
 */

/**
 * Entity
 */
class Entity {

    /**
     * Included entities
     */
    private $includes = array();

    /**
     * Local variables
     */
    private $private = array();

    /**
     * Parent Entity
     */
    private $parent = null;

    /**
     * Standard Includes
     */
    public static $standardIncludes = array();

    /**
     * Constructor
     */
    public function __construct($vars = null) {
        foreach(self::$standardIncludes as $other) {
            $this->includeEntity($other);
        }
        if(is_array($vars)) {
            foreach($vars as $var => $val) {
                if($val instanceof Entity) {
                    ;
                } else if(is_array($val)) {
                    $val = new Entity($val);
                } else if(is_string($val)) {
                    $val = new String($val);
                } else if(is_numeric($val)) {
                    $val = new Number($val);
                } else if($val === true) {
                    $val = new Entity;
                } else {
                    $val = new Void;
                }
                $this->$var = $val;
            }
        }
    }

    /**
     * You cannot add to shared variables
     */
    public function __set($var, $val) {
        $this->private[$var] = $val;
    }

    /**
     * Check variable existance
     */
    public function __isset($var) {
        if(isset($this->private[$var])) {
            return true;
        }
        foreach($this->includes as $other) {
            if(isset($other->$var)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Private variables override included variables when read
     */
    public function __get($var) {
        if(isset($this->private[$var])) {
            return $this->doReturn($this->private[$var]);
        }
        foreach($this->includes as $other) {
            if(isset($other->$var)) {
                return $this->doReturn($other->get($var));
            }
        }
        return new Void;
    }

    /**
     * Get without doReturn handling
     * Private variables override included variables when read
     */
    public function get($var) {
        if(isset($this->private[$var])) {
            return $this->private[$var];
        }
        foreach($this->includes as $other) {
            if(isset($other->$var)) {
                return $other->get($var);
            }
        }
        return new Void;
    }


    /**
     * Handle return values, especially properties
     */
    public function doReturn(&$val) {
        if($val instanceof Property) {
            return $val($this);
        }
        return $val;
    } 

    /**
     * Get the private variables
     */
    public function getPrivate() {
        return $this->private;
    }

    /**
     * Get the included entities
     */
    public function getIncludedEntities() {
        return $this->includes;
    }

    /**
     * Include an entity
     */
    public function includeEntity(Entity $entity) {
        array_unshift($this->includes, $entity);
    }
}
