<?php

/**
 * This is the model class for table "vote".
 *
 * The followings are the available columns in table 'vote':
 * @property integer $id
 * @property integer $vote
 * @property integer $site
 * @property string $ip
 * @property string $data_vote
 *
 * The followings are the available model relations:
 */
class ArticleVote extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'article_vote';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vote, article, user_id', 'numerical', 'integerOnly'=>true),
			array('ip', 'length', 'max'=>50),
			array('data_vote', 'default', 'value' => date('Y-m-d H:i:s'), 'setOnEmpty' => true, 'on' => 'insert'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, vote, article, ip, data_vote, user_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'articleid' => array(self::BELONGS_TO, 'Article', 'article'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'vote' => 'Vote',
			'article' => 'Статья',
			'ip' => 'Ip',
			'data_vote' => 'Data Vote',
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
		$criteria->compare('vote',$this->vote);
		$criteria->compare('article',$this->article);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('data_vote',$this->data_vote,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public function beforeDelete()
	{
		parent::beforeDelete();
        
		$sql = "SELECT COUNT(*) FROM article_vote WHERE article={$this->article} and vote={$this->vote}";
        $numClients = Yii::app()->db->createCommand($sql)->queryScalar();
        if($this->vote == 1){
            $article = Article::model()->updateByPk($this->article,array('yes'=>($numClients-1)));
        } else {
            $article = Article::model()->updateByPk($this->article,array('no'=>($numClients-1)));
        }
		
		return true;
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Vote the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
