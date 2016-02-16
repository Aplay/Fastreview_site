<?php

/**
 * Class EavOptionsRender
 */
class EavOptionsRender
{
    /**
     * @param $attribute
     * @param null $value
     * @param null $name
     * @param array $htmlOptions
     * @return mixed|null|string
     */
    public static function renderField($attribute, $value = null, $name = null, $htmlOptions = [])
    {
        $name = $name ?: 'EavOptions['.$attribute->id.']';
        switch ($attribute->type) {
            case EavOptions::TYPE_TEXT:
                return CHtml::textField($name, $value, $htmlOptions);
                break;
         /*   case EavOptions::TYPE_TEXT:
                return Yii::app()->getController()->widget(
                    Yii::app()->getModule('store')->getVisualEditor(),
                    [
                        'name' => $name,
                        'value' => $value,
                    ],
                    true
                );
                break;*/
            case EavOptions::TYPE_DROPDOWN:
                $data = CHtml::listData($attribute->options, 'id', 'value');

                return CHtml::dropDownList($name, $value, $data, array_merge($htmlOptions, (['empty' => '---'])));
                break;
            case EavOptions::TYPE_CHECKBOX_LIST:
                $data = CHtml::listData($attribute->options, 'id', 'value');

                return CHtml::checkBoxList($name.'[]', $value, $data, $htmlOptions);
                break;
        /*  case EavOptions::TYPE_CHECKBOX:
                return CHtml::checkBox($name, $value, CMap::mergeArray(['uncheckValue' => 0], $htmlOptions));
                break;
            case EavOptions::TYPE_NUMBER:
                return CHtml::numberField($name, $value, $htmlOptions);
                break;
            case EavOptions::TYPE_IMAGE:
                return CHtml::fileField($name, null, $htmlOptions);
                break; */
        }

        return null;
    }

    /**
     * @param $attribute
     * @param $value
     * @return string
     */
    public static function renderValue($attribute, $value)
    {
        $unit = $attribute->unit ? ' '.$attribute->unit : '';
        $res = '';
        switch ($attribute->type) {
            case EavOptions::TYPE_TEXT:
         //   case EavOptions::TYPE_SHORT_TEXT:
          /*  case EavOptions::TYPE_NUMBER:
                $res = $value;
                break; */
            case EavOptions::TYPE_DROPDOWN:
                $data = CHtml::listData($attribute->options, 'id', 'value');
                if (!is_array($value) && isset($data[$value])) {
                    $res = $data[$value];
                }
                break;
            case EavOptions::TYPE_CHECKBOX:
                $res = $value ? Yii::t("StoreModule.store", "Yes") : Yii::t("StoreModule.store", "No");
                break;
            case EavOptions::TYPE_CHECKBOX_LIST:
                $data = CHtml::listData($attribute->options, 'id', 'value');
                $result = array();

                if(!is_array($value))
                    $value = array($value);

                foreach($data as $key=>$val)
                {
                    if(in_array($key, $value))
                        $result[] = $val;
                }
                return implode(', ', $result);
                break;
        }

        return $res.$unit;
    }
}