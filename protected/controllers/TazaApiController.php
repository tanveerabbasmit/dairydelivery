<?php

class TazaApiController extends Controller
{



    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout='//layouts/column2';

    /**
     * @return array action filters
     */


    public function filters()
    {


        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }



    public function sendResponse($data)
    {
        echo  json_encode($data);
    }

    public function actionSearchCustomer()
    {
         $post = file_get_contents("php://input");
         $data = CJSON::decode($post, TRUE);
         setEffectiveDateSchedule::checkEffectiveDateSchedule('1');
         $name= $data['name'];
         if(isset($data['company_branch_id'])){
            $company_branch_id = $data['company_branch_id'];
         }else{
            $company_branch_id=0;
         }

         if($company_branch_id==1){
             $apitoken= $data['apitoken'];
             if($apitoken!='admin'){
                 die();
             }
         }

           //  $company_branch_id= $data['company_branch_id'];
             $query = "select c.client_id ,c.address ,c.fullname ,c.cell_no_1 ,c.is_active 
              from client as c 
              where (c.client_id = '$name' 
              OR c.fullname like '%$name%' 
              OR c.address like '%$name%'
              OR c.cell_no_1 like '%$name%') 
              and c.company_branch_id = '$company_branch_id' 
              AND c.is_active = 1 
              limit 15 ";
             $customerProfile = Yii::app()->db->createCommand($query)->queryAll();
             $result = array();
             foreach ($customerProfile as $value){
                 $oneOBject = array();
                 $client_id = $value["client_id"];
                 $oneOBject['client_id'] = $client_id;
                 $oneOBject['fullname'] = $value['fullname'];
                 $oneOBject['cell_no_1'] = $value['cell_no_1'];
                 $oneOBject['address'] = $value['address'];
                 $oneOBject['is_active'] = $value['is_active'];

                 $query_regular = "select * from client_product_frequency as f
               where f.client_id ='$client_id'";
                 $result_regular =  Yii::app()->db->createCommand($query_regular)->queryScalar();
                 $oneOBject['plan_type'] =0;
                 if($result_regular){
                     $oneOBject['plan_type'] =1;
                 }

                 $today_date =Date("Y-m-d");
                 $query_spacial_order = "select * from special_order as so
                  where so.client_id = '$client_id' and so.end_date >='$today_date'";

                 $result_spacial_order =  Yii::app()->db->createCommand($query_spacial_order)->queryAll();
                 if($result_spacial_order){
                     $oneOBject['spacial_order'] =true;
                 }else{
                     $oneOBject['spacial_order'] =false;
                 }

                 $query_interval = "select * from interval_scheduler as f
                 where f.client_id ='$client_id'";
                 $result_interval =  Yii::app()->db->createCommand($query_interval)->queryScalar();
                 if($result_interval){
                     $oneOBject['plan_type'] =2;
                 }
                 $result[]=$oneOBject;
             }
             $response = array(
                 'success' =>true,
                 'data' =>$result,
             );
             $this->sendResponse($response);

    }

    public function actionmakeActiveUntiveCustomer(){
       $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

         $client_id = $data['client_id'];

         $clint =Client::model()->findByPk(intval($data['client_id']));
          if($data['is_active']){
              $clint->is_active =1;
          }else{
              $clint->is_active =0;
              $clint->deactive_reason = $data['inactive_reason'];
              $clint->deactive_date =  date("Y-m-d");
          }
         if($clint->save()){
             $response = array(

                 'success' =>true,

                 'message' =>"Updated Seccessfully",
             );

         }else{
             $response = array(

                 'success' =>false,

                 'message' =>"Updated fail",
             );
         }



        $this->sendResponse($response);

    }

