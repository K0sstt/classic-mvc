<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

function debug($str) {
	echo '<pre>';
	var_dump($str);
	echo '</pre>';
	exit;
}

function debug_light($str) {
	echo '<pre>';
	var_dump($str);
	echo '</pre>';
}


function in_array_multi($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_multi($needle, $item, $strict))) {
            return true;
        }
    }

    return false;
}