<?php

/*
 * Written by Dan David
 * 
 * include file then initialize with
 * spl_autoload_register('load_classes')
 * 
 */

function load_classes($class_name) {
    $ClassFile = __DIR__ . '/classes/class.' . $class_name . '.php';
    include $ClassFile;
}

