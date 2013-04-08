<?php

/**
 * Simplified PHP
 * @author Nate Ferrero
 */

# Evaluate Buffer Contents
if(defined('Version')) {
    $system = construct(SystemType);
    property($system, 'evaluate');
    exit;
}

# Global Functions
require_once(__DIR__ . "/lib/system/global.php");

# Capture Code
ob_start();
