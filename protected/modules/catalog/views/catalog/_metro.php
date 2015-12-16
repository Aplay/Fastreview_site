<?php 
$cr=new CDbCriteria;
$cr->condition = 'city_id=:city_id';
$cr->params = array(':city_id'=>$this->city->id);
$cr->order = 'metro_name';
$metros = Metro::model()->findAll($cr);
/*$sql = 'SELECT DISTINCT t.nearest_metro 
		FROM orgs t
		LEFT JOIN "orgs_category" "categorization" ON ("categorization"."org"="t"."id")
		WHERE t.city_id='.$this->city->id .' and "categorization"."category"='.$model->id.' and t.status_org='.Orgs::STATUS_ACTIVE;

$connection=Yii::app()->db;
$command=$connection->createCommand($sql);
$datas=$command->queryALL();
*/
if($metros)
{
	echo '<div class="row">
	<div class="col-lg-8 col-lg-offset-1 col-md-8 col-md-offset-1 col-sm-8 col-sm-offset-1 col-xs-12">
	<h2 class="org_title" style="padding-left:26px;margin-bottom:26px;">'.$model->title .' по станциям метро '.$this->city->rodpad.'</h2>
	<div class="card">
	<div class="card-body card-padding">
	<div class="row">';
	foreach ($metros as $metro) 
	{
		$query = new Orgs(null);

        	$query->active()
                ->with(array(
                    'images',
                    'city',
                    ));
          
        	$query->applyCategoriesWithSub($model);
        	$cr=new CDbCriteria;
	        $cr->distinct = true; // предотвращает повтор объявлений на странице

	        if($this->city->id)
	        {
	        	$cr->addCondition('city.id='.$this->city->id.' and t.nearest_metro='.$metro->id);
	        }	
	        $query->getDbCriteria()->mergeWith($cr);
	        $provider = new CActiveDataProvider($query, array(
	            // Set id to false to not display model name in
	            // sort and page params
	            'id'=>false,
	            'pagination'=>array(
	                'pageSize'=>25,
	            )
	        ));
	        if($provider->totalItemCount > 0)
	        {
				$url =  Yii::app()->createAbsoluteUrl('/catalog/catalog/districtview', array('city'=>$this->city->url,  'url'=>$model->url, 'district'=>'metro', 'district_url'=>$metro->url));
				echo '<div class="col-sm-6 col-xs-12"><a class="graytogreen" href="'.$url.'">'.$metro->metro_name.'</a></div>';
			}
	}
	echo '</div></div></div></div></div>';
}
?>