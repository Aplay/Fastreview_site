<?php

/**
 * CJText 
 *
 * @author	 
 * @version $Id: CJSON.php 2163 2010-06-04 14:49:37Z alexander.makarow $
 * @package	system.web.helpers
 * @since 1.0
 */
class CJText 
{
   /**
	* Encodes an arbitrary variable into JSON format
	*
	* @param	mixed   $var	any number, boolean, string, array, or object to be encoded.
	*						   see argument 1 to JSON() above for array-parsing behavior.
	*						   if var is a strng, note that encode() always expects it
	*						   to be in ASCII or UTF-8 format!
	*
	* @return   string  JSON string representation of input var
	*/
	public static function declension($qty, $variants)
	{
    $residue = $qty % 10;
    if ($residue == 1 && $qty != 11) return $variants[0];
    if ($residue >= 2 && $residue <= 4) 
      if ($qty <= 11 || $qty >= 15)  return $variants[1];
    return $variants[2];
	}

	public static function format_time_left($time_left)
	{
    if ($time_left>0){
      $d = floor($time_left / (3600*24));
      $h = floor(($time_left - $d*3600*24)/ 3600);
      $m = floor(($time_left - $d*3600*24 - $h * 3600) / 60 );
      $s = ($time_left - $d*3600*24 - $h * 3600 - $m*60);
      $r = $time_left_str;
      $r .= ($d>0?$d.' дн. ':'').$h.' ч. '.$m.' мин. '.$s.' с';
      return $r;
    } else return false;
    
  }
  
  function time_diff($dt1,$dt2, $in_days = true){
      $y1 = substr($dt1,0,4);
      $m1 = substr($dt1,5,2);
      $d1 = substr($dt1,8,2);
      $h1 = substr($dt1,11,2);
      $i1 = substr($dt1,14,2);
      $s1 = substr($dt1,17,2);    

      $y2 = substr($dt2,0,4);
      $m2 = substr($dt2,5,2);
      $d2 = substr($dt2,8,2);
      $h2 = substr($dt2,11,2);
      $i2 = substr($dt2,14,2);
      $s2 = substr($dt2,17,2);    

      $r1=date('U',mktime($h1,$i1,$s1,$m1,$d1,$y1));
      $r2=date('U',mktime($h2,$i2,$s2,$m2,$d2,$y2));
      return ($r2-$r1)/($in_days?3600*24:1);
      //return ($r2-$r1);

  }

 public static function parseLinks($text){  // делать из текста ссылку
    return preg_replace("#(https?|ftp)://\S+[^\s.,>)\];'\"!?]#",'<a href="\\0" target="_blank">\\0</a>',$text);
  }

}