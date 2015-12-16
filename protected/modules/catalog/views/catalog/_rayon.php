<?php 
$cr=new CDbCriteria;
$cr->condition = 'city_id=:city_id';
$cr->params = array(':city_id'=>$this->city->id);
$cr->order = 'district_name';
$districts = District::model()->findAll($cr);
if($districts)
{
	echo '<div class="row" style="margin:100px 0;">
	<div class="col-lg-8 col-lg-offset-1 col-md-8 col-md-offset-1 col-sm-6  col-xs-6">
	<h2>'.$model->title .' по районам '.$this->city->rodpad.'</h2>
	<div class="row">';

	foreach ($districts as $district) 
	{
		if((mb_strpos($district->district_name, 'район', 0, 'UTF-8') !== false) && (mb_strpos($district->district_name, 'микрорайон', 0, 'UTF-8') === false))
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
	        	$cr->join = 'LEFT JOIN "orgs_district" "orgsdistrict" ON ("orgsdistrict"."org"="t"."id")';
	        	$cr->addCondition('city.id='.$this->city->id.' and orgsdistrict.district='.$district->id);
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
				$url =  Yii::app()->createAbsoluteUrl('/catalog/catalog/districtview', array('city'=>$this->city->url,  'url'=>$model->url, 'district'=>'rayon', 'district_url'=>$district->url));
				echo '<div class="col-sm-6 col-xs-12"><a href="'.$url.'">'.$district->district_name.'</a></div>';
			}
		}
	}
	echo '</div></div></div>';
}
?>