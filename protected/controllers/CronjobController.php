<?php

class CronjobController extends Controller
{

	public function actionMissing_delivery_message(){

        date_default_timezone_set("Asia/Karachi");

        $company_id =1;
        $query="SELECT 
            r.rider_id,
            r.fullname
            FROM rider AS r
            WHERE r.company_branch_id =1
            AND r.is_active =1";


        $rider_list  =  Yii::app()->db->createCommand($query)->queryAll();

         $message ='';
         foreach ($rider_list as $value){

             $fullname = $value['fullname'];
             $data =[
                'RiderID'=>1,
                'date'=>date("Y-m-d"),
             ];
             $list_object  = self::not_delivery_list($data);
             $list = $list_object['list'];

             $totalmissing = sizeof($list);
             $message .=$fullname .'- '.$totalmissing .' delivery missed';
             foreach ($list as $list_value){


                 $client_name = $list_value['client']['fullname'];
                 $message .=$client_name.'\n';
             }
         }
        $message ='messing_delivery';

        $companyObject = Company::model()->findByPk(intval(1));
        $companyMask = $companyObject['sms_mask'];
        smsLog::saveSms('0' ,1 ,03006053362 ,'missing_delivery' ,$message);
        $this->sendSMS('03006053362' , $message , $companyMask ,1 , 0);


    }

