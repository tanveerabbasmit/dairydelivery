<?php
/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 3/3/2017
 * Time: 3:31 PM
 */
class manageSpecialOrderDATA{
    public static $response = ['success'=>FALSE , 'message'=>'','data'=>[]];

       public static function getSpecialOrderList($data){

           $company_id = Yii::app()->user->getState('company_branch_id');
           $offset = 0 ;
           if($data){
                 $date = $data['date'];
                 $page = $data['page'];
                 $offset = $page * 10;
           }else{
               $date =  date("Y-m-d");
           }

            $query = "SELECT 
                c.client_id, 
                c.fullname,
                c.address,
                so.address as select_address,
                c.cell_no_1
                FROM special_order AS so
                LEFT JOIN client AS c ON so.client_id = c.client_id 
                WHERE date(so.requested_on) = '$date'
                AND  so.company_branch_id = '$company_id'
                GROUP BY so.client_id ";


           $result =  Yii::app()->db->createCommand($query)->queryAll();

           $final_result = [];
          foreach($result as $value_2){

             
              $fullname =  $value_2['fullname'];
              $address =   $value_2['address'];
              $address =   $value_2['address'];
              $client_id = $value_2['client_id'];


              $query = "SELECT 
                     so.is_delivered,
                     so.special_order_id,
                     p.name,
                     p.product_id,
                     p.price,
                     '0' as total_price,
                     so.quantity
                    FROM special_order AS so
                    LEFT JOIN product AS p 
                    ON p.product_id = so.product_id
                    WHERE so.client_id ='$client_id'
                    AND date(so.requested_on) ='$date'";

              $query_result =  Yii::app()->db->createCommand($query)->queryAll();

              $total_price = 0;
              $total_quanity = 0;
              $query_result_grand =[];
              foreach($query_result as $value){
                  $value['total_price'] =$value['quantity'] * $value['price'];
                  $total_price = $total_price + $value['total_price'];
                  $total_quanity = $total_quanity + $value['quantity'];
                  $query_result_grand[] = $value;
              }

              $grand_total = [];
              $grand_total['total_price'] = $total_price;
              $grand_total['total_quanity'] = $total_quanity;




              $one_object = [];
              $one_object['client_object'] =$value_2;
              $one_object['prouct_object'] =$query_result_grand;
              $one_object['grand_total'] =$grand_total;
              $final_result[] = $one_object;
          }

           return json_encode($final_result);

       }

       public static function getviewAllFunction_spacial_order($data){

           $company_id = Yii::app()->user->getState('company_branch_id');


           $start_date = $data['startDate'];
           $end_date = $data['endDate'];
           $rider_id = $data['RiderID'];
           $product_id = $data['product_id'];

           if($rider_id ==0){
               $clientQuery = "Select c.client_id ,
                     c.fullname ,c.address from client as c
                              where c.company_branch_id ='$company_id' and c.client_type ='2' ";


               $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();
           }else{
               $clientQuery = "Select c.client_id ,c.fullname ,c.address from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id 
                           where rz.rider_id = $rider_id  and c.client_type ='2' ";
               $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();

           }


            $client_id_object = array();
            $client_id_object[] =0;
            foreach ($clientResult as $value){
                 $client_id_object[] = $value['client_id'];
            }

           $client_id_list =  implode(',',$client_id_object);



           $today_date = date("Y-m-d");

           $query="SELECT so.*  ,
            c.fullname  ,
            c.address  ,
            p.name ,
            s.status_name 
            from special_order as so
            LEFT JOIN client as c ON c.client_id = so.client_id 
            LEFT JOIN product as p ON p.product_id = so.product_id
            LEFT JOIN  status as s ON s.status_id = so.status_id
            where so.company_branch_id = '$company_id' 
            AND c.client_id IN ($client_id_list) 
            and  so.delivery_on >='$today_date'
            and so.product_id ='$product_id'
            ORDER BY so.delivery_on DESC ";

            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

            $result = array();
            $result['spacial_order'] = $queryResult;
            $result['count'] = 0;

            return $result;
       }
       public static function getviewAllFunction($page){
           $company_id = Yii::app()->user->getState('company_branch_id');
           $offset = 0 ;
           if($page){

                 $offset = $page * 10;
           }else{
               $date =  date("Y-m-d");
           }

           $today_date = date("Y-m-d");

           $query="SELECT so.*  , c.fullname  ,p.name , s.status_name from special_order as so
                   LEFT JOIN client as c ON c.client_id = so.client_id 
                    LEFT JOIN product as p ON p.product_id = so.product_id
                    LEFT JOIN  status as s ON s.status_id = so.status_id
                    where so.company_branch_id = '$company_id' 
                     ORDER BY so.delivery_on DESC
                     LIMIT 10 OFFSET $offset ";



            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

           $queryCount="SELECT so.*  from special_order as so where so.company_branch_id = $company_id";
           $queryCountResult =  Yii::app()->db->createCommand($queryCount)->queryAll();
              $result = array();
              $result['order'] = $queryResult;
              $result['count'] = count($queryCountResult);

            return json_encode($result);
       }

        public static function searchDeliveryDateFuntion($date){

                 $query="SELECT so.*  , c.fullname  ,p.name , s.status_name from special_order as so
                           LEFT JOIN client as c ON c.client_id = so.client_id 
                            LEFT JOIN product as p ON p.product_id = so.product_id
                            LEFT JOIN  status as s ON s.status_id = so.status_id
                             where so.delivery_on = '$date' ";

                    $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


                    return json_encode($queryResult);
               }

       public static function getSpecialOrderCount(){

           $query="SELECT so.*   from special_order as so ";
            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


            return count($queryResult);
       }



       public static function saveNewZoneFunction($data){

              $zone = new Zone();
              $zone->company_branch_id = $data['companyBranch'];
             $zone->name  = $data['name'];
              $zone->is_active = $data['is_active'];
              $zone->is_deleted = $data['is_deleted'];
               if($zone->save()){
                   $zoneID = $zone->zone_id;
                   $query="SELECT z.* , cb.name as companyBranchName from zone as z
                  LEFT JOIN company_branch as cb ON cb.company_branch_id = z.company_branch_id
                     where z.zone_id = $zoneID ";
                   $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
                   zoneData::$response['success']=true;
                   zoneData::$response['message']='ok';
                   zoneData::$response['zone']=$queryResult;

               }else{
                   zoneData::$response['success'] = false ;
                   zoneData::$response['message'] = $zone->getErrors() ;

               }
               return json_encode(zoneData::$response);

       }

       public static function editZoneFunction($data){

              $zone = Zone::model()->findByPk($data['zone_id']);
              $zone->company_branch_id = Yii::app()->user->getState('company_branch_id');
             $zone->name  = $data['name'];
              $zone->is_active = $data['is_active'];
              $zone->is_deleted = $data['is_deleted'];
               if($zone->save()){

                   zoneData::$response['success']=true;
                   zoneData::$response['message']='ok';


               }else{
                   zoneData::$response['success'] = false ;
                   zoneData::$response['message'] = $zone->getErrors() ;

               }
               return json_encode(zoneData::$response);

       }


       public static function deleteFunction($data){


              $zone = Zone::model()->findByPk($data['zone_id']);
              $zone->is_deleted = 1 ;
               if($zone->save()){

                   zoneData::$response['success']=true;
                   zoneData::$response['message']='ok';


               }else{

                   zoneData::$response['success'] = false ;
                   zoneData::$response['message'] = $zone->getErrors() ;

               }
               return json_encode(zoneData::$response);

       }

}