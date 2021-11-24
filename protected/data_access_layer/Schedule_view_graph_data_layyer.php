<?php


class Schedule_view_graph_data_layyer
{
   public static function date_rage_delivery($data){
       $clientId = $data['client_id'];
       $startDate = $data['start_date'];
       $endDate = $data['end_date'];

       $query = "Select d.date ,p.name as product_name , p.unit , dd.quantity , dd.amount ,d.time  from delivery as d
                LEFT JOIN delivery_detail as dd ON d.delivery_id = dd.delivery_id
                LEft JOIN product as p ON p.product_id = dd.product_id
                Where d.client_id ='$clientId' AND d.date between '$startDate' AND '$endDate'
                order by d.date DESC ";
       $deliveryResult = Yii::app()->db->createCommand($query)->queryAll();
       return $deliveryResult;


   }


    public static function interval_type($client_id,$product_id){

            $query ="
            SELECT 
            cpf.orderStartDate AS start_date,
            '1'  as order_type,
            cpf.client_id , cpf.product_id  from 
            client_product_frequency as cpf
            where cpf.client_id = '$client_id'
            and cpf.product_id ='$product_id'
            union
            SELECT 
           ic.start_interval_scheduler AS start_date,
            '2' as order_type ,
            ic.client_id ,
            ic.product_id from interval_scheduler as ic
            where ic.client_id ='$client_id' 
            and   ic.product_id='$product_id' ";

        $orderList =  Yii::app()->db->createCommand($query)->queryAll();
        return isset($orderList['0']['order_type'])?$orderList['0']['order_type']:false ;

    }
    public static function get_weekly_schedule($clientID,$productID)
    {

        $clientProductFrequency = ClientProductFrequency::model()->findByAttributes(array('client_id' => $clientID, 'product_id' => $productID));
        $clientProductFrequency = ($clientProductFrequency['client_product_frequency']);
        $query = "Select 
       f.* , IFNULL(cpfq.quantity , 0) as quantity  ,
       IFNULL(pt.preferred_time_id, 0) as PreferredTime ,
       IFNULL(cpfq.isSelected, 0) as isSelected ,
       pt.preferred_time_name from frequency as f
                    left join client_product_frequency_quantity as cpfq ON cpfq.frequency_id = f.frequency_id AND cpfq.client_product_frequency_id ='$clientProductFrequency'
                    Left join preferred_time as pt ON pt.preferred_time_id = cpfq.preferred_time_id
                    order by f.frequency_id ASC ";
        $weeklyResult = Yii::app()->db->createCommand($query)->queryAll();
        $startOrderDate = ClientProductFrequency::model()->findByAttributes(array('client_id' => $clientID, 'product_id' => $productID));
        if ($startOrderDate) {
            $date =  ($startOrderDate['orderStartDate']);
        } else {
            $date = date("Y-m-d");
        }
        $list =[];
        foreach ($weeklyResult as $value){
          $frequency_id =  $value['frequency_id'];
          $quantity =  $value['quantity'];
            $list[$frequency_id]=$quantity;
        }
        $result= [];
       return $list;
    }
}