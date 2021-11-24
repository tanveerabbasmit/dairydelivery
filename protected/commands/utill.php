<?php

/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 7/18/2017
 * Time: 3:01 PM
 */
class utill
{
    public static function get_smsMask($id){
        $com = Company::model()->findByPk($id);
        return $com['sms_mask'];
    }

    public static function get_companyTitle($id){
        $com = Company::model()->findByPk($id);
        return $com;
    }

    public static function sendSMS2($num, $message , $mask , $company_branch_id , $network_id,$cleint_id){




        $client_object = Client::model()->findByPk($cleint_id);

        $is_mobile_notification = $client_object['is_mobile_notification'];

        $is_push_notification = $client_object['is_push_notification'];


        if($is_mobile_notification==1){


            // convert a number to 923
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

                $_url = 'http://pk.eocean.us/APIManagement/API/RequestAPI?user=Stylo%20IT&pwd=APzw4c7%2bf0XgoSne8lLeoL7bWMq%2fNHFNFGmy%2bPRkwhElakoluftUyn3Rs4tvYNGDCg%3d%3d&sender=Stylo%20IT&reciever='.$number.'&msg-data='.$message.'&response=string';
                $_url = 'https://pk.eocean.us/APIManagement/API/RequestAPI?user=aas&pwd=APlMLww6m7fsXeBeghBtqlM8y5PNk2Rd2ZZCoLc32z27bwkptwBdyIw7o561CyRUZw%3d%3d&sender=AAS&reciever='.$number.'&msg-data='.$message.'&response=string';
            }elseif($company_branch_id ==13){
                $_url ='http://api.bizsms.pk/api-send-branded-sms.aspx?username=leche@bizsms.pk&pass=1e3chis&text=' .$message .'&masking=LECHE&destinationnum=' . $number . '&language=English';

            }elseif($company_branch_id ==15 OR $company_branch_id ==2){
                $_url ='http://api.bizsms.pk/api-send-branded-sms.aspx?username=conformiz@bizsms.pk&pass=c3nuji8uj99&text='.$message.'&masking=Daily%20Needs&destinationnum='. $number .'&language=English';
                $_url = 'http://api.bizsms.pk/api-send-branded-sms.aspx?username=conformiz@bizsms.pk&pass=c3nuji8uj99&text='.$message.'&masking=Noor%20Milk&destinationnum='.$number.'&language=English';
            }else{
                $_url = 'http://api.bizsms.pk/api-send-branded-sms.aspx?username='.$id.'&pass='.$pass.'&text='.$message.'&masking='.$mask.'&destinationnum='.$number.'&language=English&network='.$network_id;
            }




            if($_result = file_get_contents($_url)) {
                $_result_f = json_decode($_result);
            }else{
                echo "not Send";
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

    public static function getDelivery_count($client_id){

        $query = "select ifnull(sum(dd.quantity) ,0) as quantity from delivery as d
                left join delivery_detail as dd  ON dd.delivery_id = d.delivery_id
                where d.client_id = '$client_id' ";
        $result =Yii::app()->db->createCommand($query)->queryAll();

        return $result[0]['quantity'] ;

    }

    public static function getRiderName($zone_id){

        $query = "select IFNULL(r.fullname ,'No Rider Assigned') as rider_name , r.cell_no_1 from rider_zone as rz
                        left join rider as r ON r.rider_id = rz.rider_id
                        where rz.zone_id = $zone_id
                        limit 1 ";
        $result =Yii::app()->db->createCommand($query)->queryAll();


        if($result){

        }else{
            $result1 = array();
            $result1['rider_name'] = 'Not Assigned';
            $result1['cell_no_1'] = 'Not Assigned';
            $result = array();
            $result[] = $result1 ;

        }

        return $result ;
    }

    public static function getOneCustomerTodayIntervalSceduler($client_id , $product_id){

        date_default_timezone_set("Asia/Karachi");
        $todayDate = date("Y-m-d");
        $oneClientQuery = "  select list.interval_days ,list.product_quantity,(
                                 select d.date from delivery as d
                                        left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                                        where d.client_id = '$client_id' and dd.product_id = '$product_id' 
                                        order by d.date DESC limit 1
                                                  ) as date from  interval_scheduler as list
                                    where list.client_id = '$client_id' and list.product_id='$product_id' 
                                    and list.start_interval_scheduler <= '$todayDate'
                                    and '$todayDate' not between list.halt_start_date and list.halt_end_date " ;
        $clientResult =  Yii::app()->db->createCommand($oneClientQuery)->queryAll();

        $interval_quantity = 0;
        if($clientResult){
            $interval_days = $clientResult[0]['interval_days'];
            if($clientResult[0]['date']){
                $lastDelivery = $clientResult[0]['date'];
                $today_time = strtotime($todayDate);
                $last_delivery_time = strtotime($lastDelivery);
                $diff_time = $today_time - $last_delivery_time;
                $total_days_to_delivery =floor($diff_time/(60*60*24));
                if($interval_days <= $total_days_to_delivery){
                    $interval_quantity =$clientResult[0]['product_quantity'];
                }
            }else{
                $interval_quantity =$clientResult[0]['product_quantity'];
            }

        }
        return $interval_quantity;
    }

    public static function getOneCustomerTodayIntervalSceduler_with_date($client_id , $product_id ,$todayDate){

        date_default_timezone_set("Asia/Karachi");

        $oneClientQuery = "  select list.interval_days ,list.product_quantity,(
                                 select d.date from delivery as d
                                        left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                                        where d.client_id = '$client_id' and dd.product_id = '$product_id' 
                                        order by d.date DESC limit 1
                                                  ) as date from  interval_scheduler as list
                                    where list.client_id = '$client_id' and list.product_id='$product_id' 
                                    and list.start_interval_scheduler <= '$todayDate'
                                    and '$todayDate' not between list.halt_start_date and list.halt_end_date " ;
        $clientResult =  Yii::app()->db->createCommand($oneClientQuery)->queryAll();

        $interval_quantity = 0;
        if($clientResult){
            $interval_days = $clientResult[0]['interval_days'];
            if($clientResult[0]['date']){
                $lastDelivery = $clientResult[0]['date'];
                $today_time = strtotime($todayDate);
                $last_delivery_time = strtotime($lastDelivery);
                $diff_time = $today_time - $last_delivery_time;
                $total_days_to_delivery =floor($diff_time/(60*60*24));
                if($interval_days <= $total_days_to_delivery){
                    $interval_quantity =$clientResult[0]['product_quantity'];
                }
            }else{
                $interval_quantity =$clientResult[0]['product_quantity'];
            }

        }
        return $interval_quantity;
    }

