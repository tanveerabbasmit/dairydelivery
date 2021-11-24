<?php

/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 10/17/2017
 * Time: 6:43 PM
 */
class check_remaning_bottle
 {
   public static function getRemaining_bottle($client_id , $company_branch_id){
       $query="SELECT p.*  from   product as p 
                where p.company_branch_id =$company_branch_id and bottle = 1";
     //  $product =  Yii::app()->db->createCommand($query)->queryRow();

     $bootleProduct_id = 25;


     $query_total_delivery = "select IFNULL(sum(dd.quantity) ,0) as delivered_quantity from delivery as d
        left join delivery_detail as dd ON dd.delivery_id = d.delivery_id 
        where d.client_id ='$client_id' and dd.product_id ='$bootleProduct_id'";

       $result_total_delivery =  Yii::app()->db->createCommand($query_total_delivery)->queryRow();




       $query_total_recive_bottle = "select  ifnull(sum(br.broken) ,0) + ifnull(sum(br.perfect) ,0) as total_recive  from bottle_record as br
              where br.client_id ='$client_id'";

        $result_total_recive_bottle =  Yii::app()->db->createCommand($query_total_recive_bottle)->queryRow();

       $total_recive_bottle = $result_total_recive_bottle['total_recive'];

    return   $x = $result_total_delivery['delivered_quantity']- $total_recive_bottle  ;
   }
 }