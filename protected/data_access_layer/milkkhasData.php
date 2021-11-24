<?php
/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 3/3/2017
 * Time: 3:31 PM
 */
class milkkhasData{

    public static function getClientLedgherReportFunction_PrintBill_milkkhas($data){


        $clientId = $data['clientID'];
        $clientObject_forPrint =Client::model()->findByPk(intval($clientId))->attributes;
        //  $currentBalance = APIData::calculateFinalBalance($clientId);
        $currentBalance = 0;
        $startDate = $data['startDate'];

        $endDate = $data['endDate'];
        $nextMonthDate = date('Y-m-d', strtotime('5 day', strtotime($endDate)));

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

        $reportObject = array();
        $x= strtotime($startDate);
        $y= strtotime($endDate);
        $reportData = array();
        $oneDayData = array();
        $oneDayData['discription'] = 'OPENING BALANCE' ;
        $oneDayData['date'] = $startDate ;

        $oneDayData['delivery'] = $totaldeliverySum ;
        $oneDayData['reciveAmount'] = $totalRemaining ;
        $oneDayData['balance'] = $openingTotalBalance ;

        $openingTotalBalance_first = $openingTotalBalance ;

        $reportData[] = $oneDayData ;

        $todayPaymentSum_total = 0;

        while($x < ($y+8640)){
            $oneDayData = array();
            $selectDate = date("Y-m-d", $x);
            $oneDayData['date'] = $selectDate ;
            $x += 86400;
            $queryDelivery ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d 
                where d.client_id = $clientId AND d.date = '$selectDate' ";
            $queryDelivery ="select IFNULL(sum(dd.amount),0) as deliverySum ,p.name as product_name ,p.unit , IFNULL(sum(dd.quantity),0) as deliveryQuantity_sum  from delivery as d
                left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                left join product as p ON p.product_id = dd.product_id
                where d.date = '$selectDate' and d.client_id =$clientId
                group by dd.product_id ";
            $deliveryOneDayResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
            $todaydilverySum = 0 ;
            $deliveryProduct ='';
            $find_total_deliverd_quantity =0;
           /* foreach($deliveryOneDayResult as $value){
                if($deliveryProduct !=''){
                    $deliveryProduct .= ', ';
                }
                $todaydilverySum = $todaydilverySum + $value['deliverySum'];
                $deliveryProduct .= $value['product_name']." ".$value['deliveryQuantity_sum']." ".$value['unit'];

                $find_total_deliverd_quantity = intval($find_total_deliverd_quantity) + intval($value['deliveryQuantity_sum']);
            }*/
            foreach($deliveryOneDayResult as $value){
                $todaydilverySum = $todaydilverySum + $value['deliverySum'];
                $oneDayData['discription'] = $value['product_name'];
                $oneDayData['rate'] =$value['deliveryQuantity_sum'];
                $find_total_deliverd_quantity = intval($find_total_deliverd_quantity) + intval($value['deliveryQuantity_sum']);
            }
            //  $todaydilverySum = $deliveryOneDayResult[0]['deliverySum'];
            if(true){
                //  $todaydilverySum = $deliveryOneDayResult[0]['deliverySum'];
                $todayPaymentSum = 0;
                $oneDayBalanceCount =$todaydilverySum-$todayPaymentSum ;
                $openingTotalBalance = $openingTotalBalance + $oneDayBalanceCount;
                $oneDayData['reference_number'] = '';
                $oneDayData['delivery'] = $todaydilverySum ;
                $oneDayData['reciveAmount'] = $todayPaymentSum ;
                $oneDayData['balance'] = $openingTotalBalance ;
                $currentBalance = $openingTotalBalance ;
                $reportData[] = $oneDayData ;
            }

            $queryPayment ="Select IFNULL((pm.amount_paid) , 0) as payAmountSum ,pm.reference_number from payment_master as pm
                where pm.client_id ='$clientId' AND pm.date = '$selectDate'";

            $paymentOneDayResult = Yii::app()->db->createCommand($queryPayment)->queryAll();


            foreach($paymentOneDayResult as $value){
                $todayPaymentSum = ($value['payAmountSum']);
                if($todayPaymentSum != 0){
                    $todaydilverySum = 0;
                    $todayPaymentSum = $value['payAmountSum'];
                    $todayPaymentSum_total = $todayPaymentSum_total + $todayPaymentSum ;
                    $oneDayBalanceCount = $todaydilverySum - $todayPaymentSum  ;
                    $openingTotalBalance = $oneDayBalanceCount + $openingTotalBalance;
                    $oneDayData['discription'] = 'RECEIVED' ;
                    $oneDayData['reference_number'] = $value['reference_number'];
                    $oneDayData['delivery'] = $todaydilverySum ;
                    $oneDayData['reciveAmount'] = $todayPaymentSum ;
                    $oneDayData['balance'] = $openingTotalBalance ;
                    $currentBalance = $openingTotalBalance ;
                    $reportData[] = $oneDayData ;
                }
            }
        }
        $querySumery ="select IFNULL(sum(dd.amount),0) as deliverySum ,p.name as product_name ,p.unit , IFNULL(sum(dd.quantity),0) as deliveryQuantity_sum  from delivery as d
                left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                left join product as p ON p.product_id = dd.product_id
                where  d.client_id ='$clientId' and d.date between '$startDate' and '$endDate' and p.unit is not null
                group by dd.product_id ";
        $querySumeryResult = Yii::app()->db->createCommand($querySumery)->queryAll();
        $resultArray = array();
        $resultArray['ledgerData'] = $reportData;
        $resultArray['sumery'] = $querySumeryResult;
        $resultArray['clientObject'] = $clientObject_forPrint;
        $resultArray['company_object'] = Company::model()->findByPk(intval(15))->attributes;
        $resultArray['current_balance'] = $currentBalance;
        $resultArray['nextMonthDate'] = $nextMonthDate;
        $resultArray['openingTotalBalance_first'] = $openingTotalBalance_first;
        $resultArray['todayPaymentSum_total'] = $todayPaymentSum_total;

        return json_encode($resultArray);

    }
    public static function calculate_tex_amount($pp_TxnType,$data){

        $current_amount = $data['amount_paid'];
        $client_id = $data['client_id'];


        $text_amount =0;
        if($pp_TxnType=='MPAY'){
            /* 3.5%*/
            $text_amount=  round(($current_amount *3.5)/100,0);
        }

        if($pp_TxnType=='MWALLET'){
            /* 1.5%*/
            $text_amount=  round(($current_amount *1.5)/100,0);
        }

        if($pp_TxnType=='OTC'){
            /* 2.5%*/
            $text_amount=  round(($current_amount *2.5)/100,0);
        }


        $bject =new SendboxTotalPaidAmount();
        $bject->client_id = $client_id;
        $bject->total_amount = $current_amount;
        $bject->text_amount = 	$text_amount;
        if($bject->Save()){

        }else{
            echo "pre";
            print_r($bject->getErrors());
            die();
        }

        $net_amount =  $current_amount -$text_amount;

        return $net_amount;
    }
}