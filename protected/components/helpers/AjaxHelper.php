<?php
/**
 * Класс AjaxHelper
 * Класс для удобной работы с аяксом
 * @author: Konstantin Perminov (SpiLLeR) konstantin.perminov@gmail.com
 */
class AjaxHelper {

    /**
     * Возвращает JSON с статусом = 0 и сообщение
     * @static
     * @param string $message
     * @param mixed $data
     * @param bool $endOfApplication
     * @param string $categoryTranslate
     * @return string JSON
     */
    public static function error($message, $data = null, $endOfApplication = true,  $categoryTranslate = 'ajax') {
        $result = array('status'=>0,'alertType'=>'error');

        if(is_array($message)) {
            $messages = array();
            foreach($message as $item)
                $messages[] = t($categoryTranslate, $item);
            $result['message'] = $messages;
        } else {
            $result['message'] = t($categoryTranslate, $message);
        }

        if($data !== null) {
            if(!is_array($data))
                $data = (array)$data;
            $result = array_merge($result, $data);
        }

        echo CJSON::encode($result);

        if($endOfApplication)
            Yii::app()->end();
    }

    /**
     * Возвращает JSON с статусом = 1 и сообщение
     * @static
     * @param string $message
     * @param mixed $data
     * @param bool $endOfApplication
     * @param string $categoryTranslate
     * @return void
     */
    public static function success($message, $data = null, $endOfApplication = true, $categoryTranslate = 'ajax') {
        $result = array('status'=>1,'alertType'=>'success');

        if(is_array($message)) {
            $messages = array();
            foreach($message as $item)
                $messages[] = t($categoryTranslate, $item);
            $result['message'] = $messages;
        } else {
            $result['message'] = t($categoryTranslate, $message);
        }

        if($data !== null) {
            if(!is_array($data))
                $data = (array)$data;
            $result = array_merge($result, $data);
        }

        echo CJSON::encode($result);

        if($endOfApplication)
            Yii::app()->end();
    }

    /**
     * Проверяет является ли запрос AJAX'ом
     * @static
     * @return void
     */
    public static function isAjaxRequest() {
        if(!Yii::app()->request->isAjaxRequest) {
            echo t('AlertMessage', 'I think this zone for ajax, true?!');
            Yii::app()->end();
        }
    }
}