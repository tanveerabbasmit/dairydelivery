<?php
/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 3/3/2017
 * Time: 3:31 PM
 */
class notification_alert_data{

    public static $response = ['success'=>FALSE , 'message'=>'','data'=>[]];

    public static function notification_function($clientObject ,$company_branch_id  ,$currentBalance,$companyObject){




        $notification_alert_allow_user = $clientObject['notification_alert_allow_user'];
        $grass_amount = $clientObject['grass_amount'];
        $security_type_customer = $clientObject['security_type_customer'];
           /*company data*/
        $company_notification_alert_allow = $companyObject['notification_alert_allow'];


        if($company_notification_alert_allow ==1 && $notification_alert_allow_user ==1 && $security_type_customer>0){
            self::send_alert($clientObject ,$company_branch_id  ,$currentBalance,$companyObject);
        }else{

        }

    }

    public static function send_alert($clientObject ,$company_branch_id  ,$currentBalance,$companyObject){

          $notification_start_amount =$companyObject['notification_start_amount'];
          $notification_end_amount =$companyObject['notification_end_amount'];
          $security_type_customer = $clientObject['security_type_customer'];
    }

    public static function notification_sendSMS($num, $message , $mask , $company_branch_id , $network_id){


        $messageLength  = strlen($message);
        $countSms = ceil($messageLength/160);

        date_default_timezone_set("Asia/Karachi");
        $today = date("Y-m-d");

        $companyObject = SmsCounter::model()->findByAttributes(array('company_id'=>$company_branch_id ,'date'=>$today));
        if($companyObject){

            $allreadyExistSMS =  $companyObject['total_sms'];
            $totalSMS = $allreadyExistSMS + $countSms ;
            $companyObject->total_sms = $totalSMS ;
            $companyObject->save();
        }else{
            $object = new SmsCounter();
            $object->company_id = $company_branch_id ;
            $object->date = date("Y-m-d");
            $object->total_sms = $countSms ;
            if($object->save()){

            }else{

            }

        }



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

            //   $_url = 'http://pk.eocean.us/APIManagement/API/RequestAPI?user=Stylo IT&pwd=APzw4c7%2bf0XgoSne8lLeoL7bWMq%2fNHFNFGmy%2bPRkwhElakoluftUyn3Rs4tvYNGDCg%3d%3d&sender=Stylo IT&reciever='.$number.'&msg-data='.$message.'&response=string';
            $_url = 'http://pk.eocean.us/APIManagement/API/RequestAPI?user=Stylo%20IT&pwd=APzw4c7%2bf0XgoSne8lLeoL7bWMq%2fNHFNFGmy%2bPRkwhElakoluftUyn3Rs4tvYNGDCg%3d%3d&sender=Stylo%20IT&reciever='.$number.'&msg-data='.$message.'%20DATA%20HERE&response=string';
            $_url = 'https://pk.eocean.us/APIManagement/API/RequestAPI?user=aas&pwd=APlMLww6m7fsXeBeghBtqlM8y5PNk2Rd2ZZCoLc32z27bwkptwBdyIw7o561CyRUZw%3d%3d&sender=AAS&reciever='.$number.'&msg-data='.$message.'&response=string';

        }elseif($company_branch_id ==13){
            $_url ='http://api.bizsms.pk/api-send-branded-sms.aspx?username=leche@bizsms.pk&pass=1e3chis&text=' .$message .'&masking=LECHE&destinationnum=' . $number . '&language=English';
        }elseif($company_branch_id ==15 OR $company_branch_id ==2){
            $_url ='http://api.bizsms.pk/api-send-branded-sms.aspx?username=conformiz@bizsms.pk&pass=c3nuji8uj99&text='.$message.'&masking=Daily%20Needs&destinationnum='. $number .'&language=Urdu';
            $_url = 'http://api.bizsms.pk/api-send-branded-sms.aspx?username=conformiz@bizsms.pk&pass=c3nuji8uj99&text='.$message.'&masking=Noor%20Milk&destinationnum='.$number.'&language=Urdu';
        }else{
            $_url = 'http://api.bizsms.pk/api-send-branded-sms.aspx?username='.$id.'&pass='.$pass.'&text='.$message.'&masking='.$mask.'&destinationnum='.$number.'&language=English&network='.$network_id;
        }
        if(file_get_contents($_url)) {

            //   $_result_f = json_decode(file_get_contents($_url));
        }else{


        }
    }

    public static function get_new_customer_data_for_notification(){

        $get_data = ['page'=>0];
        $company_id = Yii::app()->user->getState('company_branch_id');

        $user_id = Yii::app()->user->getState('user_id');

        $user_object = User::model()->findByPk($user_id);

        if($user_object['supper_admin_user']==1){
            $query = "UPDATE `client` SET 
            `view_by_admin` = '1'
            WHERE view_by_admin = 0 
            and 	company_branch_id = '$company_id' ";
            Yii::app()->db->createCommand($query)->query();
        }



        $result =      dashbord_graph_data::get_new_customer_notification($get_data);

        return $result;

        $customer_list = $result['customer_list'];


        $notification ='';
        foreach ($customer_list as $value){

            $notification .= '<li style="">
                <div class="col-lg-12" style="border-bottom: 1px dotted #008B8B;padding: 5px;background-color: #F5FFFA">
                <a href="">
                    <div class="col-lg-2 col-sm-2 col-2 text-center">
                         <i class="fa fa-user" style="font-size:24px;color:#5F9EA0"></i>
                    </div>
                    <div class="col-lg-9 col-sm-9 col-9">
                        <strong class="text-info">'.$value['fullname'].'</strong>
                        <br>
                        <small class="text-warning">'.$value['created_at'].'</small>
                    </div>
                    </a>
                </div>
            </li>';
        }
        if(sizeof($customer_list)>0){
            $notification .=' <li style="" id="view_more_id">
                                    <div class="col-lg-12" style="border-bottom: 1px dotted #008B8B;padding: 5px;background-color: #F5FFFA">

                                            <div class="col-lg-12 col-sm-12 col-12 text-center">
                                                <strong class="text-info">
                                                    <a onclick="view_more()" href="#">View More</a>
                                                </strong>
                                            </div>
                                    </div>
                            </li>';
        }



       // $result['customer_list'] = ;

        return $notification;
    }

}