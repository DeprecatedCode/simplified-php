<?php

S::$prototype->Entity = new Entity;

/**
 * Length Property
 */
S::$prototype->Entity->length = function($context) {
    return count($context->scope);
};
