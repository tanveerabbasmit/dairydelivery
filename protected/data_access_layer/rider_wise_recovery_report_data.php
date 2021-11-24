<?php


class rider_wise_recovery_report_data
{
   public static function get_payment_list($product_rate_client_wise,$year,$monthNum){


             $client_ids =  implode(",",$product_rate_client_wise);


            $query = "SELECT
               pm.client_id,
               sum(pm.amount_paid) as amount_paid ,
                u.full_name,r.fullname 
                FROM payment_master as pm
                LEFT JOIN     user AS u ON u.user_id = pm.user_id
                LEFT JOIN rider AS r ON r.rider_id = pm.rider_id AND r.rider_id !=0
                where pm.client_id IN ($client_ids) and 
                  month(pm.bill_month_date)='$monthNum' 
                and year(pm.bill_month_date) = '$year'
                GROUP BY pm.client_id ";



       $Payment_result =  Yii::app()->db->createCommand($query)->queryAll();



       $final_result = [];

       foreach ($Payment_result as $value){

           $client_id =$value['client_id'];
           $final_result[$client_id]=$value;
       }

       return $final_result;


   }

   public static function total_delivery($client_list_arry ,$start_calculation,$endDate){

       $company_id = Yii::app()->user->getState('company_branch_id');
       $client_ids =  implode(",",$client_list_arry);

       if($company_id==1){
           $queryDelivery2 ="Select d.client_id, IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
                 where d.client_id in ($client_ids) AND (d.date between '$start_calculation' and '$endDate')
                   group by d.client_id ";

       }else{
           $queryDelivery2 ="Select d.client_id, IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
                 where d.client_id in ($client_ids) AND d.date <= '$endDate'
                  group by d.client_id
                 ";

       }







       $Payment_result =  Yii::app()->db->createCommand($queryDelivery2)->queryAll();

        $fina_result= [];
       foreach ($Payment_result as $value){

           $client_id = $value['client_id'];
           $deliverySum = $value['deliverySum'];

           $fina_result[$client_id] = $deliverySum;

       }

      return $fina_result;

   }

   public static function payment_between_date_range($client_list_arry,$start_calculation,$endDate_p){
       $bad_debt_opeening_amount = bad_debt_record_data::total_bad_debt_amount_client_wise();


       $client_ids =  implode(",",$client_list_arry);
       $queryDelivery2 ="Select pm.client_id, IFNULL(sum(pm.amount_paid) , 0) as remainingAmount from payment_master as pm
              where pm.client_id in ($client_ids) AND (pm.bill_month_date  between  '$start_calculation' and '$endDate_p')
                 group by pm.client_id ";


       $Payment_result =  Yii::app()->db->createCommand($queryDelivery2)->queryAll();





       $fina_result= [];
       foreach ($Payment_result as $value){

           $client_id = $value['client_id'];


           $deliverySum = $value['remainingAmount'];
           if(isset($bad_debt_opeening_amount[$client_id])){
               $deliverySum = $deliverySum+ $bad_debt_opeening_amount[$client_id];
           }
           $fina_result[$client_id] = $deliverySum;

       }

       return $fina_result;

   }

   public static function total_currect_month_delivery($client_list_arry ,$monthNum,$year){

       $client_ids =  implode(",",$client_list_arry);

       $queryDelivery2 ="Select d.client_id, IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
                                where d.client_id in ($client_ids) 
                                AND  month(d.date)='$monthNum'  
                                and year(d.date) = '$year'
                               group by d.client_id ";


       $Payment_result =  Yii::app()->db->createCommand($queryDelivery2)->queryAll();





       $fina_result= [];
       foreach ($Payment_result as $value){

           $client_id = $value['client_id'];
           $deliverySum = $value['deliverySum'];

           $fina_result[$client_id] = $deliverySum;

       }

       return $fina_result;
   }

   public static function get_delivery_opening($client_list_arry,$start_calculation,$endDate_p){
       $company_id = Yii::app()->user->getState('company_branch_id');
       $client_ids =  implode(",",$client_list_arry);
       if($company_id==1){
           $queryDelivery_opening ="Select d.client_id, IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
                  where d.client_id in  ($client_ids) AND (d.date between '$start_calculation' and '$endDate_p')
                    group by d.client_id  ";

       }else{
           $queryDelivery_opening ="Select d.client_id, IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
                  where d.client_id in  ($client_ids) AND d.date  <= '$endDate_p'
                   group by d.client_id ";

       }

       $Payment_result =  Yii::app()->db->createCommand($queryDelivery_opening)->queryAll();

       $fina_result= [];
       foreach ($Payment_result as $value){

           $client_id = $value['client_id'];
           $deliverySum = $value['deliverySum'];

           $fina_result[$client_id] = $deliverySum;

       }

       return $fina_result;
   }
}