<?php

/**
 * Класс-хелпер для всякой всячины добавочный
 * Use variants:
	MHelper::get()->Add->fn_echo($value);
	OR
	MHelper::get('Add')->fn_echo($value);
	OR
	MHelper::Add()->fn_echo($value);	// PHP 5.3 only
 */
class MAdd extends MHelperBase
{
	
	/**
	 * Prints any data like a print_r function
	 * @param mixed ... Any data to be printed
	 */
	public function fn_print_r()
	{
	    static $count = 0;
	    $args = func_get_args();

	    if (isset($_SERVER['argv'])) { // console
	        $prefix = "\n";
	        $suffix = "\n\n";
	    } else {
	        $prefix = '<ol style="font-family: Courier; font-size: 12px; border: 1px solid #dedede; background-color: #efefef; float: left; padding-right: 20px;">';
	        $suffix = '</ol><div style="clear:left;"></div>';
	    }

	    if (!empty($args)) {
	        $this->fn_echo($prefix);
	        foreach ($args as $k => $v) {

	            if (isset($_SERVER['argv'])) { // console
	                $this->fn_echo(print_r($v, true));
	            } else {
	                $this->fn_echo('<li><pre>' . htmlspecialchars(print_r($v, true)) . "\n" . '</pre></li>');
	            }
	        }
	        $this->fn_echo($suffix);

	      /* 
	      doit
	       if (Yii::app()->request->isAjaxRequest) {
	            $ajax_vars = Registry::get('ajax')->getAssignedVars();
	            if (!empty($ajax_vars['debug_info'])) {
	                $args = array_merge($ajax_vars['debug_info'], $args);
	            }
	            Registry::get('ajax')->assign('debug_info', $args);
	        }*/
	    }
	    $count++;
	} 
	public function fn_flush()
	{
	   /* 
		doit
	   if (Yii::app()->request->isAjaxRequest && !Registry::get('runtime.comet')) { // do not flush output during ajax request, but flush it for COMET

	        return false;
	    }*/

	    if (function_exists('ob_flush')) {
	        @ob_flush();
	    }

	    flush();
	} 
	public function fn_echo($value)
	{
	    if (isset($_SERVER['argv'])) { // console
	        $value = str_replace(array('<br>', '<br />'), "\n", $value);
	        $value = strip_tags($value);
	    }

	    echo $value;

	    $this->fn_flush();
	}
	/**
	 * Functions check if subarray with child exists in the given array
	 *
	 * @param array $data Array with nodes
	 * @param string $childs_name Name of array with child nodes
	 * @return boolean true if there are child subarray, false otherwise.
	 */
	public function fn_check_second_level_child_array($data, $childs_name)
	{
	    foreach ($data as $l2) {
	        if (!empty($l2[$childs_name]) && is_array($l2[$childs_name]) && count($l2[$childs_name])) {
	            return true;
	        }
	    }

	    return false;
	} 
	//
	// This function splits the array into defined number of columns to
	// show it in the frontend
	// Params:
	// $data - the array that should be splitted
	// $size - number of columns/rows to split into
	// Example:
	// array (a, b, c, d, e, f, g, h, i, j, k);
	// fn_split($array, 3);
	// Result:
	// 0 -> a, b, c, d
	// 1 -> e, f, g, h
	// 2 -> i, j, k
	// ---------------------
	// fn_split($array, 3, true)
	// Result:
	//

