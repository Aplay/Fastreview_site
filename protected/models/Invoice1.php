<?php
class Invoice extends CActiveRecord {

    public function tableName() {
        return 'invoice';
    }

    public function rules() {
        return [
            ['user_id, amount, description', 'required'],
            ['description, created_at, paid_at', 'length', 'max'=>200],
        ];
    }

    public function relations() {
        return [
            'user' => [self::BELONGS_TO, 'User', 'user_id'],
        ];
    }

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }
}
?>