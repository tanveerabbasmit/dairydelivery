<?php


class dailymilksummary_view_report_data
{
   public static function carry_forward_data($product_id,$company_id,$today_data){


       $query_production="SELECT 
            nullif (sum(cp.morning),0) as morning ,
            sum(cp.afternoun) as  afternoun,
            sum(cp.evenining) as evenining
            from cattle_production as cp
            LEFT JOIN cattle_record AS c 
            ON c.cattle_record_id =cp.cattle_record_id
            WHERE  c.company_id = '$company_id' and cp.date < '$today_data' ";

       $result_production =  Yii::app()->db->createCommand($query_production)->queryRow();

       $total_production = $result_production['morning'] + $result_production['afternoun']+ $result_production['evenining'];




       $querystock_opening = "select 
                ifnull(sum(ds.quantity) ,0) as total_recive,
                ifnull(sum(ds.wastage),0) as wastage ,
                ifnull(sum(ds.return_quantity) ,0) as return_quantity
                from daily_stock as ds
                where ds.date < '$today_data' 
                and ds.product_id = '$product_id'";


       $openoning_result = Yii::app()->db->createCommand($querystock_opening)->queryAll();



       $total_recive  = $openoning_result[0]['total_recive'];
       $wastage  = $openoning_result[0]['wastage'];
       $return_quantity  = $openoning_result[0]['return_quantity'];

       $rider_wastage_query = "SELECT 
              ifnull(sum(rds.wastage_quantity),0) AS wastage_quantity
              FROM rider_daily_stock AS rds
              WHERE  rds.product_id = '$product_id' and 
              rds.date < '$today_data' ";

       $raider_wastage = Yii::app()->db->createCommand($rider_wastage_query)->queryAll();

       $rider_wastage_query =  $raider_wastage[0]['wastage_quantity'];


       $querystck_rider_opening = "select ifnull(sum(d.quantity) ,0) as quantity from delivery_detail as d
                         where d.product_id ='$product_id' and d.date< '$today_data'";


       $deliveryOneDayResult_rider_opening = Yii::app()->db->createCommand($querystck_rider_opening)->queryAll();



       $total_rider_quantity  = $deliveryOneDayResult_rider_opening[0]['quantity'];
       $oneObject['total_rider'] = $total_rider_quantity;
       $oneObject_net = $total_production + $total_recive - $return_quantity -$total_rider_quantity -$wastage -$rider_wastage_query;


       return $oneObject_net;

   }

   public static function today_production($product_id,$company_id,$today_data){
        $query="SELECT 
            nullif (sum(cp.morning),0) as morning ,
            sum(cp.afternoun) as  afternoun,
            sum(cp.evenining) as evenining
            from cattle_production as cp
            LEFT JOIN cattle_record AS c 
            ON c.cattle_record_id =cp.cattle_record_id
            WHERE  c.company_id = '$company_id' AND cp.date = '$today_data' ";
       $queryResult =  Yii::app()->db->createCommand($query)->queryRow();


       return   $queryResult;
   }

   public static function rider_return_sale($product_id,$company_id,$today_data){

       $query="SELECT r.rider_id, r.fullname from rider as r
                 where r.company_branch_id = $company_id
                order by r.is_active DESC , r.fullname ASC ";


       $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


      return $queryResult;

   }

   public static function one_day_credit_function($product_id,$company_id,$today_data){

        $query = " Select IFNULL(sum(dd.quantity),0) AS total_quantity   from delivery as d
          LEFT JOIN delivery_detail AS dd on  d.delivery_id = dd.delivery_id  
            WHERE  d.date = '$today_data' AND dd.product_id ='$product_id'";

       $queryResult =  Yii::app()->db->createCommand($query)->queryscalar();

       $final_result= [];
       $one_object =[];
       $one_object['name'] ='Credit sate';
       $one_object['amount'] =$queryResult;
       $final_result[] =$one_object;

       $result =[];
       $result['list'] = $final_result;
       $result['total'] = $queryResult;
       return $result;
   }
   public static function in_house_usage($product_id,$company_id,$today_data){

       $rider_wastage_query = "SELECT 
              ifnull(sum(rds.wastage_quantity),0) AS wastage_quantity
              FROM rider_daily_stock AS rds
              WHERE  rds.product_id = '$product_id' and 
              rds.date < '$today_data' ";

       $raider_wastage = Yii::app()->db->createCommand($rider_wastage_query)->queryAll();

       $rider_wastage =  $raider_wastage[0]['wastage_quantity'];

       $in_house_use = InHouseUsageCustomer::model()->findAllByAttributes([
           'company_id'=>$company_id
       ]);



       $result = [];
       $one_object =[];
       $one_object['name'] ='wastage';
       $one_object['amount'] =$rider_wastage;

       $result[] =$one_object;

       $total_in_home_uses = $rider_wastage;

       forEach($in_house_use as $value){
           $client_id = $value['client_id'];
           $usage_name = $value['usage_name'];
           $quantity = self::get_today_sale($client_id,$today_data);

           $one_object =[];
           $one_object['name'] =$usage_name;
           $one_object['amount'] =$quantity;

           $total_in_home_uses = $total_in_home_uses + $quantity;

           $result[] =$one_object;


       }



       $final_result = [];
       $final_result['list'] = $result;
       $final_result['total_in_home_uses'] = $total_in_home_uses;

       return $final_result;
   }
   public static function get_today_sale($client_id,$today_data){
        $query = "SELECT 
            SUM(dd.quantity) AS total_quantity
            FROM delivery AS d
            LEFT JOIN delivery_detail AS dd 
            ON d.delivery_id = dd.delivery_id
            WHERE d.client_id = '15550' 
            AND d.DATE ='2021-05-03' AND dd.product_id ='24' ";
       $query_result = Yii::app()->db->createCommand($query)->queryscalar();
       return $query_result ;

   }
   public static function get_total_purchase_farm($product_id,$company_id,$today_data){

       $querystock_opening = "select 
                ifnull(sum(ds.quantity) ,0) as total_recive,
                ifnull(sum(ds.wastage),0) as wastage ,
                ifnull(sum(ds.return_quantity) ,0) as return_quantity,
                ifnull(f.farm_id,'NA') AS farm_id,
                ifnull(f.farm_name,'NA') AS farm_name 
                from daily_stock as ds
                LEFT JOIN farm AS f ON f.farm_id =ds.farm_id
                where ds.date = '$today_data' 
                and ds.product_id = '$product_id'
                GROUP BY ds.farm_id";

       $openoning_result = Yii::app()->db->createCommand($querystock_opening)->queryAll();

        $final_rresult =[] ;

        $grand_total = 0;
       foreach ($openoning_result as $value){

              $farm_id = $value['farm_id'];
              $farm_name = $value['farm_name'];

              $total_recive = $value['total_recive'];
              $wastage = $value['wastage'];
              $return_quantity = $value['return_quantity'];
              $net_quantity =$total_recive -$wastage-$return_quantity;

              $grand_total =$grand_total + $net_quantity;

              $one_object =[];
              $one_object['farm_id'] =$farm_id;
              $one_object['farm_name'] =$farm_name;
              $one_object['net_quantity'] =$net_quantity;
              $final_rresult[] = $one_object;
       }

        $result = [];
        $result['final_rresult'] = $final_rresult;
        $result['grand_total'] = $grand_total;

        return $result;




   }
}
