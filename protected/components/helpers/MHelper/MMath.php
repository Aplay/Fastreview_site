<?php

/**
 * Класс-хелпер для работы с числами
 *
 * MHelper::Math()->inRange(1,3,2);	// PHP 5.3 only
 *
 * @version 0.1 04.12.2013
 * @author roman_mak <ha-cehe@rambler.ru>
 */
class MMath extends MHelperBase
{
	/*
	*  Возвращает true, если число $var >= max и <= min
	*/
	public function inRange($min, $max, $var){
		return( ($var >= min($min, $max)) && ($var <= max($min, $max)) );
	}
}