    public static function not_delivery_list($data){

        $company_id =1;
        $riderID =$data['RiderID'];
        $todaydate =  $data['date'];
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




        $query="SELECT p.*  from   product as p 
                where p.company_branch_id =$company_id and p.bottle = 0";
        $productList =  Yii::app()->db->createCommand($query)->queryAll();

        $total_sum_cunt_object = array();
        foreach ($productList as $product){
            $product_id = $product['product_id'];
            $total_sum_cunt_object[$product_id] = 0;
        }


        $clientQuery = "Select c.client_id,c.address , c.cell_no_1 ,c.fullname , z.name as zone_name from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id 
                           left join zone as z ON z.zone_id = c.zone_id
                           where rz.rider_id = '$riderID'  AND c.is_active = 1
                            order by c.rout_order ASC ,c.fullname ASC ";

        $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();

        $cientID = array();
        $cientID[] = 0;
        foreach($clientResult as $value){
            $cientID[] =  $value['client_id'];
        }
        $lientID_list = implode(',',$cientID);

        $notDelivery_finalResult = todayDeliveryQuantityCountForApi::getNotDeliveryTodayResult($lientID_list ,$todaydate);


        $clientPriceObject  = todayDeliveryQuantityCountForApi::getClientBaseProductPRize($lientID_list);

        //  $clientPriceObject_not_delivery_reasonType  = todayDeliveryQuantityCountForApi::getClientBaseNot_delivery_reason_type($lientID_list,$todaydate);

        $clientTotalDeliveredToday = todayDeliveryQuantityCountForApi::getDeliveryTodayAllClient($lientID_list ,$todaydate);

        //   var_dump($clientTotalDeliveredToday);
        //      die();
        $final_view_result = array();

        foreach($clientResult as $clientValue){
            $one_array_result = array();
            $one_array_result['client_id'] = $clientValue['client_id'];
            $one_array_result['zone_name'] = $clientValue['zone_name'];
            $one_array_result['address'] = $clientValue['address'] ;
            $one_array_result['cell_no_1'] = $clientValue['cell_no_1'] ;
            $one_array_result['fullname'] = $clientValue['fullname'] ;

            $totalDelivery_schedule = 0 ;
            $check_totalDelivery = 0;

            $product_SchedualrQuantity = array();
            foreach($productList as $productvalue){
                $one_product_quantity = 0 ;

                $get_product_id = $productvalue['product_id'] ;
                $oneProduct_SchedualrQuantity = array();
                $product_incex = $clientValue['client_id']."_".$productvalue['product_id'];
                $client_id = $clientValue['client_id'];
                $one_array_result['product_id'] = $productvalue['product_id'];
                $one_array_result['productName'] = $productvalue['name'];
                if(isset($clientPriceObject[$product_incex])){
                    $one_array_result['price'] = $clientPriceObject[$product_incex];

                }else{

                    $one_array_result['price'] = $productvalue['price'];

                }
                //  $totalInterval_quantity =  utill::getOneCustomerTodayIntervalSceduler_with_date( $clientValue['client_id'],$productvalue['product_id'] ,$todaydate);
                $totalInterval_quantity =  future_selet_date::getOneCustomerTodayIntervalSceduler_with_date_future_date($clientValue['client_id'],$productvalue['product_id'] ,$todaydate);
                $totalDelivery_schedule = $totalDelivery_schedule  + $totalInterval_quantity ;
                $one_product_quantity = $one_product_quantity + $totalInterval_quantity ;
                $totalWeekly_quantity  =    todayDeliveryQuantityCountForApi::getTodayDeliveryCountWeeklyRegularAndSpecial($clientValue['client_id'] ,$productvalue['product_id'], $todaydate);

                $totalDelivery_schedule = $totalDelivery_schedule  + $totalWeekly_quantity ;

                $one_product_quantity = $one_product_quantity + $totalWeekly_quantity ;

                $one_array_result['regularQuantity'] = $totalInterval_quantity + $totalWeekly_quantity ;

                $totalSpecialToday_quantity  =    todayDeliveryQuantityCountForApi::getTodaySpecialOrder($clientValue['client_id'] ,$productvalue['product_id'] ,$todaydate);

                $totalDelivery_schedule = $totalDelivery_schedule  + $totalSpecialToday_quantity ;

                $one_product_quantity = $one_product_quantity + $totalSpecialToday_quantity ;

                $one_array_result['totalSpecialQuantity'] = $totalSpecialToday_quantity;


                if($totalDelivery_schedule > 0 ){
                    if($check_totalDelivery == 0){

                    }
                }



                if(isset($clientTotalDeliveredToday[$product_incex])){
                    $one_array_result['deliveredQuantity'] = $clientTotalDeliveredToday[$product_incex]['deliveredQuantity'] ;
                    $one_array_result['time'] = $clientTotalDeliveredToday[$product_incex]['time'] ;

                    $check_totalDelivery = $clientTotalDeliveredToday[$product_incex]['deliveredQuantity'] ;
                }else{
                    $one_array_result['deliveredQuantity'] = 0;
                }

                if(isset($notDelivery_finalResult[$client_id]) ){
                    // var_dump($notDelivery_finalResult[$client_id]['time']);
                    // die();
                    $one_array_result['time'] = $notDelivery_finalResult[$client_id]['time'];
                    $one_array_result['reasonType_name'] = $notDelivery_finalResult[$client_id]['reasonType_name'];
                    $one_array_result['reject_delivery'] = true;

                }else{
                    //   $one_array_result['time'] = $value['time'] ;
                    $one_array_result['reasonType_name'] = '';
                    $one_array_result['reasonType_name'] = '';
                    $one_array_result['reject_delivery'] = false;

                }
                $oneProduct_SchedualrQuantity['quantity']= $one_product_quantity ;
                $product_SchedualrQuantity[] = $oneProduct_SchedualrQuantity;

                if($totalDelivery_schedule > 0 ){
                    if($check_totalDelivery == 0){
                        $total_sum_cunt_object[$get_product_id] = $total_sum_cunt_object[$get_product_id] + $one_product_quantity;

                    }
                }

            }



            if($totalDelivery_schedule > 0 ){
                if($check_totalDelivery == 0){
                    $one_object = array();
                    $one_object['client'] = $one_array_result ;
                    $one_object['product'] = $product_SchedualrQuantity ;
                    $final_view_result[] = $one_object;
                }
            }

        }
        // var_dump($total_sum_cunt_object);
        // die();
        $last_object_count_sum = array();
        foreach ($productList as $product){
            $product_id = $product['product_id'];
            $last_object_count_sum [] = $total_sum_cunt_object[$product_id];
        }
        $List_record = array();
        $List_record['list'] = $final_view_result ;
        $List_record['totalSum'] = $last_object_count_sum ;

        return $List_record;


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

}
