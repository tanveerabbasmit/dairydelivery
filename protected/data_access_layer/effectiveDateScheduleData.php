<?php
/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 3/3/2017
 * Time: 3:31 PM
 */
class effectiveDateScheduleData{
    public static $response = ['success'=>FALSE , 'message'=>'','data'=>[]];



    public static function effectiveDate_interval_Client_list($todaydate,$lientID_list){

           $query ="SELECT  e.client_id ,e.product_id FROM effective_date_interval_schedule AS e
              WHERE e.client_id IN ($lientID_list) AND e.start_interval_scheduler <='$todaydate'";


        $clientResult =  Yii::app()->db->createCommand($query)->queryAll();

        $result = array();

         foreach ($clientResult as $value){
             $client_id = $value['client_id'];
             $product_id = $value['product_id'];
             $client_product = $client_id.$product_id;
             $result[$client_product] = true;
          }

         return $result ;

    }
    public static function effectiveDate_weekly_Client_list($todaydate, $lientID_list){

        $query ="SELECT  e.client_id ,e.product_id FROM effective_date_schedule AS e
              WHERE e.client_id IN ($lientID_list)  AND e.DATE <='$todaydate'";

        $clientResult =  Yii::app()->db->createCommand($query)->queryAll();

        $result = array();

         foreach ($clientResult as $value){
             $client_id = $value['client_id'];
             $product_id = $value['product_id'];
             $client_product = $client_id.$product_id;
             $result[$client_product] = true;
          }

         return $result ;

    }


    public static function effectiveDate_interval_Client_list_app($todaydate,$lientID_list){

        $query ="SELECT  e.client_id ,e.product_id FROM effective_date_interval_schedule AS e
              WHERE e.client_id IN ($lientID_list) AND e.start_interval_scheduler <='$todaydate'";


        $clientResult =  Yii::app()->db->createCommand($query)->queryAll();

        $result = array();

        foreach ($clientResult as $value){
            $client_id = $value['client_id'];
            $product_id = $value['product_id'];
            $client_product = $client_id.$product_id;
            $result[$client_id] = true;
        }

        return $result ;

    }
    public static function effectiveDate_weekly_Client_list_app($todaydate, $lientID_list){

        $query ="SELECT  e.client_id ,e.product_id FROM effective_date_schedule AS e
              WHERE e.client_id IN ($lientID_list)  AND e.DATE <='$todaydate'";

        $clientResult =  Yii::app()->db->createCommand($query)->queryAll();

        $result = array();

        foreach ($clientResult as $value){
            $client_id = $value['client_id'];
            $product_id = $value['product_id'];
            $client_product = $client_id.$product_id;
            $result[$client_id] = true;
        }

        return $result ;

    }




    public static function effective_getOneCustomerTodayIntervalSceduler_with_date_future_date($client_id , $product_id ,$todayDate){
        //   $client_id = '3665';

        date_default_timezone_set("Asia/Karachi");

        $oneClientQuery = "  select list.interval_days ,list.product_quantity,list.start_interval_scheduler,(
                                 select d.date from delivery as d
                                        left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                                        where d.date >= list.start_interval_scheduler and d.client_id = '$client_id' and dd.product_id = '$product_id' 
                                        order by d.date DESC limit 1
                                                  ) as last_date_delivery ,
                                     (
                                     select date(ndr.not_delivery_dateTime) from not_delivery_record as ndr 
                                         where date(ndr.not_delivery_dateTime) >=list.start_interval_scheduler and ndr.client_id ='$client_id'
                                        order by ndr.not_delivery_record_id desc
                                          limit 1
                                     ) as last_not_delivery
                                    
                                      from  interval_scheduler as list 
                                   where list.client_id = '$client_id' and list.product_id='$product_id' 
                                    and list.start_interval_scheduler <= '$todayDate'
                                    and '$todayDate' not between list.halt_start_date and list.halt_end_date " ;


        $clientResult =  Yii::app()->db->createCommand($oneClientQuery)->queryAll();

        /*var_dump($clientResult);
            die();*/
        $interval_quantity = 0;
        if($clientResult){
            $interval_days = $clientResult[0]['interval_days'];
            $last_date_delivery = $clientResult[0]['last_date_delivery'];
            $start_interval_scheduler = $clientResult[0]['start_interval_scheduler'];

            $last_not_delivery = $clientResult[0]['last_not_delivery'];
            if($last_not_delivery){



                if($last_date_delivery > $last_not_delivery){
                    $today_time = strtotime($todayDate);
                    $last_delivery_time = strtotime($last_date_delivery);
                    $diff_time = $today_time - $last_delivery_time;
                    $total_days_to_delivery =floor($diff_time/(60*60*24));
                    $total_days_to_delivery % $interval_days ;
                    if($total_days_to_delivery % $interval_days == 0){
                        $interval_quantity = $clientResult[0]['product_quantity'];
                    }
                }else{

                    $today_time = strtotime($todayDate);
                    $tomorrow_timestamp = strtotime('+1 day', strtotime($last_not_delivery));
                    $tomorrow_date =  date("Y-m-d",$tomorrow_timestamp);
                    $today_time = strtotime($todayDate);
                    $last_delivery_time = strtotime($tomorrow_date);
                    $diff_time = $today_time - $last_delivery_time;
                    $total_days_to_delivery =floor($diff_time/(60*60*24));
                    if($total_days_to_delivery % $interval_days == 0){
                        $interval_quantity = $clientResult[0]['product_quantity'];
                    }

                    $current_todayDate = date("Y-m-d");
                    if($todayDate == $last_not_delivery){
                        $interval_quantity = $clientResult[0]['product_quantity'];
                    }
                }

            }elseif($last_date_delivery){

                $interval_days ;
                $today_time = strtotime($todayDate);
                $last_delivery_time = strtotime($last_date_delivery);
                $diff_time = $today_time - $last_delivery_time;
                $total_days_to_delivery =floor($diff_time/(60*60*24));
                $total_days_to_delivery % $interval_days ;
                if($total_days_to_delivery % $interval_days == 0){
                    $interval_quantity = $clientResult[0]['product_quantity'];
                }

            }else{

                $today_time = strtotime($todayDate);

                $interval_days ;
                $last_delivery_time = strtotime($start_interval_scheduler);
                $diff_time = $today_time - $last_delivery_time;
                $total_days_to_delivery =floor($diff_time/(60*60*24));



                if($total_days_to_delivery % $interval_days == 0){
                    $interval_quantity = $clientResult[0]['product_quantity'];
                }

            }
        }
        /* echo $interval_quantity;
            die();*/

        return $interval_quantity;
    }

}