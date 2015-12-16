<?php
// use:
// yiic stat index
class StatCommand extends CConsoleCommand
{
    

    public function actionIndex() {

        echo 'hi'; die();
        $connection=Yii::app()->db;
        $sql = "SELECT id, title FROM city WHERE (pos>0 and pos<10000) order by title";
	    $command=$connection->createCommand($sql);
		$cities=$command->queryALL();
     /*    
		$sql = "SELECT distinct city_id FROM orgs_category WHERE city_id is not null and status_org=".Orgs::STATUS_ACTIVE;
		$command=$connection->createCommand($sql);
		$datas=$command->queryALL();
		$city2 = array();
		if(!empty($datas)){
			foreach ($datas as $data) {
				$city2[] = $data['city_id'];
			}
		} */
		$cityall = $city_nul = array();
		if(!empty($cities)){
			
			foreach($cities as $city){
				
				$sql = "SELECT id, status_org FROM orgs WHERE city_id=".$city['id'];
				$command=$connection->createCommand($sql);
				$datas=$command->queryALL();
				$pub = 0; $notpub = 0;
				if($datas){
					foreach ($datas as $data) {
						if($data['status_org']==1){
							$pub++;
						} else {
							$notpub++;
						}
					}
				}
				$cityall[] = array('id'=>$city['id'],'title'=>$city['title'],'pub'=>$pub,'notpub'=>$notpub);
				/*if(!in_array($city->id, $city2)){
					$city_nul[] = array('id'=>$city->id,'title'=>$city->title);
				}*/
			}
		}
		if(!empty($cityall)){
			foreach ($cityall as $city) {
				echo $city['title'].';'.$city['id'].';'.$city['pub'].';'.$city['notpub']."\n";
			}
		}

    }

}
?>
