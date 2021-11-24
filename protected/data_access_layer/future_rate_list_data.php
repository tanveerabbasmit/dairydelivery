<?php

class future_rate_list_data
{
    public static function one_client_price($client_id,$todaydate,$productID){
        $company_id = Yii::app()->user->getState('company_branch_id');
        $query ="SELECT 
            *
            FROM future_rate_list AS l
            WHERE '$todaydate' 
            BETWEEN l.start_date AND l.end_date 
            AND l.client_id ='$client_id'
             and l.product_id='$productID'";


        $result = Yii::app()->db->createCommand($query)->queryRow();
        return $result;

    }
    public static function client_product_wise_array($todaydate){

        $company_id = Yii::app()->user->getState('company_branch_id');
        $query ="SELECT 
           *
        FROM future_rate_list AS l
        WHERE l.company_id ='$company_id'
        AND '$todaydate' 
            BETWEEN l.start_date AND l.end_date ";

        $result = Yii::app()->db->createCommand($query)->queryAll();
        $list =[];
        foreach ($result as $value){
             $client_id = $value['client_id'];
             $product_id = $value['product_id'];
             $rate = $value['rate'];
             $indexValue = $client_id."_".$product_id ;
             $list[$indexValue] =$rate;
        }
        return $list;
    }
}