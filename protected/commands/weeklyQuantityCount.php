<?php

/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 10/10/2017
 * Time: 11:49 AM
 */
class weeklyQuantityCount
{
   public static function getWeeklySchedulequantityToday($startWeakDate,$product_id ){

       $lientID_list = weeklyQuantityCount::getClientListAgainstCompany();

     $query  = " select sum(fq.quantity) as total_quantity from client_product_frequency as cpf
                    left join client_product_frequency_quantity fq
                    ON fq.client_product_frequency_id = cpf.client_product_frequency
                    where cpf.client_id in ($lientID_list) and cpf.product_id = $product_id
                    and cpf.orderStartDate <= '$startWeakDate' ";

        $queryResult = Yii::app()->db->createCommand($query)->queryAll();
       return  $totalRegualrWeekly =  $queryResult[0]['total_quantity'];


   }

   public static function getSpecialOrderCount($lientID_list , $product_id , $date){
           /*  echo $lientID_list;
              echo "<br>";
              echo $product_id;
               echo "<br>";
               echo $date ;
             echo "<br>";*/
       $clientQuery = "select IFNULL(sum(so.quantity) , 0) as totalSpecial from special_order as so
               where so.client_id in ($lientID_list) and so.product_id = '$product_id'
               and '$date' between so.start_date and so.end_date";

       $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();

       return      $clientResult[0]['totalSpecial'];

   }
   public static function getRegularInterQuantity($lientID_list ,$product_id ,$date ){

      $clientQuery = "select NULLIF(sum(((7/(list.interval_days))*(list.product_quantity))) ,0) as total from interval_scheduler as list
                    where list.client_id in ($lientID_list) and list.product_id = $product_id and list.start_interval_scheduler < '$date'";

       $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();
        return   floor($clientResult[0]['total']);
   }


   public static function getClientListAgainstCompany(){

       $company_id = Yii::app()->user->getState('company_branch_id');
       $clientQuery = "select c.client_id from client as c
                         where c.company_branch_id = $company_id and c.is_active =1";

       $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();
       $cientID = array();
       $cientID[] = 0;
       foreach($clientResult as $value){
           $cientID[] =  $value['client_id'];
       }
       return $lientID_list = implode(',',$cientID);
   }
}