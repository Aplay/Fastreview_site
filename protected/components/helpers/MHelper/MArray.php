<?php

/**
 * Класс-хелпер для работы с массивами
 *
 * @version 0.1 22.11.2011
 * @author webmaxx <webmaxx@webmaxx.name>
 */
class MArray extends MHelperBase
{
	
	/**
	 * Метод для получения элемента массива
	 * Если он не существует или пустой, то возвращается значение по умолчанию
	 * 
	 * @param string $item
	 * @param array $array
	 * @param mixed $default
	 * @return mixed
	 *
	 * @version 0.1 22.11.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 * @see CodeIgniter_2.1.0/system/helpers/array_helper.php
	 */
	public function element($item, $array, $default=false)
	{
		if (!isset($array[$item]) || $array[$item] == "")
			return $default;

		return $array[$item];
	}
	
	/**
	 * Метод возвращает случайный элемент массива
	 * 
	 * @param array $array
	 * @return mixed
	 *
	 * @version 0.1 22.11.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 * @see CodeIgniter_2.1.0/system/helpers/array_helper.php
	 */
	public function randomElement($array)
	{
		if (!is_array($array))
			return $array;
		
		return $array[array_rand($array)];
	}
	
	/**
	 * Метод для получения специфичных элементов массива
	 * Если какого либо элемента не существует или пустой, то вместо него возвращается значение по умолчанию
	 * 
	 * @param array $items
	 * @param array $array
	 * @param mixed $default
	 * @return mixed
	 *
	 * @version 0.1 22.11.2011
	 * @since 0.1
	 * @author webmaxx <webmaxx@webmaxx.name>
	 * @see CodeIgniter_2.1.0/system/helpers/array_helper.php
	 */
	public function elements($items, $array, $default=false)
	{
		$return = array();
		
		if (!is_array($items))
			$items = array($items);
		
		foreach ($items as $item) {
			if (isset($array[$item]))
				$return[$item] = $array[$item];
			else
				$return[$item] = $default;
		}

		return $return;
	}
	
	/**
	 * Метод возвращает массив, отсортированный по ключу
	 */
	public function uksorty($array,$key) {
		$this->key = $key;
		uksort($array,function($a,$b){
			if ($a[$this->key] == $b[$this->key]) {
		        return 0;
		    }
		    return ($a[$this->key] > $b[$this->key]) ? -1 : 1;
		});

	    return $array;
	}

	public  function sortFunction($field)
	{
		$code = "return strnatcmp(\$a['$field'], \$b['$field']);";
		return create_function('$a,$b', $code);
	}
}
