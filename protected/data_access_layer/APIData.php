<?php

/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 4/18/2017
 * Time: 12:40 PM
 * Time: 12:40 PM
 * Time: 12:40 PM
 */
class APIData
{
    public static function calculateFinalBalance($clientId){




        $queryDelivery ="select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
                           where d.client_id = $clientId  ";
        $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
        $totaldeliverySum = $deliveryResult[0]['deliverySum'];



         $queryDelivery ="Select sum(pm.amount_paid) as remainingAmount from payment_master as pm
            where pm.client_id =$clientId";
         $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
         $totalRemaining = $deliveryResult[0]['remainingAmount'];
         $finalAmount =    $totaldeliverySum - $totalRemaining;

        //if($clientId=='23' OR $clientId =='71'){
          //  $finalAmount =0;
        //}

          return $finalAmount ;

       /* $query ="Select IFNULL(sum(d.partial_amount),0) as unpaidAmount from delivery as d
               where d.client_id = $clientId";
        $queryResult = Yii::app()->db->createCommand($query)->queryAll();
      $totalUnpaidAmount = $queryResult[0]['unpaidAmount'];


        $querypaidAmount ="Select IFNULL(sum(pd.amount_paid) , 0) PaidAmount from payment_detail as pd
                          where pd.client_id = $clientId";
        $paidAmountResult = Yii::app()->db->createCommand($querypaidAmount)->queryAll();
        $PaidAmount = $paidAmountResult[0]['PaidAmount'];

        $paymentMasterIDArray = "Select  pd.payment_master_id from payment_detail pd
                                   where pd.client_id = $clientId
                                   Group by pd.payment_master_id ";
        $paymentIdResult = Yii::app()->db->createCommand($paymentMasterIDArray)->queryAll();
        $paymentMaster_id = array();
        foreach($paymentIdResult as $id){
            $paymentMaster_id[] = $id['payment_master_id'];
        }
        $idList = implode(',', $paymentMaster_id);
        $accountBalance_Query = "Select sum(pm.amount_paid) as totalamountPaid from  payment_master as pm
                where pm.client_id = $clientId ";
        $totalaccountPaidAmountResult = Yii::app()->db->createCommand($accountBalance_Query)->queryAll();
        $totalPaidAmount =$totalaccountPaidAmountResult[0]['totalamountPaid'];

        $finalBalance = $totalPaidAmount-$PaidAmount - $totalUnpaidAmount ;

        return $finalBalance ;*/


    }

    public static function UpdatesaveDeliveryFunction($id){
        $paymenentMasterQuery ="Select * from payment_master as pm 
                  where pm.remaining_amount > 0 AND pm.client_id = $id ";
        $paymentMasterResult = Yii::app()->db->createCommand($paymenentMasterQuery)->queryAll();
        foreach($paymentMasterResult as $paymentMasterVlaue){
            $clientID = $id;
            $paymentMasterID = $paymentMasterVlaue['payment_master_id'];
            $amountPaid = $paymentMasterVlaue['remaining_amount'];
            if(true){
                /* Select UnPaid Delivery*/

                $query ="Select * from delivery as d
                 where d.client_id = $clientID  AND d.partial_amount !=0";

                $queryResult = Yii::app()->db->createCommand($query)->queryAll();
                foreach($queryResult as $value){
                    $deliveryID = $value['delivery_id'] ;
                    if($amountPaid > 0){
                        /*deliver Paid Completely*/
                        $deliveryPartialPyment = Delivery::model()->findByPk(intval($deliveryID));
                        $partialAmount = $deliveryPartialPyment['partial_amount'];
                        if($amountPaid >= $partialAmount){
                            $deliveryPartialPyment->partial_amount = 0 ;
                            if($deliveryPartialPyment->save()){
                                $paymentDetail = new PaymentDetail();
                                $paymentDetail->delivery_id = $value['delivery_id'];
                                $paymentDetail->delivery_date = $value['date'];
                                $paymentDetail->client_id = $clientID;
                                $paymentDetail->due_amount = 0 ;
                                $paymentDetail->amount_paid = $partialAmount ;
                                $paymentDetail->payment_master_id = $paymentMasterID ;
                                $paymentDetail->payment_date = date("Y-m-d");
                                if($paymentDetail->save()){
                                    $amountPaid = $amountPaid - $value['partial_amount'] ;
                                    /*Update remaining amount*/
                                    $updateMaster = PaymentMaster::model()->findByPk(intval($paymentMasterID));
                                    $updateMaster->remaining_amount = $updateMaster['remaining_amount'] - $partialAmount ;
                                    $updateMaster->save();
                                }else{
                                    var_dump($paymentDetail->getErrors());
                                }
                            }
                        }else{
                            $deliveryPartialPyment->partial_amount = $partialAmount-$amountPaid ;
                            if($deliveryPartialPyment->save()){
                                $paymentDetail = new PaymentDetail();
                                $paymentDetail->delivery_id = $value['delivery_id'];
                                $paymentDetail->delivery_date = $value['date'];
                                $paymentDetail->client_id = $clientID;
                                $paymentDetail->due_amount = $partialAmount - $amountPaid;
                                $paymentDetail->amount_paid = $amountPaid ;
                                $paymentDetail->payment_master_id = $paymentMasterID ;
                                $paymentDetail->payment_date = date("Y-m-d");
                                if($paymentDetail->save()){
                                    $amountPaid = $amountPaid - $value['partial_amount'] ;
                                    $updateMaster = PaymentMaster::model()->findByPk(intval($paymentMasterID));
                                    $updateMaster->remaining_amount = 0 ;
                                    $updateMaster->save();
                                }else{
                                    var_dump($paymentDetail->getErrors());
                                }
                            }
                        }
                    }
                }
            }
        }
    }


