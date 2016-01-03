<?php

class Helper
{
    
  // check if a string contains "invalid" non-ascii characters:
  public static  function isPunycode($domain) {
    return preg_match('/[^a-z0-9.-]/i',$domain) ? true : false;
  }

    /** 
 * Decode IDN Punycode to UTF-8 domain name 
 * 
 * @param string $value Punycode 
 * @return string Domain name in UTF-8 charset 
 * 
 * @author Igor V Belousov <igor@belousovv.ru> 
 * @copyright 2013 Igor V Belousov 
 * @license http://opensource.org/licenses/LGPL-2.1 LGPL v2.1 
 * @link http://belousovv.ru/myscript/phpIDN 
 */ 
 public static function DecodePunycodeIDN($value) 
  { 
    Yii::import('application.vendors.punicode.*');

    require_once(Yii::getPathOfAlias('application.vendors.punicode').'/idna_convert.class.php');
    $IDN = new idna_convert();

    // Encode it to its punycode presentation
    $output = $IDN->decode($value);
    return $output;

  }

  
}

