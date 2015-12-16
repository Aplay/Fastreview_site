<?php

/**
 * Base class for all admin controllers.
 */
class SController extends RController
{

    public $layout='//layouts/locator';

    /**
     * Top menu
     * @var array
     */
    public $menu=array();

    /**
     * @var array
     */
    public $breadcrumbs=array();

    /**
     * @var string
     */
    public $pageHeader = '';

    public $pageTitle = '';

    /**
     * Buttons to display
     * @var null array
     */
    public $topButtons = null;
    /**
     * @var string path for main menu PixelAdmin
     */
    public $active_link = '';
 

    /**
     * Initialize component
     */
    public function init()
    {
        $this->pageTitle = Yii::app()->name;
    }

    /**
     * @return array
     */
  /*  public function filters()
    {
        return array('rights');
    }
    */

    /**
     * @return bool
     */
    public function beforeAction($action)
    {
      
        // Allow only authorized users access
       if (Yii::app()->user->isGuest){
            Yii::app()->user->logout();
            Yii::app()->request->redirect($this->createAbsoluteUrl('/'));
        } 
        
                
        return true;
    }



    /**
     * Set flash messages.
     *
     * @param string $message
     */
    public function setFlashMessage($message)
    {
        $currentMessages = Yii::app()->user->getFlash('messages');

        if (!is_array($currentMessages))
            $currentMessages = array();

        Yii::app()->user->setFlash('messages', CMap::mergeArray($currentMessages, array($message)));
    }

}
