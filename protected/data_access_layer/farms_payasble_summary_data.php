<?php


class farms_payasble_summary_data
{

     public static function get_farm_totlal_purchse_opening($start_date,$end_date,$farm_id){
         $stock_recived_query = "SELECT 
                sum(d.purchase_rate *d.quantity ) as  total_recive,
                sum(d.purchase_rate *d.return_quantity) AS return_quantity
                FROM daily_stock AS d
                WHERE d.date < '$start_date'
                and d.farm_id ='$farm_id'";

         $stock_recived_result = Yii::app()->db->createCommand($stock_recived_query)->queryRow();


         $total_recive = intval($stock_recived_result['total_recive']);
         $return_quantity = intval($stock_recived_result['return_quantity']);


         $net_option_stock =($total_recive -$return_quantity );

         return $net_option_stock;


     }
     public static function get_farm_total_payment_opening($start_date,$end_date,$farm_id){
         $query_payment = "SELECT 
                ifnull(sum(f.amount),0) AS amount
                FROM farm_payment AS f
                WHERE f.action_date < '$start_date'  
                and f.farm_id ='$farm_id' ";
         $farm_payment = Yii::app()->db->createCommand($query_payment)->queryRow();

         $farm_payment_amount = intval($farm_payment['amount']);

         return $farm_payment_amount;
     }
     public static function get_farm_totlal_purchse_date_range($start_date,$end_date,$farm_id){
         $stock_recived_query = "SELECT 
                sum(d.purchase_rate *d.quantity ) as  total_recive,
                sum(d.purchase_rate *d.return_quantity) AS return_quantity
                FROM daily_stock AS d
                WHERE d.date between '$start_date' and  '$end_date'
                and d.farm_id ='$farm_id'";

         $stock_recived_result = Yii::app()->db->createCommand($stock_recived_query)->queryRow();

         $total_recive = intval($stock_recived_result['total_recive']);
         $return_quantity = intval($stock_recived_result['return_quantity']);


         $net_option_stock =($total_recive -$return_quantity );

         return $net_option_stock;
     }


     public static function get_farm_total_payment_date_range($start_date,$end_date,$farm_id){
         $query_payment = "SELECT 
                ifnull(sum(f.amount),0) AS amount
                FROM farm_payment AS f
                WHERE f.action_date between '$start_date' and  '$end_date' 
                and f.farm_id ='$farm_id' ";
         $farm_payment = Yii::app()->db->createCommand($query_payment)->queryRow();

         $farm_payment_amount = intval($farm_payment['amount']);

         return $farm_payment_amount;
     }

}