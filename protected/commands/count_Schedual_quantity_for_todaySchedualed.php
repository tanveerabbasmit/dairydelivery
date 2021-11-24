<?php

/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 10/30/2017
 * Time: 3:28 PM
 */
class count_Schedual_quantity_for_todaySchedualed
{

    public static function getOneCustomerTodayIntervalSceduler_with_date_future_date_for_demand_todayScheduled($product_id ,$todayDate){


        date_default_timezone_set("Asia/Karachi");

         $company_id = Yii::app()->user->getState('company_branch_id');
         $clientObject = Client::model()->findAllByAttributes(array("company_branch_id" => $company_id));


        $total_Count = 0 ;
         foreach ($clientObject as $client_value){

            $client_id = $client_value['client_id'];
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
             /* var_dump($clientResult);
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

                     $total_days_to_delivery % $interval_days ;
                     if($total_days_to_delivery % $interval_days == 0){
                         $interval_quantity = $clientResult[0]['product_quantity'];
                     }
                 }




             }

             $total_Count = $total_Count + $interval_quantity;




         }


        return $total_Count;
    }

    public static function getTodayDeliveryCountWeeklyRegularAndSpecial_demandCount_todayScheduled($product_id ,$todaydate){

        $todaydate =  $todaydate;
        $timestamp = strtotime($todaydate);
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

        $company_id = Yii::app()->user->getState('company_branch_id');
        $clientObject = Client::model()->findAllByAttributes(array("company_branch_id" => $company_id));

        $total_Count = 0 ;
        foreach ($clientObject as $client_value){
            $clientID = $client_value['client_id'];
            $productQuery = "  Select (IFNULL(sum(cpfq.quantity) ,0) ) as quantity ,p.name as product_name ,p.product_id  , 0 as deliveryTime  from client_product_frequency as cpf
                    left join  halt_regular_orders as hro ON hro.client_id = cpf.client_id and '$todaydate' between hro.start_date AND hro.end_date and hro.product_id = cpf.product_id
                    LEFT JOIN client_product_frequency_quantity as cpfq  ON cpfq.client_product_frequency_id = cpf.client_product_frequency
                    AND cpfq.frequency_id = '$todayfrequencyID' and  hro.halt_regular_orders_id is  NULL
                    left join product as p ON p.product_id = cpf.product_id
                    where     cpf.client_id = '$clientID'  and  cpf.product_id = $product_id
                    group by p.product_id " ;

            $productLIst = Yii::app()->db->createCommand($productQuery)->queryAll();
            $quantity = 0;
            if($productLIst){
                $quantity  = $productLIst[0]['quantity'];
            }
         $total_Count = $total_Count + $quantity ;

        }



         return $total_Count ;
    }
    public static function getTodaySpecialOrder_demandCount_todayScheduled($product_id ,$todaydate){

        $todaydate =  $todaydate;
        $company_id = Yii::app()->user->getState('company_branch_id');
        $clientObject = Client::model()->findAllByAttributes(array("company_branch_id" => $company_id));

        $total_Count = 0 ;
        foreach ($clientObject as $client_value){
          $clientID = $client_value['client_id'];

             $productQuery = "  select IFNUll(sum(so.quantity) ,0) as quantity ,p.name as product_name ,p.product_id , 0 as deliveryTime from special_order as so
                     left join product as p ON p.product_id = so.product_id
                     where  so.client_id = '$clientID' and so.product_id =$product_id  AND '$todaydate' between 
                     so.start_date AND so.end_date
                     group by p.product_id " ;
            $productLIst = Yii::app()->db->createCommand($productQuery)->queryAll();
            $quantity = 0;
            if($productLIst){
                $quantity  = $productLIst[0]['quantity'];
            }
            $total_Count = $total_Count + $quantity ;
        }

          return $total_Count ;

    }



}