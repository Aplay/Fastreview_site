<?php
/**
 * @link https://github.com/himiklab/yii2-recaptcha-widget
 * @copyright Copyright (c) 2014 HimikLab
 * @license http://opensource.org/licenses/MIT MIT
 */


/**
 * Yii2 Google reCAPTCHA widget.
 *
 * For example:
 *
 * ```php
 * <?= $form->field($model, 'reCaptcha')->widget(
 *  ReCaptcha::className(),
 *  ['siteKey' => 'your siteKey']
 * ) ?>
 * ```
 *
 * or
 *
 * ```php
 * <?= ReCaptcha::widget([
 *  'name' => 'reCaptcha',
 *  'siteKey' => 'your siteKey',
 *  'widgetOptions' => ['class' => 'col-sm-offset-3']
 * ]) ?>
 * ```
 *
 * @see https://developers.google.com/recaptcha
 * @author HimikLab
 * @package himiklab\yii2\recaptcha
 */
class ReCaptcha extends CInputWidget
{
    const JS_API_URL = 'https://www.google.com/recaptcha/api.js';

    const THEME_LIGHT = 'light';
    const THEME_DARK = 'dark';

    const TYPE_IMAGE = 'image';
    const TYPE_AUDIO = 'audio';

    /** @var string Your sitekey. */
    public $siteKey;

    /** @var string Your secret. */
    public $secret;

    /** @var string The color theme of the widget. [[THEME_LIGHT]] (default) or [[THEME_DARK]] */
    public $theme;

    /** @var string The type of CAPTCHA to serve. [[TYPE_IMAGE]] (default) or [[TYPE_AUDIO]] */
    public $type;

    public $size = 'normal';

    /** @var string Your JS callback function that's executed when the user submits a successful CAPTCHA response. */
    public $jsCallback;

    /** @var array Additional html widget options, such as `class`. */
    public $widgetOptions = array();

    /**
	 * @var array the HTML attributes for the widget container.
	 */
	public $htmlOptions = array();

    public $name;

    public function init()
    {
        if (!isset($this->htmlOptions['id']))
			$this->htmlOptions['id'] = $this->getId();

        if (empty($this->siteKey)) {
            if (!empty(Yii::$app->reCaptcha->siteKey)) {
                $this->siteKey = Yii::$app->reCaptcha->siteKey;
            } else {
                throw new Exception('Required `siteKey` param isn\'t set.');
            }
        }

    
    }

    /**
     * Registers necessary client scripts.
     */
  /*  public function registerClientScript()
    {
        parent::registerClientScript();
        $cs=Yii::app()->getClientScript();
        $cs->registerScriptFile(self::JS_API_URL . '?hl=' . $this->getLanguageSuffix(),CClientScript::POS_END);

    }*/

    public function run()
    {
    	
    	$id = $this->htmlOptions['id'];

        $this->customFieldPrepare();

        $divOptions = [
            'class' => 'g-recaptcha',
           // 'data-sitekey' => $this->siteKey,
            'id'=>'div_'.$id
        ];
          
        if (!empty($this->jsCallback)) {
            $divOptions['data-callback'] = $this->jsCallback;
        }
        if (!empty($this->theme)) {
            $divOptions['data-theme'] = $this->theme;
        }
        if (!empty($this->type)) {
            $divOptions['data-type'] = $this->type;
        }
        if (!empty($this->size)) {
            $divOptions['data-size'] = $this->size;
        }
        if (isset($this->widgetOptions['class'])) {
            $divOptions['class'] = "{$divOptions['class']} {$this->widgetOptions['class']}";
        }
        $divOptions = $divOptions + $this->widgetOptions;



        echo CHtml::openTag('div', $divOptions);
        echo CHtml::closeTag('div');

    }

    protected function getLanguageSuffix()
    {
        $currentAppLanguage = Yii::$app->language;
        $langsExceptions = ['zh-CN', 'zh-TW', 'zh-TW'];

        if (strpos($currentAppLanguage, '-') === false) {
            return $currentAppLanguage;
        }

        if (in_array($currentAppLanguage, $langsExceptions)) {
            return $currentAppLanguage;
        } else {
            return substr($currentAppLanguage, 0, strpos($currentAppLanguage, '-'));
        }
    }

