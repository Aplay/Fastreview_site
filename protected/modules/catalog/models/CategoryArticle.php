<?php

/**
 * This is the model class for table "category_spec".
 *
 * The followings are the available columns in table 'category_spec':
 * @property string $id
 * @property string $title
 * @property integer $status_id
 * @property string $created_date
 * @property integer $parent_id
 */
class CategoryArticle extends CActiveRecord {
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_CLOSED = 2;
    const STATUS_ARCHIVE = 3;

    public $orgs_count;
    public $orgs_count_not_published;
    public $tmpLogotip;


    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'category_article';
    }
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Category the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title', 'required'),
            array('title,  url', 'filter', 'filter' => 'strip_tags'),
            array('title, url', 'filter','filter' =>'trim'),
            array('status_id,  parent_id', 'numerical', 'integerOnly' => true),
            array('title','unique', 'message'=>'Рубрика с таким названием уже существует.'),
            array('level, lft, rgt', 'numerical', 'integerOnly' => true),
          //  array('url, title', 'checkIfAvailable'),
            array('onmain','boolean'),
            array('icon, url, description', 'length', 'max'=>255),
            array('root', 'default', 'value'=>null),
            array('title, url', 'length', 'max'=>255),
            array('created_date', 'default', 'value' => date('Y-m-d H:i:s'), 'setOnEmpty' => true, 'on' => 'insert'),
            array('updated_date', 'default', 'value' => '0000-00-00 00:00:00', 'setOnEmpty' => true, 'on' => 'insert'),
            
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, title, url, root, lft, rgt, level, status_id, created_date, updated_date, parent_id, orgs_count, orgs_count_not_published', 'safe', 'on' => 'search'),
        );
    }
    public function behaviors() {
        return array(
     
            'tree' => array(
                'class' => 'ext.trees.ENestedSetBehavior',
                'hasManyRoots'=>true,
                'leftAttribute'=>'lft',
                'rightAttribute'=>'rgt',
                'levelAttribute'=>'level',
            ),
     
        );
    }
    

    public function scopes() {
        $alias = $this->owner->getTableAlias();
        return array(
            'active' => array(
                'condition' => $alias.'.status_id = '.self::STATUS_ACTIVE,
            ),
            'notdeleted' => array(
                'condition' => $alias.'.status_id != '.self::STATUS_DELETED,
            ),


        );
    }


    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'countOrgs' => array(self::STAT, 'ArticleCategory', 'article'),
            'categorization' => array(self::HAS_MANY, 'ArticleCategory', 'category'),
            'organizations'      => array(self::HAS_MANY, 'Article',array('article'=>'id'), 'through'=>'categorization'),
            'parent'=>array(self::BELONGS_TO, 'CategoryArticle', 'parent_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'title' => Yii::t('site','Title'),
            'status_id' => Yii::t('site','Status'),
            'created_date' => Yii::t('site','Created date'),
            'lft'              => 'Lft',
            'rgt'              => 'Rgt',
            'level'            => Yii::t('site', 'Level'),
            'url'              => 'Название для урл',
            'parent_id' => 'Предок',
            'onmain'=>'На главной',
            'icon'=>'Иконка',
            'description'=>'Описание'
        );
    }
  
    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search($additionalCriteria = null) {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;


        if($additionalCriteria !== null)
            $criteria->mergeWith($additionalCriteria);

        if($this->id)
            $this->id = (int)$this->id;
        $criteria->compare('t.id', $this->id);
        $criteria->compare('LOWER(t.title)',MHelper::String()->toLower($this->title),true);
        $criteria->compare('t.lft', $this->lft);
        $criteria->compare('t.rgt', $this->rgt);
        $criteria->compare('t.status_id', $this->status_id);
        $criteria->compare('t.created_date', $this->created_date, true);
        $criteria->compare('t.level',$this->level);
        $criteria->compare('t.url',$this->url,true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort'       => CategoryArticle::getCSort(),
            'pagination' => array(
                'pageSize' => 50,
            ),
        ));
    }

   /**
     * @return CSort to use in gridview, listview, etc...
     */
    public static function getCSort()
    {
        $sort = new CSort;
        $sort->defaultOrder = 't.title';
        $sort->attributes=array(
            
            'id' => array(
                'asc'   => 't.id',
                'desc'  => 't.id DESC',
            ),
           'title' => array(
                'asc'   => 't.title',
                'desc'  => 't.title DESC'
            ),
           'orgs_count'=>array(
                'asc' => 'orgs_count ASC',
                'desc' => 'orgs_count DESC',
            ),
           'orgs_count_not_published'=>array(
                'asc' => 'orgs_count_not_published ASC',
                'desc' => 'orgs_count_not_published DESC',
            ));
          

        return $sort;
    }
   
    protected function checkUniqueUrl($unique){
        // Check if url available
            if($this->isNewRecord)
            {
                $test = CategoryArticle::model()
                    ->withUrl($unique)
                    ->count();
            }
            else
            {
                $test = CategoryArticle::model()
                    ->withUrl($unique)
                    ->count('id!=:id', array(':id'=>$this->id));
            }
            return $test;
    }

    protected function beforeSave() {
        if(parent::beforeSave()) {

            // Create slug
            if(!$this->url){
                Yii::import('ext.SlugHelper.SlugHelper');
                $this->url = SlugHelper::run($this->title, 'yandex');
            }

            $unique = $this->url;

            $suffix = 1;

            while ($this->checkUniqueUrl($unique) > 0){
                $unique = $this->url.$suffix;
                $suffix++;
            }
            $this->url =  $unique;
            
            if($this->isNewRecord) {
                $this->status_id = self::STATUS_ACTIVE;
            } 
            $this->updated_date = date('Y-m-d H:i:s');
            return true;
        } else
            return false;
    } 


    public static function getActiveCategories() {
        return CHtml::listData(self::model()->active()->findAll(), 'id', 'title');
    }

    // this method is working. on parent_id base. very fast // 0.6sec
    public static function getRubsByParentId()
    {
       /* Выводятся только рубрики, если имеется parent */
       $connection=Yii::app()->db;
       $sql = 'SELECT DISTINCT t.category, cat.id, cat.title, cat.url, cat.parent_id
       FROM article_category t 
       LEFT OUTER JOIN category_article cat 
       ON (cat.id=t.category) 
       ORDER BY title';
       $dependency = new CDbCacheDependency('SELECT MAX(updated_date) FROM article');
       $command = $connection->cache(3600, $dependency)->createCommand($sql);
       $cats = $command->queryAll();

        $rubs = array();
        if($cats){
            foreach ($cats as $key => $row) {
                $parent_id = (int)$row['parent_id'];
                $rubs[$parent_id][]   = $row;
            }
        }
        

        $output =  array();

        if($rubs)
        {
            foreach($rubs as $key=>$value)
            {
                
                    $cat = CategoryArticle::model()->findByPk($key);

                     if($cat)
                        $output[$key] = array('id'=>$key,'title'=>$cat->title,'url'=>$cat->url,'par'=>$cat->parent_id);
                     else 
                        $output[$key] = array('id'=>$key,'title'=>'','url'=>'','par'=>0);
                     $cnt = 0;
                        for ($i=0;$i<sizeof($value);$i++)
                        {
                            if ($value[$i]['title']!=''){

                                $output[$key]['items'][] = array('id'=>$value[$i]['id'],'title'=>$value[$i]['title'],'url'=>$value[$i]['url']);
                            }
                        }
                    
                
                
            }
            
        }
        $uns = array();
        if($output){
            foreach($output as $k=>$v){
                if(!empty($v['par'])){
                    if(isset($output[$v['par']]['items'])){
                        $output[$v['par']]['items'][] = $v;
                        $sorted =  $output[$v['par']]['items'];
                        usort($sorted, MHelper::get('Array')->sortFunction('title'));
                        $output[$v['par']]['items'] = $sorted;
                        unset($output[$k]);
                    }
                }
                if($k==0 && !empty($v['items'])){
                    foreach ($v['items'] as $key => $value) {
                        foreach($output as $k2=>$v2){
                                if($value['id'] == $v2['id']){
                                    $uns[] = $key;
                                    break;
                                }
                        }
                    }
                    
                }
            }
           
            if(!empty($uns)){
                foreach ($uns as $v) {
                    unset($output[0]['items'][$v]);
                }
            }
            usort($output, MHelper::get('Array')->sortFunction('title'));
        }

        return $output;
    }
    /**
      * Получает конечные активные рубрики, в которых имеются активные фирмы по городу
      * @param $city_id int
      * @return array
      */
    public static function getRubs($city_id, $descendantsroot = null, $query = null, $root_id = null, $depth=null, $except = null)
    {
        $connection=Yii::app()->db;
        $sql = 'SELECT DISTINCT "t"."id" AS "id", "t"."title" AS "title", "t"."url" AS "url"
                FROM "category_article" "t" 
                LEFT OUTER JOIN "article_category" "categorization" 
                ON ("categorization"."category"="t"."id") 
                LEFT OUTER JOIN "article" "organizations" 
                ON ("categorization"."org"="organizations"."id") 
                WHERE (t.status_id = 1) AND (categorization.category=t.id) AND (organizations.city_id='.$city_id.') AND (organizations.status_org = '.Article::STATUS_ACTIVE.')';
        if($descendantsroot){
           $sql .=   " AND (t.lft > {$descendantsroot->lft}) AND (t.rgt < {$descendantsroot->rgt} AND (t.root = {$descendantsroot->root}))";  
            if($depth){
                $sql .=   " AND (t.level <= {$descendantsroot->level} + ".$depth.") ";  
            }
        }
        if($root_id){
            $sql .=   " AND (t.id={$root_id})";
        }
        if($query){
           $query = MHelper::String()->toLower($query);
           $query = addcslashes($query, '%_'); // escape LIKE's special characters
           $sql .=   " AND (LOWER(t.title) LIKE '%$query%')";
        }
        if($except){
            $sql .=   " AND (t.id!={$except})";
        }
        $sql .=      ' ORDER BY t.title ';
        $command=$connection->cache(4000)->createCommand($sql);
        $rows=$command->queryAll();
        return $rows;

    }

    protected function beforeDelete() {
        parent::beforeDelete();
        $this->getDeleteFileFolder();
        return true;
    }
    protected function getDeleteFileFolder() {
        $folder = Yii::getPathOfAlias('webroot').'/uploads/category_article/'.$this->id;
        if (is_dir($folder) == true)
            CFileHelper::removeDirectory($folder);
           // rmdir($folder);
        return true;
    }
    
    public function getFileFolder()
    {
        $folder = Yii::getPathOfAlias('webroot').'/uploads/category_article/';
        if (is_dir($folder) == false)
            @mkdir($folder, octdec(Yii::app()->params['storeImages']['dirMode']), true);
       
        $folder = Yii::getPathOfAlias('webroot').'/uploads/category_article/'.$this->id.'/';
        if (is_dir($folder) == false)
            @mkdir($folder, octdec(Yii::app()->params['storeImages']['dirMode']), true);
        return $folder;
    }

    public function getOrigFilePath() {
        return Yii::app()->baseUrl.'/uploads/category_article/'.$this->id.'/';
    }
    public function getViewUrl()
    {
        return Yii::app()->createAbsoluteUrl('/catalog/article/view', array( 'url'=>$this->url));
    }
    public function withUrl($url, $alias = 't')
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => $alias.'.url=:url',
            'params'    => array(':url'=>$url)
        ));
        return $this;
    }

    public function withUrls($url, $alias = 't')
    {

         $criteria=new CDbCriteria;
         $criteria->addInCondition($alias.'.url', $url);

        $this->getDbCriteria()->mergeWith($criteria);
        return $this;
    }

    /**
     * Get all categories list to display in dropdown.
     * @param type $excludeId Exclude self model
     * @return array id=>name
     */
    public static function keyValueList($root = 1)
    {
        $items=CategoryArticle::model()->findByPk($root)->asTreeArray();
        return $items;
    }
    protected $_categoryTree=array();
    protected $_categoryFlat=array();

    public function getCategoryTree($format) // for Adjacency List structure with parent_id
    {
        $categories=self::model()->findAll(array('order'=>'position'));
        foreach($categories as $category){
            if($category->parent_id==null) $category->parent_id=0;
            $this->_categoryTree[$category->parent_id][$category->id]=$category->title;
        }

        switch($format){
        case 'flat':
            $this->level2Label();
            return $this->_categoryFlat;
            break;
        case 'tree':
            return $this->formatTree();
            break;
        }
    }

    protected function level2Label($parent_id=0,$level=0)
    {
        foreach($this->_categoryTree[$parent_id] as $key=>$val){
            $this->_categoryFlat[$key]=str_repeat('&nbsp;',4*$level).$val;
            if(isset($this->_categoryTree[$key]) && $key>0)
                $this->level2Label($key,$level+1);
        }
    }

    protected function formatTree($parent_id=0)
    {
        $data=array();
        foreach($this->_categoryTree[$parent_id] as $key=>$val){
            $children=isset($this->_categoryTree[$key])?$this->formatTree($key):null;
            $expand=$children?true:false;
            $data[]=array('id'=>$key,'title'=>$val,'icon'=>false,'expand'=>$expand,'children'=>$children);
        }
        return $data;
    }

    public function checkIfAvailable($attr)
    {
        $labels = $this->attributeLabels();
        $check = CategoryArticle::model()->countByAttributes(array(
            $attr=>$this->$attr,
        ), 't.id != :id', array(':id'=>(int)$this->id));

        if($check>0)
            $this->addError($attr, $labels[$attr].' '.Yii::t('site', 'is occupied'));
    }

    /* methods for tree ext */
    /**
     * Build tree-like array for display in DropDownList
     * using in admin panel
     * @static
     * @param bool $canSelectNonLeaf can user select category that have children
     * @return string[]
     */
    public static function TreeArray($canSelectNonLeaf = true)
    {
        if ($canSelectNonLeaf)
            return self::TreeArrayLeafCanSelected();
        else
            return self::TreeArrayLeafCannotSelected();
    }

    /**
     * Build tree-like array for display in DropDownList
     * Categories that have children cannot be selected
     * using in admin panel
     * @static
     * @return string[]
     */
    public static function TreeArrayLeafCannotSelected()
    {
        $roots = self::model()->roots()->findAll();
        $res = array();
        foreach ($roots as $root) {
            if ($root->isLeaf())
                $res[$root->id] = $root->title;
            else
                $res[$root->title] = self::GetChildrenForTreeArrayLeafCannotSelected($root);
        }

        return $res;
    }

    /**
     * Build tree-like array for the category
     * Categories that have children cannot be selected
     * @static
     * @param Category $elem the category for which builds array
     * @return string[]
     */
    private static function GetChildrenForTreeArrayLeafCannotSelected($elem)
    {
        $res = array();
        $roots = $elem->children()->findAll();
        foreach ($roots as $root) {
            if ($root->isLeaf())
                $res[$root->id] = $root->title;
            else
                $res[$root->title] = self::GetChildrenForTreeArrayLeafCannotSelected($root);
        }
        return $res;
    }

    /**
     * Build tree-like array for display in DropDownList
     * Categories that have children can be selected
     * using in admin panel
     * @static
     * @return string[]
     */
    public static function TreeArrayLeafCanSelected()
    {
        $roots = self::model()->roots()->findAll();
        $res = array();
        foreach ($roots as $root) {
            $res[$root->id] = $root->GetStringName();
            if (!$root->isLeaf())
                $res = $res + self::GetChildrenForTreeArrayLeafCanSelected($root, 1);
        }

        return $res;
    }

    public static function TreeArrayActive($sign = '&nbsp')
    {
   
        $roots = self::model()
        ->active()
        ->roots()->findAll(array('order'=>'title'));
        
        $res = array();
        foreach ($roots as $root) {
            $res[$root->id] = $root->GetStringName($sign);
            if (!$root->isLeaf())
                $res = $res + self::GetChildrenForTreeArrayLeafCanSelected($root, 1, $sign);
        }

        return $res;
    } 

    /**
     * Build tree-like array for the category
     * Categories that have children can be selected
     * @static
     * @param Category $elem the category for which builds array
     * @param integer $i nesting level
     * @return string[]
     */
    private static function GetChildrenForTreeArrayLeafCanSelected($elem, $i, $sign = '&nbsp')
    {
        $res = array();
        $roots = $elem->children()->findAll(array('order'=>'title'));
        foreach ($roots as $root) {
            $res[$root->id] = $root->GetStringName($sign);
            if (!$root->isLeaf())
                $res = $res + self::GetChildrenForTreeArrayLeafCanSelected($root, $i + 1, $sign);
        }
        return $res;
    }

    /**
     * Return category name for dropDownList with spaces before it for tree-like visual view
     * @return string category name with spaces
     */
    public function GetStringName($sign = '&nbsp')
    {
        if ($this->isLeaf())
            return str_repeat($sign, ($this->level - 1) * 4) . $this->title;
        else
            return str_repeat($sign, ($this->level - 1) * 4) . "<b>" . $this->title . "</b>";
    }

    /**
     * @return Attr [] category attributes
     */
    public function GetCategoryAttributes()
    {
        $attrs = array();
        foreach ($this->attrGroups as $group) {
            foreach ($group->attrs as $attr) {
                $attrs[] = $attr;
            }
        }

        return $attrs;
    }

    public function sendMessage($email, $subject= '', $message = '')
    {
        $mailer           = Yii::app()->mail;
        $mailer->From     = 'noreply@'.Yii::app()->request->serverName;
        $mailer->FromName = Yii::app()->name;
        $mailer->Subject  = CHtml::encode($subject);
        $mailer->Body     = CHtml::encode($this->emailmessage);
        $mailer->AddAddress($email);
        $mailer->Send();
    }


}