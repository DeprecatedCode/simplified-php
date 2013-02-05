<?php

/**
 * Simplified PHP Library: Code
 * @author Nate Ferrero
 */

/**
 * Code Execute
 */
class CodeExecuteNativeProperty extends NativeProperty {

    public function __invoke($self) {
        $tokens = $self->getValue();
        echo "<pre>";
        print_r($tokens);
        exit;
    }

}

/**
 * Code Methods
 */
$code = Entity::$standard->Code->getEntityPrototype();
$code->execute = new CodeExecuteNativeProperty;