    public function actioncreateCustomer(){

        date_default_timezone_set("Asia/Karachi");
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $userName =   $data['userName'];
        $apitoken =   $data['apitoken'];
        $userPhoneNumber = $data['cell_no_1'];

        if(isset($data['company_branch_id'])){
            $company_branch_id = $data['company_branch_id'];
        }else{
            $company_branch_id=0;
        }

        if($company_branch_id==1){
            $apitoken= $data['apitoken'];
            if($apitoken!='admin'){
                die();
            }
        }
        $company_branch_id =  $data['company_branch_id'] ;
        $companyObject  =  utill::get_companyTitle($data['company_branch_id']);
        $companyMask = $companyObject['sms_mask'];
        $companyTitle = $companyObject['company_title'];
        $unregister =  Client::model()->findByAttributes(array('cell_no_1'=>$userPhoneNumber , 'is_active'=>'0' ,'company_branch_id'=>$company_branch_id ));

        if($unregister){
            try{
                $unregister->delete();
            }catch (Exception $e){
                $IDArray = array();
                $IDArray['client_id'] = "" ;

                $response = array(
                    'code' => 401,
                    'company_branch_id'=>0,
                    'success' => false,
                    'alreadyExists'=>false ,
                    'message'=>"This Phone Number Already Register",
                    'data' => $IDArray
                );
                $this->sendResponse($response);
                die();
            }

        }


        $checkUserName = Client::model()->findByAttributes(array('userName'=>$userName, 'company_branch_id'=>$company_branch_id));
        $checkUserPhoneNumber = Client::model()->findByAttributes(array('cell_no_1'=>$userPhoneNumber , 'company_branch_id'=>$company_branch_id));

        if($checkUserName){
            $IDArray = array();
            $IDArray['client_id'] = "" ;
            $response = array(
                'code' => 401,
                'company_branch_id'=>0,
                'success' =>false,
                'alreadyExists'=>true ,
                'message'=>'This username already exists . Try another username',
                'data' =>$IDArray
            );
        }elseif($checkUserPhoneNumber){
            $IDArray = array();
            $IDArray['client_id'] = "" ;
            $response = array(
                'code' => 401,
                'company_branch_id'=>0,
                'success' =>false,
                'alreadyExists'=>true ,
                'message'=>'This phone No. already exists . Try another phone No.',
                'data' =>$IDArray ,
            );
        }else{
            $network_id = 0;
            if(isset($data['network_id'])){
                $network_id = $data['network_id'];
            }
            $client = new Client();
            $client->user_id = '2';

            $client->zone_id= $data['zone_id'];
            $client->network_id= $network_id;
            $client->fullname= $data['fullName'];
            $client->userName= $data['userName'];
            $client->password= $data['password'];
            $client->company_branch_id= $data['company_branch_id'];
            $client->father_or_husband_name = ' ';
            $client->date_of_birth = $data['date_of_birth'];
            $client->email = $data['email'];
            $client->cnic = $data['cnic'];
            $client->cell_no_1 = $data['cell_no_1'];
            $client->cell_no_2 = '12334543';
            $client->residence_phone_no = '1234567';
            $client->city= 'no';
            $client->area= 'no';
            $client->address = $data['address'];
            $client->is_active = 1;
            $client->is_deleted= 0;
            $client->created_by = '2';
            $client->updated_by = '2';
            $client->login_form = '2';
            $client->view_by_admin = '1';
            $client->new_create_date= date("Y-m-d");
             if($apitoken=='admin'){
                 if($client->save()) {
                     $clientID =$client->client_id ;
                     $code = sprintf("%04s",mt_rand(0000, 9999));
                     $authenticate = new Authentication();
                     $authenticate->client_id = $clientID ;
                     $authenticate->code = $code;
                     $authenticate->SetTime = time();

                     if($authenticate->save()){

                         $IDArray = array();
                         $IDArray['client_id'] = $clientID ;
                         $response = array(
                             'code' => 200,
                             'company_branch_id'=>0,
                             'success' =>true,
                             'alreadyExists'=>false,
                             'message'=>"Customer have created successfully .",
                             'data' =>$IDArray,
                         );
                         $message = "Your verification code for ".$companyTitle. " is ".$code;


                         // smsLog::saveSms($clientID ,$company_branch_id ,$data['cell_no_1'] ,$data['fullName'] ,$message);

                         // $this->sendSMS($data['cell_no_1'],$message , $companyMask , $company_branch_id ,$network_id);


                     }
                 }else{

                     $IDArray = array();
                     $IDArray['client_id'] = "" ;

                     $response = array(
                         'code' => 401,
                         'company_branch_id'=>0,
                         'success' => false,
                         'alreadyExists'=>false ,
                         'message'=>$client->getErrors(),
                         'data' => $IDArray
                     );
                 }
             }

        }
        $this->sendResponse($response);
    }

