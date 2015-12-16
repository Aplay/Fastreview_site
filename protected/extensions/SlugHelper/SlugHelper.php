<?php

class SlugHelper {

    
    /**
     * Create slug from text. Also translit cirylic text.
     * @param string $text Text to create slug
     * @return string Slug
     */
    public static function run($text, $standard = 'iso', $tolower = true)
    {
        $matrix=array(
            "й"=>"i","ц"=>"c","у"=>"u","к"=>"k","е"=>"e","н"=>"n",
            "г"=>"g","ш"=>"sh","щ"=>"sh","з"=>"z","х"=>"h","ъ"=>"\'",
            "ф"=>"f","ы"=>"i","в"=>"v","а"=>"a","п"=>"p","р"=>"r",
            "о"=>"o","л"=>"l","д"=>"d","ж"=>"zh","э"=>"ie","ё"=>"e",
            "я"=>"ya","ч"=>"ch","с"=>"s","м"=>"m","и"=>"i","т"=>"t",
            "ь"=>"\'","б"=>"b","ю"=>"yu","і"=>"i","ї"=>"i",
            "Й"=>"I","Ц"=>"C","У"=>"U","К"=>"K","Е"=>"E","Н"=>"N",
            "Г"=>"G","Ш"=>"SH","Щ"=>"SH","З"=>"Z","Х"=>"X","Ъ"=>"\'",
            "Ф"=>"F","Ы"=>"I","В"=>"V","А"=>"A","П"=>"P","Р"=>"R",
            "О"=>"O","Л"=>"L","Д"=>"D","Ж"=>"ZH","Э"=>"IE","Ё"=>"E",
            "Я"=>"YA","Ч"=>"CH","С"=>"S","М"=>"M","И"=>"I","Т"=>"T",
            "Ь"=>"\'","Б"=>"B","Ю"=>"YU","І"=>"I","Ї"=>"I",
            "«"=>"","»"=>""," "=>"-",
        );
        $iso = array(
           "Є"=>"YE","І"=>"I","Ѓ"=>"G","і"=>"i","№"=>"#","є"=>"ye","ѓ"=>"g",
           "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D",
           "Е"=>"E","Ё"=>"YO","Ж"=>"ZH",
           "З"=>"Z","И"=>"I","Й"=>"J","К"=>"K","Л"=>"L",
           "М"=>"M","Н"=>"N","О"=>"O","П"=>"P","Р"=>"R",
           "С"=>"S","Т"=>"T","У"=>"U","Ф"=>"F","Х"=>"X",
           "Ц"=>"C","Ч"=>"CH","Ш"=>"SH","Щ"=>"SHH","Ъ"=>"'",
           "Ы"=>"Y","Ь"=>"","Э"=>"E","Ю"=>"YU","Я"=>"YA",
           "а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d",
           "е"=>"e","ё"=>"yo","ж"=>"zh",
           "з"=>"z","и"=>"i","й"=>"j","к"=>"k","л"=>"l",
           "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
           "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"x",
           "ц"=>"c","ч"=>"ch","ш"=>"sh","щ"=>"shh","ъ"=>"",
           "ы"=>"y","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya",
           "—"=>"-","«"=>"","»"=>"","…"=>""
          );
         $yandex = array( // http://xn--80aaolconwfghg9cwh.xn--p1ai/ буквы ё, х
           "Є"=>"YE","І"=>"I","Ѓ"=>"G","і"=>"i","№"=>"#","є"=>"ye","ѓ"=>"g",
           "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D",
           "Е"=>"E","Ё"=>"E","Ж"=>"ZH",
           "З"=>"Z","И"=>"I","Й"=>"J","К"=>"K","Л"=>"L",
           "М"=>"M","Н"=>"N","О"=>"O","П"=>"P","Р"=>"R",
           "С"=>"S","Т"=>"T","У"=>"U","Ф"=>"F","Х"=>"KH",
           "Ц"=>"C","Ч"=>"CH","Ш"=>"SH","Щ"=>"SHH","Ъ"=>"'",
           "Ы"=>"Y","Ь"=>"","Э"=>"E","Ю"=>"YU","Я"=>"YA",
           "а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d",
           "е"=>"e","ё"=>"e","ж"=>"zh",
           "з"=>"z","и"=>"i","й"=>"j","к"=>"k","л"=>"l",
           "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
           "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"kh",
           "ц"=>"c","ч"=>"ch","ш"=>"sh","щ"=>"shh","ъ"=>"",
           "ы"=>"y","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya",
           "—"=>"-","«"=>"","»"=>"","…"=>""," "=>"-"
          );
        $gost = array(
           "Є"=>"EH","І"=>"I","і"=>"i","№"=>"#","є"=>"eh",
           "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D",
           "Е"=>"E","Ё"=>"JO","Ж"=>"ZH",
           "З"=>"Z","И"=>"I","Й"=>"JJ","К"=>"K","Л"=>"L",
           "М"=>"M","Н"=>"N","О"=>"O","П"=>"P","Р"=>"R",
           "С"=>"S","Т"=>"T","У"=>"U","Ф"=>"F","Х"=>"KH",
           "Ц"=>"C","Ч"=>"CH","Ш"=>"SH","Щ"=>"SHH","Ъ"=>"'",
           "Ы"=>"Y","Ь"=>"","Э"=>"EH","Ю"=>"YU","Я"=>"YA",
           "а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d",
           "е"=>"e","ё"=>"jo","ж"=>"zh",
           "з"=>"z","и"=>"i","й"=>"jj","к"=>"k","л"=>"l",
           "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
           "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"kh",
           "ц"=>"c","ч"=>"ch","ш"=>"sh","щ"=>"shh","ъ"=>"",
           "ы"=>"y","ь"=>"","э"=>"eh","ю"=>"yu","я"=>"ya",
           "—"=>"-","«"=>"","»"=>"","…"=>""
          );
        switch ($standard) {
            case 'off':
                return $text;      
            case 'gost':
                $text = strtr($text, $gost);
            case 'iso':
                $text = strtr($text, $iso);
            case 'yandex':
                $text = strtr($text, $yandex);
            default: 
                $text = strtr($text, $matrix);
        }
      /*  foreach($matrix as $from=>$to)
            $text=mb_eregi_replace($from,$to,$text);
        $text = preg_replace('/[^A-Za-z0-9_\-]/', '', $text);

        return strtolower($text);*/
        $text = preg_replace('/[^A-Za-z0-9_\-]/', '', $text);
        if($tolower)
            $text = MHelper::String()->toLower($text);

        return $text;
    }

