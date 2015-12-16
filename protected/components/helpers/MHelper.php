<?php

/**
 * Класс-хелпер
 *
 * <code>
 * MHelper::get('Type')->method(params);
 * OR
 * MHelper::get()->Type->method(params);
 * OR
 * MHelper::Type()->method(params); // PHP 5.3+ only
 * </code>
 *Yii helpers.

1. Copy all files to protected/components/

2. In config:
	...
	'import' => array(
		...
		'application.components.*',
		...
	),
	...

3. Use variants:
	MHelper::get()->String->toLower('Text');
	OR
	MHelper::get('String')->toLower('Text');
	OR
	MHelper::String()->toLower('Text');	// PHP 5.3 only


Helpers list.

String
	
	Methods:
		toUpper
		toLower
		substr
		strrchr
		len
		trimSlashes
		stripSlashes
		stripQuotes
		quotesToEntities
		reduceDoubleSlashes
		reduceMultiples
		incrementString
		alternator
		repeater
		wordwrap
		truncate
		randomString
		toTranslit
		fromTranslit

Array
	
	Methods:
		element
		randomElement
		elements

Date
	
	Methods:
		timeAgo
		daysInMonth

Directory

	Methods:
		map
		clear
		delete

File
	
	Methods:
		isFile
		isReadable
		read
		write
		delete
		filenames
		filesInfo
		fileInfo
		pathInfo
		size
		sizeFormat
		send
		download
		getMimeType
		getMimeTypeByExtension
 * @version 0.1 21.08.2011
 * @author webmaxx <webmaxx@webmaxx.name>
 */
class MHelper
{

	protected static $_Instance = null;
	protected static $_Helpers = array();
	
	private function __construct() {}
	private function __clone() {}
	
	public static function __callStatic($name, $arguments)
	{
		return self::get($name);
	}

	public function __get($name)
	{
		if (!isset(self::$_Helpers[$name])) {
			$className = 'M'.ucfirst($name);
			require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'MHelper'.DIRECTORY_SEPARATOR.$className.'.php';
			self::$_Helpers[$name] = new $className;
		}
		return self::$_Helpers[$name];
	}
	
	public static function get($name)
	{
		if (self::$_Instance === null)
			self::$_Instance = new self;
		
		return $name ? self::$_Instance->{$name} : self::$_Instance;
	}
	
}

/**
 * Базовый класс для всех классов-хелперов
 *
 * @version 0.1 21.08.2011
 * @author webmaxx <webmaxx@webmaxx.name>
 */
abstract class MHelperBase
{
	protected  $_trash = array();
	
	protected function _functionExists($name)
	{
		if (!isset($this->_trash[$name]))
			$this->_trash[$name] = function_exists($name);
		
		return $this->_trash[$name];
	}
}