	public function fn_split($data, $size, $vertical_delimition = false, $size_is_horizontal = true)
	{

	    if ($vertical_delimition == false) {
	        return array_chunk($data, $size);
	    } else {

	        $chunk_count = ($size_is_horizontal == true) ? ceil(count($data) / $size) : $size;
	        $chunk_index = 0;
	        $chunks = array();
	        foreach ($data as $key => $value) {
	            $chunks[$chunk_index][] = $value;
	            if (++$chunk_index == $chunk_count) {
	                $chunk_index = 0;
	            }
	        }

	        return $chunks;
	    }
	}
	/**
	 * Merge several arrays preserving keys (recursivelly!) or not preserving
	 *
	 * @param array ... unlimited number of arrays to merge
	 * @param bool ... if true, the array keys are preserved
	 * @return array merged data
	 */
	public function fn_array_merge()
	{
	    $arg_list = func_get_args();
	    $preserve_keys = true;
	    $result = array();
	    if (is_bool(end($arg_list))) {
	        $preserve_keys = array_pop($arg_list);
	    }

	    foreach ((array) $arg_list as $arg) {
	        foreach ((array) $arg as $k => $v) {
	            if ($preserve_keys == true) {
	                $result[$k] = !empty($result[$k]) && is_array($result[$k]) ? fn_array_merge($result[$k], $v) : $v;
	            } else {
	                $result[] = $v;
	            }
	        }
	    }

	    return $result;
	}
	/*
	$tree = array();
    $tree[] = array('id'=>1, 'parent_id' => null, 'name'=>'a');
    $tree[] = array('id'=>2, 'parent_id' => null, 'name'=>'b');
    $tree[] = array('id'=>3, 'parent_id' => 2, 'name'=>'c');
    $tree[] = array('id'=>4, 'parent_id' => 2, 'name'=>'d');
    MHelper::Add()->fn_print_r(MHelper::Add()->fn_make_tree($tree, 2, 'id', 0));
   */
	public function fn_make_tree($tree, $parent_id, $key, $parent_key)
	{
	    $res = array();

	    foreach ($tree as $id => $row) {

	        if ($row['parent_id'] == $parent_id) {
	            $res[$id] = $row;
	            $res[$id][$parent_key] = $this->fn_make_tree($tree, $row[$key], $key, $parent_key);
	        }
	    }

	    return $res;
	}
	/**
	 * Convert multi-level array with "subitems" to plain representation
	 *
	 * @param array $data source array
	 * @param string $key key with subitems
	 * @param array $result resulting array, passed along multi levels
	 * @return array structured data
	 */
	public function fn_multi_level_to_plain($data, $key, $result = array())
	{
	    foreach ($data as $k => $v) {
	        if (!empty($v[$key])) {
	            unset($v[$key]);
	            array_push($result, $v);
	            $result = $this->fn_multi_level_to_plain($data[$k][$key], $key, $result);
	        } else {
	            array_push($result, $v);
	        }
	    }

	    return $result;
	}

	public function fn_fields_from_multi_level($data, $id_key, $val_key, $result = array())
	{
	    foreach ($data as $k => $v) {
	        if (!empty($v[$id_key]) && !empty($v[$val_key])) {
	            $result[$v[$id_key]] = $v[$val_key];
	        }
	    }

	    return $result;
	}
	public function fn_js_escape($str)
	{
	    return strtr($str, array('\\' => '\\\\',  "'" => "\\'", '"' => '\\"', "\r" => '\\r', "\n" => '\\n', "\t" => '\\t', '</' => '<\/', "/" => '\\/'));
	}

	public function fn_object_to_array($object)
	{
	    if (!is_object($object) && !is_array($object)) {
	        return $object;
	    }
	    if (is_object($object)) {
	        $object = get_object_vars($object);
	    }

	    return array_map('fn_object_to_array', $object);
	}

