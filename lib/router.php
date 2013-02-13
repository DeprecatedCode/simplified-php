<?php

S::$lib->Router = S::$lib->Entity;

/**
 * Router Constructor
 */
S::$lib->Router[S::CONSTRUCTOR] = function(&$context) {
    $context[S::TYPE] = S::$lib->Router[S::TYPE];
    return $context;
};
