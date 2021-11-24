<?php


class discount_amount
{
     public static function get_total_discount_amount($clientId,$startDate,$endDate){

        $query = "SELECT 
            sum(d.total_discount_amount) AS total_discount from payment_master AS pay
            LEFT JOIN discount_list AS d ON d.payment_master_id =pay.payment_master_id
            WHERE pay.client_id ='$clientId' 
            AND pay.bill_month_date BETWEEN '$startDate' AND '$endDate' ";

         $delivery_result = Yii::app()->db->createCommand($query)->queryscalar();

         return intval($delivery_result);

     }
}