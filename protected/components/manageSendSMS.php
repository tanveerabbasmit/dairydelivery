<?php

/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 9/6/2017
 * Time: 10:43 AM
 */
class manageSendSMS
{


    public static function sendSMS($num, $message , $mask , $company_branch_id , $network_id,$cleint_id=false){


        $client_object = Client::model()->findByPk($cleint_id);





        $is_mobile_notification = $client_object['is_mobile_notification'];
        $is_push_notification = $client_object['is_push_notification'];



        if($is_mobile_notification==1){

            $message = urlencode($message);

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




            if($company_branch_id ==9){

                //   $_url = 'http://pk.eocean.us/APIManagement/API/RequestAPI?user=Stylo IT&pwd=APzw4c7%2bf0XgoSne8lLeoL7bWMq%2fNHFNFGmy%2bPRkwhElakoluftUyn3Rs4tvYNGDCg%3d%3d&sender=Stylo IT&reciever='.$number.'&msg-data='.$message.'&response=string';
                $_url = 'http://pk.eocean.us/APIManagement/API/RequestAPI?user=Stylo%20IT&pwd=APzw4c7%2bf0XgoSne8lLeoL7bWMq%2fNHFNFGmy%2bPRkwhElakoluftUyn3Rs4tvYNGDCg%3d%3d&sender=Stylo%20IT&reciever='.$number.'&msg-data='.$message.'%20DATA%20HERE&response=string';
                $_url = 'https://pk.eocean.us/APIManagement/API/RequestAPI?user=aas&pwd=APlMLww6m7fsXeBeghBtqlM8y5PNk2Rd2ZZCoLc32z27bwkptwBdyIw7o561CyRUZw%3d%3d&sender=AAS&reciever='.$number.'&msg-data='.$message.'&response=string';

            }elseif($company_branch_id ==13){
                $_url ='http://api.bizsms.pk/api-send-branded-sms.aspx?username=leche@bizsms.pk&pass=1e3chis&text=' .$message .'&masking=LECHE&destinationnum=' . $number . '&language=English';
            }elseif($company_branch_id ==15 OR $company_branch_id ==2){
                $_url ='http://api.bizsms.pk/api-send-branded-sms.aspx?username=conformiz@bizsms.pk&pass=c3nuji8uj99&text='.$message.'&masking=Daily%20Needs&destinationnum='. $number .'&language=English';
                $_url = 'http://api.bizsms.pk/api-send-branded-sms.aspx?username=conformiz@bizsms.pk&pass=c3nuji8uj99&text='.$message.'&masking=Noor%20Milk&destinationnum='.$number.'&language=English';
            }else{
                $_url = 'http://api.bizsms.pk/api-send-branded-sms.aspx?username='.$id.'&pass='.$pass.'&text='.$message.'&masking='.$mask.'&destinationnum='.$number.'&language=English&network='.$network_id;
            }


            if(file_get_contents($_url)) {

                //   $_result_f = json_decode(file_get_contents($_url));
            }else{


            }



        }

        if($is_push_notification==1){

            $save_sms_notification = SaveMessageToken::model()->findByAttributes(
                ['client_id'=>$cleint_id]
            );


          if($save_sms_notification){

              $messaging_token = $save_sms_notification['messaging_token'];

              $client_id = $save_sms_notification['client_id'];

              firebase_cloud_messaging_data::sendGCM($messaging_token,$client_object['fullname'],$message);

          }
        }

    }
    public static function vendor_sms_function($num, $message , $mask , $company_branch_id , $network_id,$cleint_id=false){

        $client_object = Client::model()->findByPk($cleint_id);





        $is_mobile_notification = $client_object['is_mobile_notification'];
        $is_push_notification = $client_object['is_push_notification'];
        $message = urlencode($message);


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




            if($company_branch_id ==9){

                //   $_url = 'http://pk.eocean.us/APIManagement/API/RequestAPI?user=Stylo IT&pwd=APzw4c7%2bf0XgoSne8lLeoL7bWMq%2fNHFNFGmy%2bPRkwhElakoluftUyn3Rs4tvYNGDCg%3d%3d&sender=Stylo IT&reciever='.$number.'&msg-data='.$message.'&response=string';
                $_url = 'http://pk.eocean.us/APIManagement/API/RequestAPI?user=Stylo%20IT&pwd=APzw4c7%2bf0XgoSne8lLeoL7bWMq%2fNHFNFGmy%2bPRkwhElakoluftUyn3Rs4tvYNGDCg%3d%3d&sender=Stylo%20IT&reciever='.$number.'&msg-data='.$message.'%20DATA%20HERE&response=string';
                $_url = 'https://pk.eocean.us/APIManagement/API/RequestAPI?user=aas&pwd=APlMLww6m7fsXeBeghBtqlM8y5PNk2Rd2ZZCoLc32z27bwkptwBdyIw7o561CyRUZw%3d%3d&sender=AAS&reciever='.$number.'&msg-data='.$message.'&response=string';

            }elseif($company_branch_id ==13){
                $_url ='http://api.bizsms.pk/api-send-branded-sms.aspx?username=leche@bizsms.pk&pass=1e3chis&text=' .$message .'&masking=LECHE&destinationnum=' . $number . '&language=English';
            }elseif($company_branch_id ==15 OR $company_branch_id ==2){
                $_url ='http://api.bizsms.pk/api-send-branded-sms.aspx?username=conformiz@bizsms.pk&pass=c3nuji8uj99&text='.$message.'&masking=Daily%20Needs&destinationnum='. $number .'&language=English';
                $_url = 'http://api.bizsms.pk/api-send-branded-sms.aspx?username=conformiz@bizsms.pk&pass=c3nuji8uj99&text='.$message.'&masking=Noor%20Milk&destinationnum='.$number.'&language=English';
            }else{
                $_url = 'http://api.bizsms.pk/api-send-branded-sms.aspx?username='.$id.'&pass='.$pass.'&text='.$message.'&masking='.$mask.'&destinationnum='.$number.'&language=English&network='.$network_id;
            }





            if(file_get_contents($_url)) {

                //   $_result_f = json_decode(file_get_contents($_url));
            }else{


            }



        }





}