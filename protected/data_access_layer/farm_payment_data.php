<?php


class farm_payment_data
{

    public static function payment_ladger($data){

        $farm_id = $data['farm_id'];
        $startDate = $data['startDate'];
        $endDate = $data['endDate'];


        $stock_recived_query = "SELECT 
            sum(d.purchase_rate *d.quantity ) as  total_recive,
            sum(d.purchase_rate *d.return_quantity) AS return_quantity
            FROM daily_stock AS d
            WHERE d.date < '$startDate' 
            and d.farm_id ='$farm_id'";




        $stock_recived_result = Yii::app()->db->createCommand($stock_recived_query)->queryRow();


        $total_recive = intval($stock_recived_result['total_recive']);
        $return_quantity = intval($stock_recived_result['return_quantity']);


        $net_option_stock =($total_recive -$return_quantity );




       $query_payment = "SELECT 
        ifnull(sum(f.amount),0) AS amount
        FROM farm_payment AS f
        WHERE f.action_date <'$startDate' and f.farm_id ='$farm_id' ";

        $farm_payment = Yii::app()->db->createCommand($query_payment)->queryRow();




       $farm_payment_amount = intval($farm_payment['amount']);




        $x= strtotime($startDate);
        $y= strtotime($endDate);
        $reportData = array();
        $oneDayData = array();
        $oneDayData['discription'] = 'OPENING BALANCE' ;
        $oneDayData['date'] = $startDate ;
        //$oneDayData['stock_recived'] = $net_option_stock ;
       // $oneDayData['paid_amount'] = $farm_payment_amount;
        $oneDayData['balance'] = $net_option_stock-$farm_payment_amount ;
        $oneDayData['stock_recived'] =0;
        $oneDayData['total_purchase'] =0;
        $oneDayData['total_quantity'] =0;
        $oneDayData['paid_amount'] =0;


        $current_balnce =  $oneDayData['balance'];


        $reportData[] = $oneDayData ;



        while($x < ($y+8640)){
            $oneDayData = array();

            $selectDate = date("Y-m-d", $x);


            $x += 86400;

            $stock_recived_query = "SELECT 
                p.name AS product_name,
                d.quantity as total_quantity,
                (d.purchase_rate *d.quantity)  as  total_recive,
                (d.purchase_rate *d.return_quantity) AS return_quantity
                FROM daily_stock AS d
                LEFT JOIN product AS p ON p.product_id = d.product_id
                WHERE d.date = '$selectDate'
                and d.farm_id ='$farm_id'";



            $stock_recived_result = Yii::app()->db->createCommand($stock_recived_query)->queryAll();

            foreach($stock_recived_result as $value_stock){

                $total_quantity =  $value_stock['total_quantity'];
                $total_recive = intval($value_stock['total_recive']);
                $return_quantity = intval($value_stock['return_quantity']);

                $net_option_stock =($total_recive -$return_quantity );

                $balnce =$net_option_stock   + $current_balnce;
                $current_balnce = $balnce;

                $oneDayData = array();
                $oneDayData['discription'] = $value_stock['product_name'] ;
                $oneDayData['date'] = $selectDate ;
                $oneDayData['stock_recived'] = $net_option_stock ;
                $oneDayData['total_purchase'] = $total_recive ;
                if($total_quantity>0){
                    $oneDayData['rate'] = round($total_recive/$total_quantity,2) ;
                }
                $oneDayData['total_quantity'] = $total_quantity;
                $oneDayData['reference_no'] = '';
                $oneDayData['paid_amount'] = '';
                $oneDayData['balance'] = $current_balnce;

                $current_balnce =  $oneDayData['balance'];



                if($farm_payment_amount>0 ||$net_option_stock>0){
                    $reportData[] = $oneDayData ;
                }



            }




            $query_payment = "SELECT 
                f.reference_no,
                f.amount AS amount
                FROM farm_payment AS f
                WHERE f.action_date ='$selectDate'  and f.farm_id ='$farm_id'";





            $farm_payment = Yii::app()->db->createCommand($query_payment)->queryAll();

            foreach ($farm_payment as $value){

                $farm_payment_amount = intval($value['amount']);




                $oneDayData = array();

                $oneDayData['discription'] = '' ;
                $oneDayData['date'] = $selectDate ;
                $oneDayData['stock_recived'] = 0 ;
                $oneDayData['total_purchase'] = 0 ;

                $oneDayData['rate'] = '' ;

                $oneDayData['total_quantity'] = 0;
                $oneDayData['reference_no'] = $value['reference_no'];
                $oneDayData['paid_amount'] = $farm_payment_amount;
                $current_balnce = $current_balnce - $farm_payment_amount;
                $oneDayData['balance'] = $current_balnce;
                $current_balnce =  $oneDayData['balance'];
                if($farm_payment_amount>0 ||$net_option_stock>0){
                    $reportData[] = $oneDayData ;
                }

            }



        }

        $grad_stock_recived =0;
        $grad_total_purchase =0;
        $grad_total_quantity =0;
        $grad_paid_amount =0;
        $grad_balance =0;

        foreach ($reportData as $value){
            $grad_stock_recived = $grad_stock_recived +intval($value['stock_recived']);
            $grad_total_purchase = $grad_total_purchase + intval($value['total_purchase']);
            $grad_total_quantity = $grad_total_quantity + intval($value['total_quantity']);
            $grad_paid_amount = $grad_paid_amount +intval($value['paid_amount']);

            $grad_balance = $grad_balance +intval($value['balance']);
        }

        $grand_total_result = [];
        $grand_total_result['grad_stock_recived'] =$grad_stock_recived;
        $grand_total_result['grad_total_purchase'] =$grad_total_purchase;
        $grand_total_result['grad_total_quantity'] =$grad_total_quantity;
        $grand_total_result['grad_paid_amount'] = $grad_paid_amount;
        $grand_total_result['grad_balance'] = $grad_balance;


        $resultArray['ledgerData'] = $reportData;
        $resultArray['sumery'] = '';
        $resultArray['startDate'] = $startDate;
        $resultArray['endDate'] = $endDate;
        $resultArray['totalDelivery'] =$grand_total_result;


        return json_encode($resultArray);
    }
}