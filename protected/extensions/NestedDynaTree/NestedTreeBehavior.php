<?php

/**
 * NestedTreeBehavior class file.
 *
 * @author Szincsák András <andras@szincsak.hu>
 */

/**
 * Provides nested tree functionality for a model.
 *
 */
class NestedTreeBehavior extends CActiveRecordBehavior {

    public $titleAttribute = 'name';
    public $seller = 0;

    /**
     * Get tree items in array format
     * @param string $url url to item href
     * @return array In tree format.
     */
    public function getTree($url="") {
        $owner = $this->getOwner();
        $models = $owner::model()->findAll(array('order' => $owner->leftAttribute));
        if (count($models) == 0)
            throw new CDbException(Yii::t('tree', 'There must be minimum one root record in model `' . $owner->className . '`'));
        $data = $this->loadTree($models, $url);
        return ($data['data']);
    }
    public function getTreeSeller($url="", $seller = 0) {
        $this->seller = $seller;
        $owner = $this->getOwner();
        $category_main = $owner::model()->find(array('condition'=>'id=2'));
        $models=$category_main->children()->findAll(array('order' => $owner->leftAttribute));
       // $models = $owner::model()->findAll(array('order' => $owner->leftAttribute, 'condition'=>'id=2'));
        if (count($models) == 0)
            throw new CDbException(Yii::t('tree', 'There must be minimum one root record in model `' . $owner->className . '`'));
        $data = $this->loadTree($models, $url);
        return ($data['data']);
    }
    /**
     * Load items to an array recrusively
     * @param CActiveRecord  $models
     * @param string $url url to item href
     * @param array $NodeArray the recrusive array
     * @return array of the item 
     */
    private function loadTree($models, $url="", $NodeArray=null) {
        $NodeArray['count'] = count($models);
        unset($NodeArray['data']);
        // get all seller categories 
        $selection = ShopsCategories::getIds('cat_id','shop_id',$this->seller);
        foreach ($models as $i => $node) {
            if (!isset($NodeArray['pk']) || !in_array($node->primaryKey, $NodeArray['pk'])) {
                $NodeArray['pk'][] = $node->primaryKey;
                $NodeArray['data'][$i] = array(
                    'key' => $node->primaryKey,
                    'id' => $node->primaryKey,
                    'title' => $node->nodeTitle,
                    'tooltip' => $node->nodeTitle,

                );
                if(!empty($selection)){
                    if(in_array($node->primaryKey,$selection)){
                       $NodeArray['data'][$i]['select'] = true;
                    }
                }

                if ($url && !$node->isRoot())
                    $NodeArray['data'][$i]['href'] = $url . $node->primaryKey;
                if (!$node->isLeaf()) {
                    $inter = $this->loadTree($node->children()->findAll(), $url, $NodeArray);
                    $NodeArray['pk'] = $inter['pk'];
                    $NodeArray['data'][$i]['isFolder'] = 'true';
                    $NodeArray['data'][$i]['children'] = $inter['data'];

                }
                if ($node->isRoot()){
                    $NodeArray['data'][$i]['isFolder'] = 'true';

                }

               
            }
        }

        return $NodeArray;
    }

    /**
     * Generate the next value of the prefixed attribute 
     * 
     * @param string $attribute the name of attribute
     * @param string $prefix the prefix of the value
     * @return string the next value of the prefixed attribute 
     */
    public function getNextMaxValue($attribute='name', $prefix='new_') {
        $SSQL = "SELECT MAX(" . $attribute . ") FROM " . $this->tableName() . " WHERE " . $attribute . " like '$prefix%'";
        $value = Yii::app()->db->createCommand($SSQL)->queryScalar();
        $parts = explode($prefix, $value);
        $value = (isset($parts[1])) ? intval($parts[1]) : 0;
        return $prefix . ($value + 1);
    }

    /**
     * Get node titlte by titleAttribute property
     * @return string the title of the item 
     */
    public function getNodeTitle() {
        $owner = $this->getOwner();
        return $owner->{$this->titleAttribute};
    }

}