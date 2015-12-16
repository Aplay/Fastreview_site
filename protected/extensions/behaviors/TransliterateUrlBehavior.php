<?php
/**
 * Класс TransliterateUrlBehavior
 * Выполняет транслит из названия поля
 * @author Belyakov Yuriy http://belyakov.su
 * @link https://github.com/neo-classic/trnaslitUrlBehavior
 *
 * Подключение:
 *
 * public function behaviors() {
 *     return array(
 *         'transliterateUrlBehavior' => array(
 *             'class' => 'application.components.TransliterateUrlBehavior',
 *          ),
 *     );
 * }
 */
class TransliterateUrlBehavior extends CActiveRecordBehavior {
    /**
     * Имя поля, из которого получаем translit
     * @var string
     */
    public $sender = 'title';

    /**
     * Имя поля, которое получает translit
     * @var string
     */
    public $recipient = 'url';

    public function beforeSave($event) {
        if($this->owner->isNewRecord) {
            $existUrl = $this->owner->exists("{$this->recipient} = :url", array(':url' => $this->transliterate($this->owner{$this->sender})));
            if($existUrl) {
                $this->owner->{$this->recipient} = $this->transliterate($this->owner->{$this->sender}).'_'.time();
            } else {
                $this->owner->{$this->recipient} = $this->transliterate($this->owner->{$this->sender});
            }
        } elseif(isset($this->owner->{$this->recipient})) {
            $existUrl = $this->owner->exists("{$this->recipient} = :url", array(':url' => $this->transliterate($this->owner{$this->recipient})));
            if(!$existUrl) {
                $this->owner->{$this->recipient} = $this->transliterate($this->owner->{$this->recipient});
            }
        }
    }

    /**
     * Возвращает transliterate(строка)
     * @param $str
     * @return string
     */
    public function transliterate($str) {
        $translit = array(
            'А' => 'a', 'Б' => 'b', 'В' => 'v', 'Г' => 'g', 'Д' => 'd', 'Е' => 'e', 'Ё' => 'yo', 'Ж' => 'zh', 'З' => 'z',
            'И' => 'i', 'Й' => 'i', 'К' => 'k', 'Л' => 'l', 'М' => 'm', 'Н' => 'n', 'О' => 'o', 'П' => 'p', 'Р' => 'r',
            'С' => 's', 'Т' => 't', 'У' => 'u', 'Ф' => 'f', 'Х' => 'h', 'Ц' => 'ts', 'Ч' => 'ch', 'Ш' => 'sh', 'Щ' => 'sch',
            'Ъ' => '', 'Ы' => 'y', 'Ь' => '', 'Э' => 'e', 'Ю' => 'yu', 'Я' => 'ya',
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh', 'з' => 'z',
            'и' => 'i', 'й' => 'i', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r',
            'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'ts', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
            'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
            ' ' => '-', '!' => '', '?' => '', '('=> '', ')' => '', '#' => '', ',' => '', '№' => '',' - '=>'-','/'=>'-', ' '=>'-',
            'A' => 'a', 'B' => 'b', 'C' => 'c', 'D' => 'd', 'E' => 'e', 'F' => 'f', 'G' => 'g', 'H' => 'h', 'I' => 'i', 'J' => 'j', 'K' => 'k', 'L' => 'l', 'M' => 'm', 'N' => 'n',
            'O' => 'o', 'P' => 'p', 'Q' => 'q', 'R' => 'r', 'S' => 's', 'T' => 't', 'U' => 'u', 'V' => 'v', 'W' => 'w', 'X' => 'x', 'Y' => 'y', 'Z' => 'z'
        );
        return strtr($str, $translit);
    }
}