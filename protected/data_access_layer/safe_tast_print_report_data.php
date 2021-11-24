<?php


class safe_tast_print_report_data
{
    public static function getClientLedgherReportFunction_for_safe_tast($data){


        $clientId = $data['clientID'];
        $client_query =" SELECT  cc.category_name,
            c.security ,
        c.notification_alert_allow_user ,
        c.client_id,
        c.fullname,
        c.address,
        c.cell_no_1 ,
        z.name AS zone_name
          FROM client AS c
            LEFT JOIN zone AS z ON c.zone_id = z.zone_id
 	       LEFT JOIN customer_category AS cc ON 
	         cc.customer_category_id = c.customer_category_id
            WHERE c.client_id ='$clientId'  ";




        $clientObject_Result = Yii::app()->db->createCommand($client_query)->queryAll();
        if($clientObject_Result[0]){
            $clientObject_forPrint =$clientObject_Result[0];
        }else{
            $clientObject_forPrint=[];
        }

        $company_id = Yii::app()->user->getState('company_branch_id');


        // $clientObject_forPrint =Client::model()->findByPk(intval($clientId))->attributes;
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

        $openingTotalBalance_amount =  $totaldeliverySum - $totalRemaining ;

        $openingTotalBalance = 0;
        $reportObject = array();
        $x= strtotime($startDate);
        $y= strtotime($endDate);
        $reportData = array();
        $oneDayData = array();
        $oneDayData['discription'] = 'OPENING BALANCE' ;
        $oneDayData['date'] = $startDate ;

       // $oneDayData['delivery'] = $totaldeliverySum ;
        $oneDayData['delivery'] ='' ;
        //$oneDayData['reciveAmount'] = $totalRemaining ;
        $oneDayData['reciveAmount'] ='';
        $openingTotalBalance = $totaldeliverySum-$totalRemaining;
        $oneDayData['balance'] = $openingTotalBalance ;
        $reportData[] = $oneDayData ;
        // $reportData = array();
        $total_paid_payment_duration_this_date =0;
        while($x < ($y+8640)){

            $oneDayData = array();
            $selectDate = date("Y-m-d", $x);

            $oneDayData['date'] = $selectDate ;
            $oneDayData['just_date'] = date("d", $x)." ".date("M", $x);


            $x += 86400;
            $queryDelivery ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d 
                where d.client_id = $clientId AND d.date = '$selectDate' ";

            $queryDelivery ="select IFNULL(sum(dd.amount),0) as deliverySum ,p.name as product_name ,p.unit , IFNULL(sum(dd.quantity),'') as deliveryQuantity_sum  from delivery as d
                left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                left join product as p ON p.product_id = dd.product_id
                where d.date = '$selectDate' and d.client_id =$clientId
                group by dd.product_id ";

            $deliveryOneDayResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();

            $todaydilverySum = 0 ;
            $deliveryProduct ='';
            $deliveryProduct_quantity_sum ='';

            foreach($deliveryOneDayResult as $value){

                $todaydilverySum =  $value['deliverySum'];

                $deliveryProduct = $value['product_name']." ".$value['deliveryQuantity_sum']." ".$value['unit'];

                if($value['deliveryQuantity_sum'] >0){
                    $deliveryProduct_quantity_sum = $value['deliverySum']/$value['deliveryQuantity_sum'];
                }

                $todayPaymentSum = 0;
                $oneDayBalanceCount =$todaydilverySum-$todayPaymentSum ;
                $openingTotalBalance = $openingTotalBalance + $oneDayBalanceCount;
                $oneDayData['discription'] = $deliveryProduct ;
                $oneDayData['rate'] =$deliveryProduct_quantity_sum ;

                $oneDayData['reference_number'] = '';
                $oneDayData['delivery'] = $todaydilverySum ;
                $oneDayData['reciveAmount'] = $todayPaymentSum ;
                $oneDayData['balance'] = $openingTotalBalance ;
                $currentBalance = $openingTotalBalance ;
                $reportData[] = $oneDayData ;

            }



            if(false){

                $todayPaymentSum = 0;
                $oneDayBalanceCount =$todaydilverySum-$todayPaymentSum ;
                $openingTotalBalance = $openingTotalBalance + $oneDayBalanceCount;
                $oneDayData['discription'] = $deliveryProduct ;
                $oneDayData['rate'] =$deliveryProduct_quantity_sum ;

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
                    $todayPaymentSum = ($value['payAmountSum']);
                    $oneDayBalanceCount = $todaydilverySum - $todayPaymentSum  ;
                    $openingTotalBalance = $oneDayBalanceCount + $openingTotalBalance;
                    $oneDayData['discription'] = 'RECEIVED' ;
                    $oneDayData['reference_number'] = $value['reference_number'];
                    $oneDayData['delivery'] = $todaydilverySum ;
                    $oneDayData['reciveAmount'] = $todayPaymentSum ;
                    $total_paid_payment_duration_this_date = $total_paid_payment_duration_this_date + $todayPaymentSum;
                    $oneDayData['balance'] = $openingTotalBalance ;
                    $currentBalance = $openingTotalBalance ;
                    $reportData[] = $oneDayData ;
                }
            }
        }


        $querySumery ="select IFNULL(sum(dd.amount),0) as deliverySum ,
                p.name as product_name ,
                p.unit ,
                 IFNULL(sum(dd.quantity),0) as deliveryQuantity_sum
                   from delivery as d
                left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                left join product as p ON p.product_id = dd.product_id
                where  d.client_id ='$clientId' and d.date 
                between '$startDate' and '$endDate' 
                and p.unit is not null
                group by dd.product_id ";

        $querySumeryResult = Yii::app()->db->createCommand($querySumery)->queryAll();

        $resultArray = array();
        $resultArray['ledgerData'] = $reportData;
        $resultArray['arrearer'] =0;
        $resultArray['collection'] =0;
        $fix_ob=$openingTotalBalance_amount;

        // $openingTotalBalance_amount = $openingTotalBalance_amount- $total_paid_payment_duration_this_date;

        $openingTotalBalance_amount = $openingTotalBalance_amount;

        if($openingTotalBalance_amount>0){

            $resultArray['arrearer'] = $openingTotalBalance_amount;
        }else{
            $resultArray['collection'] = -($openingTotalBalance_amount);
            //  $resultArray['collection'] = 99;
        }

        $resultArray['opening_balance_month'] = $openingTotalBalance_amount;

        $resultArray['area'] = "lahore";

        $resultArray['total_paid_payment_duration_this_date'] = $total_paid_payment_duration_this_date;

        $resultArray['sumery'] = $querySumeryResult;

        $net_total_delivery_sum = 0;

        foreach ($querySumeryResult as $value){

            $net_total_delivery_sum = $net_total_delivery_sum + $value['deliverySum'];

        }

        $resultArray['total_summary_product'] =$net_total_delivery_sum;

        $resultArray['total_payable_amount'] = $openingTotalBalance_amount + $net_total_delivery_sum;

        $resultArray['clientObject'] = $clientObject_forPrint;
        $resultArray['company_object'] = Company::model()->findByPk(intval($company_id))->attributes;
        // $resultArray['current_balance'] = $currentBalance+$fix_ob;
        $resultArray['current_balance'] = $currentBalance;
        $resultArray['nextMonthDate'] = $nextMonthDate;
        return json_encode($resultArray);
    }
}