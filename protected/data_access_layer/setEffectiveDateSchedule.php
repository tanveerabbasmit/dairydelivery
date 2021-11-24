<?php
/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 3/3/2017
 * Time: 3:31 PM
 */
class setEffectiveDateSchedule{

    public static $response = ['success'=>FALSE , 'message'=>'','data'=>[]];

     public static function checkEffectiveDateSchedule($company_id){

         date_default_timezone_set("Asia/Karachi");
         $todaydate = date("Y-m-d");
        // $company_id = Yii::app()->user->getState('company_branch_id');

         $query ="select e.* from client as c
            right join effective_date_interval_schedule as e ON c.client_id =e.client_id
            where c.company_branch_id = '$company_id' and e.start_interval_scheduler <= '$todaydate' ";


        $clientResult =  Yii::app()->db->createCommand($query)->queryAll();


        foreach($clientResult as $value){
               $effective_date_interval_schedule_id = $value['effective_date_interval_schedule_id'];
               $client_id = $value['client_id'];
               $product_id = $value['product_id'];
               $start_interval_scheduler = $value['start_interval_scheduler'];
               $interval_days = $value['interval_days'];
               $product_quantity = $value['product_quantity'];
                $intervalSchedule = IntervalScheduler::model()->findByAttributes(array("client_id"=>$client_id , "product_id"=>$product_id));
                if($intervalSchedule){
                }else{
                    $intervalSchedule = new IntervalScheduler();
                }
                $intervalSchedule->client_id =$client_id;
                $intervalSchedule->product_id =$product_id;
                $intervalSchedule->interval_days = $interval_days;
                $intervalSchedule->product_quantity = $product_quantity;
                $intervalSchedule->start_interval_scheduler = $start_interval_scheduler;
                $intervalSchedule->is_halt =1;
                $intervalSchedule->halt_start_date   =date('Y-m-d', strtotime(' -1 day'));
                $intervalSchedule->halt_end_date   =date('Y-m-d', strtotime(' -1 day'));
                if($intervalSchedule->save()){





                  $change_scheduler_record =new ChangeSchedulerRecord();
                  $change_scheduler_record->client_id =$client_id;
                  $change_scheduler_record->company_id =$company_id;
                  $change_scheduler_record->change_form =1;
                  $change_scheduler_record->date =$start_interval_scheduler;
                  $change_scheduler_record->admin_view =1;

                  if($change_scheduler_record->save()){

                  }else{

                  }


                  EffectiveDateIntervalSchedule::model()->deleteByPk(intval($effective_date_interval_schedule_id));

                    $clientFrequency =  ClientProductFrequency::model()->findByAttributes(array('client_id'=>$client_id , 'product_id'=>$product_id));
                    if($clientFrequency){

                        $clientFFID = (($clientFrequency['client_product_frequency']));

                        ClientProductFrequencyQuantity::model()->deleteAllByAttributes(array('client_product_frequency_id'=>$clientFFID));
                        $clientFrequency->delete();
                    }

                }else{


                }

        }
         setEffectiveDateSchedule::updateEffectiveDateWeeklySchedule($company_id);
     }

