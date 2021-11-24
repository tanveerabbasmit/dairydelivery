<?php
/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 3/3/2017
 * Time: 3:31 PM
 */
class setTotalAmount{
    public static $response = ['success'=>FALSE , 'message'=>'','data'=>[]];

    public static function total_amount($deliveryId){


        $check_duplicate_query = "SELECT 
                d.delivery_detail_id,
                COUNT(*) as total
                FROM 
                delivery_detail AS d
                WHERE d.delivery_id = '$deliveryId'
                group BY d.product_id";

        $queryResult =  Yii::app()->db->createCommand($check_duplicate_query)->queryAll();

        if(isset($queryResult[0])){
           $total =  $queryResult[0]['total'];
           $delivery_detail_id =  $queryResult[0]['delivery_detail_id'];
           if($total>1){
                $object = DeliveryDetail::model()->findByPk($delivery_detail_id);
                $object->delete();
           }
        }

        $query=" SELECT * FROM delivery AS d
            WHERE d.delivery_id =$deliveryId ";

        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

        foreach($queryResult as $value){

            $delivery_amount = $value['total_amount'];
            $delivery_id = $value['delivery_id'];
            $deliveryDetail = DeliveryDetail::model()->findAllByAttributes(
                array('delivery_id'=>$delivery_id)
            );
            $amount_total =0;

            if($deliveryDetail){
                foreach($deliveryDetail as $value){
                    $amount = $value['amount'];
                    $amount_total = $amount_total + $amount;
                }
            }

            if($delivery_amount !=$amount_total){
                $delivery_object = Delivery::model()->findByPk(intval($delivery_id));
                $delivery_object->total_amount = $amount_total ;
                $delivery_object->save();
            }
        }
    }

}