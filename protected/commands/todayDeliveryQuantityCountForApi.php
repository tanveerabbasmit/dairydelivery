<?php

/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 10/11/2017
 * Time: 5:06 PM
 */
class todayDeliveryQuantityCountForApi
{
   public static function getTodayDeliveryCountWeeklyRegularAndSpecial($clientID , $product_id ,$todaydate){



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

     $productQuery = " select sum(quantity) as quantity ,product_name ,product_id ,deliveryTime from (
                Select (IFNULL(sum(cpfq.quantity) ,0) ) as quantity ,p.name as product_name ,p.product_id  , 0 as deliveryTime  from client_product_frequency as cpf
                    left join  halt_regular_orders as hro ON hro.client_id = cpf.client_id and '$todaydate' between hro.start_date AND hro.end_date and hro.product_id = cpf.product_id
                    LEFT JOIN client_product_frequency_quantity as cpfq  ON cpfq.client_product_frequency_id = cpf.client_product_frequency
                    AND cpfq.frequency_id = '$todayfrequencyID' and  hro.halt_regular_orders_id is  NULL
                    left join product as p ON p.product_id = cpf.product_id
                    where     cpf.client_id = '$clientID'  and  cpf.client_id = $product_id
                    group by p.product_id
                   
                     union
                     
                  select IFNUll(sum(so.quantity) ,0) as quantity ,p.name as product_name ,p.product_id , 0 as deliveryTime from special_order as so
                     left join product as p ON p.product_id = so.product_id
                     where  so.client_id = '$clientID' and so.product_id =$product_id  AND '$todaydate' between 
                     so.start_date AND so.end_date
                     group by p.product_id
                     
                     ) as abcd
                     group by product_id " ;


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
            return $quantity ;

   }

   public static function getTodaySpecialOrder($clientID , $product_id ,$todaydate){
       $todaydate =  $todaydate;

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
       return $quantity ;
   }

   public static function getNotDeliveryTodayResult($cleintLIst ,$todaydate){

       $todaydate =  $todaydate;


       $queryNotDelivery = " select ndr.client_id ,  (ndr.time) as time  ,rt.reasonType_name from not_delivery_record as ndr 
               left join not_delivery_reasontype as rt ON rt.not_delivery_reasonType_id = ndr.not_delivery_reasonType_id 
               where ndr.client_id in  ($cleintLIst) and date(ndr.not_delivery_dateTime) = '$todaydate' ";


       $resultNotDelivery = Yii::app()->db->createCommand($queryNotDelivery)->queryAll();



       $notDelivery_finalResult = array();
       foreach ($resultNotDelivery as $value){
           $oneArray = array();
           $oneArray['time'] = $value['time'];
           $oneArray['reasonType_name'] = $value['reasonType_name'];
           $client_id = $value['client_id'];
           $notDelivery_finalResult[$client_id] = $oneArray ;
       }
      return $notDelivery_finalResult ;

   }
   public static function getDeliveryTodayAllClient($cleintLIst ,$todaydate){

       $todaydate =  $todaydate;
       $query = " Select d.client_id ,d.edit_by_user ,dd.product_id ,
                 ifnull((dd.amount) , 0) as amount , ifnull((dd.quantity) , 0) as deliveredQuantity , d.time from delivery as d
            left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
            where d.client_id in ($cleintLIst)  AND d.date = '$todaydate' ";

       $cleintPRoductList = Yii::app()->db->createCommand($query)->queryAll();
       $finalClientList = array();
       foreach($cleintPRoductList as $value){
           $product_id = $value['product_id'];
           $client_id  = $value['client_id'];
           $indexValue = $client_id."_".$product_id ;
            $amount = $value['amount'];
            $deliveredQuantity = $value['deliveredQuantity'];
            $oneobject =array();
            $oneobject['deliveredQuantity'] =$value['deliveredQuantity'];
            $oneobject['time'] =$value['time'];
            if($deliveredQuantity>0){
                $oneobject['delivery_rate'] =$amount/$deliveredQuantity;
            }else{
                $oneobject['delivery_rate'] =0;
            }

            $oneobject['edit_by_user'] =$value['edit_by_user'];
           $finalClientList[$indexValue] =$oneobject;
       }
       return $finalClientList ;
   }
   public static function getClientBaseProductPRize_portal($cleintLIst,$todaydate){



       $query = " select cpp.* from client as c
                right join client_product_price  as cpp ON cpp.client_id = c.client_id
                  where c.client_id in ($cleintLIst)";
       $cleintPRoductList = Yii::app()->db->createCommand($query)->queryAll();
        $finalClientList = array();
        foreach($cleintPRoductList as $value){
          $product_id = $value['product_id'];
          $client_id  = $value['client_id'];
          $indexValue = $client_id."_".$product_id ;
           $finalClientList[$indexValue] =$value['price'];
        }
        $future_list = future_rate_list_data::client_product_wise_array($todaydate);

        foreach ($future_list as $key=>$value){
            $finalClientList[$key] =$value;
        }
        return $finalClientList ;
   }
   public static function getClientBaseProductPRize($cleintLIst){

       $query = " select cpp.* from client as c
                right join client_product_price  as cpp ON cpp.client_id = c.client_id
                  where c.client_id in ($cleintLIst)";

       $cleintPRoductList = Yii::app()->db->createCommand($query)->queryAll();

        $finalClientList = array();
        foreach($cleintPRoductList as $value){
          $product_id = $value['product_id'];
          $client_id  = $value['client_id'];
          $indexValue = $client_id."_".$product_id ;
           $finalClientList[$indexValue] =$value['price'];
        }






        return $finalClientList ;
   }
   public static function getClientBaseNot_delivery_reason_type($cleintLIst,$date){

       $query = " select r.reasonType_name ,dr.client_id from not_delivery_record as dr
         left join not_delivery_reasontype as  r ON dr.not_delivery_record_id =r.not_delivery_reasonType_id
         where date(dr.not_delivery_dateTime) = '$date'  and dr.client_id in ($cleintLIst)";


       $cleintPRoductList = Yii::app()->db->createCommand($query)->queryAll();


        $finalClientList = array();
        foreach($cleintPRoductList as $value){

          $client_id  = $value['client_id'];
          $reasonType_name  = $value['reasonType_name'];

           $finalClientList[$client_id] =$reasonType_name;

        }

        return $finalClientList ;
   }
}