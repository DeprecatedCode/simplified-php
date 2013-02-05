<?php

/**
 * Simplified PHP Library: Router
 * @author Nate Ferrero
 */

/**
 * Route Native Expression
 */
class RouterRouteNativeExpression extends NativeExpression {

    public function __invoke($self, $entity) {
        /**
         * Handle Requests
         */
        $root = $_SERVER['DOCUMENT_ROOT'];
        if(substr($root, strlen($root) - 1) !== '/') {
            $root .= '/';
        }
        $path = explode('/', $_SERVER['REDIRECT_URL']);
        array_shift($path);
        while(count($path)) {
            $last = array_pop($path);
            $file = $root . implode('/', $path);
            $index = $file . '/index.sp';
            $file = $file . '.sp';
            if(file_exists($index)) {
                $self->File->import(new String($index));
                return;
            }
            else if(file_exists($file)) {
                $self->File->import(new String($file));
                return;
            }
        }

        $self->error(new Entity(array(
            'statusCode' => 404, 'message' => 'Not Found'
        )));
    }
}


/**
 * Handle Errors
 */
class RouterErrorNativeExpression extends NativeExpression {

    public function __invoke($self, $entity) {

        echo $entity->statusCode->getValue() . ' ' . $entity->message->getValue();
        exit;
    }

}

/**
 * Router Methods
 */
$router = Entity::$standard->Router->getEntityPrototype();
$router->route = new RouterRouteNativeExpression;
$router->error = new RouterErrorNativeExpression;
