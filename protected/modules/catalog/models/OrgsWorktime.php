<?php

/**
 * This is the model class for table "orgs_worktime".
 *
 * The followings are the available columns in table 'orgs_worktime':
 * @property integer $id
 * @property integer $week
 * @property boolean $iswork
 * @property string $from_work
 * @property string $to_work
 * @property integer $org
 *
 * The followings are the available model relations:
 * @property Orgs $org0
 */
class OrgsWorktime extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'orgs_worktime';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('week, org', 'required'),
			array('week, org', 'numerical', 'integerOnly'=>true),
			array('from_work, to_work', 'filter', 'filter'=>array( $this, 'fullTime' )),
			array('from_work, to_work', 'type', 'type' => 'date', 'dateFormat' => 'H:mm', 'allowEmpty'=>false, 'message' => 'Введите время в формате ЧЧ:ММ '),
			array('from_work, to_work', 'filter', 'filter'=>array( $this, 'afterFullTime' )),
			array('to_work',  'customValidation'),
			array('iswork', 'boolean'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, week, iswork, from_work, to_work, org', 'safe', 'on'=>'search'),
		);
	}

	public function fullTime($value)
	{
	    //set 23:59:59
	    if($value == '24:00')
	    	$value = '23:59';
	    return $value;
	}

	public function afterFullTime($value)
	{
	    //set 23:59:59
	    if($value == '23:59')
	    	$value = '24:00';
	    return $value;
	}
    public function customValidation($attr,$params)
    {
    	if (!empty($this->$attr) && !empty($this->from_work))
        {
           if(strtotime($this->$attr) <= strtotime($this->from_work)){
               $this->addError($attr, 'Время окончания не может быть меньше времени начала');
           }
        } else if(!empty($this->$attr) && empty($this->from_work)){
        	
        		$this->addError($attr, 'Установите время начала');

        } else if(empty($this->$attr) && !empty($this->from_work)){
        	
        		$this->addError($attr, 'Установите время окончания');
        	
        }
    }
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'organization' => array(self::BELONGS_TO, 'Orgs', 'org'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'week' => 'Week',
			'iswork' => 'Iswork',
			'from_work' => 'From Work',
			'to_work' => 'To Work',
			'org' => 'Org',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('week',$this->week);
		$criteria->compare('iswork',$this->iswork);
		$criteria->compare('from_work',$this->from_work,true);
		$criteria->compare('to_work',$this->to_work,true);
		$criteria->compare('org',$this->org);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public static function setDoor($org, array $open_door, array $close_door, $iswork = true)
	{
		$dontDelete = array();
		if(!empty($open_door)){
		foreach($open_door as $week=>$time)
		{
			if(empty($time))
				continue;

			$error = false;
			$found = OrgsWorktime::model()->findByAttributes(array(
				'week'=>$week,
				'org'=>$org,
				'iswork'=>$iswork
			));

			// если не было времени - делаем
			if(!$found)
			{
				$record = new OrgsWorktime;
				$record->week = $week;
				$record->iswork = $iswork;
				$record->from_work = $time;
				if(isset($close_door[$week]))
					$record->to_work = $close_door[$week];
				$record->org = $org;
				if($record->validate()){
					if($record->from_work == '24:00')
						$record->from_work = '23:59:59';
					if($record->to_work == '24:00')
						$record->to_work = '23:59:59';
					$record->save(false);
				} else {
					// cant save that remove it block
				//	VarDumper::dump($record->from_work);
				//	VarDumper::dump($record->to_work);
				//	VarDumper::dump($record->errors); die(); // Ctrl + X	Delete line
					$error = true;
				}
				
			} else { // обновляем описание
				if($time == '24:00'){
					$time = '23:59:59';
				}
				if($found->from_work != $time){
					$found->from_work = $time;
					if(!$found->save(false, array('from_work'))){
						$error = true;
					}
				}
				if(isset($close_door[$week])){
					if($close_door[$week] == '24:00')
						$close_door[$week] = '23:59:59';
					if($found->to_work != $close_door[$week]){
						$found->to_work = $close_door[$week];
						if(!$found->save(false, array('to_work'))){
							$error = true;
						}
					}
				}
			}
			if(!$error)
				$dontDelete[] = $week;
		}	
	}
		if(sizeof($dontDelete) > 0)
		{
			$cr = new CDbCriteria;
			$cr->addNotInCondition('week', $dontDelete);
			OrgsWorktime::model()->deleteAllByAttributes(array(
				'org'=>$org,
				'iswork'=>$iswork
			), $cr);
		}
		else // удаляем все время, т.к. пустой массив
		{
			// Delete all relations
			OrgsWorktime::model()->deleteAllByAttributes(array(
				'org'=>$org,
				'iswork'=>$iswork
			));
		}  
		return true;
	}
	public static function setWorktime($org, array $open_door, array $close_door, array $break_door, array $endbreak_door)
	{
		self::setDoor($org, $open_door, $close_door);
	    self::setDoor($org, $break_door, $endbreak_door, false);	
	}
	// $city_utc - разница между временем utc и городом
	public static function workingProcess($org, $city_utc = 0){
		
        $day_number = date('w', time()); // 0 - воскресенье
        $time = date('H:i', time() + ($city_utc*60*60));  // получаем время текущее в utc
        // например заведение в москве работает до 18 час. нужно показать, что именно сейчас это заведение открыто по московскому времени.
		$wprocess = OrgsWorktime::model()->countByAttributes(array(
			'org'=>$org,
			'iswork'=>true,
			'week'=>$day_number
		), "from_work <= '{$time}' and to_work >= '{$time}'");
		if($wprocess){
			$wprocess = OrgsWorktime::model()->findByAttributes(array(
				'org'=>$org,
				'iswork'=>false,
				'week'=>$day_number
			), "from_work <= '{$time}' and to_work >= '{$time}'");
			if($wprocess){
				return 'Откроется в '.date('H:i', strtotime($wprocess->to_work));
			} else {
				return 'Сейчас открыто';
			}
		} else {
			$wprocess = OrgsWorktime::model()->countByAttributes(array(
			'org'=>$org,
			'iswork'=>true,
			));
			if($wprocess){
				return 'Сейчас закрыто';
			} else {

			}
		}
		return false;
	}
	public static function workingProcessAgo($org, $city_utc = 0){
									

        $day_number = date('w', time()); // 0 - воскресенье
        $time = date('H:i', time() + ($city_utc*60*60));  // получаем время текущее в utc
        // например заведение в москве работает до 18 час. нужно показать, что именно сейчас это заведение открыто по московскому времени.
		$wprocess = OrgsWorktime::model()->findByAttributes(array(
			'org'=>$org,
			'iswork'=>true,
			'week'=>$day_number
		), "from_work <= '{$time}' and to_work >= '{$time}'");
		if($wprocess){
			$wprocess2 = OrgsWorktime::model()->findByAttributes(array(
				'org'=>$org,
				'iswork'=>false,
				'week'=>$day_number
			), "from_work <= '{$time}' and to_work >= '{$time}'");
			if($wprocess2){ // обед 
				// return 'Откроется в '.date('H:i', strtotime($wprocess->to_work));
				// до открытия 
				// $diff = strtotime($wprocess->to_work) - strtotime($time);
				$ago = MHelper::Date()->timeAgo(strtotime($wprocess2->to_work), array('short'=>true), strtotime($time));
				return array('Сейчас закрыто', $ago);
			} else {
				$ago = MHelper::Date()->timeAgo(strtotime($wprocess->to_work), array('short'=>true), strtotime($time));
				return array('Сейчас открыто', $ago);
			}
		} else {
			$wprocess3 = OrgsWorktime::model()->findByAttributes(array(
			'org'=>$org,
			'iswork'=>true,
			'week'=>$day_number
			), "from_work <= '{$time}' and to_work <= '{$time}'");

			if($wprocess3){
			/*	if($day_number == 6)
					$next_day = 0;
				else 
					$next_day = $day_number+1;
				$wprocess4 = OrgsWorktime::model()->findByAttributes(array(
				'org'=>$org,
				'iswork'=>true,
				'week'=>$next_day
				));
				if($wprocess4){
					// $all_time = strtotime('23:59') - strtotime($wprocess3->to_work) + strtotime($wprocess4->from_work);
					// $timesp = date('H:i', $all_time);
				    // $ago = MHelper::Date()->timeAgo(strtotime('00:00'), array('short'=>true), strtotime($timesp));
					// return array('Сейчас закрыто',$ago);
				}*/
				return array('Сейчас закрыто','');

			} else {
				$wprocess5 = OrgsWorktime::model()->findByAttributes(array(
				'org'=>$org,
				'iswork'=>true,
				'week'=>$day_number
				), "from_work >= '{$time}' and to_work >= '{$time}'");
				if($wprocess5){

					$ago = MHelper::Date()->timeAgo(strtotime($time), array('short'=>true), strtotime($wprocess5->from_work));
					return array('Сейчас закрыто',$ago);

				}
			}
		}
		return array();
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OrgsWorktime the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
