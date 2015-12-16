<div class="">

                    <div class="block-header">
                        <h2>Платежи</h2>

                    </div>
                    
                    <div class="card">
                        
                        
                        <div class="card-body card-padding">
                        
                            
                            <table class="table i-table m-t-25 m-b-25" style="width:100%">
                                <thead class="t-uppercase">
                                    <th class="c-gray">Описание</th>
                                    <th class="c-gray">Дата</th>
                                    <th class="c-gray">Цена</th>
                                    <th class="c-gray">Количество</th>
                                    <th class="highlight">Всего</th>
                                </thead>
                                
                                <tbody>
                                    <thead>
        <?php 
        if($payments){
            foreach ($payments as $pay) { ?>
              <tr>
            <td>
                <p class="t-uppercase f-400"><?php echo isset($pay->org)?$pay->org->title:''; ?></p>
                <p class="text-muted hide"></p>
            </td>
             <td><?php echo Yii::app()->dateFormatter->format('d MMMM yyyy HH:mm', $pay->paid_at); ?></td>
            
            <td><?php
            if($pay->discount){

             echo $pay->amount+0;
            } else {
            $amount = $pay->amount;
            $amount =  $amount - ($amount * $pay->discount / 100);
            $amount = round($amount, 2);
            echo $amount+0;
            } ?> р.
            </td>
            <td><?php echo $pay->period.' '.Yii::t('site',Plan::getPlanSclon($pay->period_type),$pay->period); ?></td>
            <td class="highlight"><?php echo $pay->sum_discount+0; ?> р.</td>
        </tr>
          <?php  }
        } 
        ?>
                                        
                                        
                                       
                                    </thead> 
                                </tbody>
                            </table>
                            
                           
                        </div>
        
                    </div>
                    
                </div>