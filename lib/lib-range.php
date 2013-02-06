<?php

/**
 * Simplified PHP Library: Range
 * @author Nate Ferrero
 */

/**
 * Range Start
 */
class RangeStartNativeProperty extends NativeProperty {

    public function __invoke($self) {
        return new Number($self->getRangeStart());
    }

}

/**
 * Range End
 */
class RangeEndNativeProperty extends NativeProperty {

    public function __invoke($self) {
        return new Number($self->getRangeEnd());
    }

}

/**
 * Range Step
 */
class RangeStepNativeProperty extends NativeProperty {

    public function __invoke($self) {
        return new Number($self->getRangeStep());
    }

}

/**
 * Range Length
 */
class RangeLengthNativeProperty extends NativeProperty {

    public function __invoke($self) {
        return new Number($self->getRangeLength());
    }

}

/**
 * Range Methods
 */
$range = Entity::$standard->Range->getEntityPrototype();
$range->end = new RangeEndNativeProperty;
$range->step = new RangeStepNativeProperty;
$range->start = new RangeStartNativeProperty;
$range->length = new RangeLengthNativeProperty;
