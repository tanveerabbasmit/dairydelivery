<?php


class spacial_date_closing_balance
{

      public static function closing_balance($action_date ,$client_id){


          $queryDelivery ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
             where d.client_id = '$client_id' AND d.date <= '$action_date' ";



          $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
          $totaldeliverySum = $deliveryResult[0]['deliverySum'];


          $queryDelivery ="Select IFNULL(sum(pm.amount_paid) , 0) as remainingAmount 
                   from payment_master as pm
                   where pm.client_id = '$client_id' 
                   and pm.date < '$action_date' ";

          $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
          $totalRemaining = $deliveryResult[0]['remainingAmount'];
          $finalData = array();
          $finalData['openeningStock'] = $totaldeliverySum ;
          $finalData['totalRemaining'] = $totalRemaining ;


          $openingTotalBalance =  $totaldeliverySum - $totalRemaining ;

          return $openingTotalBalance;
      }
}