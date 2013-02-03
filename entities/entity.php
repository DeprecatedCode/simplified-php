<?php

/**
 * Simplified PHP
 * @author Nate Ferrero
 */

/**
 * Prototype
 */
class Prototype {

    /**
     * Local variables
     */
    protected $private = array();

    /**
     * Add to component private variables
     */
    public function __set($var, $val) {
        $this->private[$var] = $val;
    }

    /**
     * Check variable existance
     */
    public function __isset($var) {
        return isset($this->private[$var]);
    }

    /**
     * Get component variables
     */
    public function __get($var) {
        if(isset($this->private[$var])) {
            return $this->private[$var];
        }
        return new Void;
    }

    /**
     * Get the private variables
     */
    public function getPrivate() {
        return $this->private;
    }
}

/**
 * Entity
 */
class Entity extends Prototype {

    /**
     * Included entities
     */
    protected $includes = array();

    /**
     * Parent Entity
     */
    protected $parent = null;

    /**
     * Standard Includes
     */
    public static $standard = null;

    /**
     * Entity-specific Includes
     */
    public static $entityIncludes = array();

    /**
     * Constructor
     */
    public function __construct($vars = null) {
        /**
         * Include Standard Entity
         */
        if(!is_null(self::$standard)) {
            $this->includeEntity(self::$standard);
        }

        /**
         * Handle arguments
         */
        if(is_array($vars)) {
            foreach($vars as $var => $val) {
                if($val instanceof Prototype) {
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
        return $this->doReturn($this->get($var));
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
        return $this->getEntityPrototype()->$var;
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

    /**
     * Get a reference to an entity include component
     */
    public function getEntityPrototype() {
        $class = get_class($this);
        if(!isset(self::$entityIncludes[$class])) {
            self::$entityIncludes[$class] = new Prototype;
        }
        return self::$entityIncludes[$class];
    }
}
