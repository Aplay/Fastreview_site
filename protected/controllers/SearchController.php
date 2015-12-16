<?php

class SearchController extends Controller
{

    public $layout = '//layouts/board';
    public $breadcrumbs = array('Search');

    /**
     * @var string index dir as alias path from <b>application.</b>  , default to <b>runtime.search</b>
     */
    private $_indexFiles = 'runtime.search';
    /**
     * (non-PHPdoc)
     * @see CController::init()
     */
    public function init(){
        Yii::import('application.vendors.*');
        require_once('Zend/Search/Lucene.php');
        //Zend_Search_Lucene_Search_QueryParser::setDefaultEncoding('utf-8');  
        //Zend_Search_Lucene_Analysis_Analyzer::setDefault(new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8_CaseInsensitive());

Zend_Search_Lucene_Analysis_Analyzer::setDefault(new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8());
Zend_Search_Lucene_Search_QueryParser::setDefaultEncoding('utf-8');
Zend_Search_Lucene_Analysis_Analyzer::setDefault(new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8_CaseInsensitive());
        //устанавливаем ограничение на количество записей в результате поиска
        Zend_Search_Lucene::setResultSetLimit(100); 
        parent::init(); 
    }

    /**
     * Search index creation
     */
    public function actionCreate()
    {
       // setlocale(LC_CTYPE, 'ru_RU.UTF-8');
      //  Zend_Search_Lucene_Analysis_Analyzer::setDefault(new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8());    
 

        $index = new Zend_Search_Lucene(Yii::getPathOfAlias('application.' . $this->_indexFiles), true);

        $posts = IssueComment::model()->findAll();
        foreach($posts as $post){
            $doc = new Zend_Search_Lucene_Document();
            $doc->addField(Zend_Search_Lucene_Field::Text('title',CHtml::encode($post->issue->subject), 'UTF-8'));

            $doc->addField(Zend_Search_Lucene_Field::Text('link', $this->createUrl('/task/view', array('id'=>$post->issue->id)), 'UTF-8')); 
            $doc->addField(Zend_Search_Lucene_Field::Text('class_name', 'Issue'));  
            $doc->addField(Zend_Search_Lucene_Field::Text('target_id', $post->issue->id));

  $content = $this->clean_content($post->text);
            if(!empty($content)){
                $doc->addField(Zend_Search_Lucene_Field::Text('content', $content, 'UTF-8'));

                $index->addDocument($doc);
            }
        }
        $index->optimize();
        $index->commit();
       // echo 'Lucene index создан успешно';
        return true;
    }

    public function actionSearch()
    {

        die('closed');
        if (($term = Yii::app()->getRequest()->getQuery('q')) !== null) {
           $index = new Zend_Search_Lucene(Yii::getPathOfAlias('application.' . $this->_indexFiles));
            $results = new CArrayDataProvider($index->find($term), array(
                        'id' => 'search',
                        'pagination' => array(
                            'pageVar' => 'page',
                            'pageSize'=>20,
                        ),
                        'sort' => false,
                    ));
            $query = Zend_Search_Lucene_Search_QueryParser::parse($term); 

            $s = MHelper::String()->toLower($term);
            $s = addcslashes($s, '%_'); // escape LIKE's special characters
            $criteria = new CDbCriteria;
            $criteria->with = array(
                         'userization'=>array(
                             'condition'=>'userization.user_id='.Yii::app()->user->id,
                       ));
            $criteria->together = true;
            $criteria->condition ='((LOWER(t.title) LIKE :s)) or ((LOWER(t.description) LIKE :s))';
            $criteria->params = array(':s'=>"%$s%");
            $resultsPr = new CActiveDataProvider('Project', array(
                'criteria' => $criteria,
                'sort'       => array(
                    'defaultOrder' => 't.updated_date DESC',
                ),
                'pagination' => array(
                    'pageSize' => 20,
                ),
            ));

            $this->render('search', compact('results', 'term', 'query','resultsPr'));
        }
    }
    
    // Function for returning a preview of the content:
// The preview is the first XXX characters.
private function preview_content($data, $limit = 400) {
   return substr($data, 0, $limit) . '...';
} // End of preview_content() function.
// Function for stripping junk out of content:
private function clean_content($data) {
   return strip_tags($data);
}
}