    public static function detranslit($string)
    {

    	$yandex = array(
			'A'=>'А', 'B'=>'Б', 'CH'=>'Ч', 'C'=>'Ц', 'D'=>'Д', 'E'=>'Е',
			'F'=>'Ф', 'G'=>'Г', 'SH'=>'Ш', 'KH'=>'Х', 'SHH'=>'Щ',
			'I'=>'И', 'J'=>'Й', 'K'=>'К', 'L'=>'Л',  'M'=>'М', 
			'N'=>'Н', 'O'=>'О', 'P'=>'П', 'Q'=>'К',  'R'=>'Р', 
			'S'=>'С', 'T'=>'Т', 'U'=>'У', 'V'=>'В',  'W'=>'В', 
			'X'=>'', 'YA'=>'Я', 'YU'=>'Ю', 'Y'=>'Ы', 'ZH'=>'Ж', 'Z'=>'З', 
			
			'a'=>'а', 'b'=>'б', 'ch'=>'ч', 'c'=>'ц', 'd'=>'д', 'e'=>'е',
			'f'=>'ф', 'g'=>'г', 'sh'=>'ш', 'kh'=>'х', 'shh'=>'щ', 
			'i'=>'и', 'j'=>'й', 'k'=>'к', 'l'=>'л',  'm'=>'м', 
			'n'=>'н', 'o'=>'о', 'p'=>'п', 'q'=>'к',  'r'=>'р', 
			's'=>'с', 't'=>'т', 'u'=>'у', 'v'=>'в',  'w'=>'в', 
			'x'=>'', 'ya'=>'я', 'yu'=>'ю', 'y'=>'ы', 'zh'=>'ж', 'z'=>'з',
			'-'=>' ', '_'=>' '
		);
		return strtr($string, $yandex);
    }

    public static function cleanUrl($text,  $tolower = true)
    {
      
      $text = filter_var($text,FILTER_SANITIZE_URL);
     // $text = preg_replace('/[^A-Za-z0-9_\-]/', '', $text);
        if($tolower)
            $text = MHelper::String()->toLower($text);

        return $text;
    }

    /**
   * Function: sanitize
   * Returns a sanitized string, typically for URLs.
   *
   * Parameters:
   *     $string - The string to sanitize.
   *     $force_lowercase - Force the string to lowercase?
   *     $anal - If set to *true*, will remove all non-alphanumeric characters.
   */
  public static function sanitizeUrl($string, $force_lowercase = true, $anal = false) 
  {
      $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
                     "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
                     "â€”", "â€“", ",", "<", ".", ">", "/", "?");
      $clean = trim(str_replace($strip, "-", strip_tags($string)));
      $clean = preg_replace('/\s+/', "-", $clean);
      $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;
      return ($force_lowercase) ?
          (function_exists('mb_strtolower')) ?
              mb_strtolower($clean, 'UTF-8') :
              strtolower($clean) :
          $clean;
  }



}