    public static function getOneCustomerTodayIntervalScedulerForAllProduct($client_id){
        date_default_timezone_set("Asia/Karachi");
        $todayDate = date("Y-m-d");
        $oneClientQuery = "  select list.interval_days ,list.product_quantity,(
                                 select d.date from delivery as d
                                        left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                                        where d.client_id = '$client_id'  
                                        order by d.date DESC limit 1
                                                  ) as date from  interval_scheduler as list
                                    where list.client_id = '$client_id' 
                                    and list.start_interval_scheduler <= '$todayDate'
                                    and '$todayDate' not between list.halt_start_date and list.halt_end_date " ;
        $clientResult =  Yii::app()->db->createCommand($oneClientQuery)->queryAll();
        $interval_quantity = 0;
        if($clientResult){
            $interval_days = $clientResult[0]['interval_days'];
            if($clientResult[0]['date']){
                $lastDelivery = $clientResult[0]['date'];
                $today_time = strtotime($todayDate);
                $last_delivery_time = strtotime($lastDelivery);
                $diff_time = $today_time - $last_delivery_time;
                $total_days_to_delivery =floor($diff_time/(60*60*24));
                if($interval_days <= $total_days_to_delivery){
                    $interval_quantity =$clientResult[0]['product_quantity'];
                }
            }else{
                $interval_quantity =$clientResult[0]['product_quantity'];
            }

        }
        return $interval_quantity;
    }



}