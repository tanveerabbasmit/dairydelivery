<?php

class ApiController extends Controller
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

    public function actionUblpayment()
    {
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);



        $finaData = array();
        $finaData['bill_year'] ='2018';
        $finaData['bill_month'] ='5';
        $finaData['amount_paid'] =$data['amount'];
        $finaData['trans_ref_no'] ='52324';
        $finaData['client_id'] =$data['user_id'];
        $finaData['remarks'] ='hbl';
        $finaData['company_branch_id'] ='2';
        $finaData['payment_mode'] ='2';
        $finaData['startDate'] ='2018-05-20';
        echo  conformPayment::conformPaymentMethodFromPortal(2 , $finaData);
    }

    public function actionLogin(){

        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);



        $userName = $data['userName'];
        $password = $data['password'];

        $messaging_token ='';
        $type ='';
        if(isset($data['messaging_token'])){

            $messaging_token = $data['messaging_token'];

            $type = $data['type'];
        }

        if(!isset($data['company_branch_id'])){
            $data['company_branch_id'] = 1;
        }

        $company_branch_id =  $data['company_branch_id'] ;
        $query = "Select * from  client as c
        where c.userName ='$userName' and c.company_branch_id =$company_branch_id ";

        $user = Yii::app()->db->createCommand($query)->queryAll();

        if(count($user)===0) {
            $response = array(
                'code' => 401,
                'company_branch_id'=>0,
                'success' => false,
                'message'=>'Invalid username',
                'data' => []
            );
        }else{
            if($user[0]['password'] == $password){
                $zone_id= $user[0]['zone_id'];
                $rider_Object = utill::getRiderName($zone_id);

                $getDelivery_count = utill::getDelivery_count($user[0]['client_id']);


                $client_id = $user[0]['client_id'];


                if(!empty($messaging_token)){

                    $delet_object = SaveMessageToken::model()->deleteAllByAttributes([
                        'messaging_token'=>$messaging_token
                    ]);

                    $object = SaveMessageToken::model()->findByAttributes([
                        'client_id'=>$client_id
                    ]);
                    if(!$object){

                        $object = New SaveMessageToken();
                    }

                    $object->type=$type;
                    $object->messaging_token=$messaging_token;
                    $object->client_id=$client_id;


                    if($object->save()){

                    }else{
                        echo "<pre>";
                        print_r($object->getErrors());
                        die();
                    }

                }

                $client_object = Client::model()->findByPk($client_id);

                if($client_object['is_called_log']==0){
                    $client_object->is_called_log =1;
                    $client_object->is_push_notification =1;
                    $client_object->is_mobile_notification =0;
                    $client_object->save();
                }


                $data = array();
                $data['client_id'] = $user[0]['client_id'];
                $data['rider_name'] = $rider_Object[0]['rider_name'];
                $data['rider_phoneNumber'] = $rider_Object[0]['cell_no_1'];
                $data['fullname'] = $user[0]['fullname'];
                $data['email'] = $user[0]['email'];
                $data['cell_no_1'] = $user[0]['cell_no_1'];
                $data['total_delivery'] = $getDelivery_count;
                $clientObect = Client::model()->findByPk(intval($data['client_id']));
                date_default_timezone_set("Asia/Karachi");
                $clientObect->LastTime_login = date("Y-m-d H:i");
                $clientObect->save();

                $response = array(
                    'code' => 200,
                    'company_branch_id'=>0,
                    'success' =>true,

                    'message'=>'Login successfully.',
                    'data' =>$data,
                );
            }else{
                $response = array(
                    'code' => 401,
                    'company_branch_id'=>0,
                    'success' => false,

                    'message'=>'Invalid password ',
                    'data' => []
                );
            }
        }

        $this->sendResponse($response);
    }

    public function actionUpdateCustomerProfile(){

        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $clientID = $data['client_id'];

        $client = Client::model()->findByPk(intval($data['client_id']));
        $network_id = 0;

        if(isset($data['network_id'])){
            $network_id = $data['network_id'];
        }

        $client->user_id = '2';
        $client->fullname = $data['fullname'];

        $client->zone_id= $data['zone_id'];
        $client->network_id= $data['network_id'];
        $client->email = $data['email'];
        $client->cnic = $data['cnic'];
        $client->cell_no_1 = $data['cell_no_1'];
        $client->address = $data['address'];
        if($client->save()) {
            $query = "SELECT c.fullname , c.email , c.cnic ,c.cell_no_1 , c.address ,c.zone_id , z.name as zone_name from client as c
                      LEFT JOIN zone as z ON c.zone_id =  z.zone_id
                      where c.client_id = '$clientID' ";
            $customerProfile = Yii::app()->db->createCommand($query)->queryAll();
            $response = array(
                'code' => 200,
                'company_branch_id'=>0,
                'success' =>true,
                'title' => 'Update profile',
                'message'=>'Profile updated successfully.',
                'data' =>$customerProfile[0],
            );
        }else{
            $response = array(
                'code' => 401,
                'company_branch_id'=>0,
                'success' => false,
                'title' => 'forbidden',
                'message'=>$client->getErrors(),
                'data' => []
            );
        }

        $this->sendResponse($response);
    }

    public  function actiongetDiscountTypeList(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $company_id = $data['company_branch_id'];

        $query = "SELECT * FROM discount_type WHERE company_id = '$company_id' ";

        $type = Yii::app()->db->createCommand($query)->queryAll();

        $response = array(
            'code' => 200,
            'company_branch_id'=>$company_id,
            'data' => $type
        );
        $this->sendResponse($response);

    }

    public function actioncustomerSignUP(){


        date_default_timezone_set("Asia/Karachi");
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);



        $userName =   $data['userName'];
        $userPhoneNumber = $data['cell_no_1'];
        if(!isset($data['company_branch_id'])){
            $data['company_branch_id'] = 1;
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
            $client->is_active = 0;
            $client->is_deleted= 0;
            $client->created_by = '2';
            $client->updated_by = '2';
            $client->login_form = '2';
            $client->view_by_admin = '1';
            $client->is_mobile_notification = '1';
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
                        'message'=>"We've sent and SMS on your number. Please enter the verification code below to complete the sign-up process.
                                       Please allow up to a minute for your SMS to arrive.",
                        'data' =>$IDArray,
                    );
                    $message = "Your verification code for  is ".$code;
                    $message =   'Your code is '.$code;

                    smsLog::saveSms($clientID ,$company_branch_id ,$data['cell_no_1'] ,$data['fullName'] ,$message);

                    utill::sendSMS2($data['cell_no_1'] , $message , $companyMask ,$company_branch_id , 1, $clientID );

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
        $this->sendResponse($response);
    }

    public function actioncustomerSignUp_company12(){

        date_default_timezone_set("Asia/Karachi");
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $userPhoneNumber = $data['cell_no_1'];
        if(!isset($data['company_branch_id'])){
            $data['company_branch_id'] = 1;
        }
        $company_branch_id =  $data['company_branch_id'] ;
        $checkUserName = Client::model()->findByAttributes(array('userName'=>$userPhoneNumber, 'company_branch_id'=>$company_branch_id));
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
        }else{

            $client = new Client();
            $client->user_id = '2';
            $client->zone_id= '482';
            $client->network_id= 1;
            $client->fullname= $userPhoneNumber;
            $client->userName= $userPhoneNumber;
            $client->password= $userPhoneNumber;
            $client->company_branch_id= $data['company_branch_id'];
            $client->father_or_husband_name = ' ';
            $client->date_of_birth = '2018-02-01';
            $client->email = ' ';
            $client->cnic = " ";
            $client->cell_no_1 = $data['cell_no_1'];
            $client->cell_no_2 = '12334543';
            $client->residence_phone_no = '1234567';
            $client->city= 'no';
            $client->area= 'no';
            $client->address = '';
            $client->is_active = 0;
            $client->is_deleted= 0;
            $client->created_by = '2';
            $client->updated_by = '2';
            $client->login_form = '2';
            $client->view_by_admin = '1';
            if($client->save()) {
                $clientID =$client->client_id ;
                $IDArray = array();
                $IDArray['client_id'] = $clientID;
                $response = array(
                    'code' => 200,
                    'company_branch_id' => 0,
                    'success' => true,
                    'alreadyExists' => false,
                    'message' => " ",
                    'data' => $IDArray,
                );

                $companyObject  =  utill::get_companyTitle($data['company_branch_id']);
                $companyMask = $companyObject['sms_mask'];
                $companyTitle = $companyObject['company_title'];
                $companyObject = Company::model()->findByPk(intval($data['company_branch_id']));
                $phoneNo = $companyObject['phone_number'];

                $Companymessage = "A new Customer  has been registered ".$companyTitle;

                smsLog::saveSms($clientID ,$company_branch_id ,$phoneNo ,$userPhoneNumber ,$Companymessage);
                $this->sendSMS($phoneNo , $Companymessage , $companyMask , $company_branch_id , 1);

            }else{

                $IDArray = array();
                $response = array(
                    'code' => 200,
                    'company_branch_id' => 0,
                    'success' => fase,
                    'alreadyExists' => false,
                    'message' => " ",
                    'data' => $IDArray,
                );
            }
        }

        $this->sendResponse($response);

    }

    public function actionresendCode(){

        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        if(!isset($data['company_branch_id'])){
            $data['company_branch_id'] = 1;
        }
        $company_branch_id =  $data['company_branch_id'];
        $companyObject  =  utill::get_companyTitle($data['company_branch_id']);
        $companyMask = $companyObject['sms_mask'];
        $companyTitle = $companyObject['company_title'];

        $client_id = $data['client_id'];
        $clientObject = Client::model()->findByPk(intval($client_id));

        $cellNO =$clientObject['cell_no_1'];

        $authentication = Authentication::model()->findByAttributes(array('client_id'=>$client_id));
        $code =  $authentication['code'];
        $IDArray = array();
        $IDArray['client_id'] = '' ;

        $response = array(
            'code' => 200,
            'company_branch_id'=>0,
            'success' =>true,

            'message'=>"We've resent and SMS on your number. Please enter the verification code below to complete the sign-up process.
                                       Please allow up to a minute for your SMS to arrive.",
            'data' =>$IDArray,
        );
        $message = "Your verification code for ".$companyTitle." is ".$code;

        $fullname = $clientObject['fullname'];
        $network_id = $clientObject['network_id'];

        smsLog::saveSms($client_id ,$company_branch_id ,$cellNO ,$fullname ,$message);
        $this->sendSMS($cellNO ,$message , $companyMask , $company_branch_id , $network_id);

        $this->sendResponse($response);

    }

    public function actioncustomerActivationByCodeAndPhoneNo(){

        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $IDArray = array();
        $IDArray['client_id'] = '' ;


        if(!isset($data['company_branch_id'])){
            $data['company_branch_id'] = 1;
        }
        $company_branch_id = $data['company_branch_id'] ;

        $cell_no_1 = $data['cell_no_1'];

        $code = $data['code'];
        $clientOBject = Client::model()->findByAttributes(array('cell_no_1'=>$cell_no_1 ,  'company_branch_id'=>$company_branch_id));

        if($clientOBject){
            $client_id = $clientOBject['client_id'];

            $client_Object = Client::model()->findByPk(intval($client_id));

            $fullNameGet = $client_Object['fullname'];

            $codeObject = Authentication::model()->findByAttributes(array('client_id'=>$client_id), array('limit'=>1 ,   'order' => 'authentication_id desc',));

            if($codeObject){
                $registerTIme = $codeObject['SetTime'];
                $currentTime = Time();
                if( ($currentTime-$registerTIme) < 5000 ){

                    $response = array(
                        'code' => 200,
                        'company_branch_id'=>0,
                        'success' =>false,
                        'message'=>"Your code is expired Kindly register again",
                        'data' =>$IDArray,
                    );



                }else{
                    $clientOBject->is_active = 1;
                    if($clientOBject->save()){
                        $response = array(
                            'code' => 200,
                            'company_branch_id'=>0,
                            'success' =>true,
                            'message'=>"You are registered successfully',",
                            'data' =>$IDArray,
                        );
                    }

                    $companyObject  =  utill::get_companyTitle($data['company_branch_id']);
                    $companyMask = $companyObject['sms_mask'];
                    $companyTitle = $companyObject['company_title'];
                    $companyObject = Company::model()->findByPk(intval($data['company_branch_id']));

                    $phoneNo = $companyObject['phone_number'];
                    $network_id = $clientOBject['network_id'];


                    $Companymessage = "A new Customer ".$fullNameGet." has been registered ".$companyTitle;


                    $fullname = $clientOBject['fullname'];

                    if($company_branch_id==10){
                        smsLog::saveSms($client_id ,$company_branch_id ,"+923415009999" ,$fullname ,$Companymessage);
                        $this->sendSMS($phoneNo , $Companymessage , $companyMask , $company_branch_id , $network_id);

                        smsLog::saveSms($client_id ,$company_branch_id ,"+923209999688" ,$fullname ,$Companymessage);
                        $this->sendSMS($phoneNo , $Companymessage , $companyMask , $company_branch_id , $network_id);
                    }else{
                        smsLog::saveSms($client_id ,$company_branch_id ,$phoneNo ,$fullname ,$Companymessage);
                        $this->sendSMS($phoneNo , $Companymessage , $companyMask , $company_branch_id , $network_id);
                    }


                }
            } else {

                $response = array(
                    'code' => 200,
                    'company_branch_id'=>0,
                    'success' =>true,
                    'message'=>"You are not registered',",
                    'data' =>$IDArray,
                );
            }




        }else{
            $response = array(
                'code' => 200,
                'company_branch_id'=>0,
                'success' =>false,

                'message'=>"Count,t find your Acount",
                'data' =>$IDArray,
            );
        }

        $this->sendResponse($response);

    }

    public function actioncreateSpecialOrder(){

        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        if(!isset($data['company_branch_id'])){
            $data['company_branch_id'] = 1;
        }

        $specialOrdre = new SpecialOrder();
        $specialOrdre->client_id = $data['client_id'];
        $specialOrdre->product_id = $data['product_id'];
        $specialOrdre->quantity = $data['quantity'];
        $specialOrdre->delivery_on = $data['start_date'];
        $specialOrdre->start_date = $data['start_date'];
        $specialOrdre->end_date = $data['end_date'];
        $specialOrdre->company_branch_id = $data['company_branch_id'];

        $specialOrdre->status_id = 3;
        $specialOrdre->preferred_time_id = $data['preferred_time_id'];
        if($specialOrdre->save()){


            $response = array(
                'code' => 200,
                'company_branch_id'=>0,
                'success' =>true,
                'message'=>'Order created successfully.',
                'data' =>[]
            );

        }else{

            $response = array(
                'code' => 404,
                'company_branch_id'=>0,
                'success' =>false,
                'title' => 'Add Client',
                'message'=>$specialOrdre->getErrors(),
                'data' =>[],
            );
        }
        $this->sendResponse($response);
    }

    // make Schedual

    public  function actionMakeSchedual(){

        date_default_timezone_set("Asia/Karachi");



        $today_date =date("Y-m-d");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $clientID = $data['clientID'];

        $productID = $data['productID'];
        $orderStartDate = $data['orderStartDate'];
        $dayObject = $data['dayObject'];
        if($orderStartDate>$today_date){

            $clientFrequency =  ClientProductFrequency::model()->findByAttributes(array('client_id'=>$clientID , 'product_id'=>$productID));

            $clientFFID = (($clientFrequency['client_product_frequency']));

            ClientProductFrequencyQuantity::model()->deleteAllByAttributes(array('client_product_frequency_id'=>$clientFFID));

            if(isset($clientFrequency)){
                // $clientFrequency->delete();
                $client_product_frequency = $clientFrequency['client_product_frequency'];
                $clientFrequency->orderStartDate =$orderStartDate ;
                $clientFrequency->save();

            }else{
                $ClientProductFrequency = new ClientProductFrequency();
                $ClientProductFrequency->client_id = $clientID ;
                $ClientProductFrequency->product_id = $productID ;
                $ClientProductFrequency->quantity = '0' ;
                $ClientProductFrequency->total_rate = '0' ;
                $ClientProductFrequency->frequency_id = '1' ;
                $ClientProductFrequency->orderStartDate =$orderStartDate ;
                $ClientProductFrequency->save();
                $client_product_frequency = $ClientProductFrequency['client_product_frequency'];
            }


            foreach($dayObject as $value){
                if($value['slectDayForProducy']){
                    $daySave =new ClientProductFrequencyQuantity();
                    $daySave->client_product_frequency_id = $client_product_frequency ;
                    $daySave->frequency_id= $value['frequency_id'] ;
                    $daySave->quantity= $value['quantity'] ;
                    $daySave->preferred_time_id = $value['preferred_time_id'] ;
                    $daySave->save();
                }
            }



            $response = array(
                'code' => 200,
                'company_branch_id'=>0,
                'success' =>true,
                'title' => 'Make Schedual',
                'message'=>'Your weekly plan scheduled successfully.',
                'data' =>[],
            );

            $clientObject = Client::model()->findByPk(intval($clientID));
            $company_branch_id =$clientObject['company_branch_id'];
            $phoneNo =$clientObject['cell_no_1'];
            $fullname =$clientObject['fullname'];
            $network_id = $clientObject['network_id'];

            $companyObject = Company::model()->findByPk(intval($company_branch_id));
            $companyMask =    $companyObject['sms_mask'];
            $phoneNo =    $companyObject['phone_number'];
            $Companymessage = $fullname ." have changed schedule. ";
            if($company_branch_id ==10){
                smsLog::saveSms($clientID ,$company_branch_id ,"+923415009999" ,$fullname ,$Companymessage);
                $this->sendSMS_foradmin($phoneNo , $Companymessage , $companyMask ,$company_branch_id , $network_id);

                smsLog::saveSms($clientID ,$company_branch_id ,"+923209999688" ,$fullname ,$Companymessage);
                $this->sendSMS_foradmin($phoneNo , $Companymessage , $companyMask ,$company_branch_id , $network_id);

            }else{
                smsLog::saveSms($clientID ,$company_branch_id ,$phoneNo ,$fullname ,$Companymessage);
                $this->sendSMS_foradmin($phoneNo , $Companymessage , $companyMask ,$company_branch_id , $network_id);
            }

            $this->sendResponse($response);
        }else{

            $EffectiveDateSchedule =EffectiveDateSchedule::model()->findByAttributes(
                array('client_id'=>$clientID , 'product_id'=>$productID)
            );
            $effective_date_schedule_id = $EffectiveDateSchedule['effective_date_schedule_id'];

            EffectiveDateScheduleFrequency::model()->deleteAllByAttributes(
                array('effective_date_schedule_id'=>$effective_date_schedule_id)
            );
            if($EffectiveDateSchedule){
                $EffectiveDateSchedule->delete();
            }


            $effective_date_schedual =New EffectiveDateSchedule();
            $effective_date_schedual->client_id = $clientID;
            $effective_date_schedual->product_id =$productID ;

            $effective_date_schedual->date=$orderStartDate ;

            if($effective_date_schedual->save()){

                $effective_date_schedule_id = $effective_date_schedual->effective_date_schedule_id;


                foreach($dayObject as $value){


                    if($value['slectDayForProducy']){

                        $effective_date_schedule_frequency = new EffectiveDateScheduleFrequency();
                        $effective_date_schedule_frequency->effective_date_schedule_id =$effective_date_schedule_id;
                        $effective_date_schedule_frequency->frequency_id =$value['frequency_id'] ;
                        $effective_date_schedule_frequency->quantity =$value['quantity'];
                        if($effective_date_schedule_frequency->save()){

                        }else{

                        }
                    }
                }


            }

            $response = array(
                'code' => 200,
                'company_branch_id'=>0,
                'success' =>true,
                'title' => 'Make Schedual',
                'message'=>'Your weekly plan scheduled successfully.',
                'data' =>[],
            );
            $this->sendResponse($response);
        }


    }

    /**
     * @return array
     */
    public function actiondeActiveCustomerAccount()
    {
        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $customer = Client::model()->findByPk(intval($data['client_id']));
        if($customer){
            $customer->is_active = 0;
            if($customer->save()){
                $response = array(
                    'code' => 200,
                    'company_branch_id'=>0,
                    'success' =>true,
                    'title' => 'DeActive Account',
                    'message'=>'deactive Account successfully',
                    'data' =>[],
                );
            }
        }else{
            $response = array(
                'code' => 404,
                'company_branch_id'=>0,
                'success' =>false,
                'title' => 'Deactive Account',
                'message'=>'deactive Account fail',
                'data' =>[],
            );
        }
        $this->sendResponse($response);
    }

    public function actionActiveAccount()
    {
        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $customer = Client::model()->findByPk(intval($data['customerId']));
        if($customer){
            $customer->is_active = 1;
            if($customer->save()){

                $response = array(
                    'code' => 200,
                    'company_branch_id'=>0,
                    'success' =>true,
                    'title' => 'Active Account',
                    'message'=>'Active Account successfully',
                    'data' =>[],
                );
            }
        }else{

            $response = array(
                'code' => 200,
                'company_branch_id'=>0,
                'success' =>true,
                'title' => 'Active Account',
                'message'=>'Active Account successfully',
                'data' =>[],
            );
        }

        $this->sendResponse($response);
    }



    public function actionViewSpecialorderHistry(){

        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $clientID = $data['customerId'];
        $specialOrderList = SpecialOrder::model()->findAllByAttributes(array('client_id'=>$clientID));
        $resultList = array();
        foreach($specialOrderList as $value){
            $resultList[] = $value->attributes;
        }

        if(count($specialOrderList) == '0'){
            $response = array(
                'code' => 202,
                'company_branch_id'=>0,
                'success' =>true,
                'title' => 'Special List',
                'message'=>'There is no special order ',
                'data' =>$resultList,
            );
        }else{

            $response = array(
                'code' => 202,
                'company_branch_id'=>0,
                'success' =>true,
                'title' => 'Special List',
                'message'=>'Spcial Order List',
                'data' =>$resultList,
            );

        }

        $this->sendResponse($response);
    }



    public function actiongetZoneList(){

        date_default_timezone_set("Asia/Karachi");


        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        if(!isset($data['company_branch_id'])){
            $data['company_branch_id'] = 1;
        }
        $company_branch_id = $data['company_branch_id'] ;

        $query = "select * from zone as z
                    where z.company_branch_id = '$company_branch_id'
                    order by z.name ASC ";

        $zoneList = Yii::app()->db->createCommand($query)->queryAll();


        // $zone = Zone::model()->findAll(array('order'=> 'name') , 'company_branch_id'=$company_branch_id);


        $response = array(
            'code' => 202,
            'company_branch_id'=>0,
            'success' =>true,

            'message'=>'zone list',
            'data' =>$zoneList,
        );

        $this->sendResponse($response);
    }

    public function actiongetAllPlan(){
        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $clientID  = $data['client_id'];
        $todaydate = date("Y-m-d");

        $query = "Select p.product_id ,p.name as product_name ,IFNULL(cpp.price ,p.price) as price , p.unit 
              ,'regular' as order_type 
               ,IFNULL(hro.start_date , cpf.orderStartDate) as start_date ,IFNULL(hro.end_date , '') as end_date
              ,IFNULL(hro.client_id , 0) as is_halt from client_product_frequency as cpf
            LEFT JOIN product as p ON p.product_id = cpf.product_id
            LEFT JOIN client_product_price as cpp ON cpp.product_id = p.product_id and cpp.client_id = $clientID
             LEFT JOIN halt_regular_orders as hro ON hro.product_id = p.product_id AND hro.client_id = $clientID AND '$todaydate' between hro.start_date and hro.end_date
            where cpf.client_id = $clientID
            union
            select p.product_id ,p.name as product_name ,IFNULL(cpp.price ,p.price) as price , p.unit  , 'special' as order_type ,so.start_date , so.end_date , '0' as is_halt from special_order as so 
            LEFT JOIN product as p ON p.product_id = so.product_id
            LEFT JOIN client_product_price as cpp ON cpp.product_id = p.product_id and cpp.client_id = $clientID
            where so.client_id = $clientID  AND '$todaydate' between so.start_date and so.end_date
             union
             select p.product_id ,p.name as product_name ,IFNULL(cpp.price ,p.price) as price , p.unit  , 'Interval' as order_type ,(so.start_interval_scheduler) as start_date , (so.start_interval_scheduler) as end_date , 
              
               IFNULL((select isi.is_halt from interval_scheduler as isi 
                        where isi.client_id = '$clientID' and isi.product_id =so.product_id and '$todaydate'  between isi.halt_start_date and isi.halt_end_date
                        limit 1 ) ,0) as is_halt
              
              from interval_scheduler as so 
            LEFT JOIN product as p ON p.product_id = so.product_id
            LEFT JOIN client_product_price as cpp ON cpp.product_id = p.product_id and cpp.client_id = $clientID
            where so.client_id = $clientID  ";


        $productResuslt = Yii::app()->db->createCommand($query)->queryAll();
        $response = array(
            'code' => 202,
            'company_branch_id'=>0,
            'success' =>true,
            'message'=>'All Plans',
            'data' =>($productResuslt),
        );
        $this->sendResponse($response);
    }

    public function actiongetAllProduct(){

        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $client_id= $data['client_id'];
        $todaydate = date("Y-m-d");

        if(!isset($data['company_branch_id'])){
            $data['company_branch_id'] = 1;
        }
        $company_branch_id = $data['company_branch_id'] ;

        $query = "Select p.product_id ,p.name as product_name ,p.order_type, IFNULL(cpp.price , p.price) as price , p.unit
              ,  IFNULL(cpf.client_id, 0) as is_selected , iFNULL(hro.client_id , 0) as is_halt from product as p
                LEFT JOIN halt_regular_orders as hro ON hro.product_id = p.product_id AND hro.client_id = '$client_id' AND '$todaydate' between hro.start_date and hro.end_date
                left join client_product_price as cpp ON cpp.client_id ='$client_id' AND cpp.product_id = p.product_id
                left join client_product_frequency as cpf ON p.product_id = cpf.product_id and cpf.client_id ='$client_id'
                where  p.company_branch_id = $company_branch_id and p.bottle ='0'
                 group by p.product_id ";
        $productResuslt = Yii::app()->db->createCommand($query)->queryAll();

        //  var_dump($productResuslt);

        $productObject = array();
        foreach($productResuslt as $productValue){
            $product_id = $productValue['product_id'];
            $oneProductOject = array();
            $oneProductOject['product_id'] = $productValue['product_id'];
            $oneProductOject['product_name'] = $productValue['product_name'];
            $oneProductOject['order_type'] = $productValue['order_type'];
            $oneProductOject['price'] = $productValue['price'];
            $oneProductOject['unit'] = $productValue['unit'];
            $oneProductOject['is_selected'] = $productValue['is_selected'];
            $oneProductOject['is_halt'] = $productValue['is_halt'];
            $oneProductOject['regular_order_type'] = APIData::api_getCustomerRegularOrderType($client_id , $product_id);
            $productObject[] = $oneProductOject ;
        }


        $response = array(
            'code' => 202,
            'company_branch_id'=>0,
            'success' =>true,
            'message'=>'All products',
            'data' =>($productObject),
        );

        $this->sendResponse($response);
    }
    public function actiongetAllProduct_withPlans(){

        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $client_id= $data['client_id'];
        $todaydate = date("Y-m-d");

        if(!isset($data['company_branch_id'])){
            $data['company_branch_id'] = 1;
        }

        $company_branch_id = $data['company_branch_id'] ;


        $spacial_order_query = "select so.client_id ,p.name as product_name ,so.quantity , p.order_type , so.product_id ,ifnull(cpp.price , p.price) as price
                , so.start_date ,so.end_date , '3' as regular_order_type ,so.start_date ,so.end_date   from special_order as so
                left join product as p ON p.product_id = so.product_id
                left join client_product_price as cpp ON cpp.client_id = '$client_id' and cpp.product_id = p.product_id
                where so.client_id = '$client_id' and so.end_date >= '$todaydate' " ;
        $spacial_order_result = Yii::app()->db->createCommand($spacial_order_query)->queryAll();


//        $query = "Select p.product_id ,p.name as product_name ,p.order_type, concat(IFNULL(cpp.price , p.price), ' / ', p.unit) as price , p.unit
        $query = "Select p.product_id ,p.name as product_name ,p.order_type, IFNULL(cpp.price , p.price) as price , p.unit
              ,  IFNULL(cpf.client_id, 0) as is_selected , iFNULL(hro.client_id , 0) as is_halt from product as p
                 LEFT JOIN halt_regular_orders as hro ON hro.product_id = p.product_id AND hro.client_id = '$client_id' AND '$todaydate' between hro.start_date and hro.end_date
                 left join client_product_price as cpp ON cpp.client_id ='$client_id' AND cpp.product_id = p.product_id
                 left join client_product_frequency as cpf ON p.product_id = cpf.product_id and cpf.client_id ='$client_id'
                 where  p.company_branch_id = $company_branch_id and p.bottle ='0' and p.is_active=1
                 group by p.product_id ";
        $productResuslt = Yii::app()->db->createCommand($query)->queryAll();


        $productObject = array();
        foreach($productResuslt as $productValue){
            $product_id = $productValue['product_id'];
            $oneProductOject = array();
            $oneProductOject['product_id'] = $productValue['product_id'];
            $oneProductOject['product_name'] = $productValue['product_name'];
            $oneProductOject['order_type'] = $productValue['order_type'];
            $oneProductOject['price'] = $productValue['price'];
            $oneProductOject['unit'] = $productValue['unit'];
            $oneProductOject['is_selected'] = $productValue['is_selected'];
            $oneProductOject['is_halt'] = APIData::api_getCustomerHalt_OR_Resume($client_id , $product_id ,$todaydate);
            $oneProductOject['regular_order_type'] = APIData::api_getCustomerRegularOrderType($client_id , $product_id);
            $productObject[] = $oneProductOject ;

        }

        foreach($spacial_order_result as $spacial_value){

            $productObject[] = $spacial_value ;
        }
        $response = array(
            'code' => 202,
            'company_branch_id'=>0,
            'success' =>true,
            'message'=>'All products',
            'data' =>($productObject),
        );

        $this->sendResponse($response);
    }

    public function actiongetComplainType(){

        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        if(!isset($data['company_branch_id'])){
            $data['company_branch_id'] = 1;
        }

        $company_branch_id = $data['company_branch_id'] ;

        $query = "select * from complain_type as c
             where c.company_branch_id ='$company_branch_id'";
        $complainList = Yii::app()->db->createCommand($query)->queryAll();

        $response = array(
            'code' => 202,
            'company_branch_id'=>0,
            'success' =>true,
            'message'=>'Complain types',
            'data' =>($complainList),
        );
        $this->sendResponse($response);

    }

    public function actiongetCustomerProfile(){

        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $clientID =  $data['client_id'];
        $query = "SELECT c.fullname , c.email , c.cnic , c.address ,c.zone_id , z.name as zone_name ,c.cell_no_1 from client as c
                      LEFT JOIN zone as z ON c.zone_id =  z.zone_id
                      where c.client_id = '$clientID' ";
        $customerProfile = Yii::app()->db->createCommand($query)->queryAll();
        $response = array(
            'code' => 202,
            'company_branch_id'=>0,
            'success' =>true,
            'message'=>'Customer Profile',
            'data' =>$customerProfile[0],
        );
        $this->sendResponse($response);
    }

    /*Rider*/


    public function actionriderLogin(){


        date_default_timezone_set("Asia/Karachi");


        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);


        if(!isset($data['company_branch_id'])){
            $data['company_branch_id'] = 1;
        }
        $company_branch_id = $data['company_branch_id'];


        $userName = $data['userName'];
        $password = $data['password'];
        $query = "Select * from  rider as c
        where c.userName ='$userName'  and c.company_branch_id = $company_branch_id ";

        $user = Yii::app()->db->createCommand($query)->queryAll();
        if(count($user)===0) {
            $data = array();
            $data['rider_id']= '';
            $response = array(
                'code' => 401,
                'company_branch_id'=>0,
                'success' => false,
                'message'=>'Invalid username',
                'data' => $data,
            );
        }else{
            if($user[0]['password'] == $password){
                $data = array();
                $data['rider_id']= $user[0]['rider_id'];
                $data['can_add_payment']= $user[0]['can_add_payment'];
                $response = array(
                    'code' => 200,
                    'company_branch_id'=>0,
                    'success' =>true,
                    'message'=>'Login successfully.',
                    'data' =>$data,
                );
            }else{
                $response = array(
                    'code' => 401,
                    'company_branch_id'=>0,
                    'success' => false,

                    'message'=>'Invalid password ',
                    'data' => []
                );
            }
        }

        $this->sendResponse($response);
    }



    public function actiongetChangeScheduleCustomersOfRider_sample(){

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

        $todaydate =  date("Y-m-d");

        $previousdate =  date('Y-m-d', strtotime(' -1 day'));

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

            $clientQuery = "select  cs.date as change_schedual , c.client_type ,  c.fullname ,c.cell_no_1,c.address , c.client_id ,c.zone_id from client as c
                       left join change_scheduler_record as cs ON c.client_id =cs.client_id and cs.date in('$todaydate' ,'$previousdate') 
                       where c.zone_id = '$zone_id' and c.client_type =2
                      order by cs.date DESC";
        }else{
            $clientQuery = "Select cs.date as change_schedual , c.client_type ,  c.fullname ,c.cell_no_1,c.address , c.client_id , rz.zone_id from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id
                           left join change_scheduler_record as cs ON c.client_id =cs.client_id and cs.date in('$todaydate' ,'$previousdate') 
                           where rz.rider_id = $riderID  AND c.is_active = 1 and c.client_type =2 
                           group by c.client_id
                           order by cs.date DESC";


        }


        $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();

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

        foreach($clientResult as $value){

            $clientID = $value['client_id'];
            $data=array();
            $data['change_schedual_date'] = $value['change_schedual'];
            if($value['change_schedual']){

                $data['new_schedual'] =1;

            }else{
                $data['new_schedual'] =0;
            }

            $data['client_type'] = $value['client_type'];
            $data['fullname'] = $value['fullname'];
            $data['cell_no_1'] = $value['cell_no_1'];
            $data['address'] = $value['address'];
            $data['client_id'] = $value['client_id'];
            $data['client_id'] = $value['client_id'];

            if(isset($result_payment_client_list[$clientID])){
                $data['previous_month_payment'] = true;
            }else{
                $data['previous_month_payment'] = false;
            }

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
                Select (IFNULL(sum(cpfq.quantity) ,0) ) as quantity ,p.name as product_name ,p.product_id  , 0 as deliveryTime  from client_product_frequency as cpf
                    left join  halt_regular_orders as hro ON hro.client_id = cpf.client_id and '$todaydate' between hro.start_date AND hro.end_date and hro.product_id = cpf.product_id
                    LEFT JOIN client_product_frequency_quantity as cpfq  ON cpfq.client_product_frequency_id = cpf.client_product_frequency
                    AND cpfq.frequency_id = '$todayfrequencyID' and  hro.halt_regular_orders_id is  NULL
                    left join product as p ON p.product_id = cpf.product_id
                    where     cpf.client_id = '$clientID'  
                    group by p.product_id
                    union
                  select IFNUll(sum(so.quantity) ,0) as quantity ,p.name as product_name ,p.product_id , 0 as deliveryTime from special_order as so
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
                        $quantity = 0;
                    }
                    $oneProduct['product_name'] = $product['name'];
                    $oneProduct['unit'] = $product['unit'];
                    $oneProduct['product_id'] = $product['product_id'];
                    $oneProduct['deliveryTime'] = 0;
                    //   $oneProduct['clientID'] = $clientID;
                    $product_id = $product['product_id'];
                    // $intervalQuantity = utill::getOneCustomerTodayIntervalSceduler($clientID ,$product_id );

                    $intervalQuantity =  future_selet_date::getOneCustomerTodayIntervalSceduler_with_date_future_date($clientID , $product['product_id'] ,$todaydate);

                    $oneProduct['quantity'] = $quantity + $intervalQuantity;
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
    public function actiongetChangeScheduleCustomersOfRider(){

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

        $todaydate =  date("Y-m-d");

        $previousdate =  date('Y-m-d', strtotime(' -1 day'));

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

            $clientQuery = "select  cs.date as change_schedual , c.client_type ,  c.fullname ,c.cell_no_1,c.address , c.client_id ,c.zone_id from client as c
                       left join change_scheduler_record as cs ON c.client_id =cs.client_id and cs.date in('$todaydate' ,'$previousdate') 
                       where c.zone_id = '$zone_id'  and c.client_type =1
                      order by cs.date DESC";

        }else{
            $clientQuery = "Select cs.date as change_schedual , c.client_type ,  c.fullname ,c.cell_no_1,c.address , c.client_id , rz.zone_id from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id
                           left join change_scheduler_record as cs ON c.client_id =cs.client_id and cs.date in('$todaydate' ,'$previousdate') 
                           where rz.rider_id = $riderID  AND c.is_active = 1 and c.client_type =1 
                           group by c.client_id
                           order by cs.date DESC ";
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


        foreach($clientResult as $value){

            $clientID = $value['client_id'];
            $data=array();
            $data['change_schedual_date'] = $value['change_schedual'];
            if($value['change_schedual']){

                $data['new_schedual'] =1;

            }else{
                $data['new_schedual'] =0;
            }

            $data['client_type'] = $value['client_type'];
            $data['fullname'] = $value['fullname'];
            $data['cell_no_1'] = $value['cell_no_1'];
            $data['address'] = $value['address'];
            $data['client_id'] = $value['client_id'];
            $data['client_id'] = $value['client_id'];


            /* new_schedual Tag */

            if(isset($change_scheduler_client_list[$clientID])){
                // $data['new_schedual'] = true;
            }else{
                //  $data['new_schedual'] = false;
            }

            /* new_schedual Tag */

            if(isset($result_payment_client_list[$clientID])){
                $data['previous_month_payment'] = true;
            }else{
                $data['previous_month_payment'] = false;
            }

            $queryCheckTodayDelivery = "select * from delivery as d
                    where d.client_id = '$clientID' and d.date = '$todaydate'";

            $checkTodayDeliveryResult = Yii::app()->db->createCommand($queryCheckTodayDelivery)->queryAll();

            $checkDelievry = true;

            if(false){
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
                Select (IFNULL(sum(cpfq.quantity) ,0) ) as quantity ,p.name as product_name ,p.product_id  , 0 as deliveryTime  from client_product_frequency as cpf
                    left join  halt_regular_orders as hro ON hro.client_id = cpf.client_id and '$todaydate' between hro.start_date AND hro.end_date and hro.product_id = cpf.product_id
                    LEFT JOIN client_product_frequency_quantity as cpfq  ON cpfq.client_product_frequency_id = cpf.client_product_frequency
                    AND cpfq.frequency_id = '$todayfrequencyID' and  hro.halt_regular_orders_id is  NULL
                    left join product as p ON p.product_id = cpf.product_id
                    where     cpf.client_id = '$clientID'  
                    group by p.product_id
                    union
                  select IFNUll(sum(so.quantity) ,0) as quantity ,p.name as product_name ,p.product_id , 0 as deliveryTime from special_order as so
                     left join product as p ON p.product_id = so.product_id
                     where  so.client_id = '$clientID' AND '$todaydate' between 
                     so.start_date AND so.end_date
                     group by p.product_id
                     
                     ) as abcd
                     group by product_id " ;

            if(!$checkDelievry){
                $productQuery = " select sum(dd.quantity) as quantity ,p.name as product_name ,p.unit
              , p.product_id ,d.time as deliveryTime  from delivery as d
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
                        $quantity = 0;
                    }
                    $oneProduct['product_name'] = $product['name'];
                    $oneProduct['unit'] = $product['unit'];
                    $oneProduct['product_id'] = $product['product_id'];
                    $oneProduct['deliveryTime'] = 0;
                    //   $oneProduct['clientID'] = $clientID;
                    $product_id = $product['product_id'];
                    // $intervalQuantity = utill::getOneCustomerTodayIntervalSceduler($clientID ,$product_id );

                    $intervalQuantity =  future_selet_date::getOneCustomerTodayIntervalSceduler_with_date_future_date($clientID , $product['product_id'] ,$todaydate);
                    $totalSpecialToday_quantity  =    todayDeliveryQuantityCountForApi::getTodaySpecialOrder($clientID ,$product['product_id'] ,$todaydate);
                    $oneProduct['quantity'] = $quantity + $intervalQuantity + $totalSpecialToday_quantity;

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
    public function actiongetCustomersOfRider(){
        date_default_timezone_set("Asia/Karachi");
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $riderID = $data['rider_id'];


        $riderObject =Rider::model()->findByPk(intval($riderID));

        $show_customers_in_app = $riderObject['show_customers_in_app'];

        if(isset($data['company_branch_id'])){
            $company_branch_id = ($data['company_branch_id']);
        }else{
            $data['company_branch_id'] = 1;
            $company_branch_id = 1;
        }

        setEffectiveDateSchedule::checkEffectiveDateSchedule($company_branch_id);
        if(isset($data['zone_id'])){
            $zone_id = $data['zone_id'] ;
            $select_zone = true ;
        }else{
            $select_zone = false;
        }
        $todaydate =  date("Y-m-d");

        $halt_client_list = portal_radier_daily_delivery_class::get_today_client_halt($todaydate,$company_branch_id,$riderID);



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

            $clientQuery = "select cg.category_name ,  c.latitude ,c.longitude , c.client_type ,
                    c.fullname ,c.cell_no_1,c.address , 
                    c.client_id ,c.zone_id from client as c
                    LEFT join customer_category AS cg ON cg.customer_category_id = c.customer_category_id
                    where c.zone_id = '$zone_id' AND c.is_active = 1 
                  order by c.rout_order ASC ,c.fullname ASC";
        }else{

            $clientQuery = "Select  cg.category_name , c.latitude ,c.longitude , c.client_type ,  c.fullname ,c.cell_no_1,c.address , c.client_id , rz.zone_id from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id
                           LEFT join customer_category AS cg ON cg.customer_category_id = c.customer_category_id
                           where rz.rider_id = '$riderID'  AND c.is_active = 1 
                            order by c.rout_order ASC ,c.fullname ASC";
        }



        $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();

        $cientID = array();
        $cientID[] = 0;
        foreach($clientResult as $value){
            $cientID[] =  $value['client_id'];
        }

        $lientID_list = implode(',',$cientID);



        $intervalDefalutData_clientList = intervalDefalutData::intervalDefault($lientID_list);


        $finalResult = array();
        $todaydate_month =  date("m");
        $perivous_month = $todaydate_month - 1;
        $company_id =  $data['company_branch_id'] ;
        $year =date("Y");
        if($company_branch_id ==14){
            $query_payment = "select pm.client_id  from payment_master as pm
             where pm.company_branch_id ='9999' and month(pm.bill_month_date) = '$perivous_month' and year(pm.bill_month_date) ='$year'" ;

        }else{
            $query_payment = "select pm.client_id  from payment_master as pm
             where pm.company_branch_id ='$company_id' and month(pm.bill_month_date) = '$perivous_month' and year(pm.bill_month_date) ='$year'" ;
        }

        $result_payment = Yii::app()->db->createCommand($query_payment)->queryAll();
        $result_payment_client_list = array();
        foreach ($result_payment as $list){
            $client_id = $list['client_id'];
            $result_payment_client_list[$client_id] = true ;
        }

        foreach($clientResult as $value){
            $clientID = $value['client_id'];
            $data=array();
            $data['client_type'] = $value['client_type'];
            $data['fullname'] = $value['fullname'];
            $data['cell_no_1'] = $value['cell_no_1'];
            $data['address'] = $value['address'];
            $data['client_id'] = $value['client_id'];
            $data['client_id'] = $value['client_id'];
            $data['latitude'] = $value['latitude'];
            $data['longitude'] = $value['longitude'];
            $data['category_name'] = $value['category_name'];
            if(isset($result_payment_client_list[$clientID])){
                $data['previous_month_payment'] = true;
            }else{
                $data['previous_month_payment'] = false;
            }
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


            $productQuery = " select   sum(quantity) as quantity ,product_name ,product_id ,deliveryTime from (
                Select 'a' AS test , (IFNULL(sum(cpfq.quantity) ,0) ) as quantity ,p.name as product_name ,p.product_id  , 0 as deliveryTime  from client_product_frequency as cpf
                    left join  halt_regular_orders as hro ON hro.client_id = cpf.client_id and '$todaydate' between hro.start_date AND hro.end_date and hro.product_id = cpf.product_id
                    LEFT JOIN client_product_frequency_quantity as cpfq  ON cpfq.client_product_frequency_id = cpf.client_product_frequency
                    AND cpfq.frequency_id = '$todayfrequencyID' and  hro.halt_regular_orders_id is  NULL
                    left join product as p ON p.product_id = cpf.product_id
                    where     cpf.client_id = '$clientID'  
                    group by p.product_id
                    union
                  select 'b' AS test , IFNUll(sum(so.quantity) ,0) as quantity ,p.name as product_name
                   ,p.product_id , 0 as deliveryTime from special_order as so
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
                    if($intervalQuantity ==0){
                        $client_product =$clientID.$product['product_id'];
                        if(isset($intervalDefalutData_clientList[$client_product])){
                            $intervalQuantity = $intervalDefalutData_clientList[$client_product];
                        }
                    }
                    //  $totalSpecialToday_quantity  =    todayDeliveryQuantityCountForApi::getTodaySpecialOrder($clientID ,$product['product_id'] ,$todaydate);
                    /*  echo $quantity;
                       die();*/
                    $oneProduct['quantity'] = $quantity + $intervalQuantity ;
                    $newProductLIst[] = $oneProduct ;
                }
                $data['productList'] = $newProductLIst;
            }else{
                $data['productList'] = $productLIst;
            }

            if(!isset($halt_client_list[$clientID])){
                if($show_customers_in_app ==1){
                    $finalResult[] = $data ;
                }else{
                    $productList = $data['productList'] ;
                    $total_quantity = 0;
                    foreach ($productList as $value){
                        $total_quantity = $total_quantity +  $value['quantity'];
                    }
                    if($total_quantity >0){
                        $finalResult[] = $data ;
                    }
                }
            }

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

    public function actionsaveDelivery(){

        date_default_timezone_set("Asia/Karachi");

        date_default_timezone_set("Asia/Karachi");
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $clientID = $data['client_id'];

        if(!isset($data['company_branch_id'])){
            $data['company_branch_id'] = 1;
        }
        $company_branch_id = $data['company_branch_id'];
        $productObject = $data['data'];
        $totalAmount = 0 ;
        foreach($productObject as $value){

            if($value['quantity']>0){
                $totalAmount = $totalAmount + ($value['price'] * $value['quantity']);
            }
        }

        $totalBottle = 0;
        if($company_branch_id == 4) {
            $broken = $data['broken'];
            $perfect = $data['perfect'];
            $totalBottle = intval($broken) + intval($perfect);
        }
        if(isset($data['billAssign'])){
            $bill_boolean =  $data['billAssign'];
        }
        $companyObject  =  utill::get_companyTitle($company_branch_id);
        $deliverySmsYesNo =  $companyObject['rider_delivery_sms_yes_no'];
        $qualtiyReport_sms =  $companyObject['qualtiyReport_sms'];

        if($deliverySmsYesNo == 1){
            rider_delivery_sms::riderAlert($clientID , $company_branch_id);
        }
        if(isset($data['selectDate'])){
            mangeDelivery::saveDeliveryForPortal($data ,$totalAmount , $companyObject);
            $response = array(
                'code' => 200,
                'company_branch_id'=>0,
                'delivery_time'=>date("H:i"),
                'success' => true,
                'message'=>'Save Delivery',
                'data' => []
            );
            $this->sendResponse($response);
            die();
        }

        $checkDeliveryOption = false;
        $responceSaveDelivery = 0;
        if($totalBottle > 0 ){
            mangeDelivery::saveBottle($data);
            $checkDeliveryOption = true;
        }
        if($totalAmount > 0){
            $responceSaveDelivery = mangeDelivery::saveDelivery($data ,$totalAmount , $companyObject);
            $checkDeliveryOption = true;
        }
        if(isset($data['billAssign'])){
            if($data['billAssign']){
                mangeDelivery::billAssign($data , $companyObject);
                $checkDeliveryOption = true;
            }
        }
        if($checkDeliveryOption){
            $response = array(
                'code' => 200,
                'company_branch_id'=>0,
                'delivery_time'=>date("H:i"),
                'success' => true,
                'message'=>'Save Delivery',
                'data' => []
            );
        }
        else{
            $response = array(
                'code' => 200,
                'company_branch_id'=>0,
                'delivery_time'=>date("H:i"),
                'success' => false,
                'message'=>'Not Save Delivery',
                'data' => []
            );
        }
        if($responceSaveDelivery == 1){
            $response = array(
                'code' => 200,
                'company_branch_id'=>0,
                'delivery_time'=>date("H:i"),
                'success' => false,
                'message'=>'Order is Already deliverd',
                'data' => []
            );
        }
        $this->sendResponse($response);
    }

    public function actiongetWeeklySchedule(){

        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post , true);
        $clientID = $data['client_id'];
        $productID = $data['product_id'];
        $clientProductFrequency = ClientProductFrequency::model()->findByAttributes(array('client_id'=>$clientID,'product_id'=>$productID));

        $clientProductFrequency = ($clientProductFrequency['client_product_frequency']);


        $query = "Select f.* , IFNULL(cpfq.quantity , 0) as quantity  , IFNULL(pt.preferred_time_id, 0) as PreferredTime ,  IFNULL(cpfq.isSelected, 0) as isSelected ,pt.preferred_time_name from frequency as f
                    left join client_product_frequency_quantity as cpfq ON cpfq.frequency_id = f.frequency_id AND cpfq.client_product_frequency_id ='$clientProductFrequency'
                    Left join preferred_time as pt ON pt.preferred_time_id = cpfq.preferred_time_id
                    order by f.frequency_id ASC ";

        $weeklyResult = Yii::app()->db->createCommand($query)->queryAll();


        $startOrderDate = ClientProductFrequency::model()->findByAttributes(array('client_id'=>$clientID , 'product_id'=>$productID));
        if($startOrderDate){
            $date =  ($startOrderDate['orderStartDate']);
        } else {
            $date = date("Y-m-d");
        }
        $response = array(
            'code' => 200,
            'company_branch_id'=>0,
            'success' => true,
            'clientId' => $clientID,
            'productId' => $productID,
            'orderStartDate' => $date,

            'message'=>'Weekly Schedule',
            'data' => $weeklyResult
        );
        $this->sendResponse($response);
    }

    public function actionupdateWeeklySchedual(){


        date_default_timezone_set("Asia/Karachi");

        $today_date =date("Y-m-d");


        $post = file_get_contents("php://input");
        $data = CJSON::decode($post , true);
        $clientID = ($data['client_id']);
        $productID = ($data['product_id']);

        $orderStartDate = $data['orderStartDate'];



        $dayObject = $data['data'];

        if($orderStartDate<=$today_date){

            $dayObject = $data['data'];
            $clientFrequency =  ClientProductFrequency::model()->findByAttributes(array('client_id'=>$clientID , 'product_id'=>$productID));
            $clientFFID = (($clientFrequency['client_product_frequency']));
            ClientProductFrequencyQuantity::model()->deleteAllByAttributes(array('client_product_frequency_id'=>$clientFFID));
            if(isset($clientFrequency)){
                // $clientFrequency->delete();
                $client_product_frequency = $clientFrequency['client_product_frequency'];
                $clientFrequency->orderStartDate =$orderStartDate ;
                $clientFrequency->save();
            }else{

                $ClientProductFrequency = new ClientProductFrequency();
                $ClientProductFrequency->client_id = $clientID ;
                $ClientProductFrequency->product_id = $productID ;
                $ClientProductFrequency->quantity = '0' ;
                $ClientProductFrequency->total_rate = '0' ;
                $ClientProductFrequency->frequency_id = '1' ;
                $ClientProductFrequency->orderStartDate =$orderStartDate ;
                $ClientProductFrequency->save();
                $client_product_frequency = $ClientProductFrequency['client_product_frequency'];
            }


            foreach($dayObject as $value){

                if($value['isSelected'] == 1){
                    $daySave =new ClientProductFrequencyQuantity();
                    $daySave->client_product_frequency_id = $client_product_frequency ;
                    $daySave->frequency_id= $value['frequency_id'] ;
                    $daySave->quantity= $value['quantity'] ;
                    $daySave->preferred_time_id = $value['PreferredTime'] ;
                    $daySave->save();
                }
            }

            $response = array(
                'code' => 200,
                'company_branch_id'=>0,
                'success' => true,
                'message'=>'Your plan saved successfully',
                'title' => ''
            );




            $clientObject = Client::model()->findByPk(intval($clientID));
            $company_branch_id =$clientObject['company_branch_id'];
            $phoneNo =$clientObject['cell_no_1'];
            $fullname =$clientObject['fullname'];
            $network_id = $clientObject['network_id'];
            $savechagescheduler =new ChangeSchedulerRecord();
            $savechagescheduler->client_id = $clientID;
            $savechagescheduler->company_id = $company_branch_id;
            $savechagescheduler->date = date("Y-m-d");
            $savechagescheduler->change_form= 2 ;
            $savechagescheduler->save();

            $companyObject = Company::model()->findByPk(intval($company_branch_id));
            $companyMask =    $companyObject['sms_mask'];
            $phoneNo =    $companyObject['phone_number'];
            $Companymessage = $fullname ." have changed schedule. ";
            smsLog::saveSms($clientID ,$company_branch_id ,$phoneNo ,$fullname ,$Companymessage);
            $this->sendSMS_foradmin($phoneNo , $Companymessage , $companyMask ,$company_branch_id , $network_id);
            $this->sendResponse($response);


        }else{



            $EffectiveDateSchedule =EffectiveDateSchedule::model()->findByAttributes(
                array('client_id'=>$clientID , 'product_id'=>$productID)
            );
            $effective_date_schedule_id = $EffectiveDateSchedule['effective_date_schedule_id'];

            EffectiveDateScheduleFrequency::model()->deleteAllByAttributes(
                array('effective_date_schedule_id'=>$effective_date_schedule_id)
            );



            $effective_date_schedual =New EffectiveDateSchedule();
            $effective_date_schedual->client_id = $clientID;
            $effective_date_schedual->product_id =$productID ;

            $effective_date_schedual->date=$orderStartDate ;

            if($effective_date_schedual->save()){

                $effective_date_schedule_id = $effective_date_schedual->effective_date_schedule_id;


                foreach($dayObject as $value){


                    if($value['isSelected']){

                        $effective_date_schedule_frequency = new EffectiveDateScheduleFrequency();
                        $effective_date_schedule_frequency->effective_date_schedule_id =$effective_date_schedule_id;
                        $effective_date_schedule_frequency->frequency_id =$value['frequency_id'] ;
                        $effective_date_schedule_frequency->quantity =$value['quantity'];
                        if($effective_date_schedule_frequency->save()){

                        }else{

                        }
                    }
                }


            }


            $response = array(
                'code' => 200,
                'company_branch_id'=>0,
                'success' => true,
                'message'=>'Your plan saved successfully',
                'title' => ''
            );

            $this->sendResponse($response);
        }
    }

    public function actioncustomerAuthentication(){

        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post , true);
        $clientID = $data['client_id'];
        $client_Object = Client::model()->findByPk(intval($clientID));
        $fullNameGet = $client_Object['fullname'];
        $network_id = $client_Object['network_id'];
        $responceData = array();
        $responceData['client_id'] = $clientID ;
        $code = $data['code'];
        if(!isset($data['company_branch_id'])){
            $data['company_branch_id'] = 1;
        }
        $company_branch_id = $data['company_branch_id'];
        $auth = Authentication::model()->findByAttributes(array('client_id'=>$clientID , 'code'=>$code ));
        if($auth){
            $client = Client::model()->findByPk(intval($clientID));
            if($company_branch_id ==1){
                $client->is_active = 0;
            }else{
                $client->is_active = 1;
            }

            if($client->save()){
                $response = array(
                    'code' => 200,
                    'company_branch_id'=>0,
                    'success' => true,
                    'message'=>'You are registered successfully',
                    'data' => $responceData
                );
                $companyObject  =  utill::get_companyTitle($data['company_branch_id']);
                $companyMask = $companyObject['sms_mask'];
                $companyTitle = $companyObject['company_title'];
                $companyObject = Company::model()->findByPk(intval($data['company_branch_id']));
                $phoneNo = $companyObject['phone_number'];

                $Companymessage = "A new Customer ".$fullNameGet." has been registered\n ".$companyTitle;
                $fullname = "Admin";
                $client_id = '1';
                smsLog::saveSms($client_id ,$company_branch_id ,$phoneNo ,$fullname ,$Companymessage);
                $this->sendSMS($phoneNo , $Companymessage , $companyMask ,$company_branch_id , $network_id);
            }else{
                $sendData = array();
                $sendData['client_id'] = '';
                $response = array(
                    'code' => 401,
                    'success' => false,
                    'message'=>'Code is invalid',
                    'data' => $sendData
                );
            }
        }else{
            $sendData = array();
            $sendData['client_id'] = '';
            $response = array(
                'code' => 402,
                'company_branch_id'=>0,
                'success' => false,

                'message'=>'Code is invalid',

                'data' => $sendData
            );
        }
        $this->sendResponse($response);
    }

    public function actionsaveWeeklySchedule(){
        date_default_timezone_set("Asia/Karachi");
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post , true);
        $clientID = $data['clientID'];
        $productID = $data['productID'];
        $orderStartDate = $data['orderStartDate'];
        $dayObject = $data['dayObject'];

        var_dump(json_encode($data));


    }

    public function actionpaymentMethod(){

        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post , true);

        $temporaryPayment = new TemporaryPayment();
        $temporaryPayment->company_branch_id = $data['ppmpf_2'];
        $temporaryPayment->client_id = $data['ppmpf_3'];
        $temporaryPayment->amount = $data['pp_Amount'] ;;
        $temporaryPayment->reference_number = $data['pp_TxnRefNo'];
        $temporaryPayment->payment_status = 0;

        if($temporaryPayment->save()){
            $response = array(
                'code' =>200,
                'company_branch_id'=>0,
                'success' =>true,
                'message'=>'Your payment has been processed successfully',
                'data' => []
            );
        }else{

            $response = array(
                'code' =>200,
                'company_branch_id'=>0,
                'success' =>true,
                'message'=>$temporaryPayment->getErrors(),
                'data' => []
            );
        }


        $this->sendResponse($response);





        /*Payment Master*/
        /* $paymentmaster = new PaymentMaster();
         $paymentmaster->date = date("Y-m-d");
         $paymentmaster->time =  date("h:i:sa");
         $paymentmaster->payment_mode = $data['payment_mode'];
         $paymentmaster->amount_paid = $data['amount_paid'];
         $paymentmaster->remarks  = $data['remarks'];
         $paymentmaster->reference_number  = $data['reference_number'];
         if($paymentmaster->save()){
              $paymentMasterID = $paymentmaster['payment_master_id'];
              $query ="Select * from delivery as d
             where d.client_id = $clientID AND d.rider_id = $riderID AND d.payment_flage = 0 OR d.payment_flage = 1";
             $queryResult = Yii::app()->db->createCommand($query)->queryAll();
             foreach($queryResult as $value){
                 $deliveryID = $value['delivery_id'] ;
                  if($value['payment_flage']== 0){

                        if($amountPaid >= $value['total_amount']){
                            $totalAmount = $value['total_amount'] ;

                            $deliveryPaymentFlage = Delivery::model()->findByPk(intval($deliveryID));
                            $deliveryPaymentFlage->payment_flage = 2;
                            if($deliveryPaymentFlage->save()){
                                $amountPaid = $amountPaid - $value['total_amount'] ;

                                  $paymentDetail = new PaymentDetail();
                                  $paymentDetail->delivery_id = $value['delivery_id'];
                                  $paymentDetail->delivery_date = $value['date'];
                                   $paymentDetail->client_id = $clientID;
                                  $paymentDetail->due_amount = $amountPaid ;
                                  $paymentDetail->amount_paid = $value['total_amount'] ;
                                  $paymentDetail->payment_master_id = $paymentMasterID ;
                                  $paymentDetail->payment_date = date("Y-m-d");
                                   if($paymentDetail->save()){

                                   }else{
                                       var_dump($paymentDetail->getErrors());
                                   }
                            }else{
                                echo   ($deliveryPaymentFlage->getError());
                            }
                        }else{
                            $deliveryPaymentFlage = Delivery::model()->findByPk(intval($deliveryID));
                            $deliveryPaymentFlage->payment_flage = 1;
                            $deliveryPaymentFlage->partial_amount = $value['total_amount'] -$amountPaid ;
                            if($deliveryPaymentFlage->save()){



                                $paymentDetail = new PaymentDetail();
                                $paymentDetail->delivery_id = $value['delivery_id'];
                                $paymentDetail->delivery_date = $value['date'];
                                $paymentDetail->client_id = $clientID;
                                $paymentDetail->due_amount = 0 ;
                                $paymentDetail->amount_paid = $amountPaid ;
                                $paymentDetail->payment_master_id = $paymentMasterID ;
                                $paymentDetail->payment_date = date("Y-m-d");
                                if($paymentDetail->save()){
                                    $amountPaid = $amountPaid - $value['total_amount'] ;
                                }else{
                                    var_dump($paymentDetail->getErrors());
                                }
                            }
                        }
                  }else{


                  }
             }
         }*/

    }
    public function actioncreateComplaints(){

        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post , true);

        if(!isset($data['company_branch_id'])){
            $data['company_branch_id'] = 1;
        }
        $company_branch_id = $data['company_branch_id'] ;

        $companyObject  =  utill::get_companyTitle($data['company_branch_id']);
        $companyMask = $companyObject['sms_mask'];
        $companyTitle = $companyObject['company_title'];
        $complaints_notification_yes_now = $companyObject['complaints_notification_yes_now'];
        $company_phone_number = $companyObject['phone_number'];

        $complain_type_id = $data['complain_type_id'];

        $company_branch_id = $data['company_branch_id'];

        $client_id = $data['client_id'];

        $query_text = $data['query_text'];

        $complain = new Complain();

        $complain->complain_type_id = $complain_type_id;

        $complain->client_id = $client_id ;
        $complain->company_branch_id = $company_branch_id ;
        $complain->query_text = $query_text;
        $complain->status_id = 0 ;
        $complain->response = "";
        if($complain->save()){
            $clientObject = Client::model()->findByPk(intval($client_id));
            $phoneNo =  $clientObject['cell_no_1'];
            $message ='';
            if($company_branch_id ==10){
                $message .= $clientObject['fullname'];
            }else{
                $message .= 'Dear Customer';
            }
            $message .= ",\nWe've received your complaint. Our team shall look into the issue on priority. 
                         \nThank you for registering your concern.\n\n".$companyTitle;

            $phoneNo = $clientObject['cell_no_1'];
            $fullname = $clientObject['fullname'];
            $network_id = $clientObject['network_id'];
            if($phoneNo=='+923018228141'){
                return true;
            }
            smsLog::saveSms($client_id ,$company_branch_id ,$phoneNo ,$fullname ,$message);
            $this->sendSMS($phoneNo , $message , $companyMask ,$company_branch_id ,$network_id);

            if($complaints_notification_yes_now>0){
                $complain_object = ComplainType::model()->findByPk($complain_type_id);
                $complainb_type_name =  $complain_object['name'];

                $message = 'You have recived new Complain from '.$fullname.' about '. $complainb_type_name;
                smsLog::saveSms($client_id, $company_branch_id, $company_phone_number, $fullname, $message);
                $this->sendSMS($company_phone_number, $message, $companyMask, $company_branch_id, $network_id);

            }

            $emptyObject = array();
            $emptyObject['client_id'] = '';
            $response = array(
                'code' => 200,
                'company_branch_id'=>0,
                'success' => true,
                'message'=>'Complaint submitted successfully',
                'data' => $emptyObject
            );
        }else{

            $response = array(
                'code' => 401,
                'company_branch_id'=>0,
                'success' => false,
                'message'=>$complain->getErrors,
                'data' => []
            );
        }
        $this->sendResponse($response);
    }
    public function actionsaveComplain(){

        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post , true);

        if(!isset($data['company_branch_id'])){
            $data['company_branch_id'] = 1;
        }

        $company_branch_id = $data['company_branch_id'];

        $companyObject  =  utill::get_companyTitle($data['company_branch_id']);
        $companyMask = $companyObject['sms_mask'];
        $companyTitle = $companyObject['company_title'];

        $companyObject = Company::model()->findByPk(intval($data['company_branch_id']));

        $phoneNo = $companyObject['phone_number'];

        $Companymessage = "A new Compalin has been registered\n\n\ ".$companyTitle;
        $network_id = 0;
        $this->sendSMS($phoneNo , $Companymessage , $companyMask , $company_branch_id ,$network_id);

        $complain_type_id = $data['complain_type_id'];
        $client_id = $data['client_id'];
        $query_text = $data['query_text'];
        $complain = new Complain();
        $complain->complain_type_id = $complain_type_id;
        $complain->client_id = $client_id ;
        $complain->query_text = $query_text;
        $complain->company_branch_id = $data['company_branch_id'];
        $complain->status_id = 0 ;
        $complain->response = "";
        if($complain->save()){

            $clientObject = Client::model()->findByPk(intval($client_id));

            $phoneNo =  $clientObject['cell_no_1'];

            $network_id = $clientObject['network_id'];
            $message ='';
            if($company_branch_id ==10){
                $message .=$clientObject['fullname'];
            }else{
                $message .='Dear Customer';
            }
            $message = ",\nWe've received your complaint. Our team shall look into the issue on priority. 
                         \nThank you for registering your concern.\n\n".$companyTitle;
            if($phoneNo=='+923018228141'){
                return true;
            }
            $this->sendSMS($phoneNo , $message ,$companyMask , $company_branch_id ,$network_id);
            $response = array(
                'code' => 200,
                'company_branch_id'=>0,
                'success' => true,
                'message'=>'Complaint submitted successfully',
                'data' => $data
            );
        }else{

            $response = array(
                'code' => 401,
                'company_branch_id'=>0,
                'success' => false,
                'message'=>$complain->getErrors,
                'data' => []
            );
        }
        $this->sendResponse($response);
    }
    public function actioncheckAccountBalnce(){


        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post , true);
        $clientId = $data['client_id'];
        $clientObejct = Client::model()->findByPk(intval($clientId));
        $zone_id = $clientObejct['zone_id'];
        $rider_object = utill::getRiderName($zone_id);
        $amount =  APIData::calculateFinalBalance($clientId);
        $limitAmount = CompanyLimit::model()->findAll();
        $findLimitamount = $limitAmount[0]['limit_amount'];
        if($amount > $findLimitamount){
            $accees_Limit = true ;
        }else{
            $accees_Limit = false ;
        }

        $opening_balance = APIData::get_opening_balance($clientId);


        $response = array(
            'code' => 200,
            'company_branch_id'=>0,
            'success' => true,
            'accees_Limit' => $accees_Limit,
            'message'=>$rider_object[0]['rider_name'],
            'data' => $amount ,
            'opening_balance_perivous_month' => $opening_balance ,
        );
        $this->sendResponse($response);
    }



    public function actiongetPreferredTimeList(){

        date_default_timezone_set("Asia/Karachi");

        $preferdTime = PreferredTime::model()->findAll();
        $preferreddTimeList = array();
        foreach($preferdTime as $value){
            $preferreddTimeList[] = $value->attributes;
        }
        $response = array(
            'code' => 200,
            'company_branch_id'=>0,
            'success' => true,
            'message'=>'Preferred time list',
            'data' => $preferreddTimeList ,
        );
        $this->sendResponse($response);
    }

    public  function actiongetCustomerListAgainstRider(){

        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post , true);
        $riderId = $data['rider_id'];

        $clientQuery = "Select  c.fullname , c.client_id , rz.zone_id from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id
                           where rz.rider_id = $riderId  AND c.is_active = 1";
        $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();

        $response = array(
            'code' => 200,
            'company_branch_id'=>0,
            'success' => true,
            'message'=>'Customer list',
            'data' => $clientResult
        );

        $this->sendResponse($response);
    }

    public function actiongetCustomerTodayOrder()
    {


        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, true);
        $client_id = ($data['client_id']);

        if(!isset($data['company_branch_id'])){
            $data['company_branch_id'] = 1;
        }

        $company_branch_id = $data['company_branch_id'] ;

        $jd = cal_to_jd(CAL_GREGORIAN, date("m"), date("d"), date("Y"));

        $todayDay = (jddayofweek($jd, 1));
        $dayFrequency = Frequency::model()->findByAttributes(array('day_name' => $todayDay));
        $todayfrequencyID = $dayFrequency['frequency_id'];
        $todaydate = date("Y-m-d");



        $productListQuery = "
                Select cg.category_name, p.product_id ,  p.name as productName , cpfq.quantity   ,c.fullname , c.address ,c.cell_no_1 , pt.preferred_time_name , 'regular' as order_type from  client_product_frequency as cpf
                right Join  product as p ON p.product_id = cpf.product_id and p.company_branch_id = $company_branch_id
                LEFT JOIN client as c ON c.client_id = cpf.client_id
                Right JOIN client_product_frequency_quantity as cpfq ON cpfq.client_product_frequency_id = cpf.client_product_frequency AND cpfq.frequency_id = $todayfrequencyID
                LEFT JOIN preferred_time as pt ON pt.preferred_time_id =  cpfq.preferred_time_id
                 LEFT JOIN customer_category AS cg ON cg.customer_category_id =c.customer_category_id
                where cpf.client_id = $client_id AND cpf.orderStartDate <='$todaydate'
                union
                Select  cg.category_name, p.product_id , p.name as productName ,so.quantity , c.fullname , c.address , c.cell_no_1 , pt.preferred_time_name , 'special' as order_type from special_order as so
                right Join  product as p ON p.product_id = so.product_id and p.company_branch_id = $company_branch_id
                left join  client as c On c.client_id = so.client_id 
                LEFT JOIN preferred_time as pt ON pt.preferred_time_id =  so.preferred_time_id
                
                 LEFT JOIN customer_category AS cg ON cg.customer_category_id =c.customer_category_id
                 
                where so.client_id =$client_id AND '$todaydate'  between so.start_date and so.end_date  and so.start_date <= '$todaydate'";



        $productListResult = Yii::app()->db->createCommand($productListQuery)->queryAll();



        $finalData = array();
        $Productquery = "Select p.product_id ,p.name , p.unit , IFNULL(cpp.price , p.price) as price   from product as p
                       LEFT JOIN client_product_price as cpp ON cpp.product_id = p.product_id AND cpp.client_id = '$client_id'
                        where  p.company_branch_id = $company_branch_id and p.bottle = 0 " ;




        $productList = Yii::app()->db->createCommand($Productquery)->queryAll();
        $category_name = '';
        foreach($productList as $value1){
            $oneProductData = array();
            $productID = $value1['product_id'];
            $client_priduct_price = future_rate_list_data::one_client_price($client_id,$todaydate,$productID);

            $oneproductQuantity = 0;
            foreach($productListResult as $value2){
                if($value2['product_id'] ==$productID ){
                    $oneproductQuantity = $oneproductQuantity + $value2['quantity'];
                }
                $category_name =  $value2['category_name'];
            }
            $oneProductData['product_id'] = $value1['product_id'];
            $oneProductData['product_name'] = $value1['name'];
            $oneProductData['quantity'] = $oneproductQuantity;
            $oneProductData['price'] = isset($client_priduct_price['rate'])?$client_priduct_price['rate']:$value1['price'];

            $oneProductData['category_name'] = $category_name;
            $oneProductData['total_price'] = 0;
            $finalData[] = $oneProductData;
        }

        $remaining_bootle =  check_remaning_bottle::getRemaining_bottle($client_id,$company_branch_id);

        $response = array(
            'code' => 200,
            'company_branch_id'=>0,
            'remaining_bottle'=>$remaining_bootle,
            'success' => true,

            'message'=>'today delivery',
            'client_id'=> 0,
            'rider_id'=> 0,
            'data' => $finalData
        );

        $this->sendResponse($response);
    }

    public function actiongetClientAuthenticationCode(){

        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, true);
        $clientID = $data['client_id'];
        $codeObject =Authentication::model()->findByAttributes(array('client_id'=>$clientID));
        if($codeObject){
            $code =   $codeObject['code'] ;

            $response = array(
                'company_branch_id'=>0,
                'success' => true,
                'message'=>'code',
                'code' => $code
            );
        }else {
            $response = array(
                'company_branch_id'=>0,
                'success' => false,
                'message'=>'This User in not Exists',
                'code' => []
            );
        }
        $this->sendResponse($response);
    }
    public function actioncheckClientAvailability(){

        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, true);
        $userName = $data['userName'];

        if(!isset($data['company_branch_id'])){
            $data['company_branch_id'] = 1;
        }
        $company_branch_id = $data['company_branch_id'] ;

        $client = Client::model()->findByAttributes(array('userName'=>$userName , 'company_branch_id'=>$company_branch_id));
        if($client){
            $response = array(
                'company_branch_id'=>0,
                'success' => false,
                'message'=>'This username already exists',
                'data' => []
            );
        }else{
            $response = array(
                'company_branch_id'=>0,
                'success' => true,
                'message'=>'This username is available',
                'data' => []
            );
        }
        $this->sendResponse($response);
    }

    public function actiondeliverybetweenDateRange(){
        date_default_timezone_set("Asia/Karachi");
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post , true);
        $clientId = $data['client_id'];
        $startDate = $data['start_date'];
        $endDate = $data['end_date'];

        $query = "Select d.date ,p.name as product_name , p.unit , dd.quantity , dd.amount ,d.time  from delivery as d
                LEFT JOIN delivery_detail as dd ON d.delivery_id = dd.delivery_id
                LEft JOIN product as p ON p.product_id = dd.product_id
                Where d.client_id ='$clientId' AND d.date between '$startDate' AND '$endDate'
                order by d.date DESC " ;
        $deliveryResult = Yii::app()->db->createCommand($query)->queryAll();

        $response = array(
            'company_branch_id'=>0,
            'code' => 200,
            'success' => true,
            'message'=>'Produts deliveries list',
            'data' => ($deliveryResult)
        );

        $this->sendResponse($response);
    }
    public function actionchangeCustomerPassword(){

        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, true);
        $clientID = $data['client_id'];
        $oldPassword = $data['oldPassword'];
        $newPassword = $data['newPassword'];
        $clientObjct =Client::model()->findByPk(intval($clientID));
        if($clientObjct){
            $alreadyPassword =  $clientObjct['password'];
            if($alreadyPassword ==$oldPassword ){
                $clientObjct->password = $newPassword;
                if($clientObjct->save()){
                    $response = array(
                        'company_branch_id'=>0,
                        'code' => 200,
                        'success' => true,
                        'message'=>'You have changed password successfully',
                        'data' => []
                    );
                }else{
                }
            }else{
                $response = array(
                    'company_branch_id'=>0,
                    'code' => 200,
                    'success' => false,
                    'message'=>'Old password is wrong',
                    'data' => []
                );
            }
        }else{
            $response = array(
                'company_branch_id'=>0,
                'code' => 200,
                'success' => false,
                'message'=>'This user is not registered',
                'data' => []
            );
        }
        $this->sendResponse($response);
    }

    public function actiongetCustomerComplains(){

        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post ,true);
        $clientId = ($data['client_id']);
        $query = "select c.complain_id , c.query_text ,c.created_on ,c.response, s.status_name ,cp.name as reason from complain as c
          left join status as s ON s.status_id = c.status_id
          left join complain_type as cp ON cp.complain_type_id = c.complain_type_id
          where c.client_id = '$clientId'";
        $queryResult = Yii::app()->db->createCommand($query)->queryAll();
        if($queryResult){
            $response = array(
                'company_branch_id'=>0,
                'code' => 200,
                'success' => true,
                'message'=>'Complain List',
                'data' => $queryResult
            );
        }else{
            $response = array(
                'company_branch_id'=>0,
                'code' => 200,
                'success' => false,
                'message'=>'You have not registered any complain',
                'data' => $queryResult
            );
        }
        $this->sendResponse($response);
    }
    public function actioncustomerChangePassword(){

        date_default_timezone_set("Asia/Karachi");

        $post  = file_get_contents("php://input");
        $data = CJSON::decode($post , true);
        $clienID = $data['client_id'];
        $oldPassword = $data['oldPassword'];
        $newPassword = $data['newPassword'];
        $clientObject =Client::model()->findByPk(intval($clienID));
        if($clientObject){
            $getOldPassword = $clientObject['password'];
            if($oldPassword ==$getOldPassword ){
                $clientObject->password =$newPassword ;
                if($clientObject->save()){
                    $response = array(
                        'company_branch_id'=>0,
                        'code' => 200,
                        'success' => true,
                        'message'=>'You have successfully changed Password',
                        'data' => []
                    );
                }
            }else{
                $response = array(
                    'company_branch_id'=>0,
                    'code' => 401,
                    'success' => false,
                    'message'=>'Old password is wrong',
                    'data' => []
                );
            }
        }
        $this->sendResponse($response);
    }

    public  function actioncustomerForgetPassword(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post , true);
        if(!isset($data['company_branch_id'])){
            $data['company_branch_id'] = 1;
        }
        $company_branch_id = $data['company_branch_id'] ;

        $companyObject  =  utill::get_companyTitle($data['company_branch_id']);
        $companyMask = $companyObject['sms_mask'];
        $companyTitle = $companyObject['company_title'];

        $clientMobile= $data['cell_no_1'];
        $clientObject = Client::model()->findByAttributes(array('cell_no_1'=>$clientMobile , 'company_branch_id'=>$company_branch_id));
        $dataReturn = array();
        $dataReturn['client_id']= '';
        if($clientObject){
            $password =  $clientObject['password'];
            $userName =  $clientObject['userName'];
            $fullname =  $clientObject['fullname'];
            $network_id = $clientObject['network_id'];
            $message = "Hi ".$fullname.",\nYour credentials for ".$companyTitle." Mobile App are.\n";

            $message .="Username: ".$userName."\nPassword: ".$password;
            $message .="\n\n";
            $message .=$companyTitle;



            $this->sendSMS($clientMobile , $message ,$companyMask , $company_branch_id ,$network_id);
            $response = array(
                'company_branch_id'=>0,
                'code' => 200,
                'success' => true,
                'message'=>'We have sent you the details of your account on your phone number. Please check your phone inbox.',
                'data' => $dataReturn
            );

        }else{
            $message = array();
            $message['client_id'] = '123';

            $response = array(
                'company_branch_id'=>0,
                'code' => 401,
                'success' => false,
                'message'=>'This phone number is not registered',
                'data' => $dataReturn,
            );
        }
        $this->sendResponse($response);
    }



    public function actionSMSTEST(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post , true);
        $phoneNumber = $data['cell_no_1'];

        $this->sendSMS($phoneNumber,"hi from tanveer");
    }

    public function actiongetNotDeliveryReasonType(){

        date_default_timezone_set("Asia/Karachi");

        $notDeliveryResonType = NotDeliveryReasontype::model()->findAll();
        $reasonType = array();
        foreach($notDeliveryResonType as $value){
            $reasonType[]=$value->attributes;
        }
        $response = array(
            'company_branch_id'=>0,
            'code' => 200,
            'success' => true,
            'message'=>'not delivery reson type',
            'data' => $reasonType
        );

        $this->sendResponse($response);

    }
    public function actioncancelSpecialOrder(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post , true);
        $client_id = $data['client_id'];
        $product_id = $data['product_id'];
        SpecialOrder::model()->deleteAllByAttributes(array('client_id'=>$client_id , 'product_id'=>$product_id));
        $sendData = array();
        $sendData['client_id'] = '';
        $response = array(
            'company_branch_id'=>0,
            'code' => 200,
            'success' => true,
            'message'=>'You special order have cancel successfully',
            'data' => $sendData
        );

        $this->sendResponse($response);
    }
    public function actionHaltRegularOrders(){

        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post , true);

        if(!isset($data['company_branch_id'])){
            $data['company_branch_id'] = 1;
        }
        $company_branch_id = $data['company_branch_id'] ;

        $companyObject  =  utill::get_companyTitle($data['company_branch_id']);
        $companyMask = $companyObject['sms_mask'];
        $companyTitle = $companyObject['company_title'];

        $clientID =   $data['client_id'] ;
        $haltregularOrder = new HaltRegularOrders();
        $haltregularOrder->client_id = $data['client_id'];
        $haltregularOrder->product_id = $data['product_id'];
        $haltregularOrder->start_date = $data['start_date'];
        $haltregularOrder->end_date = $data['end_date'];
        if($haltregularOrder->save()){
            $sendData = array();
            $sendData['client_id'] = '';
            $response = array(
                'company_branch_id'=>0,
                'code' => 200,
                'success' => true,
                'message'=>'You regular order have halt successfully',
                'data' => $sendData
            );

        }
        $clientObject = Client::model()->findByPk(intval($clientID));
        $phoneNo =  $clientObject['cell_no_1'];
        $fullname = $clientObject['fullname'];
        $network_id = $clientObject['network_id'];

        $message = " Your regular order have been halted successfully. Please note that your regular delivery will automatically resume after the halt period is over.
          \n\n".$companyTitle;
        smsLog::saveSms($clientID ,$company_branch_id ,$phoneNo ,$fullname ,$message);
        $this->sendSMS($phoneNo,$message ,$companyMask , $company_branch_id ,$network_id);

        $this->sendResponse($response);

    }

    public function actionCancelRegularOrder(){

        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post , true);

        if(!isset($data['company_branch_id'])){
            $data['company_branch_id'] = 1;
        }
        $company_branch_id = $data['company_branch_id'] ;

        $companyObject  =  utill::get_companyTitle($data['company_branch_id']);
        $companyMask = $companyObject['sms_mask'];
        $companyTitle = $companyObject['company_title'];

        $client_id =$data['client_id'];
        $product_id = $data['product_id'];

        $clientProductFrequancy =ClientProductFrequency::model()->findByAttributes(array('client_id'=>$client_id , 'product_id'=>$product_id));
        if($clientProductFrequancy){
            $quantityID =  $clientProductFrequancy['client_product_frequency'];
            ClientProductFrequencyQuantity::model()->deleteAllByAttributes(array('client_product_frequency_id'=>$quantityID));
            $clientProductFrequancy->delete();
        }


        $clientSchedulerObject  = IntervalScheduler::model()->findByAttributes(array('client_id'=>$client_id , 'product_id'=>$product_id));
        if($clientSchedulerObject){
            $clientSchedulerObject->delete();
        }

        $sendData = array();
        $sendData['client_id'] = '';
        $response = array(
            'company_branch_id'=>0,
            'code' => 200,
            'success' => true,
            'message'=>'You regular order have canceled successfully',
            'data' => $sendData
        );
        $this->sendResponse($response);
        $clientObject = Client::model()->findByPk(intval($client_id));
        $phoneNo =  $clientObject['cell_no_1'];

        $message = "Your regular order is successfully cancelled
          \n\n".$companyTitle;

        $fullname = $clientObject['fullname'];
        $network_id = $clientObject['network_id'];

        smsLog::saveSms($client_id ,$company_branch_id ,$phoneNo ,$fullname ,$message);

        $this->sendSMS($phoneNo,$message ,$companyMask , $company_branch_id ,$network_id);

    }
    public function actionresumedRegularOrder(){

        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post , true);

        if(!isset($data['company_branch_id'])){
            $data['company_branch_id'] = 1;
        }
        $company_branch_id = $data['company_branch_id'] ;

        $companyObject  =  utill::get_companyTitle($data['company_branch_id']);
        $companyMask = $companyObject['sms_mask'];
        $companyTitle = $companyObject['company_title'];

        $client_id = $data['client_id'];
        $product_id = $data['product_id'];
        HaltRegularOrders::model()->deleteAllByAttributes(array('client_id'=>$client_id ,'product_id'=>$product_id));
        $sendData = array();
        $sendData['client_id'] = '';
        $response = array(
            'company_branch_id'=>0,
            'code' => 200,
            'success' => true,
            'message'=>'You regular order have resumed successfully',
            'data' => $sendData
        );

        $clientObject = Client::model()->findByPk(intval($client_id));
        $phoneNo =  $clientObject['cell_no_1'];
        $network_id = $clientObject['network_id'];

        $message = "Your regular delivery is resumed.
          \nThank you\n\n".$companyTitle;
        $this->sendSMS($phoneNo , $message ,$companyMask , $company_branch_id ,$network_id);
        $fullname = $clientObject['fullname'];

        smsLog::saveSms($client_id ,$company_branch_id ,$phoneNo ,$fullname ,$message);
        $this->sendResponse($response);
    }
    public function actiongetDailyCustomerAndProductQuantityCount(){


        date_default_timezone_set("Asia/Karachi");
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post ,true);
        if(!isset($data['company_branch_id'])){
            $data['company_branch_id'] = 1;
        }
        $company_branch_id = $data['company_branch_id'] ;
        $riderID = $data['rider_id'];
        $jd = cal_to_jd(CAL_GREGORIAN,date("m"),date("d"),date("Y"));

        $todayDay=(jddayofweek($jd,1));


        $dayFrequency = Frequency::model()->findByAttributes(array('day_name'=>$todayDay));
        $todayfrequencyID = $dayFrequency['frequency_id'] ;

        $todaydate =  date("Y-m-d");

        $previousdate =  date('Y-m-d', strtotime(' -1 day'));


        $clientQuery = "Select c.client_type , c.client_id from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id
                           left join halt_regular_orders hro ON hro.client_id = c.client_id and '$todaydate'
                            between hro.start_date and hro.end_date
                           where rz.rider_id = $riderID  AND c.is_active = 1 ";

        $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();

        $totalCustomer_sample = 0;
        foreach($clientResult as $count_value){
            if($count_value['client_type'] == '2'){
                $totalCustomer_sample = $totalCustomer_sample + 1;
            }
        }

        $totalCustomer = count($clientResult);

        $client_IDS = array();
        $client_IDS[] = 0;
        foreach($clientResult as $value){
            $client_IDS[] = $value['client_id'];
        }

        $getAllClient_id = implode(',' ,$client_IDS);

        $clientQuery_newSchedule  = "select cs.client_id from change_scheduler_record as cs
                left join client as c ON c.client_id = cs.client_id
                 where cs.date in ('$todaydate' ,'$previousdate') and cs.client_id in ($getAllClient_id) 
                 and c.client_type =1
                 group by cs.client_id";

        $clientResult_newSchedule =  Yii::app()->db->createCommand($clientQuery_newSchedule)->queryAll();


        /*For Interval Start*/

        $intervalQuantity = 0;


        $today = date("Y-m-d");


        $queryRiderStock ="Select p.product_id , (sum(rds.quantity) - sum(rds.return_quantity)) as net_stock  from rider_daily_stock rds
                 left join product as p ON p.product_id = rds.product_id and p.company_branch_id = $company_branch_id and p.is_active=1
                 where rds.rider_id = $riderID AND rds.date = '$today' 
                 Group by rds.product_id";


        $riderStockResult = Yii::app()->db->createCommand($queryRiderStock)->queryAll();
        $regularQuery = "Select cpf.client_id , p.product_id , p.name , sum(cpfq.quantity) as quantity  , IFNULL(hro.client_id , 0) as halt_Client from client_product_frequency as cpf
                Right Join product as p ON p.product_id = cpf.product_id  and p.company_branch_id = $company_branch_id and p.is_active=1
                Left Join zone as z ON z.zone_id =18
                LEFT JOIN halt_regular_orders as hro ON hro.product_id = p.product_id
                AND hro.client_id  in ($getAllClient_id) AND '$todaydate' between hro.start_date and hro.end_date
                Right JOIN client_product_frequency_quantity as cpfq ON 
                cpfq.client_product_frequency_id = cpf.client_product_frequency AND cpfq.frequency_id = $todayfrequencyID 
                where cpf.orderStartDate <='$todaydate' AND  cpf.client_id in ($getAllClient_id)
                group by p.product_id";

        //  $regularResult = Yii::app()->db->createCommand($regularQuery)->queryAll();



        $specialOrderQuery = " Select p.product_id , p.name  as productName , Sum(so.quantity) as quantity from special_order as so
                    Left Join  product as p ON p.product_id = so.product_id and p.company_branch_id = $company_branch_id and p.is_active=1
                    left join  client as c On c.client_id = so.client_id 
                    where so.client_id in ($getAllClient_id) AND '$todaydate' between so.start_date and so.end_date
                    Group by p.product_id ";
        //  $specialOrderResult = Yii::app()->db->createCommand($specialOrderQuery)->queryAll();



        $queryTotalDelivery = " select p.product_id , p.name , sum(dd.quantity) as totalQuantity from delivery as  d
                left join delivery_detail as dd ON d.delivery_id = dd.delivery_id
                Right join product as p ON p.product_id = dd.product_id and p.company_branch_id = $company_branch_id and p.is_active=1
                where d.date = '$today' and d.client_id in ($getAllClient_id)
                group by p.product_id  ";

        $resultTotalDelivery = Yii::app()->db->createCommand($queryTotalDelivery)->queryAll();


        //  $productList =Product::model()->findAllByattributes(array('company_branch_id'=>$company_branch_id ));
        $query="SELECT p.*  from   product as p 
                where p.company_branch_id =$company_branch_id and bottle = 0  and p.is_active=1";
        $productList =  Yii::app()->db->createCommand($query)->queryAll();
        $result=array();

        foreach($productList as $pro){
            $oneProduct = array();
            $oneProduct['unit'] = $pro['unit'];
            $oneProduct['product_id'] = $pro['product_id'];
            $oneProduct['name'] = $pro['name'];
            $oneProduct['quantity'] = $intervalQuantity;
            $oneProduct['delivered_quantity'] =0;
            $oneProduct['picked_quantity'] = 0;
            $oneProduct['picked_quantity_7'] = 0;

            /* foreach($regularResult as $regular){
                 if($pro['product_id']==$regular['product_id']){
                     $oneProduct['quantity'] =$regular['quantity'] + $oneProduct['quantity'];
                 }
             }
             foreach($specialOrderResult as $special){
                 if($pro['product_id']==$special['product_id']){
                     $oneProduct['quantity'] =$special['quantity'] + $oneProduct['quantity'];
                 }
             }*/


            $totalScheduleToday = 0;
            $totalScheduleToday_sample = 0;



            foreach($clientResult as $value){

                $client_id = $value['client_id'];
                $totalInterval_quantity =  future_selet_date::getOneCustomerTodayIntervalSceduler_with_date_future_date($client_id,$pro['product_id'] ,$todaydate);

                $totalWeekly_quantity  =    todayDeliveryQuantityCountForApi::getTodayDeliveryCountWeeklyRegularAndSpecial($client_id ,$pro['product_id'], $todaydate);
                $totalSpecialToday_quantity  =    todayDeliveryQuantityCountForApi::getTodaySpecialOrder($client_id ,$pro['product_id'] ,$todaydate);
                $totalScheduleToday = $totalScheduleToday +$totalInterval_quantity + $totalWeekly_quantity +$totalSpecialToday_quantity ;

                if($value['client_type'] == '2'){
                    $totalScheduleToday_sample = $totalScheduleToday_sample + $totalInterval_quantity + $totalWeekly_quantity +$totalSpecialToday_quantity ;
                }
            }

            $oneProduct['quantity'] =  $totalScheduleToday;


            foreach($riderStockResult as $stock){
                if($pro['product_id']==$stock['product_id']){
                    $oneProduct['picked_quantity'] =$stock['net_stock'];
                    $oneProduct['picked_quantity_7'] =$stock['net_stock'];
                }
            }
            foreach($resultTotalDelivery as $delivery){
                if($pro['product_id']==$delivery['product_id']){
                    $oneProduct['delivered_quantity'] =$delivery['totalQuantity'];
                }
            }
            $result[] = $oneProduct;

        }



        $response = array(
            'company_branch_id'=>0,
            'code' => 200,
            'success' => true,
            'rider_id' => 0,
            'totalCustomer_newSchedule' => sizeof($clientResult_newSchedule),
            'totalCustomer' => $totalCustomer,
            'totalCustomer_sample' => $totalCustomer_sample,
            'totalLeter_sample' => $totalScheduleToday_sample,
            'message'=>'Customer And Product Count',
            'data' =>$result
        );
        $this->sendResponse($response);
    }

    public function actionsaveNotDeliveryReasonType(){

        date_default_timezone_set("Asia/Karachi");


        date_default_timezone_set("Asia/Karachi");
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post , true);

        if(!isset($data['company_branch_id'])){
            $data['company_branch_id'] = 1;
        }
        $company_branch_id = $data['company_branch_id'] ;

        $companyTile  =  utill::get_companyTitle($data['company_branch_id']);


        $companyObject  =  utill::get_companyTitle($data['company_branch_id']);
        $companyMask = $companyObject['sms_mask'];
        $companyTitle = $companyObject['company_title'];


        $notDeliveryResonTypeID = $data['not_delivery_reasonType_id'];
        $reasonTypeObject = NotDeliveryReasontype::model()->findByPk(intval($notDeliveryResonTypeID));
        $resonTypeName = $reasonTypeObject['reasonType_name'];

        $recordObject = new NotDeliveryRecord();
        $recordObject->not_delivery_reasonType_id = $data['not_delivery_reasonType_id'];
        $recordObject->client_id = $data['client_id'];
        $recordObject->rider_id = $data['rider_id'];
        $recordObject->not_delivery_dateTime = date("Y-m-d h:i:sa") ;
        $recordObject->time = date("H:i");
        $recordObject->latitude = $data['lat'];
        $recordObject->longitude = $data['longi'];

        if($recordObject->save()){

            $response = array(
                'company_branch_id'=>0,
                'code' => 200,
                'success' => true,
                'message'=>'Your delivery have not been delivere',
                'data' => []
            );
        }
        $clientID = $data['client_id'] ;

        $getClientObject =Client::model()->findByPk(intval($clientID));
        $cellNo = $getClientObject['cell_no_1'];
        $fullname = $getClientObject['fullname'];
        $network_id = $getClientObject['network_id'];

        $message = "Our rider was not able to deliver products to you today due to:\n".$resonTypeName."\n\n
          Thank you\n\n".$companyTitle;
        /* $phoneNo = $value['cell_no_1'];
         $fullname = $value['fullname'];
         $client_id = $value['client_id'];
         $phoneNo = $value['cell_no_1'];*/
        smsLog::saveSms($clientID ,$company_branch_id ,$cellNo ,$fullname ,$message);
        $this->sendSMS($cellNo,$message ,$companyMask , $company_branch_id ,$network_id);

        $this->sendResponse($response);

    }
    public function actiontestSMS(){
        $this->sendSMS('03006053362',"delivered Sms");

    }
    public function actionMessageAlertPermission(){

        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post , true);
        $clientID = $data['client_id'];

        $query = "select c.daily_delivery_sms , c.alert_new_product from client as c
                where c.client_id = '$clientID'";

        $result = Yii::app()->db->createCommand($query)->queryAll();

        $response = array(
            'company_branch_id'=>0,
            'code' => 200,
            'success' => true,
            'client_id'=>'0',
            'message'=>'news alert message',
            'data' => $result[0]
        );
        $this->sendResponse($response);
    }
    public function actionsaveMessageAlertPermission(){

        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post , true);
        $clientID = $data['client_id'];
        $reciveData = $data['data'];

        $clientObject = Client::model()->findByPK(intval($clientID));
        $clientObject->daily_delivery_sms = $reciveData['daily_delivery_sms'];
        $clientObject->alert_new_product = $reciveData['alert_new_product'];
        $sendArray = array();
        $sendArray['client_id'] = 0 ;

        $clientObject->save();

        $response = array(
            'company_branch_id'=>0,
            'code' => 200,
            'success' => true,
            'client_id'=>'0',
            'message'=>'news alert message',
            'data' => $sendArray
        );

        $this->sendResponse($response);

    }
    public  function actioncheckUserActiveORInactive(){
        date_default_timezone_set("Asia/Karachi");


        $post = file_get_contents("php://input");
        $data = CJSON::decode($post , true);
        $clientID = $data['client_id'];
        $clientObject  = Client::model()->findByPk(intval($clientID));


        $sendArray = array();
        $sendArray['client_id'] = 0 ;

        if($clientObject['is_active'] == 1){
            $response = array(
                'company_branch_id'=>0,
                'code' => 200,
                'success' => true,
                'message'=>'user active Or inactive',
                'data' => $sendArray
            );
            date_default_timezone_set("Asia/Karachi");
            $clientObect = Client::model()->findByPk(intval($data['client_id']));
            $clientObect->LastTime_login = date("Y-m-d h:i:s");
            $clientObect->save();

        }else{
            $response = array(
                'company_branch_id'=>0,
                'code' => 200,
                'success' => false,
                'message'=>'user active Or inactive',
                'data' => $sendArray
            );
        }
        $this->sendResponse($response);
    }
    public function actionriderProductList(){

        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post,true);
        if(!isset($data['company_branch_id'])){
            $data['company_branch_id'] = 1;
        }
        $company_branch_id = $data['company_branch_id'] ;
        $prductQuery ="select p.product_id , p.name as product_name ,0 as quantity  from product as p
                      where p.company_branch_id =$company_branch_id ";
        $queryResult = Yii::app()->db->createCommand($prductQuery)->queryAll();
        $response = array(
            'company_branch_id'=>0,
            'code' => 200,
            'success' => true,
            'rider_id'=>0,
            'message'=>'productList',
            'data' => $queryResult
        );
        $this->sendResponse($response);
    }
    public function actionSaveGetRiderStock(){
        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post,true);
        $todayDate = date("Y-m-d");
        $riderID = $data['rider_id'];
        $productList = $data['data'];
        if(!isset($data['company_branch_id'])){
            $data['company_branch_id'] = 1;
        }
        $company_branch_id = $data['company_branch_id'];
        $sendArray = array();
        $sendArray['client_id'] = 0 ;
        foreach($productList as $value){
            $product_id = $value['product_id'];
            if(isset($value['quantity'])){

                if($value['picked_quantity'] >0 ){


                    $riderdaiLStockObject = RiderDailyStock::model()->findbyattributes(array('rider_id'=>$riderID , 'date'=>$todayDate,'product_id'=>$product_id));
                    if($riderdaiLStockObject){
                        $quantity_data = $value['picked_quantity'];
                        // if($company_branch_id==7 || $company_branch_id==10){
                        // AAMER if($company_branch_id==7){

                        // AAMER    $quantity_data =$riderdaiLStockObject['quantity'] + $value['picked_quantity'];
                        // AAMER }
                        //  $updateObject = RiderDailyStock::model()->findByPK(intval($id));
                        $riderdaiLStockObject->quantity=$quantity_data;
                        if($riderdaiLStockObject->save()){
                            $response = array(
                                'company_branch_id'=>0,
                                'code' => 200,
                                'success' => true,
                                'rider_id'=>0,
                                'message'=>'saveStock',
                                'data' => $sendArray
                            );
                        }
                    }else{

                        $model = new RiderDailyStock();
                        $model->rider_id = $riderID;
                        $model->product_id = $value['product_id'];
                        $model->date = date("Y-m-d");
                        $model->quantity = $value['picked_quantity'];
                        $model->return_quantity = 0;
                        if($model->save()){

                            $response = array(
                                'company_branch_id'=>0,
                                'code' => 200,
                                'success' => true,
                                'rider_id'=>0,
                                'message'=>'saveStock',
                                'data' => $sendArray
                            );
                        }else{
                            var_dump($model->getErrors());
                            die();
                            $response = array(
                                'company_branch_id'=>0,
                                'code' => 200,
                                'success' => false,
                                'rider_id'=>0,
                                'message'=>'saveStock',
                                'data' => $sendArray
                            );
                        }
                    }
                }

                $response = array(
                    'company_branch_id'=>0,
                    'code' => 200,
                    'success' => true,
                    'rider_id'=>0,
                    'message'=>'saveStock',
                    'data' => $sendArray
                );
            }

            $response = array(
                'company_branch_id'=>0,
                'code' => 200,
                'success' => true,
                'rider_id'=>0,
                'message'=>'saveStock',
                'data' => $sendArray
            );
        }
        $this->sendResponse($response);
    }

    ////// ///////////////////////////////////////////////////////////////////////////////////    For Order type    ///////////////////////////////////////////////////////////////////////





    public function actiongetCustomerRegularOrderType()
    {
        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $client_id = $data['client_id'];
        $product_id = $data['product_id'];

        $clientSchedulerObject  = ClientProductFrequency::model()->findByAttributes(array('client_id'=>$client_id , 'product_id'=>$product_id));
        $clientinterval =IntervalScheduler::model()->findByAttributes(array('client_id'=>$client_id , 'product_id'=>$product_id));
        if($clientSchedulerObject){

            $type  = 1;
        }else if($clientinterval){
            $type = 2 ;
        }else{
            $type = 0;
        }
        $response = array(
            'code' => 401,
            'company_branch_id' => 0,
            'success' => true,
            'message' => '',
            'data' => $type
        );
        $this->sendResponse($response);
    }

    public function actiongetCustomerIntervalSchedulerStatus(){
        date_default_timezone_set("Asia/Karachi");
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $client_id = $data['client_id'];
        $product_id = $data['product_id'];
        $todaydate = date("Y-m-d");
        $query = "select
                        IFNULL((select isi.is_halt from interval_scheduler as isi 
                        where isi.client_id = '$client_id' and isi.product_id ='$product_id' and '$todaydate'  between isi.halt_start_date and isi.halt_end_date
                        limit 1 ) ,0) as is_halt ,
              '$product_id' as product_id ,IFNULL(ist.product_quantity ,0) as product_quantity  ,IFNULL(c.client_id ,0) as client_id  , IFNULL(ist.interval_days ,0) as interval_days
            ,IFNULL(ist.start_interval_scheduler ,0) as start_interval_scheduler,IFNULL(ist.halt_start_date ,0) as halt_start_date,IFNULL(ist.halt_end_date ,0) as halt_end_date   from client as c
            left join interval_scheduler as ist ON ist.client_id = '$client_id' and ist.product_id = '$product_id'
            where c.client_id = '$client_id' ";

        $result =Yii::app()->db->createCommand($query)->queryAll();
        $response = array(
            'code' => 401,
            'company_branch_id' => 0,
            'success' => true,
            'message' => '',
            'data' => $result
        );

        $this->sendResponse($response);

    }

    public function actionsaveIntervalScheduler(){
        date_default_timezone_set("Asia/Karachi");
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $start_interval_scheduler = $data['start_interval_scheduler'];

        date_default_timezone_set("Asia/Karachi");
        $today_date = date("Y-m-d");
        if($start_interval_scheduler<=$today_date){
            $client_id = $data['client_id'];
            $product_id = $data['product_id'];
            $intervalSchedule = IntervalScheduler::model()->findByAttributes(array("client_id"=>$client_id , "product_id"=>$product_id));
            if($intervalSchedule){
            }else{
                $intervalSchedule = new IntervalScheduler();
            }

            $intervalSchedule->client_id =$data['client_id'];
            $intervalSchedule->product_id =$data['product_id'];
            $intervalSchedule->interval_days = $data['interval_days'];
            $intervalSchedule->product_quantity = $data['product_quantity'];
            $intervalSchedule->start_interval_scheduler = $data['start_interval_scheduler'];
            $intervalSchedule->is_halt =1;
            $intervalSchedule->halt_start_date   =date('Y-m-d', strtotime(' -1 day'));
            $intervalSchedule->halt_end_date   =date('Y-m-d', strtotime(' -1 day'));


            if($intervalSchedule->save()){

                $response = array(
                    'code' => 401,
                    'company_branch_id' => 0,
                    'success' => true,
                    'message' => 'save Successfully',
                );

            }else{

                $response = array(
                    'code' => 401,
                    'company_branch_id' => 0,
                    'success' => true,
                    'message' => $intervalSchedule->getErrors(),
                );

            }

            $clientObject = Client::model()->findByPk(intval($client_id));
            $company_branch_id =$clientObject['company_branch_id'];



            $savechagescheduler =new ChangeSchedulerRecord();
            $savechagescheduler->client_id = $client_id;
            $savechagescheduler->company_id = $company_branch_id;
            $savechagescheduler->date = date("Y-m-d");
            $savechagescheduler->change_form= 2 ;
            $savechagescheduler->save();

            $this->sendResponse($response);


        }else{

            $client_id = $data['client_id'];
            $product_id = $data['product_id'];
            $start_interval_scheduler = $data['start_interval_scheduler'];

            $interval_Objec = EffectiveDateIntervalSchedule::model()->findByAttributes(
                array(
                    'client_id'=>$client_id,
                    'product_id'=>$product_id,
                )
            );
            if($interval_Objec){

                $interval_Objec->product_quantity =$data['product_quantity'];
                $interval_Objec->interval_days =$data['interval_days'];
                $interval_Objec->start_interval_scheduler =$start_interval_scheduler;

                $interval_Objec->save();

            }else{

                $effective_date_interval_schedule = new EffectiveDateIntervalSchedule();
                $effective_date_interval_schedule->client_id =$client_id;
                $effective_date_interval_schedule->product_id =$product_id;
                $effective_date_interval_schedule->start_interval_scheduler =$start_interval_scheduler;
                $effective_date_interval_schedule->interval_days = $data['interval_days'];
                $effective_date_interval_schedule->product_quantity = $data['product_quantity'];
                $effective_date_interval_schedule->save();

            }

            $response = array(
                'code' => 401,
                'company_branch_id' => 0,
                'success' => true,
                'message' => 'save Successfully',
            );

            $this->sendResponse($response);
        }

    }

    public function actionresumeIntervalHaltSchedular(){
        date_default_timezone_set("Asia/Karachi");
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);



        $client_id = $data['client_id'];
        $product_id = $data['product_id'];
        $intervalSchedule = IntervalScheduler::model()->findByAttributes(array("client_id"=>$client_id , "product_id"=>$product_id));
        if($intervalSchedule){

            $intervalSchedule->halt_start_date   =date('Y-m-d', strtotime(' -1 day'));
            $intervalSchedule->halt_end_date   =date('Y-m-d', strtotime(' -1 day'));
            $intervalSchedule->save();

            $response = array(
                'code' => 401,
                'company_branch_id' => 0,
                'success' => true,
                'message' => 'Resume Successfully',
            );
        }else{

            $response = array(
                'code' => 401,
                'company_branch_id' => 0,
                'success' => false,
                'message' => 'Resume Not Successfully',
            );
        }




        $this->sendResponse($response);

    }
    public function actiongetZoneListAgainstRider(){

        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $rider_id =$data['rider_id'];
        $query = "select z.zone_id , z.name as zone_name from  rider as r
            inner join rider_zone as rz ON rz.rider_id = r.rider_id
            inner join zone as z ON z.zone_id=rz.zone_id
            where r.rider_id='$rider_id' ";

        $result =Yii::app()->db->createCommand($query)->queryAll();

        $response = array(
            'code' => 401,
            'company_branch_id' => 0,
            'success' => true,
            'message' => 'Zone List',
            'data' =>$result
        );

        $this->sendResponse($response);
    }

    public function actionhaltIntervalRegularOrder(){

        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $client_id = $data['client_id'];
        $product_id = $data['product_id'];
        $halt_flag  = $data['halt_flag'];


        $company_branch_id = $data['company_branch_id'] ;

        $companyObject  =  utill::get_companyTitle($data['company_branch_id']);
        $companyMask = $companyObject['sms_mask'];
        $companyTitle = $companyObject['company_title'];


        if($halt_flag == 2){
            $intervalSchedule = IntervalScheduler::model()->findByAttributes(array("client_id"=>$client_id , "product_id"=>$product_id));
            if($intervalSchedule){
                $intervalSchedule->halt_start_date = $data['halt_start_date'];
                $intervalSchedule->halt_end_date = $data['halt_end_date'];
                $intervalSchedule->save();
                $response = array(
                    'code' => 401,
                    'company_branch_id' => 0,
                    'success' => true,
                    'message' => 'halt Successfully',
                );
            }else{
                $response = array(
                    'code' => 401,
                    'company_branch_id' => 0,
                    'success' => false,
                    'message' => 'halt fail',
                );
            }
            $clientObject = Client::model()->findByPk(intval($client_id));
            $phoneNo =  $clientObject['cell_no_1'];
            $message = " Your regular order have been halted successfully. Please note that your regular delivery will automatically resume after the halt period is over.
             \n\n".$companyTitle;
            //   $this->sendSMS($phoneNo,$message ,$companyMask , $company_branch_id);
        }else{
            $haltregularOrder = new HaltRegularOrders();
            $haltregularOrder->client_id = $client_id;
            $haltregularOrder->product_id = $product_id;
            $haltregularOrder->start_date = $data['halt_start_date'];
            $haltregularOrder->end_date = $data['halt_end_date'];
            if($haltregularOrder->save()){
                $sendData = array();
                $sendData['client_id'] = '';
                $response = array(
                    'company_branch_id'=>0,
                    'code' => 200,
                    'success' => true,
                    'message'=>'You regular order have halt successfully',
                    'data' => $sendData
                );
            }
            $clientObject = Client::model()->findByPk(intval($client_id));
            $phoneNo =  $clientObject['cell_no_1'];
            $message = " Your regular order have been halted successfully. Please note that your regular delivery will automatically resume after the halt period is over.
             \n\n".$companyTitle;
            //    $this->sendSMS($phoneNo,$message ,$companyMask , $company_branch_id);
        }
        $this->sendResponse($response);
    }



    public  function actionmakePaymentFromApp(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $company_id = $data['company_branch_id'];
        echo conformPayment::conformPaymentMethodFromApp($company_id , $data);

    }


    public  function actionmakePaymentFromAppUbl(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $company_id = $data['company_branch_id'];
        echo conformPayment::conformPaymentMethodFromAppUbl($company_id , $data);

    }

    public function actioncheckPaymentForMonth(){

        date_default_timezone_set("Asia/Karachi");

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $cleint_Id  = $data["client_id"];
        $finalBillMothDate =$data["bill_year"]."-".$data["bill_month"]."-".'01';

        $data = array();

        $result= PaymentMaster::model()->findAllByAttributes(array('bill_month_date'=>$finalBillMothDate,'client_id'=>$cleint_Id));

        $query = "select pm.bill_month_date , pm.amount_paid from payment_master as pm
                   where pm.client_id ='$cleint_Id'
                 order by pm.bill_month_date DESC
                  limit 1" ;
        $result =Yii::app()->db->createCommand($query)->queryAll();
        if($result){
            $lastmonthPAyment = $result[0];
        }else{
            $lastmonthPAyment = array_values();
        }
        if($result){

            $response = array(
                'code' => 401,
                'company_branch_id' => 0,
                'success' => true,
                'message' => true,
                'data' =>$lastmonthPAyment
            );


        }else{
            $response = array(
                'code' => 401,
                'company_branch_id' => 0,
                'success' => true,
                'message' => false,
                'data' =>$lastmonthPAyment
            );
        }
        $this->sendResponse($response);
    }

    public static function sendSMS($num, $message , $mask , $company_branch_id ,$network_id){

        $messageLength  = strlen($message);
        $countSms = ceil($messageLength/160);
        $companyObject = Company::model()->findByPk(intval($company_branch_id));

        $allreadyExistSMS =  $companyObject['SMS_count'];
        $totalSMS = $allreadyExistSMS + $countSms ;
        $companyObject->SMS_count = $totalSMS ;
        $companyObject->save();
        $number = $num;
        if(substr($num, 0, 2) == "03"){
            $number = '923' . substr($num, 2);
        }else if(substr($num, 0, 1) == "3"){
            $number = '923' . substr($num, 1);
        }else if(substr($num, 0, 2) == "+9"){
            $number =  substr($num, 1);
        }
        // Configuration variables
        $id = "conformiz@bizsms.pk";
        $pass = "c3nuji8uj99";

        $message = urlencode($message);

        if($company_branch_id ==9){
            $_url = 'http://pk.eocean.us/APIManagement/API/RequestAPI?user=Stylo IT&pwd=APzw4c7%2bf0XgoSne8lLeoL7bWMq%2fNHFNFGmy%2bPRkwhElakoluftUyn3Rs4tvYNGDCg%3d%3d&sender=Stylo IT&reciever='.$number.'&msg-data='.$message.'&response=string';
        }elseif($company_branch_id ==13){
            $_url ='http://api.bizsms.pk/api-send-branded-sms.aspx?username=leche@bizsms.pk&pass=1e3chis&text=' .$message .'&masking=LECHE&destinationnum=' . $number . '&language=English';
        }else{
            $_url = 'http://api.bizsms.pk/api-send-branded-sms.aspx?username='.$id.'&pass='.$pass.'&text='.$message.'&masking='.$mask.'&destinationnum='.$number.'&language=English&network='.$network_id;
        }




        if($_result = file_get_contents($_url)) {
            $_result_f = json_decode($_result);
        }else{
            echo "not Send";
        }

    }
    public static function sendSMS_foradmin($num, $message , $mask , $company_branch_id ,$network_id){

        $messageLength  = strlen($message);
        $countSms = ceil($messageLength/160);
        $companyObject = Company::model()->findByPk(intval($company_branch_id));

        $allreadyExistSMS =  $companyObject['SMS_count'];
        $totalSMS = $allreadyExistSMS + $countSms ;
        $companyObject->SMS_count = $totalSMS ;
        $companyObject->save();

        // Configuration variables
        $id = "conformiz@bizsms.pk";
        $pass = "c3nuji8uj99";

        $message = urlencode($message);

        if($company_branch_id ==9) {
            //  $_url = 'http://pk.eocean.us/APIManagement/API/RequestAPI?user=Stylo IT&pwd=APzw4c7%2bf0XgoSne8lLeoL7bWMq%2fNHFNFGmy%2bPRkwhElakoluftUyn3Rs4tvYNGDCg%3d%3d&sender=Stylo IT&reciever='.$num.'&msg-data='.$message.'&response=string';
            $_url = 'https://pk.eocean.us/APIManagement/API/RequestAPI?user=aas&pwd=APlMLww6m7fsXeBeghBtqlM8y5PNk2Rd2ZZCoLc32z27bwkptwBdyIw7o561CyRUZw%3d%3d&sender=AAS&reciever=' . $num . '&msg-data=' . $message . '&response=string';
        }elseif($company_branch_id ==13){
            $_url ='http://api.bizsms.pk/api-send-branded-sms.aspx?username=leche@bizsms.pk&pass=1e3chis&text=' .$message .'&masking=LECHE&destinationnum=' . $num . '&language=English';
        }else{
            $_url = 'http://api.bizsms.pk/api-send-branded-sms.aspx?username='.$id.'&pass='.$pass.'&text='.$message.'&masking='.$mask.'&destinationnum='.$num.'&language=English&network='.$network_id;

        }
        if($_result = file_get_contents($_url)) {
            $_result_f = json_decode($_result);
        }else{
            echo "not Send";
        }
    }

    public function actiongetClientLedgherReport(){
        date_default_timezone_set("Asia/Karachi");
        $post = file_get_contents("php://input");
        $data_get = CJSON::decode($post ,True);
        $year = $data_get['year'];
        $month = $data_get['month'];
        $data = array();
        $d=cal_days_in_month(CAL_GREGORIAN,$month,$year);
        $data['clientID']= $data_get['client_id'];
        $data['startDate']= $year.'-'.$month.'-01';
        $data['endDate']=   $year.'-'.$month.'-'.$d;



        echo clientData::getClientLedgherReportFunction_for_mobile($data);
    }


}