    public function actionupdatespecialOrder(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
         $special_order_id = $data['special_order_id'];

         $start_date = $data['start_date'];
         $end_date = $data['end_date'];
         $quantity = $data['quantity'];

        $special_order_id = $data['special_order_id'];
        $specialOrder = SpecialOrder::model()->findByPk(intval($special_order_id));

        $specialOrder->quantity =$quantity;
        $specialOrder->start_date =$start_date;
        $specialOrder->end_date = $end_date;
         if($specialOrder->save()){
             $response = array(
                 'code' => 401,
                 'company_branch_id'=>0,
                 'success' => true,

                 'message'=>'Updated successfully ',

             );
         }else{
             $response = array(
                 'code' => 401,
                 'company_branch_id'=>0,
                 'success' => false,
                'message'=>$specialOrder->getErrors(),

             );
         }
        $this->sendResponse($response);
    }

    public function actiongetSpecialOrderList(){

        date_default_timezone_set("Asia/Karachi");
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
         $client_id = $data['client_id'];
         $today =date("Y-m-d");

            $query_special = "select p.product_id, o.special_order_id ,
                      o.quantity ,o.start_date ,
                      o.end_date ,p.name as product_name from special_order o
                      left join product as p ON p.product_id =o.product_id
                      where o.client_id = '$client_id' and o.end_date >= '$today'";

            $result_result =  Yii::app()->db->createCommand($query_special)->queryAll();

            $response = array(
                'code' => 401,
                'company_branch_id'=>0,
                'success' => true,

                'data'=>$result_result,

            );
         $this->sendResponse($response);

    }
    public function actioncancelSpecialOrder(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $special_order_id = $data['special_order_id'];
        $specialOrder = SpecialOrder::model()->findByPk(intval($special_order_id));

         if($specialOrder->delete()){

             $response = array(
                 'code' => 401,
                 'company_branch_id'=>0,
                 'success' => true,

                 'message'=>'Cancel successfully ',

             );
         }else{

             $response = array(
                 'code' => 401,
                 'company_branch_id'=>0,
                 'success' => false,

                 'message'=>'Cancel Failed ',

             );
         }
        $this->sendResponse($response);

    }

