<?php

$O = proto(SystemType)->operators;

/**
 * Property Operator
 */
$O->{'.'} = function($left, $right) {
    return property($left, $right);
};

/**
 * Range Operator
 */
$O->{'..'} = function($left, $right) {
    $range = construct(RangeType);
    if($right > $left) {
        $range->step = 1;
    } else {
        $range->step = -1;
    }
    $range->start = $left;
    $range->end = $right;
    return $range;
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
    
    if($left instanceof Closure) {
        $method = $left;
    } else {
        $method = property($left, '__apply__');
    }

    return $method($right);
};

/**
 * Exponent Operator
 */
$O->{'^'} = function($left, $right) {
    $idl = type($left);
    $idr = type($right);
    if($idl !== $idr) {
        throw new Exception("Cannot raise $idl ^ $idr");
    }
    switch($idl) {
        case 'Number':
            return pow($left, $right);
        default:
            throw new Exception("Cannot raise $idl ^ $idr");
    }
};

/**
 * Multiplication Operator
 */
$O->{'*'} = function($left, $right) {
    $idl = type($left);
    $idr = type($right);
    if($idl !== $idr) {
        throw new Exception("Cannot multiply $idl * $idr");
    }
    switch($idl) {
        case 'Number':
            return $left * $right;
        default:
            throw new Exception("Cannot multiply $idl * $idr");
    }
};

/**
 * Division Operator
 */
$O->{'/'} = function($left, $right) {
    $idl = type($left);
    $idr = type($right);
    if($idl !== $idr) {
        throw new Exception("Cannot divide $idl / $idr");
    }
    switch($idl) {
        case 'Number':
            return $left / $right;
        default:
            throw new Exception("Cannot divide $idl / $idr");
    }
};

/**
 * Addition Operator
 */
$O->{'+'} = function($left, $right) {
    $idl = type($left);
    $idr = type($right);
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
    if(is_null($left)) {
        $left = 0;
    }
    if(is_null($right)) {
        $right = 0;
    }
    $idl = type($left);
    $idr = type($right);
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

/**
 * Equality Operator
 */
$O->{'='} = function($left, $right) {
    return $left === $right;
};
