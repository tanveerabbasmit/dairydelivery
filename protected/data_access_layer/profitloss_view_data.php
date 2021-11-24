<?php


class profitloss_view_data
{
    public static function total_sold_stock($data){

         $start_date =  $data['start_date'];
         $end_date =  $data['end_date'];

        $company_id = Yii::app()->user->getState('company_branch_id');

        $querystck_rider_opening= "SELECT sum(dd.amount) AS total_amount FROM delivery AS d
            LEFT JOIN delivery_detail AS dd 
            ON dd.delivery_id =d.delivery_id
            WHERE d.company_branch_id ='$company_id'
            AND dd.date BETWEEN '$start_date' AND  '$end_date'  ";

        $deliveryOneDayResult_rider_opening = Yii::app()->db->createCommand($querystck_rider_opening)->queryscalar();

        if($deliveryOneDayResult_rider_opening){
            return $deliveryOneDayResult_rider_opening;
        }else{
            return  0;
        }

    }

    public static function total_purchased_stock_profitloss($data){
        $start_date =  $data['start_date'];
        $end_date =  $data['end_date'];

        $company_id = Yii::app()->user->getState('company_branch_id');

        $querystock_opening = "select 
                ifnull(sum(ds.quantity) ,0) as total_recive,
                ifnull(sum(ds.wastage),0) as wastage ,
                ifnull(sum(ds.return_quantity) ,0) as return_quantity
                from daily_stock as ds
                where ds.date between '$start_date' and '$end_date' 
                and ds.company_branch_id = '$company_id'";

        $openoning_result = Yii::app()->db->createCommand($querystock_opening)->queryAll();


        $total_recive  = $openoning_result[0]['total_recive'];

        $wastage  = $openoning_result[0]['wastage'];
        $return_quantity  = $openoning_result[0]['return_quantity'];
        $net_purchase = $total_recive - $wastage-$return_quantity;

        return $net_purchase;
    }

    public static function vendor_bills_function($data){
        $start_date =  $data['start_date'];
        $end_date =  $data['end_date'];

        $company_id = Yii::app()->user->getState('company_branch_id');

        $query = " SELECT 
            ifnull(SUM(b.net_amount),0) AS net_amount
            FROM bill_from_vendor AS b
            WHERE b.company_id = '$company_id'
            AND b.action_date BETWEEN '$start_date' and '$end_date'  ";

        // $query .=" and b.action_date <'$startDate' ";
        $result = Yii::app()->db->createCommand($query)->queryscalar();

        return $result;

    }

    public static function expenses_function($data){
        $start_date =  $data['start_date'];
        $end_date =  $data['end_date'];
        $company_id = Yii::app()->user->getState('company_branch_id');

        $query="SELECT 
                SUM(er.amount) as total_amount
             FROM expence_report AS er
            left join expence_type as et ON er.expenses_type_id = et.expence_type
            where er.company_id = '$company_id' and er.date between
                '$start_date' and '$end_date' ";
        $result = Yii::app()->db->createCommand($query)->queryscalar();

        if($result){
            return $result;
        }else{
            return 0;
        }

    }
    public static function expense_per_ltr($expence,$purchased){
            if($purchased==0){
                return 0;
            }
            $rate = $expence/$purchased;
            return $rate;
    }
    public static function get_total_sale($data){
        $start_date =  $data['start_date'];
        $end_date =  $data['end_date'];
        $company_id = Yii::app()->user->getState('company_branch_id');

        $queryTotalCount = " select ifnull(sum(dd.quantity) ,0) as quantity ,
                ifnull(sum(dd.amount) ,0) as amount  from delivery as d
                left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                where d.company_branch_id = '$company_id' and 
                d.date BETWEEN '$start_date' and '$end_date'  ";

        $queryResult =  Yii::app()->db->createCommand($queryTotalCount)->queryAll();

        $quantity = $queryResult[0]['quantity'];
        $amount = $queryResult[0]['amount'];
        $result =[];
        $result['quantity'] =$quantity;
        $result['amount'] =$amount;

        return $result;
    }

}