    public static function api_getCustomerRegularOrderType($client_id , $product_id)
    {



        $clientSchedulerObject  = ClientProductFrequency::model()->findByAttributes(array('client_id'=>$client_id , 'product_id'=>$product_id));
        $clientinterval =IntervalScheduler::model()->findByAttributes(array('client_id'=>$client_id , 'product_id'=>$product_id));
        if($clientSchedulerObject){
            $type  = '1';
        }else if($clientinterval){
            $type = '2' ;
        }else{
            $type = '0';
        }
       return $type ;
    }

    public static function api_getCustomerHalt_OR_Resume($client_id , $product_id ,$todaydate)
    {
              $response  = '0' ;
        $weekly_query = "select * from halt_regular_orders as hro 
           where hro.client_id = '$client_id' and hro.product_id  = '$product_id'
            and  '$todaydate' between hro.start_date and hro.end_date";
        $weekly_Resuslt = Yii::app()->db->createCommand($weekly_query)->queryAll();
         if($weekly_Resuslt){
             $response = '1';
         }

        $interval_query = "select * from interval_scheduler as int_s
         where int_s.client_id = '$client_id' and int_s.product_id ='$product_id'
          and '$todaydate' between int_s.halt_start_date and int_s.halt_end_date";
        $interval_Resuslt = Yii::app()->db->createCommand($interval_query)->queryAll();
        if($interval_Resuslt){
            $response = '1';
        }
            return  $response ;

    }


    public static function sendSMS($num, $message){

        // convert a number to 923
        $number = $num;

        if(substr($num, 0, 2) == "03")
            $number = '923' . substr($num, 2);
        else if(substr($num, 0, 1) == "3")
            $number = '923' . substr($num, 1);

        // Configuration variables
        $id = "conformiz@bizsms.pk";
        $pass = "c3nuji8uj99";
        $mask = "emremr";

        $message = urlencode($message);

        $_url = 'http://api.bizsms.pk/api-send-branded-sms.aspx?username='.$id.'&pass='.$pass.'&text='.$message.'&masking='.$mask.'&destinationnum='.$number.'&language=English';

        if($_result = file_get_contents($_url)) {
            $_result_f = json_decode($_result);
        }

    }
    public static function get_opening_balance($clientId){



        $startDate = date("Y-m")."-1";


        $queryDelivery ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
            where d.client_id = $clientId AND d.date < '$startDate' ";


        $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
        $totaldeliverySum = $deliveryResult[0]['deliverySum'];


        $queryDelivery ="Select IFNULL(sum(pm.amount_paid) , 0) as remainingAmount from payment_master as pm
            where pm.client_id = $clientId AND pm.date < '$startDate' ";

        $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
        $totalRemaining = $deliveryResult[0]['remainingAmount'];
        $finalData = array();
        $finalData['openeningStock'] = $totaldeliverySum ;
        $finalData['totalRemaining'] = $totalRemaining ;


        $openingTotalBalance =  $totaldeliverySum - $totalRemaining ;

        return $openingTotalBalance;

    }



}