<?php
Yii::import('application.modules.comments.models.CommentArticle');
/**
 * This is the model class for table "article".
 *
 * The followings are the available columns in table 'article':
 * @property integer $id
 * @property string $title
 * @property string $url
 * The followings are the available model relations:
 * @property ArticleCategory[] $articleCategories
 * @property ArticleImages[] $articleImages
 */
class Article extends BaseModel
{
	const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;

	public $tmpFiles;
	public $tmpLogotip;
	public $rubric_title;
	public $categorie;
	public $categories_ar;
	public $articleorg_ar;
	public $articleorg;
	public $firmname;
	public $firmurl;
	public $firmphoto;
	public $firmdescription;
	public $article_ar;
	public $maxFiles = 25;

	private $_newRec = false;
	private $_curr;
	private $_oldAttributes = array();

 
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'article';
	}
	public function scopes()
	{
		
		$alias = $this->getTableAlias(true);
                
		return array(
			'active'=>array(
				'condition'=>$alias.'.status_org = '.self::STATUS_ACTIVE,
			),
			'fullactive'=>array(
				'condition'=>$alias.'.status_org = '.self::STATUS_ACTIVE.' and '.$alias.'.verified is true',
			),
			'notactive'=>array(
				'condition'=>$alias.'.status_org= '.self::STATUS_INACTIVE,
			),
			'deleted'=>array(
				'condition'=>$alias.'.status_org = '.self::STATUS_DELETED,
			),
			'byViews'=>array('order'=>$alias.'.views_count DESC'),
			'hits'=>array(
		          'order'=>$alias.'.views_count DESC',
		          'limit'=>5,
		    ),
		    'novis'=>array(
		          'order'=>$alias.'.created_date DESC',
		          'limit'=>5,
		    ),
		);
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		if($this->scenario == 'part'){
				$r =  array(
				array('description, part_org', 'required'),
			    array('title, url, articleorg', 'filter', 'filter' => 'strip_tags'),
	            array('title, url, logotip_realname, description, articleorg', 'filter','filter' =>'trim'),
				array('views_count, author, status_org, city_id, yes, no, part, part_order, part_org', 'numerical', 'integerOnly'=>true),
				array('title, url, logotip, logotip_realname', 'length', 'max'=>255),
			 // array('articleorg_ar','checkFirmsUrls'),
				array('description','nolinks'),
				array('articleorg', 'url', 'validateIDN'=>true, 'defaultScheme'=>'http'),
				array('description, tmpFiles, tmpLogotip, categories_ar, articleorg_ar, article_ar', 'safe'),
				// The following rule is used by search().
				// @todo Please remove those attributes that should not be searched.
				array('id, title, url, description, logotip, logotip_realname, city_id, views_count, created_date, updated_date, author, rubric_title, status_org, yes, no, part, part_order, part_org', 'safe', 'on'=>'search'),
			);
		} else {


			$r =  array(
				array('title, description', 'required'),
				array('object_id', 'required', 'message'=>'Выберите объект'),
			    array('title, url, articleorg', 'filter', 'filter' => 'strip_tags'),
	            array('title, url, logotip_realname, description, articleorg', 'filter','filter' =>'trim'),
				array('views_count, author, status_org, city_id, yes, no, part, part_order', 'numerical', 'integerOnly'=>true),
				array('title, url, logotip, logotip_realname', 'length', 'max'=>255),
			 // array('articleorg_ar','checkFirmsUrls'),
				array('articleorg', 'url', 'validateIDN'=>true, 'defaultScheme'=>'http'),
				array('description, tmpFiles, tmpLogotip, categories_ar, articleorg_ar, article_ar', 'safe'),
				// array('description','nolinks'),
				array('verified, mywork','boolean'),
				array('created_date', 'default', 'value' => date('Y-m-d H:i:s'), 'setOnEmpty' => true, 'on' => 'insert'),
	            array('updated_date', 'default', 'value' => '0000-00-00 00:00:00', 'setOnEmpty' => true, 'on' => 'insert'),
				// The following rule is used by search().
				// @todo Please remove those attributes that should not be searched.
				array('id, title, url, object_id, description, logotip, logotip_realname, city_id, views_count, created_date, updated_date, author, rubric_title, status_org, yes, no, part, part_order, part_org, verified, mywork', 'safe', 'on'=>'search'),
			);
		}
		return $r;
	}

	public function nolinks($attribute,$params)
	{
	  $pattern = '#(www\.|https?://)?[a-z0-9]+\.[a-z0-9]{2,4}\S*#i';	
	  preg_match_all($pattern, $this->$attribute, $matches, PREG_PATTERN_ORDER);
	  if(isset($matches[0]) && !empty($matches[0])){
	     $this->addError($attribute, 'Размещение веб-ссылок запрещено');
	      return false;
	  }
	  return true;
	 }
	/* public function checkFirmsUrls($attribute, $params)
    {
        if (!empty($this->$attribute) && is_array($this->$attribute)) {
        	$ar = $this->$attribute;
            for ($i=0;count($ar)>0;$i++) 
            {
            	$this->articleorg = trim($ar[$i]);
            	if(!$this->articleorg->validate())
            	{
            		$this->addError($attribute, 'Необходимо указать url');
            		return false;
            	}
            }
        	
        } 
        return true;   
    }*/

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'categorization' => array(self::HAS_MANY, 'ArticleCategory', 'article'),
			'images' => array(self::HAS_MANY, 'ArticleImages', 'article'),
			'mainImage' => array(self::HAS_ONE, 'ArticleImages', 'article', 'condition'=>'mainImage.is_main=1'),
			'imagesNoMain'    => array(self::HAS_MANY, 'ArticleImages', 'article', 'condition'=>'is_main=0'),
			'categories'      => array(self::HAS_MANY, 'CategoryArticle',array('category'=>'id'), 'through'=>'categorization'),
			'authorid'=>array(self::BELONGS_TO, 'User', 'author'),
			'city' => array(self::BELONGS_TO, 'City', 'city_id'),
			'organization' => array(self::HAS_MANY, 'ArticleOrg', 'article'),
			'orgs'      => array(self::HAS_MANY, 'Objects', array('org'=>'id'), 'through'=>'organization'),
			'parts'=>array(self::HAS_MANY, 'Article', 'part',  'order'=>'parts.part_order'),
			'parent'=>array(self::BELONGS_TO, 'Article', 'part'),
			'org' => array(self::BELONGS_TO, 'Objects', 'part_org'),
			'comments' => array(self::HAS_MANY, 'CommentArticle', 'object_pk', 'condition'=>'comments.id_parent is NULL'),
			'object' => array(self::BELONGS_TO, 'Objects', 'object_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Название',
			'url' => 'Урл',
			'description' => 'Описание',
			'logotip' => 'Фото',
			'created_date' => Yii::t('site','Created Date'),
            'updated_date' => Yii::t('site','Updated Date'),
            'tmpFiles' => 'Фото',
            'author'=>'Автор',
            'rubric_title'=>'Теги',
            'status_org'=>'Статус',
            'city_id' => 'Город',
            'articleorg_ar'=>'Фирма, url',
            'articleorg'=>'Фирма, url',
            'views_count'=>'Просмотры',
            'yes' => 'Yes',
			'no' => 'No',
			'firmname'=>'Фирма',
			'firmurl'=>'Фирма, url',
			'firmphoto'=>'Фото фирмы',
			'firmdescription'=>'Описание',
			'verified'=>'Проверка',
			'mywork'=>'Моя работа',
			'categorie'=>'Выбор рубрики',
			'object_id'=>'Объект'
            
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
	public function search($params = array(), $additionalCriteria = null, $pagination = true)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		// $rubric_table = Category::model()->tableName();
		// $rubric_orgs_table = OrgsCategory::model()->tableName();
    	// $rubric_title_sql = "(select LOWER(rt.title) from $rubric_table as rt, $rubric_orgs_table as rot where rot.org = t.id)";

		$criteria->with = array(
			 'categorization',
		     'categories'=>array(),
			);
		

		if($additionalCriteria !== null)
			$criteria->mergeWith($additionalCriteria);

		if($this->id)
			$this->id = (int)$this->id;
		$criteria->compare('t.id',$this->id);
		if($this->title){
			$criteria->compare('LOWER(t.title)',MHelper::String()->toLower($this->title),true);
		}
		$criteria->compare('LOWER(t.description)',MHelper::String()->toLower($this->description),true);
	
		$criteria->compare('t.url',$this->url,true);
		$criteria->compare('t.logotip',$this->logotip,true);
		$criteria->compare('t.created_date',$this->created_date,true); 
		$criteria->compare('t.city_id',$this->city_id);
		$criteria->compare('t.status_org',$this->status_org);
		$criteria->compare('t.views_count',$this->views_count);
		$criteria->compare('t.yes',$this->yes);
		$criteria->compare('t.no',$this->no);
		$criteria->compare('t.part',$this->part);
		$criteria->compare('t.part_order',$this->part_order);
		$criteria->compare('t.part_org',$this->part_org);
		$criteria->compare('t.verified',$this->verified,false);
		$criteria->compare('t.mywork',$this->mywork,false);
		$criteria->compare('t.object_id',$this->object_id);

		if ($this->updated_date)  {
			$criteria->addCondition("t.updated_date >='" . date('Y-m-d 00:00:00', strtotime($this->updated_date)) . "'");
			$criteria->addCondition("t.updated_date <='" . date('Y-m-d 23:59:59', strtotime($this->updated_date)) . "'");
		}
		$criteria->compare('t.author', $this->author);
		if(!empty($this->rubric_title)){
			$criteria->with['categories'] = array('together'=>true);
	    	$criteria->compare('LOWER(categories.title)', MHelper::String()->toLower($this->rubric_title), true);
		} 
        if($pagination){
        	return new CActiveDataProvider($this, array(
				'criteria'   => $criteria,
				'sort'       => Article::getCSort(),
				'pagination' => array(
					'pageSize'=>50,
				)
			));
        } else {
        	return new CActiveDataProvider($this, array(
				'criteria'   => $criteria,
				'sort'       => Article::getCSort('t.id'),
				'pagination' => false
			));
        }
		
	}

	/**
	 * @return CSort to use in gridview, listview, etc...
	 */
	public static function getCSort($order = '')
	{	
		// используется и в админке и при выдаче организаций в категориях на сайте, поэтому $order
		$sort = new CSort;
		if($order){
			$sort->defaultOrder = $order;
		} else {
			$sort->defaultOrder = 't.updated_date DESC';
		}

		$sort->attributes=array(
			
		    'id' => array(
				'asc'   => 't.id',
				'desc'  => 't.id DESC',
			),
		   'created' => array(
				'asc'   => 't.created_date',
				'desc'  => 't.created_date DESC',
			),
		   'title' => array(
				'asc'   => 't.title',
				'desc'  => 't.title DESC',
			),
		   'views_count' => array(
				'asc'   => 't.views_count',
				'desc'  => 't.views_count DESC',
			), 
		   'updated_date'=>array(
                'asc'=>'t.updated_date',
                'desc'=>'t.updated_date DESC',
            ),
		  
		);

		return $sort;
	}
	
	/**
	 * @return array
	 */
	public static function getStatusNames()
	{
		return array(
			self::STATUS_ACTIVE=>'Опубликовано',
			self::STATUS_INACTIVE=>'Не опубликовано',
		);
	}
	public function withUrl($url, $alias = 't')
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => $alias.'.url=:url',
            'params'    => array(':url'=>$url)
        ));
        return $this;
    }
	protected function checkUniqueUrl($unique){
        // Check if url available
            if($this->isNewRecord) {
                $test = Article::model()
                    ->withUrl($unique)
                    ->count();
            } else {
                $test = Article::model()
                    ->withUrl($unique)
                    ->count('id!=:id', array(':id'=>$this->id));
            }
            return $test;
    }

    protected function beforeDelete() {
        parent::beforeDelete();
        ArticleVote::model()->deleteAllByAttributes(array('article'=>$this->id));
        $this->getDeleteFileFolder();
        // all comments remove
        CommentArticle::model()->deleteAllByAttributes(array(
				'object_pk'=>$this->id
		));
        return true;
    }
    public function addDropboxLogoFiles($uploadsession, $clear = true)
    {
        $files = $this->tmpLogotip;


        if($files){

            if(Yii::app()->session->itemAt($uploadsession)){
       
                $folder='uploads'. DIRECTORY_SEPARATOR .'tmp'. DIRECTORY_SEPARATOR;

                $dataSession = Yii::app()->session->itemAt($uploadsession);
               
                if(!is_array($files)){
                	$files = array($files);
                }
                foreach($files as $fileUploadName){
                	
                    if(is_array($dataSession)){
                        foreach($dataSession as $key => $value){
                            if($fileUploadName == $key){
                                if(file_exists($folder.$value )) {

                                    $file = $folder.$value;
                                    $ext = pathinfo($folder.$value, PATHINFO_EXTENSION);

                                    $base = md5(rand(1000,4000));
                                    $unique = $base.'_article';
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
                                        if(!$this->save(true,array('logotip','logotip_realname'))){
                                        	// VarDumper::dump($this->errors); die(); // Ctrl + X	Delete line
                                        }
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
	public function addDropboxFiles($uploadsession)
    {
        $files = $this->tmpFiles;
        
       // $existFiles = count($this->images);
       // $availableFiles = $this->maxFiles - $existFiles;
        $cnt = 0;
      //  if($files){

            if(Yii::app()->session->itemAt($uploadsession)){

                // $folder='uploads'. DIRECTORY_SEPARATOR .'tmp'. DIRECTORY_SEPARATOR;
                $folder = Yii::getPathOfAlias(Yii::app()->params['storeImages']['tmp']).DIRECTORY_SEPARATOR;

                $dataSession = Yii::app()->session->itemAt($uploadsession);
                

              //  foreach($files as $fileUploadName){

                    if(is_array($dataSession)){
                        foreach($dataSession as $key => $value){
                        		
                          //  if($fileUploadName == $key){
                        	
                                if(@file_exists($folder.$value )) {
                                

                                	//if($cnt >= $availableFiles)	
                                	//	break 2;

                                   /* $file = $folder.$value;
                                    $ext = pathinfo($folder.$value, PATHINFO_EXTENSION);

                                    $base = md5(rand(1000,4000));
                                    $unique = $base.'_article';
                                    $suffix = 1;

                                    while (file_exists($this->getFileFolder() . $unique . $ext)){
                                        $unique = $base.'_'.$suffix;
                                        $suffix++;
                                    }
                                    $filename =  $unique . '.' . $ext;
                                    $fullPath = $this->getFileFolder() . $filename;*/

                                    $fullPath = $this->getFileFolder().$value;
 
                                    if (copy($folder.$value, $fullPath)) {

                                        unlink($folder.$value);
                                        $image = new ArticleImages();
                                        $image->article = $this->getPrimaryKey();
                                        $image->filename = $value;
                                        $image->realname = $key;
                                        
                                       /* Yii::import('ext.phpthumb.PhpThumbFactory');
										$thumb  = PhpThumbFactory::create($fullPath);
										$thumb->setOptions(array('jpegQuality'=>100));
										$thumb->resize(1000)->save($fullPath);
										*/
										
                                        $image->save();
                                        $cnt++;
	                                        
                                        
                                    } else {
                                    
                                       // throw new CHttpException(404, Yii::t('Site', 'Cannot copy file to folder.'));
                                    }
                                    

                                }

                         //   }
                        }
                    }

                    
              //  }
            }
       
       // }
           
    }
    public function getFileFolder()
    {
    	
    	$folder = Yii::getPathOfAlias('webroot').'/uploads/article/';
        if (is_dir($folder) == false)
            @mkdir($folder, octdec(Yii::app()->params['storeImages']['dirMode']), true);

    	$folder_id = $this->id;
    	if($this->part){
    		$folder_id = $this->part;
    	}

        
        $folder = Yii::getPathOfAlias('webroot').'/uploads/article/'.$folder_id.'/';
        if (is_dir($folder) == false)
            @mkdir($folder, octdec(Yii::app()->params['storeImages']['dirMode']), true);
        return $folder;
    }

    public function getOrigFilePath() {
    	$folder_id = $this->id;
    	if($this->part){
    		$folder_id = $this->part;
    	}
        return Yii::app()->baseUrl.'/uploads/article/'.$this->id.'/';
    }

    public function getThumbsFilePaths($filename, $type = 'field') {
    	
    	$src_or = $this->getOrigFilePath().$filename;
        $thumbFolders = Yii::app()->params['storeImages']['thumbFolders'];
        $getfolder = Yii::getPathOfAlias('webroot').'/'.Yii::app()->params['storeImages']['thumbUrl'];
    	if (is_dir($getfolder) == false){
    		@mkdir($getfolder, octdec(Yii::app()->params['storeImages']['dirMode']), true);
    	}
        $thumbUrl = Yii::app()->params['storeImages']['thumbUrl'];
        $thumbler = '<div style="100%" class="lazy-load-'.$type.'"><noscript ';
        foreach($thumbFolders as $size){
        	$getfolder = Yii::getPathOfAlias('webroot').$thumbUrl.$size; 
        	if (is_dir($getfolder) == false){
                @mkdir($getfolder, octdec(Yii::app()->params['storeImages']['dirMode']), true);
    		}       	
        	$thumbPath = $getfolder.'/'.$this->id.'_'.$filename;
        	if(!file_exists($thumbPath)){
        		$fullPath  = $this->getFileFolder().$filename;
        		if(file_exists($fullPath)){
        			Yii::import('ext.phpthumb.PhpThumbFactory');
					$thumb  = PhpThumbFactory::create($fullPath);
					$thumb->setOptions(array('jpegQuality'=>90));
					$thumb->resize($size)->save($thumbPath);
        		} else {
        			continue;
        		}
        	}
        	$src = Yii::app()->baseUrl.$thumbUrl.$size.'/'.$this->id.'_'.$filename;
        	// имеем тхумбнэйл
        	$thumbler .= ' data-src-'.$size.'="'.$src.'" data-alt="'.$this->title.'" ';
        }
        if($type == 'field'){
        	$thumbler .= ' ><div class="item-gallery-bg" style="background-image:url('.$src_or.'); "></div></noscript></div>';
        } else {
        	$thumbler .= ' ><img class="picture img-responsive" alt="'.$this->title.'" src="'.$src_or.'"></noscript></div>';
    	}
        return $thumbler;
    }

    protected function getDeleteFileFolder() {
        $folder = Yii::getPathOfAlias('webroot').'/uploads/article/'.$this->id;
        if (is_dir($folder) == true)
        	CFileHelper::removeDirectory($folder);
           // rmdir($folder);
        return true;
    }
	public function beforeSave()
	{
		if(parent::beforeSave()) {

			$reurl = false;
			if($this->isNewRecord) {
				$this->_newRec = true;
				$this->author = (Yii::app()->user)?Yii::app()->user->id:null;
			} else {
				$this->_curr = self::findByPk($this->id,array('select'=>'status_org'));
               if($this->_curr) {
                	if($this->title != $this->_curr->title)
                        $reurl = true;
	        	} 

			}

			// Create slug
			Yii::import('ext.SlugHelper.SlugHelper');
	        if(!$this->url  || $reurl){
	          //  $this->url = SlugHelper::run(preg_replace('/\s{2,}/', ' ', $this->synonim));
	            $this->url = SlugHelper::run($this->title, 'yandex');
	        } else {
	        	$this->url = SlugHelper::run($this->url, 'yandex');
	        }

	        $this->description = trim(MHelper::String()->purifyFromScript($this->description));

	        $this->updated_date = date('Y-m-d H:i:s');
	        
    		return true;
        } else
            return false;
	}
	

	public function behaviors()
	{
		return array(
		/*	'eavAttr' => array(
				'class'     => 'ext.behaviors.eav.EEavBehavior',
				'tableName' => 'products_variants',
				'entityField' => 'product_id',
				'attributeField' => 'attribute_name',

			),*/
		    'fileBehavior'=> array(
          		'class' => 'application.components.behaviors.FileBehavior',
          		'attribute' => 'logotip',
          	),
			/* 'comments' => array(
				'class'       => 'application.modules.comments.components.CommentBehavior',
				'class_name'  => 'application.modules.catalog.models.Article',
				'owner_title' => 'title', // Attribute name to present comment owner in admin panel
			), */
  
		);
	}

	public function beforeValidate()
	{
		
		return parent::beforeValidate();
	}


	public function applyCategoriesWithSub($categories, $select = 't.*')
	{
		$cat = array();
		
		if($categories instanceof CategoryArticle){

        	$cat[] = $categories->id;
        	$category = CategoryArticle::model()->findByPk($categories->id);
			$descendants = $category->descendants()->findAll();
			if(!empty($descendants)){
			foreach($descendants as $c){
                        if(is_object($c)){
                            $cat[] = $c->id;
                        } else {
                            $cat[] = $c;
                        }
                    }
            }
             $categories = $cat;

        } 
 
		$criteria = new CDbCriteria;

		if($select)
			$criteria->select = $select;
                
		$criteria->join = 'LEFT JOIN "article_category" "categorization" ON ("categorization"."article"="t"."id")';

		$criteria->addInCondition('categorization.category', $categories);
		
                $this->getDbCriteria()->mergeWith($criteria);

		return $this;
	}


	/**
	 * Set organization categories 
	 * @param array $categories ids.
	 */
	public function setCategories(array $categories,$add = false)
	{    
        $dontDelete = array();
        if(!empty($categories)){
		foreach($categories as $c){
			if(empty($c))
				continue;
			$found = ArticleCategory::model()->findByAttributes(array(
				'category'=>$c,
				'article'=>$this->id
			));
			// если не было категории - делаем
			if(!$found){
				$record = new ArticleCategory;
				$record->category = (int)$c;
				$record->article = $this->id;
				$record->save(false);
                                
			} 
			$dontDelete[] = $c;
		}
		}
		if($add === false){
			// Удаляем все категории, которых не было в массиве
			if(sizeof($dontDelete) > 0){
				$cr = new CDbCriteria;
				$cr->addNotInCondition('category', $dontDelete);

				ArticleCategory::model()->deleteAllByAttributes(array(
					'article'=>$this->id,
				), $cr);
			} else { // удаляем все категории, т.к. пустой массив
				// Delete all relations
				ArticleCategory::model()->deleteAllByAttributes(array(
					'article'=>$this->id,
				));
			}
		}
   
	}
	/**
	 * Set article-org 
	 * @param array $orgs ids.
	 */
	public function setArticleOrg(array $orgs,$add = false)
	{    
        $dontDelete = array();
        if(!empty($orgs)){
		foreach($orgs as $c){
			if(empty($c))
				continue;
			$found = ArticleOrg::model()->findByAttributes(array(
				'org'=>$c,
				'article'=>$this->id
			));
			// если не было связи - делаем
			if(!$found){
				$record = new ArticleOrg;
				$record->org = (int)$c;
				$record->article = $this->id;
				$record->save(false);
                                
			} 
			$dontDelete[] = $c;
		}
		}
		if($add === false){
			// Удаляем все категории, которых не было в массиве
			if(sizeof($dontDelete) > 0){
				$cr = new CDbCriteria;
				$cr->addNotInCondition('org', $dontDelete);

				ArticleOrg::model()->deleteAllByAttributes(array(
					'article'=>$this->id,
				), $cr);
			} else { // удаляем все категории, т.к. пустой массив
				// Delete all relations
				ArticleOrg::model()->deleteAllByAttributes(array(
					'article'=>$this->id,
				));
			}
		}
   
	}
	
    public static function parseUrlShow($url, $social = false){
    	$url = parse_url(CHtml::encode($url));
		$host = isset($url['host']) ? $url['host'] : '';
		if(stripos($host, "www.") === 0)
	                $host = substr($host, 4);

		$path  = isset($url['path']) ? $url['path'] : '';
		if(!$social){
			$url = $host.$path;
		} else {
			$url = trim($path,'\/');
		}

		return $url;
    }
	/**
	 * Delete related data.
	 */
	public function afterDelete()
	{

		// Delete images
		$images = $this->images;
		if(!empty($images))
		{
			foreach ($images as $image)
				$image->delete();
		}
		

		return parent::afterDelete();
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Orgs the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
