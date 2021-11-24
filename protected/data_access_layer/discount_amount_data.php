<?php

class discount_amount_data
{
    public static function discount_of_amount($payment_master_id){

        $query ="SELECT 
            dis.discount_list_id,
            dis.payment_master_id,
            dis.total_discount_amount,
            t.discount_type_name
            FROM discount_list AS dis
            LEFT JOIN discount_type AS t ON t.discount_type_id =dis.discount_type_id
            WHERE dis.payment_master_id =$payment_master_id ";

        $list = Yii::app()->db->createCommand($query)->queryAll();



        $total_amount = 0;
        foreach ($list as $value){
            $total_amount = $total_amount +$value['total_discount_amount'];
        }
        if(sizeof($list)==0){
            $list =[
              [
                  'discount_list_id'=>0,
                  'discount_type_id'=>1,
                  'payment_master_id'=>$payment_master_id,
                  'total_discount_amount'=>0,
                  'discount_type_name'=>'Discount',
              ]
            ];
        }
        $result =[];
        $result['list']=$list;
        $result['total_amount']=$total_amount;

        return $result;
    }
}