    public function actiongetCustomersOfRiderDateWise(){

        date_default_timezone_set("Asia/Karachi");



        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $riderID = ($data['rider_id']);
        if(isset($data['company_branch_id'])){
            $company_branch_id = ($data['company_branch_id']);

        }else{
            $data['company_branch_id'] = 1;
            $company_branch_id = 1;
        }

        if(isset($data['zone_id'])){
            $zone_id = $data['zone_id'] ;
            $select_zone = true ;
        }else{
            $select_zone = false;
        }
        setEffectiveDateSchedule::checkEffectiveDateSchedule($company_branch_id);

         $todaydate =  $data['selecDate'];


        $timestamp = strtotime($todaydate);
        $day = date('D', $timestamp);
        $todayfrequencyID = '';
        if($day == 'Mon'){
            $todayfrequencyID = 1 ;
        }elseif($day == 'Tue'){
            $todayfrequencyID = 2;
        }elseif($day == 'Wed'){
            $todayfrequencyID = 3 ;
        }elseif($day == 'Thu'){
            $todayfrequencyID = 4 ;
        }elseif($day == 'Fri'){
            $todayfrequencyID = 5 ;
        }elseif($day == 'Sat'){
            $todayfrequencyID = 6 ;
        }else{
            $todayfrequencyID = 7 ;
        }


        if($select_zone){

            $clientQuery = "select  c.client_type ,  c.fullname ,c.cell_no_1,c.address , c.client_id ,c.zone_id from client as c
                    where c.zone_id = '$zone_id'
                  order by c.rout_order ASC ,c.fullname ASC";
        }else{

            $clientQuery = "Select c.client_type ,  c.fullname ,c.cell_no_1,c.address , c.client_id , rz.zone_id from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id
                           where rz.rider_id = $riderID  AND c.is_active = 1 
                            order by c.rout_order ASC ,c.fullname ASC";
        }


        $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();

          /* new schedual customer List*/

            $client_array_list = array();

            $id_list = array();
            $id_list[] =0;
            foreach ($clientResult as $value){
                $id_list[] = $value['client_id'];
            }

            $client_ids_list =implode(',' ,$id_list);

            $future_date_quanitiy_object = array();

            if($todaydate >date("Y-m-d")){
                $future_date_quanitiy_object = setEffectiveDateSchedule::getFutureDateQuantity($client_ids_list,$todaydate);

            }

            $change_scheduler_client_list = apiTazaData::get_change_scheduler_client_list($client_ids_list);


          /* new schedual customer List*/

        $finalResult = array();



        $todaydate_month =  date("m");
        $perivous_month = $todaydate_month - 1;
        $company_id =  $data['company_branch_id'] ;
        $query_payment = "select pm.client_id  from payment_master as pm
             where pm.company_branch_id ='$company_id' and month(pm.bill_month_date) = '$perivous_month'" ;


        $result_payment = Yii::app()->db->createCommand($query_payment)->queryAll();
        $result_payment_client_list = array();
        foreach ($result_payment as $list){
            $client_id = $list['client_id'];
            $result_payment_client_list[$client_id] = true ;
        }

        date_default_timezone_set("Asia/Karachi");

        $today_Server_Time =date("Y-m-d");

        if($todaydate > $today_Server_Time){
            $effectiveDate_intervat_clientList = effectiveDateScheduleData::effectiveDate_interval_Client_list_app($todaydate,$client_ids_list);
            $effectiveDate_weekly_clientList = effectiveDateScheduleData::effectiveDate_weekly_Client_list_app($todaydate,$client_ids_list);
        }

        foreach($clientResult as $value){

            $clientID = $value['client_id'];

            $effectiove_future_schedule = false;

            if($todaydate > $today_Server_Time){
               if(isset($effectiveDate_intervat_clientList[$clientID])){
                   $effectiove_future_schedule = true;
               }
               if(isset($effectiveDate_weekly_clientList[$clientID])){
                   $effectiove_future_schedule = true;
               }
            }


            $data=array();
            $data['client_type'] = $value['client_type'];

            if($effectiove_future_schedule){
                $data['fullname'] = $value['fullname']."(#)";
            }else{
                $data['fullname'] = $value['fullname'];
            }

            $data['cell_no_1'] = $value['cell_no_1'];
            $data['address'] = $value['address'];
            $data['client_id'] = $value['client_id'];
            $data['client_id'] = $value['client_id'];

            if(isset($result_payment_client_list[$clientID])){
                $data['previous_month_payment'] = true;
            }else{
                $data['previous_month_payment'] = false;
            }


            /* new_schedual Tag */

            if(isset($change_scheduler_client_list[$clientID])){
                $data['new_schedual'] = 1;
            }else{
                $data['new_schedual'] = 0;
            }

           /* if($effectiove_future_schedule){
                $data['new_schedual'] = 1;
            }*/

            /* new_schedual Tag */


            $queryCheckTodayDelivery = "select * from delivery as d
                    where d.client_id = '$clientID' and d.date = '$todaydate'";

            $checkTodayDeliveryResult = Yii::app()->db->createCommand($queryCheckTodayDelivery)->queryAll();

            $checkDelievry = true;
            if($checkTodayDeliveryResult){
                $data['is_delivered'] = 2;
                $checkDelievry = false;
            }else{
                $data['is_delivered'] = 1;
                $checkDelievry = true;
            }
            $queryCheckTodayDelivery = "select * from not_delivery_record as d
                    where d.client_id = '$clientID' and date(d.not_delivery_dateTime) = '$todaydate'";
            $checkTodayDeliveryResult = Yii::app()->db->createCommand($queryCheckTodayDelivery)->queryAll();
            if($checkTodayDeliveryResult){
                if($checkDelievry){
                    $data['is_delivered'] = 3;
                }
            }

            /* $productQuery = "Select (IFNULL(sum(cpfq.quantity) ,0) + IFNULL(sum(so.quantity),0)) as quantity ,p.name as product_name ,p.product_id  , 0 as deliveryTime  from client_product_frequency as cpf
                 left join  halt_regular_orders as hro ON hro.client_id = cpf.client_id and '$todaydate' between hro.start_date AND hro.end_date and hro.product_id = cpf.product_id
                 LEFT JOIN client_product_frequency_quantity as cpfq  ON cpfq.client_product_frequency_id = cpf.client_product_frequency
                 AND cpfq.frequency_id = '$todayfrequencyID' and  hro.halt_regular_orders_id is  NULL
                 left join product as p ON p.product_id = cpf.product_id
                 left join special_order as so ON so.client_id = '$clientID' AND so.product_id = p.product_id AND '$todaydate' between so.start_date AND so.end_date
                 where cpf.client_id = '$clientID'
                 group by cpf.product_id " ;*/


            $productQuery = " select sum(quantity) as quantity ,product_name ,product_id ,deliveryTime from (
                Select 'a' as test_quantity , (IFNULL(sum(cpfq.quantity) ,0) ) as quantity ,p.name as product_name ,p.product_id  , 0 as deliveryTime  from client_product_frequency as cpf
                    left join  halt_regular_orders as hro ON hro.client_id = cpf.client_id and '$todaydate' between hro.start_date AND hro.end_date and hro.product_id = cpf.product_id
                    LEFT JOIN client_product_frequency_quantity as cpfq  ON cpfq.client_product_frequency_id = cpf.client_product_frequency
                    AND cpfq.frequency_id = '$todayfrequencyID' and  hro.halt_regular_orders_id is  NULL
                    left join product as p ON p.product_id = cpf.product_id
                    where     cpf.client_id = '$clientID'  
                    group by p.product_id
                    union
                  select  'b' as test_quantity ,IFNUll(sum(so.quantity) ,0) as quantity ,p.name as product_name ,p.product_id , 0 as deliveryTime from special_order as so
                     left join product as p ON p.product_id = so.product_id
                     where  so.client_id = '$clientID' AND '$todaydate' between 
                     so.start_date AND so.end_date
                     group by p.product_id
                     
                     ) as abcd
                     group by product_id " ;

            if(!$checkDelievry){
                $productQuery = " select sum(dd.quantity) as quantity ,p.name as product_name ,p.unit , p.product_id ,d.time as deliveryTime  from delivery as d
                left join delivery_detail as dd ON d.delivery_id = dd.delivery_id
                left join product as p ON p.product_id =dd.product_id
                where d.client_id = '$clientID' and d.date = '$todaydate'
                group by dd.product_id ";
            }


            $productLIst = Yii::app()->db->createCommand($productQuery)->queryAll();

            $checkQuantityProduct = array();
            foreach($productLIst as $value){
                $productID = $value['product_id'];
                $checkQuantityProduct[$productID] = $value['quantity'];
            }

            $query=" SELECT p.*  from   product as p 
                where p.company_branch_id =$company_branch_id and p.is_active = 1 and p.bottle =0";
            $productList =  Yii::app()->db->createCommand($query)->queryAll();

            if($checkDelievry){
                $newProductLIst = array();
                foreach($productList as $product){
                    $oneProduct = array();
                    $product_id = $product['product_id'];
                    if(isset($checkQuantityProduct[$product_id])){
                        $quantity = $checkQuantityProduct[$product_id] ;
                    }else{
                        $quantity = '0';
                    }
                    $oneProduct['product_name'] = $product['name'];
                    $oneProduct['unit'] = $product['unit'];
                    $oneProduct['product_id'] = $product['product_id'];
                    $oneProduct['deliveryTime'] = 0;
                    //   $oneProduct['clientID'] = $clientID;
                    $product_id = $product['product_id'];
                    // $intervalQuantity = utill::getOneCustomerTodayIntervalSceduler($clientID ,$product_id );

                    $intervalQuantity =  future_selet_date::getOneCustomerTodayIntervalSceduler_with_date_future_date($clientID , $product['product_id'] ,$todaydate);
                    $client_product =  $clientID.$product_id ;
                    if(isset($future_date_quanitiy_object[$client_product])){
                        $oneProduct['quantity'] = $future_date_quanitiy_object[$client_product] ;
                    }else{
                        $oneProduct['quantity'] = $quantity + $intervalQuantity;
                    }



                    $newProductLIst[] = $oneProduct ;
                }
                $data['productList'] = $newProductLIst;
            }else{
                $data['productList'] = $productLIst;
            }


            $finalResult[] = $data ;
        }
        $response = array(
            'code' => 200,
            'company_branch_id'=>0,
            'success' => true,
            'message'=>'Customers List',
            'data' => $finalResult
        );
        $this->sendResponse($response);
    }


}
