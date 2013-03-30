<?php

$O = S::$lib->System->operators;

/**
 * Property Operator
 */
$O->{'.'} = function($left, $right) {
    return S::property($left, $right);
};

/**
 * No-op Operator
 */
$O->{'@'} = function($left, $right) {
    if($left === null) {
        return $right;
    } else if($right === null) {
        return null;
    }
    
    $method = "__apply_" . strtolower(S::type($right)) . "__";
    $method = S::property($left, $method);
    return $method($right);
};

/**
 * Addition Operator
 */
$O->{'+'} = function($left, $right) {
    $idl = S::type($left);
    $idr = S::type($right);
    if($idl !== $idr) {
        throw new Exception("Cannot add $idl + $idr");
    }
    switch($idl) {
        case 'Entity':
            $x = new stdClass;
            foreach($left as $key => $value) {
                $x->$key = $value;
            }
            foreach($right as $key => $value) {
                $x->$key = $value;
            }
            return $x;
        case 'String':
            return $left . $right;
        case 'Number':
            return $left + $right;
        default:
            throw new Exception("Cannot add $idl + $idr");
    }
};

/**
 * Subtraction Operator
 */
$O->{'-'} = function($left, $right) {
    $idl = S::type($left);
    $idr = S::type($right);
    if($idl !== $idr) {
        throw new Exception("Cannot subtract $idl - $idr");
    }
    switch($idl) {
        case 'Entity':
            $x = new stdClass;
            foreach($left as $key => $value) {
                if(!isset($right->$key)) {
                    $x->$key = $value;
                }
            }
            return $x;
        case 'String':
            return str_replace($right, '', $left);
        case 'Number':
            return $left - $right;
        default:
            throw new Exception("Cannot subtract $idl - $idr");
    }
};
