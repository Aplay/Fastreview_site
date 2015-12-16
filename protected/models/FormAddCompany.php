<?php
/**
 * Форма обратной связи
 *
 * @version GIT: $Id$
 * @revision: $Revision$
 */
class FormAddCompany extends Orgs
{

	public $reCaptcha;
	public $cats;
	public $site;
	public $tempphones;
	public $plan;
	public $yourname;
	public $yourphone;
	public $youremail;
	public $promo;



    /**
     * @return type Правила валидации атрибутов
     */
	public function rules()
	{

		$r =  array(

			array('title, address, rubrictext', 'required', 'on' => 'step1'),
			array('title, address, rubrictext, yourname, yourphone, youremail', 'required', 'on' => 'step2'),
			array('title, address, rubrictext, yourname, yourphone, youremail, plan', 'required', 'on' => 'step3'),
			array('title, address, tempphones, site, cats, description, rubrictext, vkontakte, twitter, yourname, yourphone, youremail', 'filter', 'filter' => 'strip_tags'),
            array('title, address, tempphones, site, cats, description, rubrictext, vkontakte, twitter, yourname, yourphone, youremail', 'filter','filter' =>'trim'),
         //   array('street, dom', 'filter', 'filter'=>array( $this, 'removeCommas' )),
			array('city_id, author, lasteditor, status_org, plan', 'numerical', 'integerOnly'=>true),
			array('title, address, tempphones, site, cats, youremail, street, dom, email, vkontakte, facebook, twitter, instagram, youtube, promo', 'length', 'max'=>255),
			array('yourname', 'length', 'max' => 255, 'min' => 3, 'tooShort' => 'Имя мин. 3 симв.', 'tooLong' => 'Имя макс. 255 симв.', 'on' => 'step2'),
			array('yourphone', 'length', 'max' => 255, 'min' => 3, 'tooShort' => 'Телефон мин. 3 симв.', 'tooLong' => 'Телефон макс. 255 симв.', 'on' => 'step2'),
			array('email, youremail', 'email', 'on' => 'step2'),
			array('verified','boolean'),
			array('description, rubrictext, address_comment, lat, lng', 'safe'),
			array('site, vkontakte, facebook, twitter, instagram, youtube', 'url', 'validateIDN'=>true, 'defaultScheme'=>'http'),
            array('phones, phone_comments, categories_ar, http, http_comments, area, region, district', 'safe'),

			// array('reCaptcha', 'ReCaptchaValidator',  'secret'=>Yii::app()->reCaptcha->secret, 'message'=>'Неправильный код проверки'),
		);
	/*	if (Yii::app()->user->isGuest) {
			if (!($this->reCaptcha = Yii::app()->request->getPost(ReCaptchaValidator::CAPTCHA_RESPONSE_FIELD))) {
            	$r[] = array('reCaptcha', 'required');
        	}
        } */
        return $r;
	}

    /**
     * @return array Метки атрибутов (name=>label)
     */
	public function attributeLabels()
	{
		return array(
			'title'=>'Название',
			'address'=>'Адрес',
			'reCaptcha'=>'Код проверки',
			'tempphones'=>'Телефон',
			// 'cats'=>'Вид деятельности',
			'rubrictext'=>'Вид деятельности',
			'site'=>'Сайт',
			'description'=>'Описание',
			'vkontakte'=>'Vkontakte',
			'twitter'=>'Twitter',
			'facebook'=>'Facebook',
			'instagram'=>'Instagram',
			'youtube'=>'Youtube',
			'youremail'=>'Ваш E-mail',
			'yourphone'=>'Ваш телефон',
			'yourname'=>'Ваше имя',
			'plan'=>'Тариф'
		);
	}
}