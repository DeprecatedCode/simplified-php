<?php

/**
 * Simplified PHP Library: File
 * @author Nate Ferrero
 */

/**
 * File Import Native Expression
 */
class FileImportNativeExpression extends NativeExpression {

    public function __invoke($self, $entity) {
        if($entity instanceof String) {
            $str = new String(file_get_contents($entity->getValue()));
            print_r($str->execute);
            return $str->execute;
        } else {
            throw new Exception("Argument must be a String");
        }
    }

}

/**
 * File Read Native Expression
 */
class FileReadNativeExpression extends NativeExpression {

    public function __invoke($self, $entity) {
        if($entity instanceof String) {
            return new String(file_get_contents($entity->getValue()));
        } else {
            throw new Exception("Argument must be a String");
        }
    }

}

/**
 * File Write Native Expression
 */
class FileWriteNativeExpression extends NativeExpression {

    public function expressionArgs() {
        return new Entity(array(
            'path' => new Void,
            'data' => new String
        ));
    }

    public function __invoke($self, $entity) {
        if($entity->path instanceof String && $entity->data instanceof String) {
            file_put_contents($entity->path->getValue(), $entity->data->getValue());
            return new Void;
        } else {
            throw new Exception("Arguments path and data must each be a String");
        }
    }

}

/**
 * String Methods
 */
$file = Entity::$standard->File->getEntityPrototype();
$file->import = new FileImportNativeExpression;
$file->read = new FileReadNativeExpression;
$file->write = new FileWriteNativeExpression;
