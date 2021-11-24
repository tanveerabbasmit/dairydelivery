<?php


class vendor_payasble_summary_data
{
   public static function vendor_purchase_date_range($data){

       $startDate = $data['startDate'];
       $endDate = $data['endDate'];
       $vendor_id = $data['vendor_id'];

       $query = "SELECT 
                 i.item_name,
                 b.remarks,
                sum(b.net_amount) AS net_amount
                FROM bill_from_vendor AS b
                LEFT JOIN item  AS i ON i.item_id = b.item_id
                WHERE b.vendor_id = '$vendor_id' ";

       $query .=" and b.action_date between '$startDate' and '$endDate' ";



       $result = Yii::app()->db->createCommand($query)->queryRow();


       if($result['net_amount']){
           return $result['net_amount'];
       }else{
           return 0;
       }
   }

   public static function vendor_payment($data){

       $startDate = $data['startDate'];

       $endDate = $data['endDate'];


       $vendor_id = $data['vendor_id'];

       $query = " SELECT 
                    sum(v.amount) AS  amount ,
                    v.reference_no as remarks 
                    FROM vendor_payment as v  
                    where  v.vendor_id ='$vendor_id' ";
       $query .= " and v.action_date between '$startDate' and '$endDate' " ;



       $result =  Yii::app()->db->createCommand($query)->queryRow();


       if($result['amount']){
           return $result['amount'];
       }else{
           return 0;
       }


   }
}