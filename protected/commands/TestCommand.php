<?php
Yii::import('application.models.NetRu');
Yii::import('application.models.NetCity');
Yii::import('application.models.NetCountry');
class TestCommand extends CConsoleCommand
{
    
    
    public function actionIndex() {

                   echo "This is the test!!!<br>";
                   $ip = '80.67.208.0';
                   $sql = "select * from net_ru where begin_ip <= INET_ATON('".$ip."') AND end_ip >= INET_ATON('".$ip."')  limit 1";
                   $range = NetRu::model()->findBySql($sql);
                   $city = new NetCity;

                    if(!$range){
                        $range_id =  49849; 
                      } else {
                        $range_id = $range->city_id;  
                        $city = NetCity::model()->findByPk($range_id);
                        if($city){
                            $city_title = $city->title;
                            $country_title = $city->countries->title;
                        }
                      }
                    echo '<p>Ваш ip-адрес:'. $ip.'</p>';
                    echo '<p>Местонахождение:'. $city_title.' / '. $country_title.'</p>';

    }

}
?>
