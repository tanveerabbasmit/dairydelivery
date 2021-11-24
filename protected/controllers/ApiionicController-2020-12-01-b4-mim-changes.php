<?php

class ApiionicController extends Controller
{

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout='//layouts/column2';

    /**
     * @return array action filters
     */

    public $enableCsrfValidation = false;
    public function filters()
    {

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
        header('Content-type: application/json; charset=utf-8');
        header('Content-Type: text/html; charset=utf-8');

        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }



    public function sendResponse($data)
    {

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
        header('Content-type: application/json; charset=utf-8');
        header('Content-Type: text/html; charset=utf-8');


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

       // $post = file_get_contents("php://input");
        //$data = CJSON::decode($post, TRUE);

        $data = $_POST;



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

       // $post = file_get_contents("php://input");
        //$data = CJSON::decode($post, TRUE);

        $data = $_POST;

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

       // $post = file_get_contents("php://input");
        //$data = CJSON::decode($post, TRUE);
        $data = $_POST;
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
       // $post = file_get_contents("php://input");
       // $data = CJSON::decode($post, TRUE);

        $data = $_POST;
        if(!isset($data['userName'])){
             $post = file_get_contents("php://input");
             $data = CJSON::decode($post, TRUE);
        }



        if(!isset($data['zone_id']) ||  empty($data['zone_id'])){
            $data['zone_id'] = '1309';
        }
        if(!isset($data['cnic'])){
            $data['cnic'] ='0';
        }

        if(!isset($data['address'])){
            $data['address'] ='no';
        }



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
            $client->date_of_birth = '0000-00-00';
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
       // $post = file_get_contents("php://input");
       // $data = CJSON::decode($post, TRUE);

        $data = $_POST;

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

                $Companymessage = "A new Customer  has been registered\n\n ".$companyTitle;

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

       // $post = file_get_contents("php://input");
       // $data = CJSON::decode($post, TRUE);

        $data = $_POST;

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

       // $post = file_get_contents("php://input");
       // $data = CJSON::decode($post, TRUE);

        $data = $_POST;

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


                    $Companymessage = "A new Customer ".$fullNameGet." has been registered\n\n ".$companyTitle;


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

       // $post = file_get_contents("php://input");
       // $data = CJSON::decode($post, TRUE);

        $data = $_POST;


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

       // $post = file_get_contents("php://input");
        //$data = CJSON::decode($post, TRUE);

        $data = $_POST;

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

       // $post = file_get_contents("php://input");
       // $data = CJSON::decode($post, TRUE);
        $data = $_POST;

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

       // $post = file_get_contents("php://input");
        //$data = CJSON::decode($post, TRUE);
        $data = $_POST;
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

    /* public function actioncreateComplaints(){
         $post = file_get_contents("php://input");
         $data = CJSON::decode($post, TRUE);
         $complain = new Complain();
         $complain->client_id = $data['client_id'];
         $complain->complain_type_id = $data['complain_type_id'];
         $complain->query_text = $data['query_text'];
         $complain->status_id =3;
         $complain->response = '';
          if($complain->save()){
              $response = array(
                  'code' => 200,
                  'success' =>true,
                  'title' => 'Complain Register',
                  'message'=>'Complaint submitted successfully',
                  'data' =>[],
              );
          }else{
                 $message = $complain->getErrors();
              $response = array(
                  'code' => 404,
                  'success' =>false,
                  'title' => 'Complain Register',
                  'message'=>$message,
                  'data' =>[],
              );
          }

          $this->sendResponse($response);
     }*/

    public function actionViewSpecialorderHistry(){

        date_default_timezone_set("Asia/Karachi");

       // $post = file_get_contents("php://input");
       // $data = CJSON::decode($post, TRUE);
        $data = $_POST;
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


       // $post = file_get_contents("php://input");
        //$data = CJSON::decode($post, TRUE);
        $data = $_POST;
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

       // $post = file_get_contents("php://input");
        //$data = CJSON::decode($post, TRUE);
        $data = $_POST;
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

        //$post = file_get_contents("php://input");
        //$data = CJSON::decode($post, TRUE);
        $data = $_POST;
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

       // $post = file_get_contents("php://input");
        //$data = CJSON::decode($post, TRUE);
        $data = $_POST;
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

       // $post = file_get_contents("php://input");
       // $data = CJSON::decode($post, TRUE);
        $data = $_POST;

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

       // $post = file_get_contents("php://input");
        //$data = CJSON::decode($post, TRUE);
        $data = $_POST;
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

}