	public function fn_parse_date($timestamp, $end_time = false)
	{
	    if (!empty($timestamp)) {
	        if (is_numeric($timestamp)) {
	            return $timestamp;
	        }

	        $ts = explode('/', $timestamp);
	        $ts = array_map('intval', $ts);
	        if (empty($ts[2])) {
	            $ts[2] = date('Y');
	        }
	        if (count($ts) == 3) {
	            list($h, $m, $s) = $end_time ? array(23, 59, 59) : array(0, 0, 0);
	           /* if (Registry::get('settings.Appearance.calendar_date_format') == 'month_first') {
	                $timestamp = mktime($h, $m, $s, $ts[0], $ts[1], $ts[2]);
	            } else {
	                $timestamp = mktime($h, $m, $s, $ts[1], $ts[0], $ts[2]);
	            }*/
	            $timestamp = mktime($h, $m, $s, $ts[1], $ts[0], $ts[2]);
	        } else {
	            $timestamp = TIME;
	        }
	    }

	    return !empty($timestamp) ? $timestamp : TIME;
	}
	//
	// The function returns Host IP and Proxy IP.
	//
	public function fn_get_ip($return_int = false)
	{
	    $forwarded_ip = '';
	    $fields = array(
	        'HTTP_X_FORWARDED_FOR',
	        'HTTP_X_FORWARDED',
	        'HTTP_FORWARDED_FOR',
	        'HTTP_FORWARDED',
	        'HTTP_forwarded_ip',
	        'HTTP_X_COMING_FROM',
	        'HTTP_COMING_FROM',
	        'HTTP_CLIENT_IP',
	        'HTTP_VIA',
	        'HTTP_XROXY_CONNECTION',
	        'HTTP_PROXY_CONNECTION');

	    $matches = array();
	    foreach ($fields as $f) {
	        if (!empty($_SERVER[$f])) {
	            preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/", $_SERVER[$f], $matches);
	            if (!empty($matches) && !empty($matches[0]) && $matches[0] != $_SERVER['REMOTE_ADDR']) {
	                $forwarded_ip = $matches[0];
	                break;
	            }
	        }
	    }

	    $ip = array('host' => $forwarded_ip, 'proxy' => $_SERVER['REMOTE_ADDR']);

	    if ($return_int) {
	        foreach ($ip as $k => $_ip) {
	            $ip[$k] = empty($_ip) ? 0 : sprintf("%u", ip2long($_ip));
	        }
	    }

	    if (empty($ip['host']) || !fn_is_inet_ip($ip['host'], $return_int)) {
	        $ip['host'] = $ip['proxy'];
	        $ip['proxy'] = $return_int ? 0 : '';
	    }

	    return $ip;
	}
	//
	// If there is IP address in address scope global then return true.
	//
	public function fn_is_inet_ip($ip, $is_int = false)
	{
	    if ($is_int) {
	        $ip = long2ip($ip);
	    }
	    $_ip = explode('.', $ip);

	    return
	        ($_ip[0] == 10 ||
	        ($_ip[0] == 172 && $_ip[1] >= 16 && $_ip[1] <= 31) ||
	        ($_ip[0] == 192 && $_ip[1] == 168) ||
	        ($_ip[0] == 127 && $_ip[1] == 0 && $_ip[2] == 0 && $_ip[3] == 1) ||
	        ($_ip[0] == 255 && $_ip[1] == 255 && $_ip[2] == 255 && $_ip[3] == 255))
	        ? false : true;
	} 
	//
	// Converts unicode encoded strings like %u0414%u0430%u043D to correct utf8 representation.
	//
	public function fn_unicode_to_utf8($str)
	{
	    preg_match_all("/(%u[0-9A-F]{4})/", $str, $subs);
	    $utf8 = array();
	    if (!empty($subs[1])) {
	        foreach ($subs[1] as $unicode) {
	            $_unicode = hexdec(substr($unicode, 2, 4));
	            if ($_unicode < 128) {
	                $_utf8 = chr($_unicode);
	            } elseif ($_unicode < 2048) {
	                $_utf8 = chr(192 +  (($_unicode - ($_unicode % 64)) / 64));
	                $_utf8 .= chr(128 + ($_unicode % 64));
	            } else {
	                $_utf8 = chr(224 + (($_unicode - ($_unicode % 4096)) / 4096));
	                $_utf8 .= chr(128 + ((($_unicode % 4096) - ($_unicode % 64)) / 64));
	                $_utf8 .= chr(128 + ($_unicode % 64));
	            }
	            $utf8[$unicode] = $_utf8;
	        }
	    }
	    if (!empty($utf8)) {
	        foreach ($utf8 as $unicode => $_utf8) {
	            $str = str_replace($unicode, $_utf8, $str);
	        }
	    }

	    return $str;
	}
	// Compacts the text through truncating middle chars and replacing them by dots
	public function fn_compact_value($value, $max_width)
	{
	    $escaped = false;
	    $length = strlen($value);

	    $new_value = $value = $this->fn_html_escape($value, true);
	    if (strlen($new_value) != $length) {
	        $escaped = true;
	    }

	    if ($length > $max_width) {
	        $len_to_strip = $length - $max_width;
	        $center_pos = $length / 2;
	        $new_value = substr($value, 0, $center_pos - ($len_to_strip / 2)) . '...' . substr($value, $center_pos + ($len_to_strip / 2));
	    }

	    return ($escaped == true) ? $this->fn_html_escape($new_value) : $new_value;
	}
	/**
	 * Add/remove html special chars
	 *
	 * @param mixed $data data to filter
	 * @param bool $revert if true, decode special chars
	 * @return mixed filtered variable
	 */
	public function fn_html_escape($data, $revert = false)
	{
	    if (is_array($data)) {
	        foreach ($data as $k => $sub) {
	            if (is_string($k)) {
	                $_k = ($revert == false) ? htmlspecialchars($k, ENT_QUOTES, 'UTF-8') : htmlspecialchars_decode($k, ENT_QUOTES);
	                if ($k != $_k) {
	                    unset($data[$k]);
	                }
	            } else {
	                $_k = $k;
	            }
	            if (is_array($sub) === true) {
	                $data[$_k] = $this->fn_html_escape($sub, $revert);
	            } elseif (is_string($sub)) {
	                $data[$_k] = ($revert == false) ? htmlspecialchars($sub, ENT_QUOTES, 'UTF-8') : htmlspecialchars_decode($sub, ENT_QUOTES);
	            }
	        }
	    } else {
	        $data = ($revert == false) ? htmlspecialchars($data, ENT_QUOTES, 'UTF-8') : htmlspecialchars_decode($data, ENT_QUOTES);
	    }

	    return $data;
	}
	public function fn_truncate_chars($text, $limit, $ellipsis = '...')
	{
	    if (strlen($text) > $limit) {
	        $pos_end = strpos(str_replace(array("\r\n", "\r", "\n", "\t"), ' ', $text), ' ', $limit);

	        if($pos_end !== false)
	            $text = trim(substr($text, 0, $pos_end)) . $ellipsis;
	    }

	    return $text;
	} 
	/**
	 * Calculate unsigned crc32 sum
	 *
	 * @param string $key - key to calculate sum for
	 * @return int - crc32 sum
	 */
	public function fn_crc32($key)
	{
	    return sprintf('%u', crc32($key));
	}
	/**
	 * Check whether string is UTF-8 encoded
	 *
	 * @param string $str
	 * @return boolean
	 */
	public function fn_is_utf8($str)
	{
	    $c = 0; $b = 0;
	    $bits = 0;
	    $len = strlen($str);
	    for ($i = 0; $i < $len; $i++) {
	        $c = ord($str[$i]);
	        if ($c > 128) {
	            if (($c >= 254)) {
	                return false;
	            } elseif ($c >= 252) {
	                $bits = 6;
	            } elseif ($c >= 248) {
	                $bits = 5;
	            } elseif ($c >= 240) {
	                $bits = 4;
	            } elseif ($c >= 224) {
	                $bits = 3;
	            } elseif ($c >= 192) {
	                $bits = 2;
	            } else {
	                return false;
	            }

	            if (($i + $bits) > $len) {
	                return false;
	            }

	            while ($bits > 1) {
	                $i++;
	                $b = ord($str[$i]);
	                if ($b < 128 || $b > 191) {
	                    return false;
	                }
	                $bits--;
	            }
	        }
	    }

	    return true;
	}
	/**
	 * Detect the cyrillic encoding of string
	 *
	 * @param string $str
	 * @return string cyrillic encoding
	 */
	public function fn_detect_cyrillic_charset($str)
	{
	    fn_define('LOWERCASE', 3);
	    fn_define('UPPERCASE', 1);

	    $charsets = array(
	        'KOI8-R' => 0,
	        'CP1251' => 0,
	        'CP866' => 0,
	        'ISO-8859-5' => 0,
	        'MAC-CYRILLIC' => 0
	    );

	    for ($i = 0, $length = strlen($str); $i < $length; $i++) {
	        $char = ord($str[$i]);
	        //non-russian characters
	        if ($char < 128 || $char > 256) {
	            continue;
	        }

	        //CP866
	        if (($char > 159 && $char < 176) || ($char > 223 && $char < 242)) {
	            $charsets['CP866'] += LOWERCASE;
	        }

	        if (($char > 127 && $char < 160)) {
	            $charsets['CP866'] += UPPERCASE;
	        }

	        //KOI8-R
	        if (($char > 191 && $char < 223)) {
	            $charsets['KOI8-R'] += LOWERCASE;
	        }
	        if (($char > 222 && $char < 256)) {
	            $charsets['KOI8-R'] += UPPERCASE;
	        }

	        //CP1251
	        if ($char > 223 && $char < 256) {
	            $charsets['CP1251'] += LOWERCASE;
	        }
	        if ($char > 191 && $char < 224) {
	            $charsets['CP1251'] += UPPERCASE;
	        }

	        //MAC-CYRILLIC
	        if ($char > 221 && $char < 255) {
	            $charsets['MAC-CYRILLIC'] += LOWERCASE;
	        }
	        if ($char > 127 && $char < 160) {
	            $charsets['MAC-CYRILLIC'] += UPPERCASE;
	        }

	        //ISO-8859-5
	        if ($char > 207 && $char < 240) {
	            $charsets['ISO-8859-5'] += LOWERCASE;
	        }
	        if ($char > 175 && $char < 208) {
	            $charsets['ISO-8859-5'] += UPPERCASE;
	        }
	    }

	    arsort($charsets);

	    return current($charsets) > 0 ? key($charsets) : '';
	} 
	/**
	 * Detect encoding by language
	 *
	 * @param string $resource string or file path
	 * @param string $resource_type 'S' (string) or 'F' (file)
	 * @param string $lang_code language of the file characters
	 * @return string  detected encoding
	 */

