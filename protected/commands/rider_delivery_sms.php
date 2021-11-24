<?php

/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 10/9/2017
 * Time: 3:13 PM
 */
class rider_delivery_sms
{


   public static function riderAlert($client_id , $company_id){

    $clientObject= Client::model()->findByPk(intval($client_id));
      $zone_id = $clientObject['zone_id'];
       $todaydate =  date("Y-m-d");
       $rider_deliveryAlertObject = RiderDeliveryAlert::model()->findByAttributes(array('zone_id'=>$zone_id , 'date'=>$todaydate));
     if($rider_deliveryAlertObject){


       }else{
           $rider_deliveryAlertObject = new RiderDeliveryAlert();
           $rider_deliveryAlertObject->zone_id = $zone_id;
           $rider_deliveryAlertObject->date = $todaydate;
           $rider_deliveryAlertObject->status = 1;
           $rider_deliveryAlertObject->save();

          $clientQuery = " select c.cell_no_1 ,c.client_id ,c.fullname  from client as c
                 where c.zone_id =  $zone_id and c.client_id !='$client_id' ";
           $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();
          // $company_id = Yii::app()->user->getState('company_branch_id');
           $companyObject = Company::model()->findByPK(intval($company_id));
           $sms = $companyObject['rider_delivery_sms'];

           $companyObject  =  utill::get_companyTitle($company_id);

           $companyMask = $companyObject['sms_mask'];
           $getmessage = $companyObject['company_title'];

            $message = $sms."\n\n".$getmessage;


           foreach($clientResult as $value){

             $client_id = $value['client_id'];
               $phoneNo =  $value['cell_no_1'];
               $reponce = rider_delivery_sms::checktodayDelivery($client_id);
               if($reponce){

                   $phoneNo = $value['cell_no_1'];
                   $fullname = $value['fullname'];

                   $phoneNo = $value['cell_no_1'];



                   smsLog::saveSms($client_id ,$company_id ,$phoneNo ,$fullname ,$message);
                  utill::sendSMS2($phoneNo , $message , $companyMask ,$company_id,$client_id);
               }


           }

       }


   }



   public static function checktodayDelivery($clientID){

       $todaydate =  date("Y-m-d");
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


       $productQuery = "Select (IFNULL(sum(cpfq.quantity) ,0) + IFNULL(sum(so.quantity),0)) as quantity ,p.name as product_name ,p.product_id  , 0 as deliveryTime  from client_product_frequency as cpf
                left join  halt_regular_orders as hro ON hro.client_id = cpf.client_id and '$todaydate' between hro.start_date AND hro.end_date and hro.product_id = cpf.product_id
                LEFT JOIN client_product_frequency_quantity as cpfq  ON cpfq.client_product_frequency_id = cpf.client_product_frequency
                AND cpfq.frequency_id = '$todayfrequencyID' and  hro.halt_regular_orders_id is  NULL
                left join product as p ON p.product_id = cpf.product_id
                left join special_order as so ON so.client_id = '$clientID' AND so.product_id = p.product_id AND '$todaydate' between so.start_date AND so.end_date
                where cpf.client_id = '$clientID' " ;

       $productLIst = Yii::app()->db->createCommand($productQuery)->queryAll();

        $quantity = $productLIst[0]['quantity'];
       $result = false ;
        if($quantity >0){
            $result = true ;
        }else{

           $intervalQunatity = utill::getOneCustomerTodayIntervalScedulerForAllProduct($clientID);
            if($intervalQunatity > 0){
                $result = true ;
            }
        }

       return $result;
   }
}