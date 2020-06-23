<?php
/**
 * PoolServices Framework: strings manipulations
 *
 * @package	poolservices
 * @since	poolservices 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Check multibyte functions
if ( ! defined( 'POOLSERVICES_MULTIBYTE' ) ) define( 'POOLSERVICES_MULTIBYTE', function_exists('mb_strpos') ? 'UTF-8' : false );

if (!function_exists('poolservices_strlen')) {
	function poolservices_strlen($text) {
		return POOLSERVICES_MULTIBYTE ? mb_strlen($text) : strlen($text);
	}
}

if (!function_exists('poolservices_strpos')) {
	function poolservices_strpos($text, $char, $from=0) {
		return POOLSERVICES_MULTIBYTE ? mb_strpos($text, $char, $from) : strpos($text, $char, $from);
	}
}

if (!function_exists('poolservices_strrpos')) {
	function poolservices_strrpos($text, $char, $from=0) {
		return POOLSERVICES_MULTIBYTE ? mb_strrpos($text, $char, $from) : strrpos($text, $char, $from);
	}
}

if (!function_exists('poolservices_substr')) {
	function poolservices_substr($text, $from, $len=-999999) {
		if ($len==-999999) { 
			if ($from < 0)
				$len = -$from; 
			else
				$len = poolservices_strlen($text)-$from;
		}
		return POOLSERVICES_MULTIBYTE ? mb_substr($text, $from, $len) : substr($text, $from, $len);
	}
}

if (!function_exists('poolservices_strtolower')) {
	function poolservices_strtolower($text) {
		return POOLSERVICES_MULTIBYTE ? mb_strtolower($text) : strtolower($text);
	}
}

if (!function_exists('poolservices_strtoupper')) {
	function poolservices_strtoupper($text) {
		return POOLSERVICES_MULTIBYTE ? mb_strtoupper($text) : strtoupper($text);
	}
}

if (!function_exists('poolservices_strtoproper')) {
	function poolservices_strtoproper($text) { 
		$rez = ''; $last = ' ';
		for ($i=0; $i<poolservices_strlen($text); $i++) {
			$ch = poolservices_substr($text, $i, 1);
			$rez .= poolservices_strpos(' .,:;?!()[]{}+=', $last)!==false ? poolservices_strtoupper($ch) : poolservices_strtolower($ch);
			$last = $ch;
		}
		return $rez;
	}
}

if (!function_exists('poolservices_strrepeat')) {
	function poolservices_strrepeat($str, $n) {
		$rez = '';
		for ($i=0; $i<$n; $i++)
			$rez .= $str;
		return $rez;
	}
}

if (!function_exists('poolservices_strshort')) {
	function poolservices_strshort($str, $maxlength, $add='...') {
		if ($maxlength < 0) 
			return $str;
		if ($maxlength == 0) 
			return '';
		if ($maxlength >= poolservices_strlen($str)) 
			return strip_tags($str);
		$str = poolservices_substr(strip_tags($str), 0, $maxlength - poolservices_strlen($add));
		$ch = poolservices_substr($str, $maxlength - poolservices_strlen($add), 1);
		if ($ch != ' ') {
			for ($i = poolservices_strlen($str) - 1; $i > 0; $i--)
				if (poolservices_substr($str, $i, 1) == ' ') break;
			$str = trim(poolservices_substr($str, 0, $i));
		}
		if (!empty($str) && poolservices_strpos(',.:;-', poolservices_substr($str, -1))!==false) $str = poolservices_substr($str, 0, -1);
		return ($str) . ($add);
	}
}

// Clear string from spaces, line breaks and tags (only around text)
if (!function_exists('poolservices_strclear')) {
	function poolservices_strclear($text, $tags=array()) {
		if (empty($text)) return $text;
		if (!is_array($tags)) {
			if ($tags != '')
				$tags = explode($tags, ',');
			else
				$tags = array();
		}
		$text = trim(chop($text));
		if (is_array($tags) && count($tags) > 0) {
			foreach ($tags as $tag) {
				$open  = '<'.esc_attr($tag);
				$close = '</'.esc_attr($tag).'>';
				if (poolservices_substr($text, 0, poolservices_strlen($open))==$open) {
					$pos = poolservices_strpos($text, '>');
					if ($pos!==false) $text = poolservices_substr($text, $pos+1);
				}
				if (poolservices_substr($text, -poolservices_strlen($close))==$close) $text = poolservices_substr($text, 0, poolservices_strlen($text) - poolservices_strlen($close));
				$text = trim(chop($text));
			}
		}
		return $text;
	}
}

// Return slug for the any title string
if (!function_exists('poolservices_get_slug')) {
	function poolservices_get_slug($title) {
		return poolservices_strtolower(str_replace(array('\\','/','-',' ','.'), '_', $title));
	}
}

// Replace macros in the string
if (!function_exists('poolservices_strmacros')) {
	function poolservices_strmacros($str) {
		return str_replace(array("{{", "}}", "((", "))", "||"), array("<i>", "</i>", "<b>", "</b>", "<br>"), $str);
	}
}

// Unserialize string (try replace \n with \r\n)
if (!function_exists('poolservices_unserialize')) {
	function poolservices_unserialize($str) {
		if ( is_serialized($str) ) {
			try {
				$data = unserialize($str);
			} catch (Exception $e) {
				dcl($e->getMessage());
				$data = false;
			}
			if ($data===false) {
				try {
					$data = @unserialize(str_replace("\n", "\r\n", $str));
				} catch (Exception $e) {
					dcl($e->getMessage());
					$data = false;
				}
			}
			return $data;
		} else
			return $str;
	}
}
?>