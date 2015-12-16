<?php

/**
 * This is the model class for table "category".
 *
 * The followings are the available columns in table 'category':
 * @property string $id
 * @property string $title
 * @property string $description
 * @property integer $status_id
 * @property string $author_id
 * @property string $created_date
 * @property string $updated_date
 * @property integer $parent_id
 */
class Category extends CActiveRecord {
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_CLOSED = 2;
    const STATUS_ARCHIVE = 3;

    public $orgs_count;
    public $orgs_count_not_published;
    public $tmpLogotip;

    private $_oldAttributes = array();

    private $_newRec = false;

    public function init()
    {
        $this->attachEventHandler("onAfterFind", function ($event)
        {
            $event->sender->OldAttributes = $event->sender->Attributes;
        });
    }
    public function setOldAttributes($value)
    {
        $this->_oldAttributes = $value;
    }

    public function getOldAttributes()
    {
        return $this->_oldAttributes;
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'category';
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
            array('title, description, url, keywords', 'filter', 'filter' => 'strip_tags'),
            array('title, description, url, keywords', 'filter','filter' =>'trim'),
            array('status_id, author_id, parent_id', 'numerical', 'integerOnly' => true),
            array('title','unique', 'message'=>'Рубрика с таким названием уже существует.'),
            array('level, lft, rgt', 'numerical', 'integerOnly' => true),
          //  array('url, title', 'checkIfAvailable'),
            array('root', 'default', 'value'=>null),
            array('title, url, icon, logotip, logotip_realname', 'length', 'max'=>255),
            array('description, full_path, keywords', 'type', 'type'=>'string'),
            array('created_date', 'default', 'value' => date('Y-m-d H:i:s'), 'setOnEmpty' => true, 'on' => 'insert'),
            array('updated_date', 'default', 'value' => '0000-00-00 00:00:00', 'setOnEmpty' => true, 'on' => 'insert'),
            array('tmpLogotip', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, title, url, root, lft, rgt, level, description, icon, status_id, author_id, created_date, updated_date, full_path, keywords, parent_id, orgs_count, orgs_count_not_published, logotip, logotip_realname', 'safe', 'on' => 'search'),
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
        	// 'orgs_count' => array(self::STAT, 'OrgsCategory', 'category'),
            'author' => array(self::BELONGS_TO, 'User', 'author_id'),
            'countOrgs' => array(self::STAT, 'OrgsCategory', 'org'),
            'categorization' => array(self::HAS_MANY, 'OrgsCategory', 'category'),
            'organizations'      => array(self::HAS_MANY, 'Orgs',array('org'=>'id'), 'through'=>'categorization'),
            'parent'=>array(self::BELONGS_TO, 'Category', 'parent_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'title' => Yii::t('site','Title'),
            'description' => Yii::t('site','Description'),
            'status_id' => Yii::t('site','Status'),
            'author_id' => Yii::t('site','Author'),
            'created_date' => Yii::t('site','Created date'),
            'updated_date' => Yii::t('site','Updated date'),
            'lft'              => 'Lft',
            'rgt'              => 'Rgt',
            'level'            => Yii::t('site', 'Level'),
            'url'              => 'Название для урл',
            'full_path'        => Yii::t('site', 'Full path'),
            'icon' => Yii::t('site', 'Icon'),
            'keywords'=>'Ключевые слова',
            'parent_id' => 'Предок',
            'logotip' => 'Иконка (34x34)',

        );
    }
    public function applyUser($users, $select = 't.*')
    {

        if(empty($user))
            return $this;

        $criteria = new CDbCriteria;
        if($select)
            $criteria->select = $select;    

        $criteria->addCondition('user_id', $user);
        $this->getDbCriteria()->mergeWith($criteria);

        return $this;
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

		   $criteria->join="LEFT JOIN orgs_category On t.id=orgs_category.category";
           $criteria->select=array('t.*','COUNT(orgs_category.id) AS orgs_count', 'COUNT(CASE WHEN orgs_category.status_org = '.Orgs::STATUS_NOT_ACTIVE.' THEN 1 END) AS orgs_count_not_published');
		   $criteria->group='t.id';

        if($additionalCriteria !== null)
            $criteria->mergeWith($additionalCriteria);

        if($this->id)
            $this->id = (int)$this->id;
        $criteria->compare('t.id', $this->id);
        $criteria->compare('LOWER(t.title)',MHelper::String()->toLower($this->title),true);
        $criteria->compare('t.description', $this->description, true);
        $criteria->compare('t.lft', $this->lft);
        $criteria->compare('t.rgt', $this->rgt);
        $criteria->compare('t.status_id', $this->status_id);
        $criteria->compare('t.author_id', $this->author_id);
        $criteria->compare('t.created_date', $this->created_date, true);
        $criteria->compare('t.updated_date', $this->updated_date, true);
        $criteria->compare('t.full_path', $this->full_path, true);
        $criteria->compare('t.level',$this->level);
        $criteria->compare('t.url',$this->url,true);
        $criteria->compare('t.icon',$this->icon,true);
        $criteria->compare('LOWER(t.keywords)',MHelper::String()->toLower($this->keywords),true);


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort'       => Category::getCSort(),
            'pagination' => array(
                'pageSize' => 10,
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
                $test = Category::model()
                    ->withUrl($unique)
                    ->count();
            }
            else
            {
                $test = Category::model()
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

            
            // Create category full path.
            $ancestors = $this->ancestors()->findAll();
            if(sizeof($ancestors))
            {
                // Remove root category from path
            //    unset($ancestors[0]);

                $path = array();
                foreach($ancestors as $ancestor)
                    array_push($path, $ancestor->url);
                array_push($path, $this->url);
                $this->full_path = implode('/', array_filter($path));
            } else {
                $this->full_path = $this->url;
            }
            
            if($this->isNewRecord) {
                $this->status_id = self::STATUS_ACTIVE;
                $this->author_id = Yii::app()->user->id;
                $this->_newRec = true;
            } 

            $this->updated_date = date('Y-m-d H:i:s');

            return true;
        } else
            return false;
    } 

    protected function afterSave() {

        parent::afterSave();

            if(!$this->_newRec){
                // нужно исправить full path у дочерних узлов, если алиас изменился.
                $oldAssignedId = $this->OldAttributes['url'];
                $newAssignedId = $this->url;
                if($newAssignedId != $oldAssignedId){

                    $descendants=$this->descendants()->findAll();
                    if(sizeof($descendants))
                    {

                        foreach($descendants as $descendant) {
                            $full_path = explode('/', $descendant->full_path);
                            if($full_path){
                                foreach($full_path as $key => &$paths){
                                    if($paths == $oldAssignedId){
                                        $paths = $newAssignedId;
                                    }
                                }
                                $descendant->full_path = implode('/', array_filter($full_path));
                                $descendant->saveNode(true,array('full_path'));

                            }
                        }
                    }

                }
            }

            $log = new ActionLog;
            $toUpdate = false;
            if($this->status_id == self::STATUS_ACTIVE){
                // find last update date
                $lastUpdateDate = ActionLog::model()->find(array('condition'=>'model_name=:model_name and model_id=:model_id and (event=:event or event=:event2)', 'params'=>array(':model_name'=>'Category',':model_id'=>$this->id, ':event'=>ActionLog::ACTION_CREATE, ':event2'=>ActionLog::ACTION_UPDATE),'order'=>'datetime DESC'));
                if($lastUpdateDate){
                    $now = time(); 
                    $last_date = strtotime($lastUpdateDate->datetime);
                    $datediff = $now - $last_date;
                    if(floor($datediff/(60*60*24)) > 30 ){ // more 30 days left
                        $toUpdate = true;
                    }
                } else {
                    $toUpdate = true;
                }
                if(!$this->_newRec){
                    $log->event = ActionLog::ACTION_UPDATE;
                } else {
                    $log->event = ActionLog::ACTION_CREATE;
                }
                if($toUpdate) {
                    $log->user_id = Yii::app()->user->id;
                    $log->model_name = 'Category';
                    $log->datetime = date('Y-m-d H:i:s');
                    $log->model_id = $this->id;
                    $log->save();
                }
           }
            return true;
      
    }

    public static function getActiveCategories() {
        return CHtml::listData(self::model()->active()->findAll(), 'id', 'title');
    }

    // this method is working. on parent_id base. very fast // 0.6sec
    public static function getRubsByParentId($city_id)
    {
       /* Выводятся только рубрики, если имеется parent */
       $connection=Yii::app()->db;
       $sql = 'SELECT DISTINCT t.category, cat.id, cat.title, cat.url, cat.parent_id
       FROM orgs_category t 
       LEFT OUTER JOIN category cat 
       ON (cat.id=t.category) 
       WHERE t.city_id='.$city_id .' and t.status_org='.Orgs::STATUS_ACTIVE.'  ORDER BY title';
       $command = $connection->createCommand($sql);
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
                $cat = Category::model()->findByPk($key);
                if($cat){
                    $output[$key] = array('id'=>$key,'title'=>$cat->title,'url'=>$cat->url,'par'=>$cat->parent_id);
                    for ($i=0;$i<sizeof($value);$i++)
                    {
                        if ($value[$i]['title']!='')
                            $output[$key]['items'][] = array('id'=>$value[$i]['id'],'title'=>$value[$i]['title'],'url'=>$value[$i]['url']);
                    }
                }
            }
        }

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
            }
            usort($output, MHelper::get('Array')->sortFunction('title'));
        }
        return $output;

      /*  $output = "<res>";

        if($rubs)
        {
            foreach($rubs as $key=>$value)
            {
                $output .= '<bl par="'.$key.'">';
                for ($i=0;$i<sizeof($value);$i++)
                {

                    if ($value[$i]['title']!='')
                        $output .= '<elm id ="'.$value[$i]['id'].'" ><![CDATA['.CHtml::encode($value[$i]['title']).']]></elm>';
                }
                $output .= '</bl>';
            }
        }

        $output.="</res>";

        return $output;*/
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
                FROM "category" "t" 
                LEFT OUTER JOIN "orgs_category" "categorization" 
                ON ("categorization"."category"="t"."id") 
                LEFT OUTER JOIN "orgs" "organizations" 
                ON ("categorization"."org"="organizations"."id") 
                WHERE (t.status_id = 1) AND (categorization.category=t.id) AND (organizations.city_id='.$city_id.') AND (organizations.status_org = '.Orgs::STATUS_ACTIVE.')';
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

       /* $criteria=new CDbCriteria;
        $criteria->order='t.title'; // 'order'=>'t.lft',
        $criteria->scopes = array('active');
        $criteria->select = array('t.id','t.title','t.url');
        $criteria->with = array(
            'categorization'=>array(
                'condition'=>'categorization.category=t.id',
                'together'=>true,
                ),
            'organizations'=>array(
                'select'=>array('organizations.id'),
                'condition'=>'organizations.city_id='.$city_id,
                'together'=>true
                )
        );
        return Category::model()->findAll($criteria);*/

    }

    protected function beforeDelete() {
        parent::beforeDelete();
        $this->getDeleteFileFolder();
        return true;
    }
    protected function getDeleteFileFolder() {
        $folder = Yii::getPathOfAlias('webroot').'/uploads/category/'.$this->id;
        if (is_dir($folder) == true)
        	CFileHelper::removeDirectory($folder);
           // rmdir($folder);
        return true;
    }
    public function addDropboxLogoFiles($uploadsession, $clear = true)
    {
        $files = $this->tmpLogotip;

        if($files){

            if(Yii::app()->session->itemAt($uploadsession)){
            	
                $folder='uploads'. DIRECTORY_SEPARATOR .'tmp'. DIRECTORY_SEPARATOR;

                $dataSession = Yii::app()->session->itemAt($uploadsession);
                

                foreach($files as $fileUploadName){

                    if(is_array($dataSession)){
                        foreach($dataSession as $key => $value){
                            if($fileUploadName == $key){
                                if(file_exists($folder.$value )) {

                                    $file = $folder.$value;
                                    $ext = pathinfo($folder.$value, PATHINFO_EXTENSION);

                                    $base = md5(rand(1000,4000));
                                    $unique = $base;
                                    $suffix = 1;

                                    while (file_exists($this->getFileFolder() . $unique . $ext)){
                                        $unique = $base.'_'.$suffix;
                                        $suffix++;
                                    }
                                    $filename =  $unique . '.' . $ext;
                                    
                                    if (copy($folder.$value, $this->getFileFolder() . $filename)) {
                                    	if($clear)
                                        	unlink($folder.$value);
                                        $this->logotip = $filename;
                                        $this->logotip_realname = $key;
                                        $this->saveNode(array('logotip','logotip_realname'));
                                    } 

                                }
                            break;
                            }
                        }
                    }

                    
                }
            }
            
        }
    }
    public function getFileFolder()
    {
        $folder = Yii::getPathOfAlias('webroot').'/uploads/category/'.$this->id.'/';
        if (is_dir($folder) == false)
            @mkdir($folder, octdec(Yii::app()->params['storeImages']['dirMode']), true);
        return $folder;
    }

    public function getOrigFilePath() {
        return Yii::app()->baseUrl.'/uploads/category/'.$this->id.'/';
    }
    public function getViewUrl($city)
    {
        return Yii::app()->createAbsoluteUrl('/catalog/catalog/view', array('city'=>$city, 'url'=>$this->url));
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
        $items=Category::model()->findByPk($root)->asTreeArray();
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
        $check = Category::model()->countByAttributes(array(
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