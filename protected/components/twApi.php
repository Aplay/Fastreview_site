<?php
class twApi extends CApplicationComponent
{
    public $passUrl;
    public $redirectTo = '/';


    public function getAuthUrl($redirectTo = null)
    {
        if($redirectTo)
            $this->redirectTo = $redirectTo;
        Yii::app()->session['socredirect'] = $this->redirectTo;
        
        return $this->passUrl;
    }

}

?>