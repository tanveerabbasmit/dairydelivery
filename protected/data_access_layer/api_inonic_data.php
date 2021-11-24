<?php


class api_inonic_data
{
    public static function halt_delivery_delete($data){
        $client_id =$data['client_id'];
        $product_id =$data['product_id'];
        $start_date =$data['start_date'];
        $end_date =$data['end_date'];


        $query = "SELECT * FROM 
            halt_regular_orders AS hr
            WHERE hr.start_date 
            BETWEEN '$start_date' 
            AND '$end_date' 
            AND hr.client_id = '$client_id'
            AND hr.product_id ='$product_id' ";



        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


        foreach ($queryResult as $value){
           $halt_regular_orders_id = $value['halt_regular_orders_id'];

           $object = HaltRegularOrders::model()->findByPk($halt_regular_orders_id);
            $object->delete();
        }
    }
    public static function save_special_order_delete($data){
        $client_id =$data['client_id'];
        $product_id =$data['product_id'];
        $start_date =$data['start_date'];
        $end_date =$data['end_date'];


        $query = "SELECT * FROM 
            special_order AS hr
            WHERE hr.start_date 
            BETWEEN '$start_date' 
            AND '$end_date' 
            AND hr.client_id = '$client_id'
            AND hr.product_id ='$product_id' ";


        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

        foreach ($queryResult as $value){


           $special_order_id = $value['special_order_id'];

           $object = SpecialOrder::model()->findByPk($special_order_id);
            $object->delete();
        }
    }

    public static function get_all_plan_data($data){


        $client_id = $data['client_id'];
        $product_id = $data['product_id'];
        $start_date = $data['start_date'];
        $end_date = $data['end_date'];
        $already_delivered = clientData::get_delivery_between_date_rang_api($data);

        $order_list_object = clientData::getOrderAgainstClint_api($client_id);

        $order_list =[];
        foreach ($order_list_object as $value){
            $product_id =$value['product_id'];
            $order_list[$product_id] =$value;
        }
        $final_Result= [];
        if(isset($order_list[$product_id]['order_type'])){

            $final_Result['plan_type']  = isset($order_list[$product_id]['order_type'])?$order_list[$product_id]['order_type']:null;
            $final_Result['start_date'] = $order_list[$product_id]['start_date'];

            $final_Result['plan_info'] = clientData::selectFrequencyForOrderFunction_api($order_list[$product_id]['order_type'],$data);

        }else{

            $final_Result['plan_type']  = null;
            $final_Result['start_date'] = '';
            $final_Result['plan_info'] = [];
        }

        $final_Result['special_orders'] = clientData::manageSpecialOrder_function($data);
        $final_Result['halted_dates'] = clientData::halt_regular_order_api_function($data);
        $final_Result['already_delivered'] =$already_delivered;

         return $final_Result;
    }
    public static function deleted_halt_dates(){

    }
}