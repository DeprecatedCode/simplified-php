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
    
    if(is_callable($left)) {
        $method = $left;
    } else {
        $method = property($left, '__apply__');
    }

    # Expand when reaching a list
    if(is_array($right)) {
        $out = array();
        foreach($right as $item) {
            if(is_object($item) && type($item) == RangeType) {
                for($i=$item->start; $i <= $item->end; $i++) {
                    $out[] = $method($i);
                }
                continue;
            }
            $out[] = $method($item);
        }
        return $out;
    }
    return $method($right);
};

/**
 * Multiplication Operator
 */
$O->{'*'} = function($left, $right) {
    $idl = type($left);
    $idr = type($right);
    if($idl !== $idr) {
        throw new Exception("Cannot multiply $idl + $idr");
    }
    switch($idl) {
        case 'Number':
            return $left * $right;
        default:
            throw new Exception("Cannot multiply $idl + $idr");
    }
};

/**
 * Division Operator
 */
$O->{'/'} = function($left, $right) {
    $idl = type($left);
    $idr = type($right);
    if($idl !== $idr) {
        throw new Exception("Cannot divide $idl + $idr");
    }
    switch($idl) {
        case 'Number':
            return $left / $right;
        default:
            throw new Exception("Cannot divide $idl + $idr");
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
