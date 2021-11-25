<?php

class ApiionicController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

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
        echo json_encode($data);
    }

    public function actionDashboard()
    {
        if ($this->isOptionsCall()) {
            return;
        }
        $data = $this->getRequestData();
        $client_id = $data['client_id'];
        /* $client_id =155509;*/

        $query = "SELECT fullname ,company_branch_id from client where client_id = '$client_id'";
        $customerProfile = Yii::app()->db->createCommand($query)->queryAll();

        // See if data is actually loaded
        if (!isset($customerProfile[0]['company_branch_id'])) {
            $this->sendResponse([
                'code' => 401,
                'company_branch_id' => 0,
                'success' => false,
                'message' => 'Please login to continue.',
                'data' => []
            ]);
            return;
        }

        $company_branch_id = $customerProfile[0]['company_branch_id'];

        $order =  clientData::getOrderAgainstClint($client_id);

        $today_date= date("Y-m-d");
        $get_date = '0000-00-00';
        for ($x = 0; $x <= 7; $x++) {

            $select_date = date('Y-m-d', strtotime(+$x.' day', strtotime($today_date)));

            $delivery_date = ionic_api_data::getRiderDialyDeliveryReport_for_ionic_dashborad($client_id,$company_branch_id,date('Y-m-d'));



            if(!isset($delivery_date[0]['regularQuantity'])){
                $delivery_date[0]['regularQuantity'] =0;
            }

            if(!isset($delivery_date[0]['totalSpecialQuantity'])){
                $delivery_date[0]['totalSpecialQuantity'] =0;
            }

            $total =  $delivery_date[0]['regularQuantity'] +$delivery_date[0]['totalSpecialQuantity'];
            if($total>0){
                $get_date = $select_date;
                break;
            }

        }


        $response = $this->makeSuccessResponse((object)[
            'currentBalance' => APIData::calculateFinalBalance($client_id),
            'customerName' => isset($customerProfile[0]['fullname']) ? $customerProfile[0]['fullname'] : '',
            'activeOrders' => sizeof($order),
            'delivery_date' => $get_date,
        ]);

        $this->sendResponse($response);
    }

    public function actionUblpayment()
    {
        $data = $this->getRequestData();

        $finaData = array();
        $finaData['bill_year'] = '2018';
        $finaData['bill_month'] = '5';
        $finaData['amount_paid'] = $data['amount'];
        $finaData['trans_ref_no'] = '52324';
        $finaData['client_id'] = $data['user_id'];
        $finaData['remarks'] = 'hbl';
        $finaData['company_branch_id'] = '2';
        $finaData['payment_mode'] = '2';
        $finaData['startDate'] = '2018-05-20';

        echo  conformPayment::conformPaymentMethodFromPortal(2, $finaData);
    }

    public function actiongetPlanData(){
        if ($this->isOptionsCall()) {
            return;
        }
        $data = $this->getRequestData();

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
        $response = array(
            'code' => 202,
            'company_branch_id'=>0,
            'success' =>true,
            'message'=>'Get Plan Data',
            'data' =>$final_Result,
        );
        $this->sendResponse($response);
    }
    public function actionLogin()
    {
        if ($this->isOptionsCall()) {
            return;
        }

        $data = $this->getRequestData();

        $userName = $data['userName'];
        $password = $data['password'];
        $messaging_token = '';
        $type = '';

        if (isset($data['messaging_token'])) {
            $messaging_token = $data['messaging_token'];
            $type = $data['type'];
        }

        if (!isset($data['company_branch_id'])) {
            $data['company_branch_id'] = 1;
        }

        $company_branch_id =  $data['company_branch_id'];

        if($company_branch_id==18){

            $query = "Select * from  client as c
             where c.userName ='$userName' and 
             c.is_active ='1' and c.is_guest =0 
             and  c.company_branch_id in (18,20,21,22) ";

            $user = Yii::app()->db->createCommand($query)->queryAll();
            if (count($user)>0){
                $is_approved = $user[0]['is_approved'];

                if($is_approved==0){
                    $response = array(
                        'code' => 401,
                        'company_branch_id' => 0,
                        'success' => false,
                        'message' => 'Your account is under approval! You will be sent an sms on your provided phone number as soon as your account is approved.',
                        'data' => []
                    );

                    $this->sendResponse($response);
                    die();
                }

            }
        }else{


            $query = "Select * from  client as c where c.userName ='$userName' and c.is_active ='1' and c.is_guest='0' and  c.company_branch_id = " . $company_branch_id;

        }


        $user = Yii::app()->db->createCommand($query)->queryAll();
        if (count($user) === 0) {
            $response = array(
                'code' => 401,
                'company_branch_id' => 0,
                'success' => false,
                'message' => 'Invalid username',
                'data' => []
            );
        } else {

            if ($user[0]['password'] == $password) {
                $zone_id = $user[0]['zone_id'];
                $rider_Object = utill::getRiderName($zone_id);
                $getDelivery_count = utill::getDelivery_count($user[0]['client_id']);
                $client_id = $user[0]['client_id'];
                $company_branch_id = $user[0]['company_branch_id'];
                if (!empty($messaging_token)) {
                    $object = SaveMessageToken::model()->findByAttributes([
                        'client_id' => $client_id
                    ]);

                    if (!$object) {
                        $object = new SaveMessageToken();
                    }

                    $object->type = $type;
                    $object->messaging_token = $messaging_token;
                    $object->client_id = $client_id;

                    if ($object->save()) {
                    } else {
                        echo "<pre>";
                        print_r($object->getErrors());
                        die();
                    }
                }

                $client_object = Client::model()->findByPk($client_id);
                if ($client_object['is_called_log'] == 0) {
                    if($company_branch_id==4){
                        $client_object->is_called_log = 1;
                        $client_object->is_push_notification = 1;
                        $client_object->is_mobile_notification = 0;
                        $client_object->save();
                    }
                }

                $data = array();
                $data['client_id'] = $user[0]['client_id'];
                $data['rider_name'] = $rider_Object[0]['rider_name'];
                $data['rider_phoneNumber'] = $rider_Object[0]['cell_no_1'];
                $data['fullname'] = $user[0]['fullname'];
                $data['company_branch_id'] = $company_branch_id;
                $data['email'] = $user[0]['email'];
                $data['cell_no_1'] = $user[0]['cell_no_1'];
                $data['total_delivery'] = $getDelivery_count;
                $clientObect = Client::model()->findByPk(intval($data['client_id']));

                $clientObect->LastTime_login = date("Y-m-d H:i");
                $clientObect->save();
                $response = array(
                    'code' => 200,
                    'company_branch_id' =>$company_branch_id,
                    'success' => true,
                    'message' => 'Login successfully.',
                    'data' => $data,
                );
            } else {

                $response = array(
                    'code' => 401,
                    'company_branch_id' => 0,
                    'success' => false,
                    'message' => 'Invalid password ',
                    'data' => []
                );

            }
        }

        $this->sendResponse($response);
    }

    public function actionSaveCustomerNotificationStatus()
    {
        if ($this->isOptionsCall()) {
            return;
        }

        $data = $this->getRequestData();

        $clientID = $data['client_id'];
        $daily_delivery_sms = $data['daily_delivery_sms'];
        $alert_new_product = $data['alert_new_product'];

        $client = Client::model()->findByPk(intval($clientID));


        $client->daily_delivery_sms = $daily_delivery_sms;
        $client->alert_new_product = $alert_new_product;


        if($client->save()){
            $response = array(
                'code' => 200,
                'company_branch_id'=>0,
                'success' => true,
                'message'=>'',
                'data' => $data
            );
        }else{
            $response = array(
                'code' => 402,
                'company_branch_id'=>0,
                'success' => false,
                'message'=>$client->getErrors(),

                'data' => $data
            );
        }





        $this->sendResponse($response);

    }
    public function actionCustomerNotificationStatus()
    {
        if ($this->isOptionsCall()) {
            return;
        }

        $data = $this->getRequestData();

        $clientID = $data['client_id'];

        $client = Client::model()->findByPk(intval($clientID));

        $result = [];
        $result['daily_delivery_sms'] = $client['daily_delivery_sms'];
        $result['alert_new_product'] = $client['alert_new_product'];

        $response = array(
            'code' => 200,
            'company_branch_id'=>0,
            'success' => true,

            'message'=>'',

            'data' => $result
        );

        $this->sendResponse($response);

    }
    public function actionUpdateCustomerProfile()
    {
        if ($this->isOptionsCall()) {
            return;
        }

        $data = $this->getRequestData();

        $clientID = $data['client_id'];
        $client = Client::model()->findByPk(intval($data['client_id']));
        $network_id = 0;

        if (isset($data['network_id'])) {
            $network_id = $data['network_id'];
        }

        $client->user_id = '2';
        $client->fullname = $data['fullname'];
        $client->zone_id = $data['zone_id'];

        $client->network_id = isset($data['network_id']) ? $data['network_id'] : 0;

        $client->email = $data['email'];
        $client->cnic = $data['cnic'];
        $client->cell_no_1 = $data['cell_no_1'];
        $client->address = $data['address'];

        if ($client->save()) {
            $query = "SELECT c.fullname , c.email , c.cnic ,c.cell_no_1 , c.address ,c.zone_id , z.name as zone_name from client as c " .
                "LEFT JOIN zone as z ON c.zone_id =  z.zone_id where c.client_id = '$clientID' ";
            $customerProfile = Yii::app()->db->createCommand($query)->queryAll();
            $response = array(
                'code' => 200,
                'company_branch_id' => 0,
                'success' => true,
                'title' => 'Update profile',
                'message' => 'Profile updated successfully.',
                'data' => $customerProfile[0],
            );
        } else {
            $response = array(
                'code' => 401,
                'company_branch_id' => 0,
                'success' => false,
                'title' => 'forbidden',
                'message' => $client->getErrors(),
                'data' => []
            );
        }

        $this->sendResponse($response);
    }

    public  function actiongetDiscountTypeList()
    {
        $data = $this->getRequestData();

        $company_id = $data['company_branch_id'];
        $query = "SELECT * FROM discount_type WHERE company_id = '$company_id' ";
        $type = Yii::app()->db->createCommand($query)->queryAll();
        $response = array(
            'code' => 200,
            'company_branch_id' => $company_id,
            'data' => $type
        );

        $this->sendResponse($response);
    }

    public function actioncustomerSignUP()
    {

        date_default_timezone_set("Asia/Karachi");

        $data = $this->getRequestData();
        if (!isset($data['userName'])) {
            $post = file_get_contents("php://input");
            $data = CJSON::decode($post, TRUE);
        }
        if (!isset($data['zone_id']) ||  empty($data['zone_id'])) {
            $data['zone_id'] = '1309';
        }
        if (!isset($data['cnic'])) {
            $data['cnic'] = '0';
        }

        if (!isset($data['address'])) {
            $data['address'] = 'no';
        }

        $userName =   $data['userName'];
        $userPhoneNumber = $data['cell_no_1'];
        if (!isset($data['company_branch_id'])) {
            $data['company_branch_id'] = 1;
        }

        $company_branch_id =  $data['company_branch_id'];
        $companyObject  =  utill::get_companyTitle($data['company_branch_id']);
        $companyMask = $companyObject['sms_mask'];
        $companyTitle = $companyObject['company_title'];
        $unregister =  Client::model()->findByAttributes(array('cell_no_1' => $userPhoneNumber, 'is_active' => '0', 'company_branch_id' => $company_branch_id));

        if ($unregister) {
            try {
                $unregister->delete();
            } catch (Exception $e) {
                $IDArray = array();
                $IDArray['client_id'] = "";
                $response = array(
                    'code' => 401,
                    'company_branch_id' => 0,
                    'success' => false,
                    'alreadyExists' => false,
                    'message' => "This Phone Number Already Register",
                    'data' => $IDArray
                );

                $this->sendResponse($response);
                die();
            }
        }

        $checkUserName = Client::model()->findByAttributes(array('userName' => $userName, 'company_branch_id' => $company_branch_id));

        $checkUserPhoneNumber = Client::model()->findByAttributes(array('cell_no_1' => $userPhoneNumber, 'company_branch_id' => $company_branch_id));

        if ($checkUserName) {
            $IDArray = array();
            $IDArray['client_id'] = "";
            $response = array(
                'code' => 401,
                'company_branch_id' => 0,
                'success' => false,
                'alreadyExists' => true,
                'message' => 'This username already exists . Try another username',
                'data' => $IDArray
            );
        } elseif ($checkUserPhoneNumber) {

            $IDArray = array();
            $IDArray['client_id'] = "";
            $response = array(
                'code' => 401,
                'company_branch_id' => 0,
                'success' => false,
                'alreadyExists' => true,
                'message' => 'This phone No. already exists . Try another phone No.',
                'data' => $IDArray,
            );
        } else {
            $network_id = 0;
            if (isset($data['network_id'])) {
                $network_id = $data['network_id'];
            }
            $client = new Client();
            $client->user_id = '2';
            $client->zone_id = $data['zone_id'];
            $client->network_id = $network_id;
            $client->fullname = $data['fullName'];
            $client->userName = $data['userName'];
            $client->password = $data['password'];

            $client->latitude = isset($data['latitude'])?$data['latitude']:'0';
            $client->longitude =isset($data['longitude'])?$data['longitude']:'0';

            $client->company_branch_id = $data['company_branch_id'];
            $client->father_or_husband_name = ' ';
            $client->date_of_birth = '0000-00-00';
            $client->email = $data['email'];
            $client->cnic = $data['cnic'];
            $client->cell_no_1 =validate_phone_number_with_code::validate_phone_number($data['cell_no_1']);
            $client->cell_no_2 = '12334543';
            $client->residence_phone_no = '1234567';
            $client->city = 'no';
            $client->area = 'no';
            $client->address = $data['address'];
            $client->is_active = 0;
            $client->is_deleted = 0;
            $client->created_by = '2';
            $client->updated_by = '2';
            $client->login_form = '2';
            $client->view_by_admin = '1';

            $client->is_approved = '0';

            $client->created_at = date("Y-m-d H:i:s");
            $client->new_create_date = date("Y-m-d");

            $client->is_mobile_notification = '1';


            if ($client->save()) {
                $clientID = $client->client_id;
                $code = sprintf("%04s", mt_rand(0000, 9999));
                $authenticate = new Authentication();
                $authenticate->client_id = $clientID;
                $authenticate->code = $code;
                $authenticate->SetTime = time();

                if ($authenticate->save()) {
                    $IDArray = array();
                    $IDArray['client_id'] = $clientID;
                    $response = array(
                        'code' => 200,
                        'company_branch_id' => 0,
                        'success' => true,
                        'alreadyExists' => false,
                        'message' => "We've sent and SMS on your number. Please enter the verification code below to complete the sign-up process.
                                       Please allow up to a minute for your SMS to arrive.",
                        'data' => $IDArray,
                    );

                    if($data['company_branch_id'] ==18){


                        $message =   'New customer signup, please approve customer.';
                        smsLog::saveSms($clientID, $company_branch_id, '+923214667127', 'Company Admin', $message);
                        utill::sendSMS2('+923214667127', $message, $companyMask, $company_branch_id, 1, $clientID);


                    }else{
                        $message = "Your verification code for  is " . $code;
                        $message =   ' Your code is ' . $code;
                        smsLog::saveSms($clientID, $company_branch_id, $data['cell_no_1'], $data['fullName'], $message);
                        utill::sendSMS2($data['cell_no_1'], $message, $companyMask, $company_branch_id, 1, $clientID);
                    }

                    // $this->sendSMS($data['cell_no_1'],$message , $companyMask , $company_branch_id ,$network_id);
                }
            } else {

                $IDArray = array();
                $IDArray['client_id'] = "";
                $response = array(
                    'code' => 401,
                    'company_branch_id' => 0,
                    'success' => false,
                    'alreadyExists' => false,
                    'message' => $client->getErrors(),
                    'data' => $IDArray
                );
            }
        }

        $this->sendResponse($response);
    }

    public function actioncustomerSignUp_company12()
    {
        $data = $this->getRequestData();

        $userPhoneNumber = $data['cell_no_1'];
        if (!isset($data['company_branch_id'])) {
            $data['company_branch_id'] = 1;
        }

        $company_branch_id =  $data['company_branch_id'];
        $checkUserName = Client::model()->findByAttributes(array('userName' => $userPhoneNumber, 'company_branch_id' => $company_branch_id));
        if ($checkUserName) {
            $IDArray = array();
            $IDArray['client_id'] = "";
            $response = array(
                'code' => 401,
                'company_branch_id' => 0,
                'success' => false,
                'alreadyExists' => true,
                'message' => 'This username already exists . Try another username',
                'data' => $IDArray
            );
        } else {
            $client = new Client();
            $client->user_id = '2';
            $client->zone_id = '482';
            $client->network_id = 1;
            $client->fullname = $userPhoneNumber;
            $client->userName = $userPhoneNumber;
            $client->password = $userPhoneNumber;
            $client->company_branch_id = $data['company_branch_id'];
            $client->father_or_husband_name = ' ';
            $client->date_of_birth = '2018-02-01';
            $client->email = ' ';
            $client->cnic = " ";
            $client->cell_no_1 = $data['cell_no_1'];
            $client->cell_no_2 = '12334543';
            $client->residence_phone_no = '1234567';
            $client->city = 'no';
            $client->area = 'no';
            $client->address = '';
            $client->is_active = 0;
            $client->is_deleted = 0;
            $client->created_by = '2';
            $client->updated_by = '2';
            $client->login_form = '2';
            $client->view_by_admin = '1';

            if ($client->save()) {
                $clientID = $client->client_id;
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
                $Companymessage = "A new Customer  has been registered\n\n " . $companyTitle;
                smsLog::saveSms($clientID, $company_branch_id, $phoneNo, $userPhoneNumber, $Companymessage);
                $this->sendSMS($phoneNo, $Companymessage, $companyMask, $company_branch_id, 1);
            } else {
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

    public function actionresendCode()
    {
        $data = $this->getRequestData();

        if (!isset($data['company_branch_id'])) {
            $data['company_branch_id'] = 1;
        }

        $company_branch_id =  $data['company_branch_id'];
        $companyObject  =  utill::get_companyTitle($data['company_branch_id']);
        $companyMask = $companyObject['sms_mask'];
        $companyTitle = $companyObject['company_title'];
        $client_id = $data['client_id'];
        $clientObject = Client::model()->findByPk(intval($client_id));
        $cellNO = $clientObject['cell_no_1'];
        $authentication = Authentication::model()->findByAttributes(array('client_id' => $client_id));
        $code =  $authentication['code'];
        $IDArray = array();
        $IDArray['client_id'] = '';
        $response = array(
            'code' => 200,
            'company_branch_id' => 0,
            'success' => true,
            'message' => "We've resent and SMS on your number. Please enter the verification code below to complete the sign-up process.
                                       Please allow up to a minute for your SMS to arrive.",
            'data' => $IDArray,
        );

        $message = "Your verification code for " . $companyTitle . " is " . $code;
        $fullname = $clientObject['fullname'];
        $network_id = $clientObject['network_id'];
        smsLog::saveSms($client_id, $company_branch_id, $cellNO, $fullname, $message);
        $this->sendSMS($cellNO, $message, $companyMask, $company_branch_id, $network_id);
        $this->sendResponse($response);
    }

    public function actionCustomer_activation_by_code(){

        date_default_timezone_set("Asia/Karachi");

        // $post = file_get_contents("php://input");
        // $data = CJSON::decode($post , true);

        $data = $this->getRequestData();

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
                $client->is_active = 1;
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

                $Companymessage = "A new Customer ".$fullNameGet." has been registered\n\n\ ".$companyTitle;
                $fullname = "Admin";
                $client_id = '1';

                if($company_branch_id ==1){
                    $phoneNo = '+923021118292';
                    smsLog::saveSms($client_id ,$company_branch_id ,$phoneNo ,$fullname ,$Companymessage);
                    $this->sendSMS($phoneNo , $Companymessage , $companyMask ,$company_branch_id , $network_id);

                    $phoneNo = '+923341118292';
                    smsLog::saveSms($client_id ,$company_branch_id ,$phoneNo ,$fullname ,$Companymessage);
                    $this->sendSMS($phoneNo , $Companymessage , $companyMask ,$company_branch_id , $network_id);
                }
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
    public function actioncustomerActivationByCodeAndPhoneNo()
    {
        $data = $this->getRequestData();

        $IDArray = array();
        $IDArray['client_id'] = '';
        if (!isset($data['company_branch_id'])) {
            $data['company_branch_id'] = 1;
        }

        $company_branch_id = $data['company_branch_id'];
        $cell_no_1 = $data['cell_no_1'];
        $code = $data['code'];

        $clientOBject = Client::model()->findByAttributes(array('cell_no_1' => $cell_no_1,  'company_branch_id' => $company_branch_id));
        if ($clientOBject) {
            $client_id = $clientOBject['client_id'];
            $client_Object = Client::model()->findByPk(intval($client_id));
            $fullNameGet = $client_Object['fullname'];
            $codeObject = Authentication::model()->findByAttributes(array('client_id' => $client_id), array('limit' => 1,   'order' => 'authentication_id desc',));

            if ($codeObject) {
                $registerTIme = $codeObject['SetTime'];
                $currentTime = Time();
                if (($currentTime - $registerTIme) < 5000) {
                    $response = array(
                        'code' => 200,
                        'company_branch_id' => 0,
                        'success' => false,
                        'message' => "Your code is expired Kindly register again",
                        'data' => $IDArray,
                    );
                } else {
                    $clientOBject->is_active = 1;
                    if ($clientOBject->save()) {
                        $response = array(
                            'code' => 200,
                            'company_branch_id' => 0,
                            'success' => true,
                            'message' => "You are registered successfully',",
                            'data' => $IDArray,
                        );
                    }

                    $companyObject  =  utill::get_companyTitle($data['company_branch_id']);
                    $companyMask = $companyObject['sms_mask'];
                    $companyTitle = $companyObject['company_title'];
                    $companyObject = Company::model()->findByPk(intval($data['company_branch_id']));
                    $phoneNo = $companyObject['phone_number'];
                    $network_id = $clientOBject['network_id'];
                    $Companymessage = "A new Customer " . $fullNameGet . " has been registered\n\n " . $companyTitle;
                    $fullname = $clientOBject['fullname'];

                    if ($company_branch_id == 10) {
                        smsLog::saveSms($client_id, $company_branch_id, "+923415009999", $fullname, $Companymessage);
                        $this->sendSMS($phoneNo, $Companymessage, $companyMask, $company_branch_id, $network_id);
                        smsLog::saveSms($client_id, $company_branch_id, "+923209999688", $fullname, $Companymessage);
                        $this->sendSMS($phoneNo, $Companymessage, $companyMask, $company_branch_id, $network_id);
                    } else {
                        smsLog::saveSms($client_id, $company_branch_id, $phoneNo, $fullname, $Companymessage);
                        $this->sendSMS($phoneNo, $Companymessage, $companyMask, $company_branch_id, $network_id);
                    }
                }
            } else {
                $response = array(
                    'code' => 200,
                    'company_branch_id' => 0,
                    'success' => true,
                    'message' => "You are not registered',",
                    'data' => $IDArray,
                );
            }
        } else {
            $response = array(
                'code' => 200,
                'company_branch_id' => 0,
                'success' => false,
                'message' => "Count,t find your Acount",
                'data' => $IDArray,
            );
        }
        $this->sendResponse($response);
    }



    public function actioncreateSpecialOrder()
    {

        if ($this->isOptionsCall()) {
            return;
        }

        $data = $this->getRequestData();



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

        if ($specialOrdre->save()) {
            $response = $this->makeSuccessResponse();
        } else {
            $response = array(
                'code ' => 404,
                'company_branch_id' => 0,
                'success' => false,
                'title' => 'Add Client',
                'message' => $specialOrdre->getErrors(),
                'data' => [],
            );
            $response = $this->makeFailureResponse();
        }

        $this->sendResponse($response);
    }

    // make Schedual
    public  function actionMakeSchedual()
    {
        if ($this->isOptionsCall()) {
            return;
        }
        $data = $this->getRequestData();

        $clientID = $data['clientID'];
        $productID = $data['productID'];
        $orderStartDate = $data['orderStartDate'];
        $dayObject = $data['dayObject'];
        $today_date = date("Y-m-d");



        if ($orderStartDate > $today_date) {

            $clientFrequency =  ClientProductFrequency::model()->findByAttributes(array('client_id' => $clientID, 'product_id' => $productID));
            $clientFFID = (($clientFrequency['client_product_frequency']));
            ClientProductFrequencyQuantity::model()->deleteAllByAttributes(array('client_product_frequency_id' => $clientFFID));
            if (isset($clientFrequency)) {
                // $clientFrequency->delete();
                $client_product_frequency = $clientFrequency['client_product_frequency'];
                $clientFrequency->orderStartDate = $orderStartDate;
                $clientFrequency->save();
            } else {
                $ClientProductFrequency = new ClientProductFrequency();
                $ClientProductFrequency->client_id = $clientID;
                $ClientProductFrequency->product_id = $productID;
                $ClientProductFrequency->quantity = '0';
                $ClientProductFrequency->total_rate = '0';
                $ClientProductFrequency->frequency_id = '1';
                $ClientProductFrequency->orderStartDate = $orderStartDate;
                $ClientProductFrequency->save();
                $client_product_frequency = $ClientProductFrequency['client_product_frequency'];
            }

            foreach ($dayObject as $value) {
                if ($value['slectDayForProducy']) {
                    $daySave = new ClientProductFrequencyQuantity();
                    $daySave->client_product_frequency_id = $client_product_frequency;
                    $daySave->frequency_id = $value['frequency_id'];
                    $daySave->quantity = $value['quantity'];
                    $daySave->preferred_time_id = $value['preferred_time_id'];
                    $daySave->save();
                }
            }


            $clientSchedulerObject  = IntervalScheduler::model()->findByAttributes(array('client_id'=>$clientID , 'product_id'=>$productID));
            if($clientSchedulerObject){
                $clientSchedulerObject->delete();
            }

            $response = $this->makeSuccessResponse();

            $clientObject = Client::model()->findByPk(intval($clientID));
            $company_branch_id = $clientObject['company_branch_id'];
            $phoneNo = $clientObject['cell_no_1'];
            $fullname = $clientObject['fullname'];
            $network_id = $clientObject['network_id'];
            $companyObject = Company::model()->findByPk(intval($company_branch_id));
            $companyMask =    $companyObject['sms_mask'];
            $phoneNo =    $companyObject['phone_number'];
            $Companymessage = $fullname . " have changed schedule. ";

            if ($company_branch_id == 10) {
                smsLog::saveSms($clientID, $company_branch_id, "+923415009999", $fullname, $Companymessage);
                $this->sendSMS_foradmin($phoneNo, $Companymessage, $companyMask, $company_branch_id, $network_id);
                smsLog::saveSms($clientID, $company_branch_id, "+923209999688", $fullname, $Companymessage);
                $this->sendSMS_foradmin($phoneNo, $Companymessage, $companyMask, $company_branch_id, $network_id);
            } else {
                smsLog::saveSms($clientID, $company_branch_id, $phoneNo, $fullname, $Companymessage);
                $this->sendSMS_foradmin($phoneNo, $Companymessage, $companyMask, $company_branch_id, $network_id);
            }

            $this->sendResponse($response);
        } else {
            $EffectiveDateSchedule = EffectiveDateSchedule::model()->findByAttributes(
                array('client_id' => $clientID, 'product_id' => $productID)
            );

            $effective_date_schedule_id = $EffectiveDateSchedule['effective_date_schedule_id'];
            EffectiveDateScheduleFrequency::model()->deleteAllByAttributes(
                array('effective_date_schedule_id' => $effective_date_schedule_id)
            );

            if ($EffectiveDateSchedule) {
                $EffectiveDateSchedule->delete();
            }

            $effective_date_schedual = new EffectiveDateSchedule();
            $effective_date_schedual->client_id = $clientID;
            $effective_date_schedual->product_id = $productID;
            $effective_date_schedual->date = $orderStartDate;
            if ($effective_date_schedual->save()) {
                $effective_date_schedule_id = $effective_date_schedual->effective_date_schedule_id;
                foreach ($dayObject as $value) {
                    if ($value['slectDayForProducy']) {
                        $effective_date_schedule_frequency = new EffectiveDateScheduleFrequency();
                        $effective_date_schedule_frequency->effective_date_schedule_id = $effective_date_schedule_id;
                        $effective_date_schedule_frequency->frequency_id = $value['frequency_id'];
                        $effective_date_schedule_frequency->quantity = $value['quantity'];
                        if ($effective_date_schedule_frequency->save()) {
                        } else {
                        }
                    }
                }
            }

            $response = $this->makeSuccessResponse();

            $this->sendResponse($response);
        }
    }

    /**
     * @return array
     */
    public function actiondeActiveCustomerAccount()
    {
        if ($this->isOptionsCall()) {
            return;
        }
        $data = $this->getRequestData();

        $customer = Client::model()->findByPk(intval($data['client_id']));
        if ($customer) {
            $customer->is_active = 0;

            if ($customer->save()) {
                $response = array(
                    'code' => 200,
                    'company_branch_id' => 0,
                    'success' => true,
                    'title' => 'DeActive Account',
                    'message' => 'deactive Account successfully',
                    'data' => [],
                );
            }
        } else {
            $response = array(
                'code' => 404,
                'company_branch_id' => 0,
                'success' => false,
                'title' => 'Deactive Account',
                'message' => 'deactive Account fail',
                'data' => [],
            );
        }

        $this->sendResponse($response);
    }

    public function actionActiveAccount()
    {
        if ($this->isOptionsCall()) {
            return;
        }
        $data = $this->getRequestData();

        $customer = Client::model()->findByPk(intval($data['customerId']));

        if ($customer) {
            $customer->is_active = 1;
            if ($customer->save()) {
                $response = array(
                    'code' => 200,
                    'company_branch_id' => 0,
                    'success' => true,
                    'title' => 'Active Account',
                    'message' => 'Active Account successfully',
                    'data' => [],
                );
            }
        } else {
            $response = array(
                'code' => 200,
                'company_branch_id' => 0,
                'success' => true,
                'title' => 'Active Account',
                'message' => 'Active Account successfully',
                'data' => [],
            );
        }

        $this->sendResponse($response);
    }



    public function actionViewSpecialorderHistry()
    {
        if ($this->isOptionsCall()) {
            return;
        }
        $data = $this->getRequestData();

        $clientID = $data['customerId'];
        $specialOrderList = SpecialOrder::model()->findAllByAttributes(array('client_id' => $clientID));
        $resultList = array();

        foreach ($specialOrderList as $value) {
            $resultList[] = $value->attributes;
        }

        if (count($specialOrderList) == '0') {
            $response = array(
                'code' => 202,
                'company_branch_id' => 0,
                'success' => true,
                'title' => 'Special List',
                'message' => 'There is no special order ',
                'data' => $resultList,
            );
        } else {
            $response = array(
                'code' => 202,
                'company_branch_id' => 0,
                'success' => true,
                'title' => 'Special List',
                'message' => 'Spcial Order List',
                'data' => $resultList,
            );
        }

        $this->sendResponse($response);
    }

    public function actiongetZoneList()
    {
        if ($this->isOptionsCall()) {
            return;
        }
        $data = $this->getRequestData();

        if (!isset($data['company_branch_id'])) {
            $data['company_branch_id'] = 1;
        }

        $company_branch_id = $data['company_branch_id'];

        $response = array(
            'code' => 202,
            'company_branch_id' => 0,
            'success' => true,
            'message' => 'zone list',
            'data' => $this->getZoneList($company_branch_id),
        );

        $this->sendResponse($response);
    }

    public function actiongetAllPlan()
    {
        if ($this->isOptionsCall()) {
            return;
        }

        $data = $this->getRequestData();

        $clientID  = $data['client_id'];
        //   $clientID  =1262532;
        $todaydate = date("Y-m-d");

        $query = "Select p.product_id ,p.name as product_name ,IFNULL(cpp.price ,p.price) as price , p.unit ,'regular' as order_type 
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



        if (sizeof($productResuslt) == 0) {
            $query = " SELECT 
                    0 AS is_halt,
                    p.product_id,
                    p.price,
                    p.unit,
                    e.start_interval_scheduler AS start_date,
                    p.name AS product_name
                    FROM effective_date_interval_schedule AS e
                    LEFT JOIN product AS p ON e.product_id = p.product_id
                    WHERE e.client_id = '$clientID'   ";
            $productResuslt = Yii::app()->db->createCommand($query)->queryAll();
        }
        $response = array(
            'code' => 202,
            'company_branch_id' => 0,
            'success' => true,
            'message' => 'All Plans',
            'data' => ($productResuslt),
        );

        $this->sendResponse($response);
    }

    public function actiongetAllProduct()
    {
        if ($this->isOptionsCall()) {
            return;
        }

        $data = $this->getRequestData();

        $client_id = $data['client_id'];
        $todaydate = date("Y-m-d");
        if (!isset($data['company_branch_id'])) {
            $data['company_branch_id'] = 1;
        }

        $company_branch_id = $data['company_branch_id'];
        $query = "Select p.product_id,p.description ,p.name as product_name, p.image, p.order_type, IFNULL(cpp.price , p.price) as price , p.unit
              ,  IFNULL(cpf.client_id, 0) as is_selected , iFNULL(hro.client_id , 0) as is_halt from product as p
                LEFT JOIN halt_regular_orders as hro ON hro.product_id = p.product_id AND hro.client_id = '$client_id' AND '$todaydate' between hro.start_date and hro.end_date
                left join client_product_price as cpp ON cpp.client_id ='$client_id' AND cpp.product_id = p.product_id
                left join client_product_frequency as cpf ON p.product_id = cpf.product_id and cpf.client_id ='$client_id'
                where  p.company_branch_id = $company_branch_id and p.bottle ='0' and p.is_active =1
                 group by p.product_id ";

        $productResuslt = Yii::app()->db->createCommand($query)->queryAll();

        //  var_dump($productResuslt);

        $productObject = array();
        foreach ($productResuslt as $productValue) {
            $product_id = $productValue['product_id'];
            $oneProductOject = array();
            $oneProductOject['product_id'] = $productValue['product_id'];
            $oneProductOject['product_name'] = $productValue['product_name'];
            $oneProductOject['description'] = $productValue['description'];
            $oneProductOject['product_image'] = 'https://dairydelivery.conformiz.com/themes/milk/images/product/' . $productValue['image'];
            $oneProductOject['order_type'] = $productValue['order_type'];
            $oneProductOject['price'] = $productValue['price'];
            $oneProductOject['unit'] = $productValue['unit'];
            $oneProductOject['is_selected'] = $productValue['is_selected'];
            $oneProductOject['is_halt'] = $productValue['is_halt'];
            $oneProductOject['regular_order_type'] = APIData::api_getCustomerRegularOrderType($client_id, $product_id);
            $productObject[] = $oneProductOject;

        }

        $response = array(
            'code' => 202,
            'company_branch_id' => 0,
            'success' => true,
            'message' => 'All products',
            'data' => ($productObject),
        );

        $this->sendResponse($response);
    }

    public function actiongetAllProduct_withPlans()
    {
        if ($this->isOptionsCall()) {
            return;
        }
        $data = $this->getRequestData();

        $client_id = $data['client_id'];
        $todaydate = date("Y-m-d");
        if (!isset($data['company_branch_id'])) {
            $data['company_branch_id'] = 1;
        }

        $company_branch_id = $data['company_branch_id'];

        $spacial_order_query = "select so.client_id ,p.name as product_name ,so.quantity , p.order_type , so.product_id ,ifnull(cpp.price , p.price) as price
                , so.start_date ,so.end_date , '3' as regular_order_type ,so.start_date ,so.end_date   from special_order as so
                left join product as p ON p.product_id = so.product_id
                left join client_product_price as cpp ON cpp.client_id = '$client_id' and cpp.product_id = p.product_id
                where so.client_id = '$client_id' and so.end_date >= '$todaydate' ";

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

        foreach ($productResuslt as $productValue) {
            $product_id = $productValue['product_id'];
            $oneProductOject = array();
            $oneProductOject['product_id'] = $productValue['product_id'];
            $oneProductOject['product_name'] = $productValue['product_name'];
            $oneProductOject['order_type'] = $productValue['order_type'];
            $oneProductOject['price'] = $productValue['price'];
            $oneProductOject['unit'] = $productValue['unit'];
            $oneProductOject['is_selected'] = $productValue['is_selected'];
            $oneProductOject['is_halt'] = APIData::api_getCustomerHalt_OR_Resume($client_id, $product_id, $todaydate);
            $oneProductOject['regular_order_type'] = APIData::api_getCustomerRegularOrderType($client_id, $product_id);
            $productObject[] = $oneProductOject;
        }

        foreach ($spacial_order_result as $spacial_value) {
            $productObject[] = $spacial_value;
        }

        $response = array(
            'code' => 202,
            'company_branch_id' => 0,
            'success' => true,
            'message' => 'All products',
            'data' => ($productObject),
        );

        $this->sendResponse($response);
    }

    public function actiongetComplainType()
    {
        if ($this->isOptionsCall()) {
            return;
        }
        $data = $this->getRequestData();
        $company_branch_id = $data['company_branch_id'];
        $query = "select * from complain_type as c
             where c.company_branch_id ='$company_branch_id'";
        $complainList = Yii::app()->db->createCommand($query)->queryAll();
        $response = $this->makeSuccessResponse($complainList);
        $this->sendResponse($response);
    }

    public function actiongetCustomerProfile()
    {

        if ($this->isOptionsCall()) {
            return;
        }

        $data = $this->getRequestData();

        $clientID =  $data['client_id'];
        $query = "SELECT c.client_id, c.fullname , c.email , c.cnic , c.address ,c.zone_id , z.name as zone_name ,c.cell_no_1 from client as c
                      LEFT JOIN zone as z ON c.zone_id =  z.zone_id
                      where c.client_id = '$clientID' ";

        $customerProfile = Yii::app()->db->createCommand($query)->queryAll();

        $response = array(
            'code' => 202,
            'company_branch_id' => 0,
            'success' => true,
            'message' => 'Customer Profile',
            'data' => (object)[
                'zones' => $this->getZoneList($data['company_branch_id']),
                'profile' => $customerProfile[0],
            ],
        );

        $this->sendResponse($response);
    }

    public function actionsaveIntervalScheduler()
    {
        if ($this->isOptionsCall()) {
            return;
        }

        $data = $this->getRequestData();

        $start_interval_scheduler = $data['start_interval_scheduler'];

        $today_date = date("Y-m-d");

        $client_id = $data['client_id'];
        $product_id = $data['product_id'];

        if ($start_interval_scheduler <= $today_date) {




            $interval_Objec = EffectiveDateIntervalSchedule::model()->findByAttributes(
                array(
                    'client_id' => $client_id,
                    'product_id' => $product_id,
                )
            );
            if ($interval_Objec) {
                $interval_Objec->delete();
            }




            $intervalSchedule = IntervalScheduler::model()->findByAttributes(array("client_id" => $client_id, "product_id" => $product_id));
            if ($intervalSchedule) {
            } else {
                $intervalSchedule = new IntervalScheduler();
            }

            $intervalSchedule->client_id = $data['client_id'];
            $intervalSchedule->product_id = $data['product_id'];
            $intervalSchedule->interval_days = $data['interval_days'];
            $intervalSchedule->product_quantity = $data['product_quantity'];
            $intervalSchedule->start_interval_scheduler = $data['start_interval_scheduler'];
            $intervalSchedule->is_halt = 1;
            $intervalSchedule->halt_start_date   = date('Y-m-d', strtotime(' -1 day'));
            $intervalSchedule->halt_end_date   = date('Y-m-d', strtotime(' -1 day'));

            if ($intervalSchedule->save()) {

                $clientFrequency =  ClientProductFrequency::model()->findByAttributes(array('client_id'=>$client_id , 'product_id'=>$product_id));
                if($clientFrequency){

                    $clientFFID = (($clientFrequency['client_product_frequency']));

                    ClientProductFrequencyQuantity::model()->deleteAllByAttributes(array('client_product_frequency_id'=>$clientFFID));
                    $clientFrequency->delete();
                }

                $response = $this->makeSuccessResponse();
            } else {
                $response = $this->makeFailureResponse($intervalSchedule->getErrors());
            }

            $clientObject = Client::model()->findByPk(intval($client_id));

            $company_branch_id = $clientObject['company_branch_id'];
            $savechagescheduler = new ChangeSchedulerRecord();
            $savechagescheduler->client_id = $client_id;
            $savechagescheduler->company_id = $company_branch_id;
            $savechagescheduler->date = date("Y-m-d");
            $savechagescheduler->change_form = 2;
            $savechagescheduler->save();

            $this->sendResponse($response);
        } else {

            $intervalSchedule = IntervalScheduler::model()->findByAttributes(array("client_id" => $client_id, "product_id" => $product_id));
            if ($intervalSchedule) {
                $intervalSchedule->delete();
            }

            $client_id = $data['client_id'];
            $product_id = $data['product_id'];
            $start_interval_scheduler = $data['start_interval_scheduler'];
            $interval_Objec = EffectiveDateIntervalSchedule::model()->findByAttributes(
                array(
                    'client_id' => $client_id,
                    'product_id' => $product_id,
                )
            );

            if ($interval_Objec) {

                $interval_Objec->product_quantity = $data['product_quantity'];
                $interval_Objec->interval_days = $data['interval_days'];
                $interval_Objec->start_interval_scheduler = $start_interval_scheduler;
                $interval_Objec->save();
            } else {

                $effective_date_interval_schedule = new EffectiveDateIntervalSchedule();
                $effective_date_interval_schedule->client_id = $client_id;
                $effective_date_interval_schedule->product_id = $product_id;
                $effective_date_interval_schedule->start_interval_scheduler = $start_interval_scheduler;
                $effective_date_interval_schedule->interval_days = $data['interval_days'];
                $effective_date_interval_schedule->product_quantity = $data['product_quantity'];
                $effective_date_interval_schedule->save();
            }

            $response = $this->makeSuccessResponse();

            $this->sendResponse($response);
        }
    }

    public function actionchangeCustomerPassword()
    {

        if ($this->isOptionsCall()) {
            return;
        }

        $data = $this->getRequestData();

        $clientID = $data['client_id'];
        $oldPassword = $data['oldPassword'];
        $newPassword = $data['newPassword'];
        $clientObjct = Client::model()->findByPk(intval($clientID));
        if ($clientObjct) {
            $alreadyPassword =  $clientObjct['password'];
            if ($alreadyPassword == $oldPassword) {
                $clientObjct->password = $newPassword;
                if ($clientObjct->save()) {
                    $response = array(
                        'company_branch_id' => 0,
                        'code' => 200,
                        'success' => true,
                        'message' => 'You have changed password successfully',
                        'data' => []
                    );
                } else {
                }
            } else {
                $response = array(
                    'company_branch_id' => 0,
                    'code' => 200,
                    'success' => false,
                    'message' => 'Old password is wrong',
                    'data' => []
                );
            }
        } else {
            $response = array(
                'company_branch_id' => 0,
                'code' => 200,
                'success' => false,
                'message' => 'This user is not registered',
                'data' => []
            );
        }

        $this->sendResponse($response);
    }


    public function actionresumeIntervalHaltSchedular()
    {

        if ($this->isOptionsCall()) {
            return;
        }

        $data = $this->getRequestData();



        $client_id = $data['client_id'];
        $product_id = $data['product_id'];
        $intervalSchedule = IntervalScheduler::model()->findByAttributes(array("client_id" => $client_id, "product_id" => $product_id));
        if ($intervalSchedule) {

            $intervalSchedule->halt_start_date   = date('Y-m-d', strtotime(' -1 day'));
            $intervalSchedule->halt_end_date   = date('Y-m-d', strtotime(' -1 day'));
            $intervalSchedule->save();

            $response = array(
                'code' => 401,
                'company_branch_id' => 0,
                'success' => true,
                'message' => 'Resume Successfully',
            );
        } else {

            $response = array(
                'code' => 401,
                'company_branch_id' => 0,
                'success' => false,
                'message' => 'Resume Not Successfully',
            );
        }




        $this->sendResponse($response);
    }


    public function actioncancelSpecialOrder()
    {
        if ($this->isOptionsCall()) {
            return;
        }

        $data = $this->getRequestData();
        $client_id = $data['client_id'];
        $product_id = $data['product_id'];
        SpecialOrder::model()->deleteAllByAttributes(array('client_id' => $client_id, 'product_id' => $product_id));
        $sendData = array();
        $sendData['client_id'] = '';
        $response = array(
            'company_branch_id' => 0,
            'code' => 200,
            'success' => true,
            'message' => 'You special order have cancel successfully',
            'data' => $sendData
        );

        $this->sendResponse($response);
    }

    public function actionHaltRegularOrders()
    {

        if ($this->isOptionsCall()) {
            return;
        }

        $data = $this->getRequestData();

        if (!isset($data['company_branch_id'])) {
            $data['company_branch_id'] = 1;
        }
        $company_branch_id = $data['company_branch_id'];

        $companyObject  =  utill::get_companyTitle($data['company_branch_id']);
        $companyMask = $companyObject['sms_mask'];
        $companyTitle = $companyObject['company_title'];

        $clientID =   $data['client_id'];
        $haltregularOrder = new HaltRegularOrders();
        $haltregularOrder->client_id = $data['client_id'];
        $haltregularOrder->product_id = $data['product_id'];
        $haltregularOrder->start_date = $data['start_date'];
        $haltregularOrder->end_date = $data['end_date'];
        if ($haltregularOrder->save()) {
            $sendData = array();
            $sendData['client_id'] = '';
            $response = array(
                'company_branch_id' => 0,
                'code' => 200,
                'success' => true,
                'message' => 'You regular order have halt successfully',
                'data' => $sendData
            );
        }
        $clientObject = Client::model()->findByPk(intval($clientID));
        $phoneNo =  $clientObject['cell_no_1'];
        $fullname = $clientObject['fullname'];
        $network_id = $clientObject['network_id'];

        $message = " Your regular order have been halted successfully. Please note that your regular delivery will automatically resume after the halt period is over.
          \n\n" . $companyTitle;
        smsLog::saveSms($clientID, $company_branch_id, $phoneNo, $fullname, $message);
        $this->sendSMS($phoneNo, $message, $companyMask, $company_branch_id, $network_id);

        $this->sendResponse($response);
    }

    public function actionCancelRegularOrder()
    {

        if ($this->isOptionsCall()) {
            return;
        }

        $data = $this->getRequestData();


        $company_branch_id = $data['company_branch_id'];

        $companyObject  =  utill::get_companyTitle($data['company_branch_id']);
        $companyMask = $companyObject['sms_mask'];
        $companyTitle = $companyObject['company_title'];

        $client_id = $data['client_id'];
        $product_id = $data['product_id'];

        $clientProductFrequancy = ClientProductFrequency::model()->findByAttributes(array('client_id' => $client_id, 'product_id' => $product_id));
        if ($clientProductFrequancy) {
            $quantityID =  $clientProductFrequancy['client_product_frequency'];
            ClientProductFrequencyQuantity::model()->deleteAllByAttributes(array('client_product_frequency_id' => $quantityID));
            $clientProductFrequancy->delete();
        }


        $clientSchedulerObject  = IntervalScheduler::model()->findByAttributes(array('client_id' => $client_id, 'product_id' => $product_id));
        if ($clientSchedulerObject) {
            $clientSchedulerObject->delete();
        }

        $EffectiveDateSchedule = EffectiveDateSchedule::model()->findByAttributes(
            array('client_id' => $client_id, 'product_id' => $product_id)
        );

        if ($EffectiveDateSchedule) {
            $EffectiveDateSchedule->delete();
        }

        $EffectiveDateSchedule = EffectiveDateIntervalSchedule::model()->findByAttributes(
            array('client_id' => $client_id, 'product_id' => $product_id)
        );

        if ($EffectiveDateSchedule) {
            $EffectiveDateSchedule->delete();
        }

        $sendData = array();
        $sendData['client_id'] = '';

        $response = $this->makeSuccessResponse();
        $this->sendResponse($response);
        $clientObject = Client::model()->findByPk(intval($client_id));
        $phoneNo =  $clientObject['cell_no_1'];

        $message = "Your regular order is successfully cancelled
          \n\n" . $companyTitle;

        $fullname = $clientObject['fullname'];
        $network_id = $clientObject['network_id'];

        smsLog::saveSms($client_id, $company_branch_id, $phoneNo, $fullname, $message);

        $this->sendSMS($phoneNo, $message, $companyMask, $company_branch_id, $network_id);
    }
    public function actionresumedRegularOrder()
    {

        if ($this->isOptionsCall()) {
            return;
        }

        $data = $this->getRequestData();
        if (!isset($data['company_branch_id'])) {
            $data['company_branch_id'] = 1;
        }
        $company_branch_id = $data['company_branch_id'];

        $companyObject  =  utill::get_companyTitle($data['company_branch_id']);
        $companyMask = $companyObject['sms_mask'];
        $companyTitle = $companyObject['company_title'];

        $client_id = $data['client_id'];
        $product_id = $data['product_id'];
        HaltRegularOrders::model()->deleteAllByAttributes(array('client_id' => $client_id, 'product_id' => $product_id));
        $sendData = array();
        $sendData['client_id'] = '';
        $response = array(
            'company_branch_id' => 0,
            'code' => 200,
            'success' => true,
            'message' => 'You regular order have resumed successfully',
            'data' => $sendData
        );

        $clientObject = Client::model()->findByPk(intval($client_id));
        $phoneNo =  $clientObject['cell_no_1'];
        $network_id = $clientObject['network_id'];

        $message = "Your regular delivery is resumed.
          \nThank you\n\n" . $companyTitle;
        $this->sendSMS($phoneNo, $message, $companyMask, $company_branch_id, $network_id);
        $fullname = $clientObject['fullname'];

        smsLog::saveSms($client_id, $company_branch_id, $phoneNo, $fullname, $message);
        $this->sendResponse($response);
    }
    public function actionhaltIntervalRegularOrder()
    {

        if ($this->isOptionsCall()) {
            return;
        }

        $data = $this->getRequestData();

        $client_id = $data['client_id'];
        $product_id = $data['product_id'];
        $halt_flag  = $data['halt_flag'];


        $company_branch_id = $data['company_branch_id'];

        $companyObject  =  utill::get_companyTitle($data['company_branch_id']);
        $companyMask = $companyObject['sms_mask'];
        $companyTitle = $companyObject['company_title'];


        if ($halt_flag == 2) {
            $intervalSchedule = IntervalScheduler::model()->findByAttributes(array("client_id" => $client_id, "product_id" => $product_id));
            if ($intervalSchedule) {
                $intervalSchedule->halt_start_date = $data['halt_start_date'];
                $intervalSchedule->halt_end_date = $data['halt_end_date'];
                $intervalSchedule->save();
                /* $response = array(
                     'code' => 401,
                     'company_branch_id' => 0,
                     'success' => true,
                     'message' => 'halt Successfully',
                 );*/

                $response = $this->makeSuccessResponse();
            } else {
                /* $response = array(
                     'code' => 401,
                     'company_branch_id' => 0,
                     'success' => false,
                     'message' => 'halt fail',
                 );*/
                $response = $this->makeSuccessResponse();
                // $response = $this->makeFailureResponse();
            }
            $clientObject = Client::model()->findByPk(intval($client_id));
            $phoneNo =  $clientObject['cell_no_1'];
            $message = " Your regular order have been halted successfully. Please note that your regular delivery will automatically resume after the halt period is over.
             \n\n" . $companyTitle;
            //   $this->sendSMS($phoneNo,$message ,$companyMask , $company_branch_id);
        } else {
            $haltregularOrder = new HaltRegularOrders();
            $haltregularOrder->client_id = $client_id;
            $haltregularOrder->product_id = $product_id;
            $haltregularOrder->start_date = $data['halt_start_date'];
            $haltregularOrder->end_date = $data['halt_end_date'];
            if ($haltregularOrder->save()) {
                $sendData = array();
                $sendData['client_id'] = '';

                $response = $this->makeSuccessResponse();
            }
            $clientObject = Client::model()->findByPk(intval($client_id));
            $phoneNo =  $clientObject['cell_no_1'];
            $message = " Your regular order have been halted successfully. Please note that your regular delivery will automatically resume after the halt period is over.
             \n\n" . $companyTitle;
            //    $this->sendSMS($phoneNo,$message ,$companyMask , $company_branch_id);
            $this->makeFailureResponse();
        }
        $this->sendResponse($response);
    }

    private function getZoneList($companyId)
    {
        $query = "select * from zone as z 
            where z.show_in_app =1 
            and z.company_branch_id = '$companyId' 
            order by z.name ASC ";

        return Yii::app()->db->createCommand($query)->queryAll();
    }


    public function actiongetCustomerRegularOrderType()
    {

        if ($this->isOptionsCall()) {
            return;
        }

        $data = $this->getRequestData();

        $client_id = $data['client_id'];
        $product_id = $data['product_id'];

        $clientSchedulerObject  = ClientProductFrequency::model()->findByAttributes(array('client_id' => $client_id, 'product_id' => $product_id));


        $clientinterval = IntervalScheduler::model()->findByAttributes(array('client_id' => $client_id, 'product_id' => $product_id));

        if (!$clientinterval) {
            $clientinterval = EffectiveDateIntervalSchedule::model()
                ->findAllByAttributes(array('client_id' => $client_id, 'product_id' => $product_id));
        }
        if ($clientSchedulerObject) {

            $type  = 1;
        } else if ($clientinterval) {
            $type = 2;
        } else {
            $type = 0;
        }
        $response = $this->makeSuccessResponse($type);
        $this->sendResponse($response);
    }

    public function actiongetCustomerIntervalSchedulerStatus()
    {

        if ($this->isOptionsCall()) {
            return;
        }

        $data = $this->getRequestData();


        $client_id = $data['client_id'];
        // $client_id = '12625';
        $product_id = $data['product_id'];
        //$product_id =24;

        $todaydate = date("Y-m-d");

        $query = "select
                        IFNULL((select isi.is_halt from interval_scheduler as isi 
                        where isi.client_id = '$client_id' and isi.product_id ='$product_id' and '$todaydate'  between isi.halt_start_date and isi.halt_end_date
                        limit 1 ) ,0) as is_halt ,
              '$product_id' as product_id ,IFNULL(ist.product_quantity ,0) as product_quantity  ,IFNULL(c.client_id ,0) as client_id  , IFNULL(ist.interval_days ,0) as interval_days
            ,IFNULL(ist.start_interval_scheduler ,0) as start_interval_scheduler,IFNULL(ist.halt_start_date ,0) as halt_start_date,IFNULL(ist.halt_end_date ,0) as halt_end_date   from client as c
            left join interval_scheduler as ist ON ist.client_id = '$client_id' and ist.product_id = '$product_id'
            where c.client_id = '$client_id' and ist.product_quantity>0 ";

        $result = Yii::app()->db->createCommand($query)->queryAll();

        if (sizeof($result) == 0) {



            $query = " SELECT 
                    0 AS is_halt,
                    p.product_id,
                    p.price,
                    p.unit,
                    e.start_interval_scheduler,
                    e.start_interval_scheduler AS halt_start_date,
                    p.name AS product_name,
                    e.interval_days,
                    e.product_quantity  
        
                    FROM effective_date_interval_schedule AS e
                    LEFT JOIN product AS p ON e.product_id = p.product_id
                    WHERE e.client_id = '$client_id'   ";


            $result = Yii::app()->db->createCommand($query)->queryAll();
        }


        $response = array(
            'code' => 401,
            'company_branch_id' => 0,
            'success' => true,
            'message' => '',
            'data' => $result
        );

        $this->sendResponse($response);
    }


    private function isOptionsCall()
    {
        if (!isset($_POST['company_branch_id'])) {
            $response = array(
                'code' => 200,
                'company_branch_id' => 0,
                'success' => false,
                'message' => 'empty company id',
                'data' => '',
            );
            $this->sendResponse($response);
            return true;
        }

        return false;
    }

    private function getRequestData()
    {
        date_default_timezone_set("Asia/Karachi");

        // $post = file_get_contents("php://input");
        // $data = CJSON::decode($post, TRUE);

        return $_POST;
    }

    private function makeResponse($success, $code = 200,  $message = '', $data = [])
    {
        return [
            'code' => $code,
            'company_branch_id' => 0,
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ];
    }

    private function makeSuccessResponse($data = [])
    {
        return $this->makeResponse(true, 200, '', $data);
    }

    private function makeFailureResponse($message = '', $code = 500, $data = [])
    {
        return $this->makeResponse(false, $code, $message, $data);
    }


    public static function sendSMS($num, $message, $mask, $company_branch_id, $network_id)
    {

        $messageLength  = strlen($message);
        $countSms = ceil($messageLength / 160);
        $companyObject = Company::model()->findByPk(intval($company_branch_id));

        $allreadyExistSMS =  $companyObject['SMS_count'];
        $totalSMS = $allreadyExistSMS + $countSms;
        $companyObject->SMS_count = $totalSMS;
        $companyObject->save();
        $number = $num;
        if (substr($num, 0, 2) == "03") {
            $number = '923' . substr($num, 2);
        } else if (substr($num, 0, 1) == "3") {
            $number = '923' . substr($num, 1);
        } else if (substr($num, 0, 2) == "+9") {
            $number =  substr($num, 1);
        }
        // Configuration variables
        $id = "conformiz@bizsms.pk";
        $pass = "c3nuji8uj99";

        $message = urlencode($message);

        if ($company_branch_id == 9) {
            $_url = 'http://pk.eocean.us/APIManagement/API/RequestAPI?user=Stylo IT&pwd=APzw4c7%2bf0XgoSne8lLeoL7bWMq%2fNHFNFGmy%2bPRkwhElakoluftUyn3Rs4tvYNGDCg%3d%3d&sender=Stylo IT&reciever=' . $number . '&msg-data=' . $message . '&response=string';
        } elseif ($company_branch_id == 13) {
            $_url = 'http://api.bizsms.pk/api-send-branded-sms.aspx?username=leche@bizsms.pk&pass=1e3chis&text=' . $message . '&masking=LECHE&destinationnum=' . $number . '&language=English';
        } else {
            $_url = 'http://api.bizsms.pk/api-send-branded-sms.aspx?username=' . $id . '&pass=' . $pass . '&text=' . $message . '&masking=' . $mask . '&destinationnum=' . $number . '&language=English&network=' . $network_id;
        }




        if ($_result = file_get_contents($_url)) {
            $_result_f = json_decode($_result);
        } else {
            echo "not Send";
        }
    }

    public function actiongetPreferredTimeList()
    {

        date_default_timezone_set("Asia/Karachi");
        $preferdTime = PreferredTime::model()->findAll();

        $preferreddTimeList = array();

        foreach ($preferdTime as $value) {
            $preferreddTimeList[] = $value->attributes;
        }
        $response = array(
            'code' => 200,
            'company_branch_id' => 0,
            'success' => true,
            'message' => 'Preferred time list',
            'data' => $preferreddTimeList,
        );
        $this->sendResponse($response);
    }

    public function actiongetWeeklySchedule()
    {

        if ($this->isOptionsCall()) {
            return;
        }
        $data = $this->getRequestData();
        $clientID = $data['client_id'];
        $productID = $data['product_id'];
        $clientProductFrequency = ClientProductFrequency::model()->findByAttributes(array('client_id' => $clientID, 'product_id' => $productID));
        $clientProductFrequency = ($clientProductFrequency['client_product_frequency']);
        $query = "Select f.* , IFNULL(cpfq.quantity , 0) as quantity  , IFNULL(pt.preferred_time_id, 0) as PreferredTime ,  IFNULL(cpfq.isSelected, 0) as isSelected ,pt.preferred_time_name from frequency as f
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
        $response_data = array(

            'clientId' => $clientID,
            'productId' => $productID,
            'orderStartDate' => $date,
            'message' => 'Weekly Schedule',
            'data' => $weeklyResult
        );
        $response = $this->makeSuccessResponse($response_data);
        $this->sendResponse($response);
    }

    public function actionupdateWeeklySchedual()
    {




        $today_date = date("Y-m-d");


        if ($this->isOptionsCall()) {
            return;
        }

        $data = $this->getRequestData();
        $clientID = ($data['client_id']);
        $productID = ($data['product_id']);

        $orderStartDate = $data['orderStartDate'];

        $dayObject = $data['data'];

        $dayObject = CJSON::decode($dayObject, TRUE);

        if ($orderStartDate <= $today_date) {


            $clientFrequency =  ClientProductFrequency::model()->findByAttributes(array('client_id' => $clientID, 'product_id' => $productID));
            $clientFFID = (($clientFrequency['client_product_frequency']));
            ClientProductFrequencyQuantity::model()->deleteAllByAttributes(array('client_product_frequency_id' => $clientFFID));
            if (isset($clientFrequency)) {
                // $clientFrequency->delete();
                $client_product_frequency = $clientFrequency['client_product_frequency'];
                $clientFrequency->orderStartDate = $orderStartDate;
                $clientFrequency->save();
            } else {

                $ClientProductFrequency = new ClientProductFrequency();
                $ClientProductFrequency->client_id = $clientID;
                $ClientProductFrequency->product_id = $productID;
                $ClientProductFrequency->quantity = '0';
                $ClientProductFrequency->total_rate = '0';
                $ClientProductFrequency->frequency_id = '1';
                $ClientProductFrequency->orderStartDate = $orderStartDate;
                $ClientProductFrequency->save();
                $client_product_frequency = $ClientProductFrequency['client_product_frequency'];
            }


            foreach ($dayObject as $value) {


                if ($value['isSelected'] == 1) {
                    $daySave = new ClientProductFrequencyQuantity();
                    $daySave->client_product_frequency_id = $client_product_frequency;
                    $daySave->frequency_id = $value['frequency_id'];
                    $daySave->quantity = $value['quantity'];
                    $daySave->preferred_time_id = $value['PreferredTime'];
                    $daySave->save();
                    $clientSchedulerObject  = IntervalScheduler::model()->findByAttributes(array('client_id'=>$clientID , 'product_id'=>$productID));
                    if($clientSchedulerObject){
                        $clientSchedulerObject->delete();
                    }


                }
            }


            $response = $this->makeSuccessResponse();



            $clientObject = Client::model()->findByPk(intval($clientID));
            $company_branch_id = $clientObject['company_branch_id'];
            $phoneNo = $clientObject['cell_no_1'];
            $fullname = $clientObject['fullname'];
            $network_id = $clientObject['network_id'];
            $savechagescheduler = new ChangeSchedulerRecord();
            $savechagescheduler->client_id = $clientID;
            $savechagescheduler->company_id = $company_branch_id;
            $savechagescheduler->date = date("Y-m-d");
            $savechagescheduler->change_form = 2;
            $savechagescheduler->save();

            $companyObject = Company::model()->findByPk(intval($company_branch_id));
            $companyMask =    $companyObject['sms_mask'];
            $phoneNo =    $companyObject['phone_number'];
            $Companymessage = $fullname . " have changed schedule. ";
            smsLog::saveSms($clientID, $company_branch_id, $phoneNo, $fullname, $Companymessage);
            $this->sendSMS_foradmin($phoneNo, $Companymessage, $companyMask, $company_branch_id, $network_id);
            $this->sendResponse($response);
        } else {


            $EffectiveDateSchedule = EffectiveDateSchedule::model()->findByAttributes(
                array('client_id' => $clientID, 'product_id' => $productID)
            );
            $effective_date_schedule_id = $EffectiveDateSchedule['effective_date_schedule_id'];

            EffectiveDateScheduleFrequency::model()->deleteAllByAttributes(
                array('effective_date_schedule_id' => $effective_date_schedule_id)
            );



            $effective_date_schedual = new EffectiveDateSchedule();
            $effective_date_schedual->client_id = $clientID;
            $effective_date_schedual->product_id = $productID;

            $effective_date_schedual->date = $orderStartDate;

            if ($effective_date_schedual->save()) {

                $effective_date_schedule_id = $effective_date_schedual->effective_date_schedule_id;


                foreach ($dayObject as $value) {


                    if ($value['isSelected']) {

                        $effective_date_schedule_frequency = new EffectiveDateScheduleFrequency();
                        $effective_date_schedule_frequency->effective_date_schedule_id = $effective_date_schedule_id;
                        $effective_date_schedule_frequency->frequency_id = $value['frequency_id'];
                        $effective_date_schedule_frequency->quantity = $value['quantity'];
                        if ($effective_date_schedule_frequency->save()) {
                        } else {
                        }
                    }
                }
            }


            $response = $this->makeSuccessResponse();

            $this->sendResponse($response);
        }
    }

    public static function sendSMS_foradmin($num, $message, $mask, $company_branch_id, $network_id)
    {

        $messageLength  = strlen($message);
        $countSms = ceil($messageLength / 160);
        $companyObject = Company::model()->findByPk(intval($company_branch_id));

        $allreadyExistSMS =  $companyObject['SMS_count'];
        $totalSMS = $allreadyExistSMS + $countSms;
        $companyObject->SMS_count = $totalSMS;
        $companyObject->save();

        // Configuration variables
        $id = "conformiz@bizsms.pk";
        $pass = "c3nuji8uj99";

        $message = urlencode($message);

        if ($company_branch_id == 9) {
            //  $_url = 'http://pk.eocean.us/APIManagement/API/RequestAPI?user=Stylo IT&pwd=APzw4c7%2bf0XgoSne8lLeoL7bWMq%2fNHFNFGmy%2bPRkwhElakoluftUyn3Rs4tvYNGDCg%3d%3d&sender=Stylo IT&reciever='.$num.'&msg-data='.$message.'&response=string';
            $_url = 'https://pk.eocean.us/APIManagement/API/RequestAPI?user=aas&pwd=APlMLww6m7fsXeBeghBtqlM8y5PNk2Rd2ZZCoLc32z27bwkptwBdyIw7o561CyRUZw%3d%3d&sender=AAS&reciever=' . $num . '&msg-data=' . $message . '&response=string';
        } elseif ($company_branch_id == 13) {
            $_url = 'http://api.bizsms.pk/api-send-branded-sms.aspx?username=leche@bizsms.pk&pass=1e3chis&text=' . $message . '&masking=LECHE&destinationnum=' . $num . '&language=English';
        } else {
            $_url = 'http://api.bizsms.pk/api-send-branded-sms.aspx?username=' . $id . '&pass=' . $pass . '&text=' . $message . '&masking=' . $mask . '&destinationnum=' . $num . '&language=English&network=' . $network_id;
        }
        if ($_result = file_get_contents($_url)) {
            $_result_f = json_decode($_result);
        } else {
            echo "not Send";
        }
    }

    public function actiondeliverybetweenDateRange()
    {
        if ($this->isOptionsCall()) {
            return;
        }

        $data = $this->getRequestData();

        $clientId = $data['client_id'];
        $startDate = $data['start_date'];
        $endDate = $data['end_date'];

        $query = "Select d.date ,p.name as product_name , p.unit , dd.quantity , dd.amount ,d.time  from delivery as d
                LEFT JOIN delivery_detail as dd ON d.delivery_id = dd.delivery_id
                LEft JOIN product as p ON p.product_id = dd.product_id
                Where d.client_id ='$clientId' AND d.date between '$startDate' AND '$endDate'
                order by d.date DESC ";
        $deliveryResult = Yii::app()->db->createCommand($query)->queryAll();


        $response = $this->makeSuccessResponse($deliveryResult);

        $this->sendResponse($response);
    }

    public function actionSaveCustomerProfile(){
        if ($this->isOptionsCall()) {
            return;
        }
        $data = $this->getRequestData();
        $client_id = $data['client_id'];
        $object = Client::model()->findByPk($client_id);
        $object->fullname =$data['fullname'];
        $object->cell_no_1 =$data['cell_no_1'];
        if($object->save()){
            $response = $this->makeSuccessResponse();
        }else{

            $response = $this->makeFailureResponse('Data is not saved successfully',500,$object->getErrors());
        }


        $this->sendResponse($response);
    }
    public function actionPaymentHistory()
    {
        if ($this->isOptionsCall()) {
            return;
        }
        $data = $this->getRequestData();
        $client_id = $data['client_id'];

        $start_date = $data['start_date'];
        $end_date = $data['end_date'];

        $query = " select 
                    pm.date ,
                    pm.amount_paid ,
                    pm.reference_number,
                    pm.payment_mode
                    from payment_master as pm
                    left join client as c ON c.client_id = pm.client_id  
                    where pm.client_id = '$client_id'
 	                AND pm.date BETWEEN   '$start_date' AND '$end_date'
                    order by pm.date DESC";

        $result = Yii::app()->db->createCommand($query)->queryAll();

        // $result =  clientData::oneCustomerAmountListFunction($client_id);
        $response = $this->makeSuccessResponse((object)[
            'records' => $result,
            'balance' => APIData::calculateFinalBalance($client_id),
        ]);

        $this->sendResponse($response);
    }

    public function actioncreateComplaints()
    {
        if ($this->isOptionsCall()) {
            return;
        }

        $data = $this->getRequestData();

        $company_branch_id = $data['company_branch_id'];
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

        $complain->client_id = $client_id;
        $complain->company_branch_id = $company_branch_id;
        $complain->query_text = $query_text;
        $complain->status_id = 0;
        $complain->response = "";
        if ($complain->save()) {
            $clientObject = Client::model()->findByPk(intval($client_id));
            $phoneNo =  $clientObject['cell_no_1'];
            $message = '';
            if ($company_branch_id == 10) {
                $message .= $clientObject['fullname'];
            } else {
                $message .= 'Dear Customer';
            }
            $message .= ",\nWe've received your complaint. Our team shall look into the issue on priority. 
                         \nThank you for registering your concern.\n\n" . $companyTitle;

            $phoneNo = $clientObject['cell_no_1'];
            $fullname = $clientObject['fullname'];
            $network_id = $clientObject['network_id'];
            if($phoneNo=='+923018228141'){
                return true;
            }

            smsLog::saveSms($client_id, $company_branch_id, $phoneNo, $fullname, $message);
            $this->sendSMS($phoneNo, $message, $companyMask, $company_branch_id, $network_id);
            if($complaints_notification_yes_now>0){
                $complain_object = ComplainType::model()->findByPk($complain_type_id);
                $complainb_type_name =  $complain_object['name'];
                $message = 'You have received new Complain from '.$fullname.' about '. $complainb_type_name;
                smsLog::saveSms($client_id, $company_branch_id, $company_phone_number, $fullname, $message);
                $this->sendSMS($company_phone_number, $message, $companyMask, $company_branch_id, $network_id);
                if($company_branch_id==1){
                    $phoneNo = '+923021118292';
                    smsLog::saveSms($client_id ,$company_branch_id ,$phoneNo ,$fullname ,$message);
                    $this->sendSMS($phoneNo , $message , $companyMask ,$company_branch_id , $network_id);
                    $phoneNo = '+923341118292';
                    smsLog::saveSms($client_id ,$company_branch_id ,$phoneNo ,$fullname ,$message);
                    $this->sendSMS($phoneNo , $message , $companyMask ,$company_branch_id , $network_id);
                }

            }
            $emptyObject = array();
            $emptyObject['client_id'] = '';

            $response = $this->makeSuccessResponse();
        } else {



            $response = $this->makeFailureResponse('Data is not saved successfully',500,$complain->getErrors());
        }
        $this->sendResponse($response);
    }

    public function actionget_ledger_report(){

        if ($this->isOptionsCall()) {
            return;
        }

        $data_get = $this->getRequestData();
        $year = $data_get['year'];
        $month = $data_get['month'];
        $data = array();

        $d=cal_days_in_month(CAL_GREGORIAN,$month,$year);


        $data['clientID']= $data_get['client_id'];
        $data['startDate']= $year.'-'.$month.'-01';
        $data['endDate']=   $year.'-'.$month.'-'.$d;



        $list = clientData::getClientLedgherReportFunction_for_mobile($data);

        $response = $this->makeSuccessResponse(CJSON::decode($list ,True));
        $this->sendResponse($response);

    }


    public  function actioncustomerForgetPassword(){

        if ($this->isOptionsCall()) {
            return;
        }

        $data = $this->getRequestData();



        //$post = file_get_contents("php://input");
        //$data = CJSON::decode($post , true);

        if(!isset($data['company_branch_id'])){
            $data['company_branch_id'] = 1;
        }
        $company_branch_id = $data['company_branch_id'] ;

        $companyObject  =  utill::get_companyTitle($data['company_branch_id']);
        $companyMask = $companyObject['sms_mask'];
        $companyTitle = $companyObject['company_title'];

        $clientMobile=validate_phone_number_with_code::validate_phone_number($data['cell_no_1']);


        if($company_branch_id == '18'){

            $query = "select * from client where cell_no_1 ='$clientMobile' and company_branch_id in (18,20,21,22) ";

            $result =  Yii::app()->db->createCommand($query)->queryAll();
            if(count($result)>0){
                $clientObject =$result[0];
            }else{
                $response = $this->makeFailureResponse('This phone number is not registered');
                $this->sendResponse($response);
                die();
            }
        }else{

            $clientObject = Client::model()->findByAttributes(array('cell_no_1'=>$clientMobile , 'company_branch_id'=>$company_branch_id ));
        }


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


            // smsLog::saveSms($client_id, $company_branch_id, $phoneNo, $fullname, $message);
            $this->sendSMS($clientMobile , $message ,$companyMask , $company_branch_id ,$network_id);
            $response = array(
                'company_branch_id'=>0,
                'code' => 200,
                'success' => true,
                'message'=>'We have sent you the details of your account on your phone number. Please check your phone inbox.',
                'data' => $dataReturn
            );

            $message='We have sent you the details of your account on your phone number. Please check your phone inbox.';

            $response = $this->makeSuccessResponse($message ,True);
            $this->sendResponse($response);
            die();
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
            $response = $this->makeFailureResponse('This phone number is not registered');
        }
        $this->sendResponse($response);
    }

    public function actionsaveHaltedDates(){

        if ($this->isOptionsCall()) {
            return;
        }
        $data = $this->getRequestData();
        api_inonic_data::halt_delivery_delete($data);
        $date =json_decode($data['dates'],true);


        $client_id =$data['client_id'];
        $product_id =$data['product_id'];
        $start_date =$data['start_date'];
        $end_date =$data['end_date'];

        api_inonic_data::halt_delivery_delete($data);

        foreach ($date as $value){

            $object = new HaltRegularOrders();
            $object->client_id =$client_id;
            $object->product_id =$product_id;
            $object->start_date =$value;
            $object->end_date =$value;

            if($object->save()){

                $reponce_data=  api_inonic_data::get_all_plan_data($data);

                $response = $this->makeSuccessResponse($reponce_data);
            }else{
                $message= '';
                $response = $this->makeSuccessResponse($object->getErrors() ,false);

            }
        }

        $this->sendResponse($response);
    }
    public function actionsaveSpecialOrder(){

        if ($this->isOptionsCall()) {
            return;
        }

        $data = $this->getRequestData();
        api_inonic_data::save_special_order_delete($data);
        $order =json_decode($data['order'],true);

        
        $client_id =$data['client_id'];
        $product_id =$data['product_id'];
        $start_date =$data['start_date'];
        $end_date =$data['end_date'];

        foreach ($order as $date=>$qunatity){

            $object = new SpecialOrder();
            $object->client_id =$client_id;
            $object->product_id =$product_id;
            $object->start_date =$date;
            $object->end_date =$date;
            $object->end_date =$date;
            $object->requested_on =$date;
            $object->quantity =$qunatity;
            $object->status_id =0;
            $object->delivery_on ='0000-00-00';
            $object->preferred_time_id ='00000000';

            if($object->save()){
                $reponce_data =  api_inonic_data::get_all_plan_data($data);

                $response = $this->makeSuccessResponse($reponce_data);
            }else{
                $message= '';
                $response = $this->makeSuccessResponse($object->getErrors() ,false);

            }
        }

        $this->sendResponse($response);
    }
    public function actioncreateGuestLogin(){
        if ($this->isOptionsCall()) {
            return;
        }
        $data = $this->getRequestData();
        $company_branch_id = $data['company_branch_id'];


        $client = new Client();
        $client->user_id = '2';
        $client->zone_id = '199';
        $client->network_id = 0;
        $client->fullname = 'Guest';
        $client->userName = 'guest';
        $client->password = '12345';

        $client->latitude = '0';
        $client->longitude = '0';

        $client->company_branch_id = $company_branch_id;
        $client->father_or_husband_name = ' ';
        $client->date_of_birth = '0000-00-00';
        $client->email ='';
        $client->cnic = '';
        $client->cell_no_1 ='03000';
        $client->cell_no_2 = '12334543';
        $client->residence_phone_no = '1234567';
        $client->city = 'no';
        $client->area = 'no';
        $client->address = 'testinf';
        $client->is_active = 0;
        $client->is_deleted = 0;
        $client->created_by = '2';
        $client->updated_by = '2';
        $client->is_guest = '0';
        $client->login_form = '2';
        $client->view_by_admin = '1';

        $client->is_approved = '0';

        $client->is_mobile_notification = '1';


        if ($client->save()) {
           $clientID = $client->client_id;
            $response = array(
                'code' => 200,
                'company_branch_id' => $company_branch_id,
                'success' => true,
                'alreadyExists' => false,
                'message' => "",
                'data' => $clientID,
            );
        } else {

            $IDArray = array();
            $IDArray['client_id'] = "";
            $response = array(
                'code' => 401,
                'company_branch_id' => 0,
                'success' => false,
                'alreadyExists' => false,
                'message' => $client->getErrors(),
                'data' => $IDArray
            );
        }

        $this->sendResponse($response);
    }

    public function actionupdateGuestLogin()
    {
        if ($this->isOptionsCall()) {
            return;
        }
        $data = $this->getRequestData();
        $company_branch_id = $data['company_branch_id'];
        $client_id = $data['client_id'];
        if($client_id==0){

            $response = array(
                'code' => 401,
                'company_branch_id' => 0,
                'success' => false,
                'message' => 'Requested user does not exist',
                'data' => $client_id,
            );
            $this->sendResponse($response);
            die();
        }


        if (!isset($data['zone_id']) ||  empty($data['zone_id'])) {
            $data['zone_id'] = '1309';
        }

        if (!isset($data['cnic'])) {
            $data['cnic'] = '0';
        }

        if (!isset($data['address'])) {
            $data['address'] = 'no';
        }

        $userName =   $data['userName'];
        $userPhoneNumber = $data['cell_no_1'];
        if (!isset($data['company_branch_id'])) {
            $data['company_branch_id'] = 1;
        }

        $company_branch_id =  $data['company_branch_id'];
        $companyObject  =  utill::get_companyTitle($data['company_branch_id']);
        $companyMask = $companyObject['sms_mask'];
        $companyTitle = $companyObject['company_title'];
        $unregister =  Client::model()->findByAttributes(array('cell_no_1' => $userPhoneNumber, 'is_active' => '0', 'company_branch_id' => $company_branch_id));

        if ($unregister) {
            try {
                $unregister->delete();
            } catch (Exception $e) {
                $IDArray = array();
                $IDArray['client_id'] = "";
                $response = array(
                    'code' => 401,
                    'company_branch_id' => 0,
                    'success' => false,
                    'alreadyExists' => false,
                    'message' => "This Phone Number Already Register",
                    'data' => $IDArray
                );

                $this->sendResponse($response);
                die();
            }
        }

        $checkUserName = Client::model()->findByAttributes(array('userName' => $userName, 'company_branch_id' => $company_branch_id));

        $checkUserPhoneNumber = Client::model()->findByAttributes(array('cell_no_1' => $userPhoneNumber, 'company_branch_id' => $company_branch_id));

        if ($checkUserName) {
            $IDArray = array();
            $IDArray['client_id'] = "";
            $response = array(
                'code' => 401,
                'company_branch_id' => 0,
                'success' => false,
                'alreadyExists' => true,
                'message' => 'This username already exists . Try another username',
                'data' => $IDArray
            );
        } elseif ($checkUserPhoneNumber) {

            $IDArray = array();
            $IDArray['client_id'] = "";
            $response = array(
                'code' => 401,
                'company_branch_id' => 0,
                'success' => false,
                'alreadyExists' => true,
                'message' => 'This phone No. already exists . Try another phone No.',
                'data' => $IDArray,
            );
        } else {
            $network_id = 0;
            if (isset($data['network_id'])) {
                $network_id = $data['network_id'];
            }
            $client =Client::model()->findByPk($client_id);
            $client->user_id = '2';
            $client->zone_id = $data['zone_id'];
            $client->network_id = $network_id;
            $client->fullname = $data['fullName'];
            $client->userName = $data['userName'];
            $client->password = $data['password'];

            $client->latitude = $data['latitude'];
            $client->longitude = $data['longitude'];

            $client->company_branch_id = $data['company_branch_id'];
            $client->father_or_husband_name = ' ';
            $client->date_of_birth = '0000-00-00';
            $client->email = $data['email'];
            $client->cnic = $data['cnic'];
            $client->cell_no_1 =validate_phone_number_with_code::validate_phone_number($data['cell_no_1']);
            $client->cell_no_2 = '12334543';
            $client->residence_phone_no = '1234567';
            $client->city = 'no';
            $client->area = 'no';
            $client->address = $data['address'];
            $client->is_active = 0;
            $client->is_deleted = 0;
            $client->created_by = '2';
            $client->updated_by = '2';
            $client->login_form = '2';
            $client->view_by_admin = '1';

            $client->is_approved = '0';
            $client->is_guest = '1';

            $client->is_mobile_notification = '1';


            if ($client->save()) {
                $clientID = $client->client_id;
                $code = sprintf("%04s", mt_rand(0000, 9999));
                $authenticate = new Authentication();
                $authenticate->client_id = $clientID;
                $authenticate->code = $code;
                $authenticate->SetTime = time();

                if ($authenticate->save()) {
                    $IDArray = array();
                    $IDArray['client_id'] = $clientID;
                    $response = array(
                        'code' => 200,
                        'company_branch_id' => 0,
                        'success' => true,
                        'alreadyExists' => false,
                        'message' => "We've sent and SMS on your number. Please enter the verification code below to complete the sign-up process.
                                       Please allow up to a minute for your SMS to arrive.",
                        'data' => $IDArray,
                    );

                    if($data['company_branch_id'] ==18){


                        $message =   'New customer signup, please approve customer.';

                        smsLog::saveSms($clientID, $company_branch_id, '+923214667127', 'Company Admin', $message);
                        utill::sendSMS2('+923214667127', $message, $companyMask, $company_branch_id, 1, $clientID);


                    }else{
                        $message = "Your verification code for  is " . $code;
                        $message =   ' Your code is ' . $code;
                        smsLog::saveSms($clientID, $company_branch_id, $data['cell_no_1'], $data['fullName'], $message);
                        utill::sendSMS2($data['cell_no_1'], $message, $companyMask, $company_branch_id, 1, $clientID);
                    }

                    // $this->sendSMS($data['cell_no_1'],$message , $companyMask , $company_branch_id ,$network_id);
                }
            } else {

                $IDArray = array();
                $IDArray['client_id'] = "";
                $response = array(
                    'code' => 401,
                    'company_branch_id' => 0,
                    'success' => false,
                    'alreadyExists' => false,
                    'message' => $client->getErrors(),
                    'data' => $IDArray
                );
            }
        }

        $this->sendResponse($response);
    }

}