     public static function updateEffectiveDateWeeklySchedule($company_id){

         date_default_timezone_set("Asia/Karachi");
         $todaydate = date("Y-m-d");
        // $company_id = Yii::app()->user->getState('company_branch_id');

        $query_effective_date = "select e.* from client as c
         right join effective_date_schedule as e ON c.client_id = e.client_id
         where c.company_branch_id ='$company_id' and e.date <= '$todaydate'";

         $result_effective_date =  Yii::app()->db->createCommand($query_effective_date)->queryAll();

          foreach ($result_effective_date as $value){

              $effective_date_schedule_id =$value['effective_date_schedule_id'];
              $client_id =$value['client_id'];
              $product_id =$value['product_id'];
              $date =$value['date'];

               /*delete Interval */

                  $clientSchedulerObject  = IntervalScheduler::model()->findByAttributes(array('client_id'=>$client_id , 'product_id'=>$product_id));
                  if($clientSchedulerObject){

                      $clientSchedulerObject->delete();

                  }

               /*delete Interval */

              /*save weekly Schedual Master Table Start*/

              $clientFrequency =  ClientProductFrequency::model()->findByAttributes(array('client_id'=>$client_id , 'product_id'=>$product_id));

              $clientFFID = $clientFrequency['client_product_frequency'];

              ClientProductFrequencyQuantity::model()->deleteAllByAttributes(array('client_product_frequency_id'=>$client_id));


              if(isset($clientFrequency)){
                  // $clientFrequency->delete();
                  $client_product_frequency = $clientFrequency['client_product_frequency'];
                  $clientFrequency->orderStartDate =$date ;
                  $clientFrequency->save();
              }else{
                  $ClientProductFrequency = new ClientProductFrequency();
                  $ClientProductFrequency->client_id = $client_id ;
                  $ClientProductFrequency->product_id = $product_id ;
                  $ClientProductFrequency->quantity = '0' ;
                  $ClientProductFrequency->total_rate = '0' ;
                  $ClientProductFrequency->frequency_id = '1' ;
                  $ClientProductFrequency->orderStartDate =$date ;
                  $ClientProductFrequency->save();
                  $client_product_frequency = $ClientProductFrequency['client_product_frequency'];
              }

              $change_scheduler_record =new ChangeSchedulerRecord();
              $change_scheduler_record->client_id =$client_id;
              $change_scheduler_record->company_id =$company_id;
              $change_scheduler_record->change_form =1;
              $change_scheduler_record->date =$date;
              $change_scheduler_record->admin_view =1;
              $change_scheduler_record->save();


              /*save weekly Schedual Master Table end*/

              $effective_date_schedule_object=EffectiveDateScheduleFrequency::model()->findAllByAttributes(
                  array("effective_date_schedule_id"=>$effective_date_schedule_id)
              );


              ClientProductFrequencyQuantity::model()->deleteAllByAttributes(array('client_product_frequency_id'=>$client_product_frequency));
              foreach($effective_date_schedule_object as $date_value){

                  $effective_date_schedule_frequency_id = $date_value['effective_date_schedule_frequency_id'];
                  $frequency_id = $date_value['frequency_id'];
                  $quantity = $date_value['quantity'];
                  $daySave =new ClientProductFrequencyQuantity();
                  $daySave->client_product_frequency_id = $client_product_frequency ;
                  $daySave->frequency_id= $frequency_id;
                  $daySave->quantity= $quantity;
                  $daySave->preferred_time_id = 1;
                  $daySave->save();

                  EffectiveDateScheduleFrequency::model()->deleteByPk(intval($effective_date_schedule_frequency_id));

              }

              /* delete effective Quantity  start*/
                EffectiveDateSchedule::model()->deleteByPk(intval($effective_date_schedule_id));



               /* $delete = "delete  FROM  effective_date_schedule_frequency
                  where effective_date_schedule_id ='$effective_date_schedule_id'";

              Yii::app()->db->createCommand($delete)->queryAll();*/
               /* delete effective Quantity  end*/

          }




     }

      public static function getFutureDateQuantity($clientList ,$date ){

            $timestamp = strtotime($date);
            $day = date('D', $timestamp);
            $todayfrequencyID = '';
            if($day == 'Mon'){
              $todayfrequencyID = 1 ;
            }elseif($day == 'Tue'){
              $todayfrequencyID = 2;
            }elseif($day == 'Wed'){
              $todayfrequencyID = 3 ;
            }elseif($day == 'Thu'){
              $todayfrequencyID = 4 ;
            }elseif($day == 'Fri'){
              $todayfrequencyID = 5 ;
            }elseif($day == 'Sat'){
              $todayfrequencyID = 6 ;
            }else{
              $todayfrequencyID = 7 ;
            }


            $query_interval="SELECT * FROM effective_date_interval_schedule ef
              WHERE ef.client_id IN ($clientList ,0) AND ef.start_interval_scheduler <='$date' ";

            $interval_result = Yii::app()->db->createCommand($query_interval)->queryAll();
            $client_result = array();

            foreach ($interval_result as $value){
             $clint_id =$value['client_id'];

             $product_id =$value['product_id'];

              $client_product = $clint_id.$product_id;
              $client_result[$client_product] = $value['product_quantity'];
            }

            $query_weekly = "SELECT * FROM effective_date_schedule AS e
            LEFT JOIN effective_date_schedule_frequency AS f ON f.effective_date_schedule_id = e.effective_date_schedule_id
            WHERE f.frequency_id='$todayfrequencyID' AND e.DATE <= '$date' AND e.client_id IN ($clientList.0) ";



            $weekly_result = Yii::app()->db->createCommand($query_weekly)->queryAll();



            foreach ($weekly_result as $value){
              $clint_id =$value['client_id'];

              $product_id =$value['product_id'];

              $client_product = $clint_id.$product_id;
              $client_result[$client_product] = $value['quantity'];
            }

          return $client_result ;

      }

}