<?php

/**
 * Test + List
 */
proto(TestType)->__apply_list__ = function($context) {
    return function($list) use($context) {
        $tests = array();
        foreach($list as $file) {
            ;
        }
        $req = S::construct('Request');
        $args = S::property($req, 'args');
        $id = S::property($args, '!--=sphp-test-id');
        $x = new stdClass;
        $x->tests = $tests;
        return S::construct('Test', $x);
    };
};