	public function fn_detect_encoding($resource, $resource_type = 'S', $lang_code = CART_LANGUAGE)
	{
	    $enc = '';
	    $str = $resource;

	    if ($resource_type == 'F') {
	        $str = file_get_contents($resource);
	        if ($str == false) {
	            return $enc;
	        }
	    }

	    if (!$this->fn_is_utf8($str)) {
	        if (in_array($lang_code, array('en', 'fr', 'es', 'it', 'nl', 'da', 'fi', 'sv', 'pt', 'nn', 'no'))) {
	            $enc = 'ISO-8859-1';
	        } elseif (in_array($lang_code, array('hu', 'cs', 'pl', 'bg', 'ro'))) {
	            $enc = 'ISO-8859-2';
	        } elseif (in_array($lang_code, array('et', 'lv', 'lt'))) {
	            $enc = 'ISO-8859-4';
	        } elseif ($lang_code == 'ru') {
	            $enc = $this->fn_detect_cyrillic_charset($str);
	        } elseif ($lang_code == 'ar') {
	            $enc = 'ISO-8859-6';
	        } elseif ($lang_code == 'el') {
	            $enc = 'ISO-8859-7';
	        } elseif ($lang_code == 'he') {
	            $enc = 'ISO-8859-8';
	        } elseif ($lang_code == 'tr') {
	            $enc = 'ISO-8859-9';
	        }
	    } else {
	        $enc = 'UTF-8';
	    }

	    return $enc;
	}
	public function fn_array_to_xml($data)
	{
	    if (!is_array($data)) {
	        return $this->fn_html_escape($data);
	    }

	    $return = '';
	    foreach ($data as $key => $value) {
	        $attr = '';
	        if (is_array($value) && is_numeric(key($value))) {
	            foreach ($value as $k => $v) {
	                $arr = array(
	                    $key => $v
	                );
	                $return .= $this->fn_array_to_xml($arr);
	                unset($value[$k]);
	            }
	            unset($data[$key]);
	            continue;
	        }
	        if (strpos($key, '@') !== false) {
	            $data = explode('@', $key);
	            $key = $data[0];
	            unset($data[0]);

	            if (count($data) > 0) {
	                foreach ($data as $prop) {
	                    if (strpos($prop, '=') !== false) {
	                        $prop = explode('=', $prop);
	                        $attr .= ' ' . $prop[0] . '="' . $prop[1] . '"';
	                    } else {
	                        $attr .= ' ' . $prop . '=""';
	                    }
	                }
	            }
	        }
	        $return .= '<' . $key . $attr . '>' . $this->fn_array_to_xml($value) . '</' . $key . '>';
	    }

	    return $return;
	}
	/**
	 * Builds hierarchic tree from array width id and parent_id
	 * @param array $array array of data, must be sorted ASC by  parent_id
	 * @param string $object_key  name of id key in array
	 * @param string $parent_key name of parent key in array
	 * @param string $cildren_key name of key whee sub elements will be located in tree
	 * @return array
	 */
	public function fn_build_hierarchic_tree($array, $object_key, $parent_key = 'parent_id', $child_key = 'children')
	{
	    $rev_arr = array_reverse($array);
	    foreach ($rev_arr as $brunch) {
	        if ($brunch[$parent_key] == 0) {
	            continue;
	        } else {
	            $array[$brunch[$parent_key]][$child_key][$brunch[$object_key]] = $array[$brunch[$object_key]];
	            unset($array[$brunch[$object_key]]);
	        }
	    }

	    return $array;
	}
	/**
	 * Gets count of directory subdirectories
	 *
	 * @param string $path directory path
	 * @return int number of subdirectories
	 */
	public function fn_dirs_count($path)
	{
	    $dirscount = 0;

	    if (empty($path) || !is_dir($path) || !($dir = opendir($path))) {
	        return $dirscount;
	    }

	    while (($file = readdir($dir)) !== false) {
	        if ($file == '.' || $file == '..') {
	            continue;
	        }

	        if (is_dir($path . '/' . $file)) {
	            $dirscount++;
	            $dirscount += $this->fn_dirs_count($path . '/' . $file);
	        }
	    }

	    closedir($dir);

	    return $dirscount;
	} 
	/**
	 * Add slashes
	 *
	 * @param mixed $var variable to add slashes to
	 * @param boolean $escape_nls if true, escape "new line" chars with extra slash
	 * @return mixed filtered variable
	 */
	public function fn_add_slashes(&$var, $escape_nls = false)
	{
	    if (!is_array($var)) {
	        return ($var === null) ? null : (($escape_nls == true) ? str_replace("\n", "\\n", addslashes($var)) : addslashes($var));
	    } else {
	        $slashed = array();
	        foreach ($var as $k => $v) {
	            $sk = addslashes($k);
	            if (!is_array($v)) {
	                $sv = ($v === null) ? null : (($escape_nls == true) ? str_replace("\n", "\\n", addslashes($v)) : addslashes($v));
	            } else {
	                $sv = fn_add_slashes($v, $escape_nls);
	            }
	            $slashed[$sk] = $sv;
	        }

	        return ($slashed);
	    }
	}
	/**
	 * Convert multi-level array to single-level array
	 *
	 * @param array $item Multi-level array
	 * @param string $delimiter Delimiter name
	 * @return array Single-level array
	 */
	public function fn_foreach_recursive($item, $delimiter, $name = '', $arr = array())
	{
	    if (is_array($item)) {
	        foreach ($item as $key => $value) {
	            $new_key = $name === '' ? $key : $name . $delimiter . $key;
	            $arr = $this->fn_foreach_recursive ($value, $delimiter, $new_key, $arr);
	        }
	    } else {
	        $arr[$name] = $item;
	    }

	    return $arr;
	}
	/**
	 * Rounds a value down with a given step
	 *
	 * @param int $value
	 * @param int $step
	 * @return int Rounded value
	 */
	public function fn_floor_to_step($value, $step)
	{
	    $floor = false;

	    if (empty($step) && !empty($value)) {
	        $floor = $value;

	    } elseif (!empty($value) && !empty($step)) {
	        if ($value % $step) {
	            $floor = floor($value / $step) * $step;
	        } else {
	            $floor = $value;
	        }
	    }

	    return $floor;
	}
	/**
	 * Rounds a value up with a given step
	 *
	 * @param int $value
	 * @param int $step
	 * @return int Rounded value
	 */
	public function fn_ceil_to_step($value, $step)
	{
	    $ceil = false;

	    if (empty($step) && !empty($value)) {
	        $ceil = $value;

	    } elseif (!empty($value) && !empty($step)) {
	        if ($value % $step) {
	            $ceil = ceil($value / $step) * $step;
	        } else {
	            $ceil = $value;
	        }
	    }

	    return $ceil;
	}
	/**
	 * Convert underscored string to CamelCase
	 *
	 * @param str  $string String
	 * @param bool $upper  upper-camelcase/lower-camelcase
	 * @return str
	 */
	public function fn_camelize($string, $upper = true)
	{
	    $regexp = $upper ? '/(?:^|_)(.?)/e' : '/_(.?)/e';
	    $string = preg_replace($regexp, "strtoupper('$1')", $string);

	    return $string;
	}
	/**
	 * Convert CamelCase (lower or upper) string to underscored
	 *
	 * @param str  $string    String
	 * @param bool $delimiter Delimiter
	 * @return str
	 */
	public function fn_uncamelize($string, $delimiter = '_')
	{
	    $string = preg_replace("/(?!^)[[:upper:]]+/", $delimiter . '$0', $string);

	    return strtolower($string);
	}
	public function fn_exim_json_encode($data)
	{
	    if (is_callable('mb_encode_numericentity') && is_callable('mb_decode_numericentity')) {
	        $_data = fn_exim_prepare_data_to_convert($data);

	        return mb_decode_numericentity(json_encode($_data), array (0x80, 0xffff, 0, 0xffff), 'UTF-8');
	    } else {
	        return json_encode($data);
	    }
	}
	public function fn_exim_prepare_data_to_convert($data)
	{
	    $_data = array();
	    if (is_array($data) && is_callable('mb_encode_numericentity')) {
	        foreach ($data as $k => $v) {
	            $key = mb_encode_numericentity($k, array (0x80, 0xffff, 0, 0xffff), 'UTF-8');
	            if (is_array($v)) {
	                $_data[$key] = $this->fn_exim_prepare_data_to_convert($v);
	            } else {
	                $_data[$key] = mb_encode_numericentity($v, array (0x80, 0xffff, 0, 0xffff), 'UTF-8');
	            }
	        }
	    } else {
	        $_data = $data;
	    }

	    return $_data;
	}
}

