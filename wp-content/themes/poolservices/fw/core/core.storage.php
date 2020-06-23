<?php
/**
 * PoolServices Framework: theme variables storage
 *
 * @package	poolservices
 * @since	poolservices 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get theme variable
if (!function_exists('poolservices_storage_get')) {
	function poolservices_storage_get($var_name, $default='') {
		global $POOLSERVICES_STORAGE;
		return isset($POOLSERVICES_STORAGE[$var_name]) ? $POOLSERVICES_STORAGE[$var_name] : $default;
	}
}

// Set theme variable
if (!function_exists('poolservices_storage_set')) {
	function poolservices_storage_set($var_name, $value) {
		global $POOLSERVICES_STORAGE;
		$POOLSERVICES_STORAGE[$var_name] = $value;
	}
}

// Check if theme variable is empty
if (!function_exists('poolservices_storage_empty')) {
	function poolservices_storage_empty($var_name, $key='', $key2='') {
		global $POOLSERVICES_STORAGE;
		if (!empty($key) && !empty($key2))
			return empty($POOLSERVICES_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return empty($POOLSERVICES_STORAGE[$var_name][$key]);
		else
			return empty($POOLSERVICES_STORAGE[$var_name]);
	}
}

// Check if theme variable is set
if (!function_exists('poolservices_storage_isset')) {
	function poolservices_storage_isset($var_name, $key='', $key2='') {
		global $POOLSERVICES_STORAGE;
		if (!empty($key) && !empty($key2))
			return isset($POOLSERVICES_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return isset($POOLSERVICES_STORAGE[$var_name][$key]);
		else
			return isset($POOLSERVICES_STORAGE[$var_name]);
	}
}

// Inc/Dec theme variable with specified value
if (!function_exists('poolservices_storage_inc')) {
	function poolservices_storage_inc($var_name, $value=1) {
		global $POOLSERVICES_STORAGE;
		if (empty($POOLSERVICES_STORAGE[$var_name])) $POOLSERVICES_STORAGE[$var_name] = 0;
		$POOLSERVICES_STORAGE[$var_name] += $value;
	}
}

// Concatenate theme variable with specified value
if (!function_exists('poolservices_storage_concat')) {
	function poolservices_storage_concat($var_name, $value) {
		global $POOLSERVICES_STORAGE;
		if (empty($POOLSERVICES_STORAGE[$var_name])) $POOLSERVICES_STORAGE[$var_name] = '';
		$POOLSERVICES_STORAGE[$var_name] .= $value;
	}
}

// Get array (one or two dim) element
if (!function_exists('poolservices_storage_get_array')) {
	function poolservices_storage_get_array($var_name, $key, $key2='', $default='') {
		global $POOLSERVICES_STORAGE;
		if (empty($key2))
			return !empty($var_name) && !empty($key) && isset($POOLSERVICES_STORAGE[$var_name][$key]) ? $POOLSERVICES_STORAGE[$var_name][$key] : $default;
		else
			return !empty($var_name) && !empty($key) && isset($POOLSERVICES_STORAGE[$var_name][$key][$key2]) ? $POOLSERVICES_STORAGE[$var_name][$key][$key2] : $default;
	}
}

// Set array element
if (!function_exists('poolservices_storage_set_array')) {
	function poolservices_storage_set_array($var_name, $key, $value) {
		global $POOLSERVICES_STORAGE;
		if (!isset($POOLSERVICES_STORAGE[$var_name])) $POOLSERVICES_STORAGE[$var_name] = array();
		if ($key==='')
			$POOLSERVICES_STORAGE[$var_name][] = $value;
		else
			$POOLSERVICES_STORAGE[$var_name][$key] = $value;
	}
}

// Set two-dim array element
if (!function_exists('poolservices_storage_set_array2')) {
	function poolservices_storage_set_array2($var_name, $key, $key2, $value) {
		global $POOLSERVICES_STORAGE;
		if (!isset($POOLSERVICES_STORAGE[$var_name])) $POOLSERVICES_STORAGE[$var_name] = array();
		if (!isset($POOLSERVICES_STORAGE[$var_name][$key])) $POOLSERVICES_STORAGE[$var_name][$key] = array();
		if ($key2==='')
			$POOLSERVICES_STORAGE[$var_name][$key][] = $value;
		else
			$POOLSERVICES_STORAGE[$var_name][$key][$key2] = $value;
	}
}

// Add array element after the key
if (!function_exists('poolservices_storage_set_array_after')) {
	function poolservices_storage_set_array_after($var_name, $after, $key, $value='') {
		global $POOLSERVICES_STORAGE;
		if (!isset($POOLSERVICES_STORAGE[$var_name])) $POOLSERVICES_STORAGE[$var_name] = array();
		if (is_array($key))
			poolservices_array_insert_after($POOLSERVICES_STORAGE[$var_name], $after, $key);
		else
			poolservices_array_insert_after($POOLSERVICES_STORAGE[$var_name], $after, array($key=>$value));
	}
}

// Add array element before the key
if (!function_exists('poolservices_storage_set_array_before')) {
	function poolservices_storage_set_array_before($var_name, $before, $key, $value='') {
		global $POOLSERVICES_STORAGE;
		if (!isset($POOLSERVICES_STORAGE[$var_name])) $POOLSERVICES_STORAGE[$var_name] = array();
		if (is_array($key))
			poolservices_array_insert_before($POOLSERVICES_STORAGE[$var_name], $before, $key);
		else
			poolservices_array_insert_before($POOLSERVICES_STORAGE[$var_name], $before, array($key=>$value));
	}
}

// Push element into array
if (!function_exists('poolservices_storage_push_array')) {
	function poolservices_storage_push_array($var_name, $key, $value) {
		global $POOLSERVICES_STORAGE;
		if (!isset($POOLSERVICES_STORAGE[$var_name])) $POOLSERVICES_STORAGE[$var_name] = array();
		if ($key==='')
			array_push($POOLSERVICES_STORAGE[$var_name], $value);
		else {
			if (!isset($POOLSERVICES_STORAGE[$var_name][$key])) $POOLSERVICES_STORAGE[$var_name][$key] = array();
			array_push($POOLSERVICES_STORAGE[$var_name][$key], $value);
		}
	}
}

// Pop element from array
if (!function_exists('poolservices_storage_pop_array')) {
	function poolservices_storage_pop_array($var_name, $key='', $defa='') {
		global $POOLSERVICES_STORAGE;
		$rez = $defa;
		if ($key==='') {
			if (isset($POOLSERVICES_STORAGE[$var_name]) && is_array($POOLSERVICES_STORAGE[$var_name]) && count($POOLSERVICES_STORAGE[$var_name]) > 0) 
				$rez = array_pop($POOLSERVICES_STORAGE[$var_name]);
		} else {
			if (isset($POOLSERVICES_STORAGE[$var_name][$key]) && is_array($POOLSERVICES_STORAGE[$var_name][$key]) && count($POOLSERVICES_STORAGE[$var_name][$key]) > 0) 
				$rez = array_pop($POOLSERVICES_STORAGE[$var_name][$key]);
		}
		return $rez;
	}
}

// Inc/Dec array element with specified value
if (!function_exists('poolservices_storage_inc_array')) {
	function poolservices_storage_inc_array($var_name, $key, $value=1) {
		global $POOLSERVICES_STORAGE;
		if (!isset($POOLSERVICES_STORAGE[$var_name])) $POOLSERVICES_STORAGE[$var_name] = array();
		if (empty($POOLSERVICES_STORAGE[$var_name][$key])) $POOLSERVICES_STORAGE[$var_name][$key] = 0;
		$POOLSERVICES_STORAGE[$var_name][$key] += $value;
	}
}

// Concatenate array element with specified value
if (!function_exists('poolservices_storage_concat_array')) {
	function poolservices_storage_concat_array($var_name, $key, $value) {
		global $POOLSERVICES_STORAGE;
		if (!isset($POOLSERVICES_STORAGE[$var_name])) $POOLSERVICES_STORAGE[$var_name] = array();
		if (empty($POOLSERVICES_STORAGE[$var_name][$key])) $POOLSERVICES_STORAGE[$var_name][$key] = '';
		$POOLSERVICES_STORAGE[$var_name][$key] .= $value;
	}
}

// Call object's method
if (!function_exists('poolservices_storage_call_obj_method')) {
	function poolservices_storage_call_obj_method($var_name, $method, $param=null) {
		global $POOLSERVICES_STORAGE;
		if ($param===null)
			return !empty($var_name) && !empty($method) && isset($POOLSERVICES_STORAGE[$var_name]) ? $POOLSERVICES_STORAGE[$var_name]->$method(): '';
		else
			return !empty($var_name) && !empty($method) && isset($POOLSERVICES_STORAGE[$var_name]) ? $POOLSERVICES_STORAGE[$var_name]->$method($param): '';
	}
}

// Get object's property
if (!function_exists('poolservices_storage_get_obj_property')) {
	function poolservices_storage_get_obj_property($var_name, $prop, $default='') {
		global $POOLSERVICES_STORAGE;
		return !empty($var_name) && !empty($prop) && isset($POOLSERVICES_STORAGE[$var_name]->$prop) ? $POOLSERVICES_STORAGE[$var_name]->$prop : $default;
	}
}
?>