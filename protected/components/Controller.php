<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController {

    public $pageTitle = '';
    public $pageDescription = '';

    public $pageKeywords = '';

    /**
     * @var string
     */
    public $pageAuthor = '';

    /**
     * @var string
     */
    private $_pageTitle;
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/fastreview';
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();
    /**
     * @var string path for main menu PixelAdmin
     */
    public $active_predicate = '';
    /**
     * @var string path for main menu PixelAdmin
     */
    public $active_link = '';


    public $city;

    /**
     * Initialize component
     */
    public function init()
    {
        parent::init();
        $this->pageTitle = Yii::app()->name;
        $this->pageDescription = Yii::app()->name;
    }
    /**
     * Set layout and view
     * @param mixed $model
     * @param string $view Default view name
     * @return string
     */

    public function filters()
    {
        return array(
            'accessControl',
        );
    }  


    /**
     * @param $message
       // пример использования в контроллере:
       // $err = array('test1','test2'); // складываем ошибки в массив // можно доделать с использовать _addError как в контроллере orders/cart если запрос через ajax
       // Yii::app()->user->setFlash('messages_red', $err);
       // $this->addFlashMessage($model->getErrors(),'red'); // до кучи берем ошибки модели
       // Если была/были впереди ошибка/ошибки, показываем все
     */
    public  function addFlashMessage($message,$style='success')
    {
        

        $currentMessages = Yii::app()->user->getFlash('error'); 
        

        if (!is_array($currentMessages))
            $currentMessages = array($currentMessages);

        $map = new CMap($currentMessages);

        if(is_array($message)){
            foreach($message as $mes){
                if(is_array($mes)){
                    $map->mergeWith($mes);
                } else {
                    $map->mergeWith(array($mes));
                }
                
            }
            
        } else {
            $map->mergeWith(array($message));
        }
        
        $messages = $map->toArray();

        Yii::app()->user->setFlash($style, $messages);    
        
 
    }



     /**
     * Загрузка удаленных файлов
     */
    public static function getRemoteContents($remoteUrl)
    {
        if (empty($remoteUrl))
            return;

        if (ini_get('allow_url_fopen'))
            return @file_get_contents($remoteUrl);
        else {
            $ch = curl_init($remoteUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            curl_close($ch);
            return $data;
        } 

       /* $ch = curl_init();
                    // added
                    curl_setopt($ch, CURLOPT_URL, $remoteUrl);
                   // curl_setopt($ch, CURLOPT_POST, false);
                   // curl_setopt($ch, CURLOPT_BINARYTRANSFER, false);
                   // curl_setopt($ch, CURLOPT_HEADER, true);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $data = curl_exec($ch);
            curl_close($ch);
            // If HTTP response is not 200, throw exception
            if ($httpCode != 200) {

                //throw new Exception($httpCode);
                return false;
            }
            return $data;*/

    }
    
    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
   /* protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === $model->formId) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }*/
    protected function performAjaxValidation($model, $form)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']===$form)
        {

            $errors = CActiveForm::validate($model);
            
            if ($errors !== '[]') {
               echo $errors;
               Yii::app()->end();
            } 
        }
    }
}