    protected function customFieldPrepare()
    {

       if ($this->hasModel()) {

            $inputName = $this->getInputName($this->model, $this->attribute);
            $inputId = $this->getInputId($this->model, $this->attribute);
        } else {

            $inputName = $this->name;
           // $inputId = 'recaptcha-' . $this->name;
            $inputId = $this->htmlOptions['id'];
        }
        $jsCode = '';
        if (empty($this->jsCallback)) {
          //  $jsCode = "var recaptchaCallback_".$this->htmlOptions['id']." = function(response){jQuery('#{$inputId}').val(response);};";
            $jsCode = "var widgetId1;var widgetId2;var widgetId3;var widgetId4;var widgetId5;var widgetId6;var onloadCallback = function(){ 
            	if($('#div_recaptcha_feedback').length){
            	widgetId1 = grecaptcha.render('div_recaptcha_feedback', {
          					'sitekey' : '".$this->siteKey."'
        					}); 
				}
				if($('#div_recaptcha_comment').length){
				widgetId2 = grecaptcha.render('div_recaptcha_comment', {
          					'sitekey' : '".$this->siteKey."'
        					}); 
				}
				if($('#div_recaptcha_addphoto').length){
				widgetId3 = grecaptcha.render('div_recaptcha_addphoto', {
          					'sitekey' : '".$this->siteKey."'
        					}); 
				}
				if($('#div_recaptcha_feedback_update').length){
				widgetId4 = grecaptcha.render('div_recaptcha_feedback_update', {
          					'sitekey' : '".$this->siteKey."'
        					}); 
				}
				if($('#div_FormAddCompany_reCaptcha').length){
				widgetId5 = grecaptcha.render('div_FormAddCompany_reCaptcha', {
          					'sitekey' : '".$this->siteKey."'
        					}); 
				}
				if($('#div_recaptcha_comment_article').length){
				widgetId6 = grecaptcha.render('div_recaptcha_comment_article', {
          					'sitekey' : '".$this->siteKey."'
        					}); 
				}
				};";
        } else {
          //  $jsCode = "var recaptchaCallback_".$this->htmlOptions['id']." = function(response){jQuery('#{$inputId}').val(response); {$this->jsCallback}(response);};";
        }
      //  $this->jsCallback = 'recaptchaCallback';

        $cs=Yii::app()->getClientScript();
        $cs->registerScriptFile('https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit&hl=ru-RU', CClientScript::POS_END, array('async', 'defer'));
        $cs->registerScript('reCap'.$this->htmlOptions['id'], $jsCode, CClientScript::POS_END);

        echo CHtml::hiddenField($inputName, null, array('id' => $inputId));
    }

    /**
     * Generates an appropriate input name for the specified attribute name or expression.
     *
     * This method generates a name that can be used as the input name to collect user input
     * for the specified attribute. The name is generated according to the [[Model::formName|form name]]
     * of the model and the given attribute name. For example, if the form name of the `Post` model
     * is `Post`, then the input name generated for the `content` attribute would be `Post[content]`.
     *
     * See [[getAttributeName()]] for explanation of attribute expression.
     *
     * @param Model $model the model object
     * @param string $attribute the attribute name or expression
     * @return string the generated input name
     * @throws InvalidParamException if the attribute name contains non-word characters.
     */
    protected function getInputName($model, $attribute)
    {
        $formName = $model->formName();
        if (!preg_match('/(^|.*\])([\w\.]+)(\[.*|$)/', $attribute, $matches)) {
            throw new Exception('Attribute name must contain word characters only.');
        }
        $prefix = $matches[1];
        $attribute = $matches[2];
        $suffix = $matches[3];
        if ($formName === '' && $prefix === '') {
            return $attribute . $suffix;
        } elseif ($formName !== '') {
            return $formName . $prefix . "[$attribute]" . $suffix;
        } else {
            throw new Exception(get_class($model) . '::formName() cannot be empty for tabular inputs.');
        }
    }
    /**
     * Generates an appropriate input ID for the specified attribute name or expression.
     *
     * This method converts the result [[getInputName()]] into a valid input ID.
     * For example, if [[getInputName()]] returns `Post[content]`, this method will return `post-content`.
     * @param Model $model the model object
     * @param string $attribute the attribute name or expression. See [[getAttributeName()]] for explanation of attribute expression.
     * @return string the generated input ID
     * @throws InvalidParamException if the attribute name contains non-word characters.
     */
    protected function getInputId($model, $attribute)
    {
        $name = strtolower($this->getInputName($model, $attribute));
        return str_replace(['[]', '][', '[', ']', ' ', '.'], ['', '-', '-', '', '-', '-'], $name);
    }
}
