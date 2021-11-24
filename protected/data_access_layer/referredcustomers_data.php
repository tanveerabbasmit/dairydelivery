<?php


class referredcustomers_data
{
      public static function get_total_of_refered_customer_quantity($start_date,$end_date ,$list,$product_id){

          $client_ids =[];
          foreach ($list as $value){
              $client_ids[] = $value['client_id'];
          }

          $client_ids_list = implode(',',$client_ids);

            $query = "SELECT sum(dd.quantity) FROM delivery AS d
                LEFT JOIN delivery_detail AS dd
                ON d.delivery_id =dd.delivery_id
                WHERE d.date BETWEEN '$start_date'  
                     AND '$end_date'  
                AND d.client_id IN ($client_ids_list)
                and dd.product_id='$product_id'  ";


            
          $queryResult =  Yii::app()->db->createCommand($query)->queryscalar();

          if($queryResult){
              return $queryResult;
          }else{
              return 0;
          }

      }
      public static function get_first_delivery_on($client_id,$data){
          $todayYear = $data['todayYear'];
          $todayMonth = $data['todayMonth'];
          $product_id = $data['product_id'];

          $start_date =$todayYear.'-'.$todayMonth.'-01';
          $end_date = $todayYear.'-'.$todayMonth.'-31';


        $query = "SELECT d.date FROM delivery AS d
        WHERE d.client_id ='13971'
        ORDER BY d.date ASC 
        limit 1";

        $queryResult =  Yii::app()->db->createCommand($query)->queryscalar();

        return $queryResult;
      }
      public static function get_last_delivery_on($client_id,$data){
          $todayYear = $data['todayYear'];
          $todayMonth = $data['todayMonth'];
          $product_id = $data['product_id'];

          $start_date =$todayYear.'-'.$todayMonth.'-01';
          $end_date = $todayYear.'-'.$todayMonth.'-31';


        $query = "SELECT d.date FROM delivery AS d
        WHERE d.client_id ='13971'
        ORDER BY d.date DESC 
        limit 1";

        $queryResult =  Yii::app()->db->createCommand($query)->queryscalar();

        return $queryResult;
      }
      public static function get_product_quantity($client_id,$data){
          $todayYear = $data['todayYear'];
          $todayMonth = $data['todayMonth'];
          $product_id = $data['product_id'];

          $start_date =$todayYear.'-'.$todayMonth.'-01';
          $end_date = $todayYear.'-'.$todayMonth.'-31';

        $query = "SELECT 
            sum(dd.quantity) AS quantity ,
            sum(dd.amount) AS amount
            FROM delivery AS d
            LEFT JOIN delivery_detail AS dd 
            ON d.delivery_id = dd.delivery_id
            WHERE 
                  d.date BETWEEN '$start_date' AND '$end_date'
            AND dd.product_id ='$product_id' AND d.client_id ='$client_id' ";

          $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

         $quantity = $queryResult[0]['quantity'];
         $amount = $queryResult[0]['amount'];

         $result = [];
         $result['quantity'] = $quantity;
         $result['amount'] = $amount;
         $result['rate'] = $amount;
         if($quantity>0){
             $result['rate'] = round(($amount/$quantity),2);
         }

         return $result;

      }
}