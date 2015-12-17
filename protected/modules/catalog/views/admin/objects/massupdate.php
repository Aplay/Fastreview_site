<?php
$filtrTitle =  $filtrAddress = $filtrRubric =    $filtrStatus =  '';
$query = $_GET['Orgs'];
if(!empty($query)){

    if(isset($query['title']))
        $filtrTitle = $query['title'];
    if(isset($query['city_search']))
        $filtrAddress = $query['city_search'];
    if($query['street']){
        if(!empty($filtrAddress))
            $filtrAddress .= ', ';
        $filtrAddress .= $query['street'];
    }
    if($query['dom']){
        if(!empty($filtrAddress))
            $filtrAddress .= ', ';
        $filtrAddress .= $query['dom'];
    }
    if(isset($query['rubric_title']))
        $filtrRubric = $query['rubric_title'];

  //  $filtrPerDate = $query['per_from'];
  //  $filtrToDate = $query['per_to'];
  //  $filtrLoguser = $query['log_user'];
    if(isset($query['status_org'])){
    	$filtrStatus = $query['status_org'];
    	$statuses[4] = 'Все статусы';
        $statuses += Orgs::getStatusNames();
    } 
    
}

if(!empty($query) && isset($dataProvider->data[0]) && !empty($dataProvider->data[0])){





$totalItemCount = $dataProvider->totalItemCount; // the total number of data items
$itemCount = $dataProvider->itemCount; // the number of data items in the current page
$pageSize = $dataProvider->pagination->pageSize; // number of items in each page
$pageCount = $dataProvider->pagination->pageCount; // number of pages


$model = $dataProvider->data[0]; // data items currently available

$phones = $model->orgsPhones;
$categories_ar =   array();
$categories = $model->categories;

$http = $model->orgsHttp;
$video = $model->orgsVideo;
if($categories){
    foreach($categories as $cats){
        $categories_ar[] = $cats->id;
    }
}


$worktime = $model->orgsWorktimes;

?>
<div class="panel-body padding-sm">
        <span class="panel-title">Массовый редактор компаний. <?php echo Yii::t('site', 'Selected|Selected', $masstotalall).' '.$masstotalall.' '.Yii::t('site', 'org|orgs', $masstotalall); ?>.
        <br>Фильтр выборки: <br>
<?php echo $filtrTitle?'Название: '.$filtrTitle.'<br>':''; ?>
<?php echo $filtrAddress?'Адрес: '.$filtrAddress.'<br>':''; ?>
<?php echo $filtrRubric?'Рубрика: '.$filtrRubric.'<br>':''; ?>

<?php // echo $filtrPerDate?'Редактировано с: '.$filtrPerDate.'<br>':''; ?>
<?php // echo $filtrToDate?'Редактировано по: '.$filtrToDate.'<br>':''; ?>
<?php // echo $filtrLoguser?'Редактировал: '.$filtrLoguser.'<br>':''; ?>
<?php echo $filtrStatus?'Статус: '.$statuses[$filtrStatus].'<br>':''; ?>
<p><strong>Отметьте галочкой поля для редактирования. При дополнении перед словом добавить пробел, если необходимо.
</strong></p>
        </span>
</div>
<?php
$this->renderPartial('_formmass', array(
	'model'=>$model,
    'phones'=>$phones,
    'categories_ar'=>$categories_ar,
    'http'=>$http,
    'video'=>$video,
    'worktime'=>$worktime,
	)); 

} else if(!empty($query)){
    ?>
<div class="panel-body padding-sm">
    <span class="panel-title">Массовый редактор компаний. <?php echo Yii::t('site', 'Selected|Selected', $masstotalall).' '.$masstotalall.' '.Yii::t('site', 'org|orgs', $masstotalall); ?>.
    <br>Фильтр выборки: <br>
<?php echo $filtrTitle?'Название: '.$filtrTitle.'<br>':''; ?>
<?php echo $filtrAddress?'Адрес: '.$filtrAddress.'<br>':''; ?>
<?php echo $filtrRubric?'Рубрика: '.$filtrRubric.'<br>':''; ?>

<?php // echo $filtrPerDate?'Редактировано с: '.$filtrPerDate.'<br>':''; ?>
<?php // echo $filtrToDate?'Редактировано по: '.$filtrToDate.'<br>':''; ?>
<?php // echo $filtrLoguser?'Редактировал: '.$filtrLoguser.'<br>':''; ?>
<?php echo $filtrStatus?'Статус: '.$statuses[$filtrStatus].'<br>':''; ?>
    </span>
</div>
<?php
    $this->renderPartial('application.views.common._flashMessage');
	echo 'Организации по заданному фильтру отсутствуют';
} else {
    $this->renderPartial('application.views.common._flashMessage');
    echo 'Организации по заданному фильтру отсутствуют';
}
?>