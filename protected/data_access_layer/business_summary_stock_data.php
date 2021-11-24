<?php


class business_summary_stock_data
{
    public static function opening_stock($startDate ,$product_id ){
        $querystock_opening = "select 
                ifnull(sum(ds.quantity) ,0) as total_recive,
                ifnull(sum(ds.wastage),0) as wastage ,
                ifnull(sum(ds.return_quantity) ,0) as return_quantity
                from daily_stock as ds
                where ds.date < '$startDate' 
                and ds.product_id = '$product_id'";

          $openoning_result = Yii::app()->db->createCommand($querystock_opening)->queryAll();


         $total_recive  = $openoning_result[0]['total_recive'];

        $wastage  = $openoning_result[0]['wastage'];
        $return_quantity  = $openoning_result[0]['return_quantity'];


        $rider_wastage_query = "SELECT 
              ifnull(sum(rds.wastage_quantity),0) AS wastage_quantity
              FROM rider_daily_stock AS rds
              WHERE  rds.product_id = '$product_id' and 
              rds.date < '$startDate' 
              ";

        $raider_wastage = Yii::app()->db->createCommand($rider_wastage_query)->queryAll();

        $rider_wastage_query =  $raider_wastage[0]['wastage_quantity'];



        $querystck_rider_opening= "select ifnull(sum(d.quantity) ,0) as quantity from delivery_detail as d
                         where d.product_id ='$product_id' and d.date< '$startDate'";

        $deliveryOneDayResult_rider_opening = Yii::app()->db->createCommand($querystck_rider_opening)->queryAll();
        $total_rider_quantity  = $deliveryOneDayResult_rider_opening[0]['quantity'];
        $oneObject['total_rider'] = $total_rider_quantity;
        $oneObject['net'] = $total_recive - $return_quantity -$total_rider_quantity -$wastage ;
        $balance =   $oneObject['net'];

         return $balance;


    }

    public static function total_purchased_stock($startDate ,$endDate,$product_id){
        $querystock_opening = "select 
                ifnull(sum(ds.quantity) ,0) as total_recive,
                ifnull(sum(ds.wastage),0) as wastage ,
                ifnull(sum(ds.return_quantity) ,0) as return_quantity
                from daily_stock as ds
                where ds.date between '$startDate' and '$endDate' 
                and ds.product_id = '$product_id'";

        $openoning_result = Yii::app()->db->createCommand($querystock_opening)->queryAll();


        $total_recive  = $openoning_result[0]['total_recive'];

        $wastage  = $openoning_result[0]['wastage'];
        $return_quantity  = $openoning_result[0]['return_quantity'];
        $net_purchase = $total_recive - $wastage-$return_quantity;

        return $net_purchase;
    }
    public static function total_sold_stock($startDate ,$endDate,$product_id){

        $querystck_rider_opening= "select ifnull(sum(d.quantity) ,0) as 
                          quantity from delivery_detail as d
                         where d.product_id ='$product_id'
                          and d.date between  '$startDate' and '$endDate' ";

        $deliveryOneDayResult_rider_opening = Yii::app()->db->createCommand($querystck_rider_opening)->queryAll();
        $total_rider_quantity  = $deliveryOneDayResult_rider_opening[0]['quantity'];

        return $total_rider_quantity;
    }
    public static function total_rider_wastage($startDate ,$endDate,$product_id){
        $rider_wastage_query = "SELECT 
                    ifnull(sum(rds.wastage_quantity),0) AS wastage_quantity
                    FROM rider_daily_stock AS rds
                    WHERE  rds.product_id = '$product_id' and 
                    rds.date between  '$startDate' and '$endDate'  ";

        $raider_wastage = Yii::app()->db->createCommand($rider_wastage_query)->queryAll();

        $rider_wastage_query =  $raider_wastage[0]['wastage_quantity'];

        return $rider_wastage_query;
    }

}