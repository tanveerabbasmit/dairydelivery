<?php


class raej_data
{
    public static function getClientLedgherReportFunction_raej_company($data){

        $clientId = $data['clientID'];
        $client_query =" SELECT c.security ,
            c.notification_alert_allow_user , 
            c.client_id,c.fullname,
            c.address,c.cell_no_1 ,z.name AS zone_name
            FROM client AS c
            LEFT JOIN zone AS z ON c.zone_id = z.zone_id
            WHERE c.client_id ='$clientId'  ";


        $clientObject_Result = Yii::app()->db->createCommand($client_query)->queryAll();
        if($clientObject_Result[0]){
            $clientObject_forPrint =$clientObject_Result[0];
        }else{
            $clientObject_forPrint=[];
        }

        $company_id = Yii::app()->user->getState('company_branch_id');


        $currentBalance = APIData::calculateFinalBalance($clientId);
        $currentBalance = 0;
        $startDate = $data['startDate'];

        $endDate = $data['endDate'];





        $bad_debt_opeening_amount = bad_debt_record_data::bad_debt_opeening_amount_till_toady($data);


        $nextMonthDate = date('Y-m-d', strtotime('5 day', strtotime($endDate)));


        $queryDelivery ="Select IFNULL(sum(d.total_amount),0) 
               as deliverySum from delivery as d
            where d.client_id = $clientId AND d.date < '$startDate' ";

        $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();

        $totaldeliverySum = $deliveryResult[0]['deliverySum'];

        $queryDelivery ="Select IFNULL(sum(pm.amount_paid) , 0) 
          as remainingAmount from payment_master as pm
            where pm.client_id = $clientId AND pm.date < '$startDate' ";
        $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
        $totalRemaining = $deliveryResult[0]['remainingAmount'];
        $finalData = array();

        $finalData['openeningStock'] = $totaldeliverySum ;
        $finalData['totalRemaining'] = $totalRemaining ;
        $openingTotalBalance_amount =  $totaldeliverySum - $totalRemaining ;


        $reportData =[];


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
        $resultArray['ledgerData'] =self::product_sale_list_between_date_rage($clientId,$startDate,$endDate);
        $resultArray['total_delivery_amount'] =self::count_total_of_delivery($resultArray['ledgerData']);
        $resultArray['arrearer'] =0;
        $resultArray['collection'] =0;
        $fix_ob=$openingTotalBalance_amount;
        $openingTotalBalance_amount = $openingTotalBalance_amount - $bad_debt_opeening_amount;
        if($openingTotalBalance_amount>0){
            $resultArray['arrearer'] = $openingTotalBalance_amount;
        }else{
            $resultArray['arrearer'] = ($openingTotalBalance_amount);
        }

        $resultArray['area'] = "lahore";
        $resultArray['sumery'] = $querySumeryResult;

        $resultArray['clientObject'] = $clientObject_forPrint;

        $resultArray['company_object'] = Company::model()->findByPk(intval($company_id))->attributes;

        $resultArray['current_balance'] = $currentBalance+$openingTotalBalance_amount;
        $resultArray['nextMonthDate'] = $nextMonthDate;
        $resultArray['today_date'] = date('Y-m-d');
        return json_encode($resultArray);
    }
    public static function count_total_of_delivery($ledgerData){
        $total = 0;
        foreach($ledgerData as $value){
            $total =  $value['amount'] + $total;
        }

        return $total;
    }
    public static function product_sale_list_between_date_rage($clientId,$startDate,$endDate){
        $company_id = Yii::app()->user->getState('company_branch_id');


        $product_list = productData::getproductList_arrayForm(0);

        $list =[];

        foreach($product_list as $value){
            $product_id = $value['product_id'];
            $one_object = [];
            $one_object['name'] = $value['name'];

            $oneday_query = "SELECT 
                dd.date,
                sum(ifnull(dd.quantity , 0)) as quantity ,
                sum(ifnull(dd.amount , 0)) as amount
                from delivery d
                left join delivery_detail as dd 
                ON d.delivery_id = dd.delivery_id 
                where dd.DATE between '$startDate' AND  '$endDate' 
                AND d.client_id = '$clientId' AND dd.product_id ='$product_id' ";

            $oneday_result  = Yii::app()->db->createCommand($oneday_query)->queryRow();

            $quantity = $oneday_result['quantity'];
            $amount = $oneday_result['amount'];


            $one_object['quantity'] =  $quantity;
            $one_object['amount'] =  $amount;
            $one_object['price'] =  0;
            if($quantity>0){
                $one_object['price'] =round(($amount/$quantity),2);
            }

            $list[] = $one_object;
        }
        return $list;


    }
}