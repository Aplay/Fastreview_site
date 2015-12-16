<?php
/**
* AgeValidator class file
* public function rules()
{
    return array(
 
        # If you imported ext.validators.age.EAgeValidator or placed EAgeValidator.php
        # to directory, that is imported (for example, protected/components)
        array('your_birthdate_field_name1', 'EAgeValidator'),
        ...
        # With options and full path to extension   
        array('your_birthdate_field_name2', 'ext.validators.age.EAgeValidator',                    
                  'minAge'=>16, 
                  'maxAge'=>120, 
                  'allowEmpty'=>false
        ),
        ...
    );
}
* @author Vitaliy Stepanenko <mail@vitaliy.in>
* @package Validators 
* @version $Id: EAgeValidator.php 27614 2011-04-18 11:06:03Z stepanenko $
* @license BSD
*/

/**
* Validates min/max age by birthdate
* @see CValidator
*/
class EAgeValidator extends CValidator {

	/**#@+
	* Validation error types.
	* @see EAgeValidator::getMessage()
	*/
	const ERROR_EMPTY			= 1;
	const ERROR_INVALID_FORMAT	= 2;
	const ERROR_UNDERAGE		= 3;
	const ERROR_OVERERAGE		= 4;
	/**#@-**/

	/**
	* Validation error messages on english
	* @todo Replace this to correct english messages (need help!)
	* @see EAgeValidator::getMessage()
	* @var array
	*/
	public $error_empty = 'Поле обязательно для заполнения.';
	public $error_invalid_format = 'Неверный формат.';
	public $error_underage = 'Возраст не должен быть меньше {minAge}';
	public $error_overage = 'Возраст не должен быть больше {maxAge}';

	public static $messages = array(
		self::ERROR_EMPTY				=> 'This field is required.',
		self::ERROR_INVALID_FORMAT		=> 'This is not a date.',
		self::ERROR_UNDERAGE			=> 'Your age must be not less than {minAge}.',
		self::ERROR_OVERERAGE			=> 'Your age must be not more than {maxAge}.'
	);

	/**#@+
	* Options hat can be configured
	*/
	
	/**
	* Minimal allowed age
	* @var int
	*/
	public $minAge = 18;

	/**
	* Maximal allowed age
	* @var int
	*/
	public $maxAge = 100;

	/**
	* @var boolean whether the attribute value can be null or empty. Defaults to true,
	* meaning that if the attribute is empty, it is considered valid.
	*/
	public $allowEmpty = false;

	/**
	* Message category for translation,
	* @see Yii::t()
	* 
	* @var string
	*/
	public $messageCategory	= 'ageValidator';

	/**
	* Message source application component name for translation,
	* Defaults to null, meaning using 'coreMessages' 
	* @see Yii::t()
	* @var string or null
	*/
	public $messageSource = null;

	/**#@-*/

	/**
	* @param int $errorCode See class constants
	* @param mixed $params Params that must be a part of message text.
	* @return string message for validation error
	*/
	protected function getMessage($errorCode,$params = null)
	{
		//return Yii::t($this->messageCategory,self::$messages[$errorCode],$params,$this->messageSource);
		return Yii::t($this->messageCategory,$this->errorMes($errorCode),$params,$this->messageSource);
	}
	protected function errorMes($errorCode = 0){
		$array_codes = array('',
			$this->error_empty,
			$this->error_invalid_format,
			$this->error_underage,
			$this->error_overage
			);
		return $array_codes[$errorCode];
	}
	/**
	* Validates the attribute of the object.
	* If there is any error, the error message is added to the object.
	* @param CModel the object being validated
	* @param string name of the attribute being validated
	*/
	protected function validateAttribute($object,$attribute)
	{	
		if($this->isEmpty($object->$attribute)) {
			if ($this->allowEmpty) {
				return;
			} else {
				$this->addError($object,$attribute,$this->getMessage(self::ERROR_EMPTY));
			}
		}

		$birthdate=strtotime($object->$attribute);
		if (!$birthdate) {
			$this->addError($object,$attribute,$this->getMessage(self::ERROR_INVALID_FORMAT));
			return;
		}
		$age = $this->getAge($birthdate);
		
		if ($age<$this->minAge) {
			$this->addError($object,$attribute,$this->getMessage(self::ERROR_UNDERAGE,array('{minAge}'=>$this->minAge)));
			return;
		}
		
		if ($age>$this->maxAge) {
			$this->addError($object,$attribute,$this->getMessage(self::ERROR_UNDERAGE,array('{maxAge}'=>$this->maxAge)));	
			return;
		}
		
	}
	
	/**	
	* Calculates age by birthdate.
	* @param timestamp $birthdate
	* @returns int age
	*/
	public function getAge($birthdate)
	{

		# Explode the date into meaningful variables
		$birthYear	= date('Y',$birthdate);
		$birthMonth	= date('m',$birthdate);
		$birthDay	= date('d',$birthdate);
		# Find the differences
		$yearDiff	= date('Y') - $birthYear;
		$monthDiff	= date('m') - $birthMonth;
		$dayDiff	= date('d') - $birthDay;
		# If the birthday has not occured this year
		if ($dayDiff < 0 or $monthDiff < 0) {
			$yearDiff--;
		} 
		return $yearDiff;
	}

	/**
	* Helper method, not used in Yii validation system.
	* 
	* Usage:
	* <code>
	*	$validator = new EAgeValidator;
	*	$isAdult = $validator->validateValue('1987-05-31');
	*	#or
	*	$isAdult = Yii::createComponent(array('class'=>'ext.validators.age.EAgeValidator','minAge'=>16,'maxAge'=>120))->validateValue('2010-05-05');
	* </code> 
	*
	* @param string $birthdate
	* @return bool true if value is valid
	*/
	public function validateValue($birthdate) 
	{
		$birthdate=strtotime($birthdate);
		if (!$birthdate) {
			return false;
		}
		$age = $this->getAge($birthdate);
		return $age >= $this->minAge and $age <= $this->maxAge;
	}
}
