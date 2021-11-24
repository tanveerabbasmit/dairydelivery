<?php

/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 8/31/2017
 * Time: 1:09 PM
 */
class mangeDelivery
{

    public static  function saveDelivery($data , $totalAmount ,$companyObject){

        $clientID = $data['client_id'];
        $riderID = $data['rider_id'];
        $latitude = $data['lat'];
        $longitude = $data['longi'];
        $productObject = $data['data'];

        $company_branch_id = $data['company_branch_id'];


        date_default_timezone_set("Asia/Karachi");
        $selected_date =  date("Y-m-d") ;
        $make_already_delivery = false ;
        $query = "select dd.product_id from delivery as d
                     left join delivery_detail as dd  ON d.delivery_id = dd.delivery_id
                     where d.client_id ='$clientID' and  d.date = '$selected_date' ";
        $todayDeliveryReuslt = Yii::app()->db->createCommand($query)->queryAll();

        foreach($todayDeliveryReuslt as $value){
            if($value['product_id'] != 34){

                $make_already_delivery = true;
            }

        }
        if($make_already_delivery){
            return  1;
            die();
        }
        $company_branch_id = $data['company_branch_id'];
        $delivery = new Delivery();
        $delivery->company_branch_id = $data['company_branch_id'] ;
        $delivery->client_id = $clientID;
        $delivery->rider_id = $riderID ;
        $delivery->date = date("Y-m-d") ;
        $delivery->time = date("H:i") ;
        $delivery->tax_percentage = 0 ;
        $delivery->amount_with_tax = 0 ;
        $delivery->tax_amount = 0 ;
        $delivery->latitude = $latitude ;
        $delivery->longitude = $longitude ;
        $delivery->amount = 0 ;
        $delivery->discount_percentage = 0 ;
        $delivery->total_amount = ($totalAmount);
        $delivery->partial_amount = $totalAmount;
        if($delivery->save()){

             $client_object = Client::model()->findByPk($clientID);
             $latitude_get = $client_object['latitude'];
             if($latitude_get==0){
                 $client_object->latitude  =$latitude;
                 $client_object->longitude = $longitude;
                 $client_object->save();
             }


            if($company_branch_id ==15){
                if($latitude>0 OR $longitude>0){
                    LatitudeLongitudeData::actionSaveDimention($clientID,$latitude,$longitude);
                }
            }

            $deliveryID = $delivery->delivery_id ;
            foreach($productObject as $value){
                if($value['quantity'] != 0){
                    $deliveryDetail = new DeliveryDetail();
                    $deliveryDetail->delivery_id = $deliveryID ;
                    $deliveryDetail->product_id = $value['product_id'] ;
                    $deliveryDetail->date = date("Y-m-d") ;
                    $deliveryDetail->quantity = $value['quantity'] ;
                    $deliveryDetail->amount = $value['price'] * $value['quantity'] ;
                    $deliveryDetail->adjust_amount = 0 ;
                    if($deliveryDetail->save()){
                    }else{
                    }
                }
            }
            one_time_delivery_data::de_active_customer($clientID);
            setTotalAmount::total_amount($deliveryID);
            APIData::UpdatesaveDeliveryFunction($clientID);
        }else{

        }
        $response = array(
            'code' => 200,
            'company_branch_id'=>0,
            'delivery_time'=>date("H:i"),
            'success' => true,
            'message'=>'Save Delivery',
            'data' => []
        );
        $clientObject = Client::model()->findByPk(intval($clientID));
        $phoneNo =  $clientObject['cell_no_1'];
        $fullname = $clientObject['fullname'];
        $network_id = $clientObject['network_id'];

        $deliverySms =  $clientObject['daily_delivery_sms'];

        $qualtiyReport_sms =  $companyObject['qualtiyReport_sms'];

        $message ='';

        if($company_branch_id==1){

            $message .= "Milk Delivery Report\n" .date("Y M d")."\n";
            foreach($productObject as $value){
                if($value['quantity'] !=0){
                    $count_totalPrice = $value['price'] * $value['quantity'] ;
                    $productID = $value['product_id'];
                    $productName =Product::model()->findByPk(intval($productID));
                    $message .= "Qty- ".$value['quantity'].' '.$productName['unit'];
                    // $message .= $count_totalPrice."\n";
                    $totalAmount = $totalAmount + $count_totalPrice ;
                }
            }
            $message .= "\nTime- ".date("h:i a");


            $currentBalance = APIData::calculateFinalBalance($clientID);
            $companyMask = $companyObject['sms_mask'];
            $companyTitle = $companyObject['company_title'];

            $message .= "\nPayment Balance- Rs".($currentBalance);
            //  $message .="\n".$companyTitle."\n In case of any difference please contact us.";
            $message .="\nTaza Farms.\nCustomer Services\n0321 111 TAZA(8292)";



        }else{

            if($company_branch_id==10){
                $today_date_blaze =' for '.date('d').date('M').' ';
                $change_name = str_replace("Mr.","",$fullname);
                $change_name = str_replace("M/S","",$change_name);
                $change_name = str_replace("Mr","",$change_name);
                $change_name = str_replace("Mrs","",$change_name);
                $change_name = str_replace("Dr.","",$change_name);
                $change_name = str_replace("Miss","",$change_name);
                $message .= 'Dear '.$change_name;
            }else{

                $message .= 'Dear Customer'."\n";
                $today_date_blaze='';
            }



            $message .= "Delivery Report".$today_date_blaze."\n@".date("H:i")."\n";
            $totalAmount = 0 ;
            foreach($productObject as $value){
                if($value['quantity'] !=0){
                    $count_totalPrice = $value['price'] * $value['quantity'] ;
                    $productID = $value['product_id'];
                    $productName =Product::model()->findByPk(intval($productID));
                    $message .= $productName['name'].' '.$value['quantity'].' '.$productName['unit'].' ,Rs.';
                    $message .= $count_totalPrice."\n";
                    $totalAmount = $totalAmount + $count_totalPrice ;
                }

            }

            $currentBalance = APIData::calculateFinalBalance($clientID);


            $companyMask = $companyObject['sms_mask'];
            $companyTitle = $companyObject['company_title'];

            $message .= "Bill: Rs.".($totalAmount);
            $message .= "\nBalance: Rs.".($currentBalance);



            if($qualtiyReport_sms == 1 || $qualtiyReport_sms == 10){

                $todayDate = date("Y-m-d");
                $qualityreportObject = MilkDailyQuallityreport::model()->findByAttributes(array('company_branch_id'=>$company_branch_id ,'date'=>$todayDate ));

                if($qualityreportObject){
                    $protein = $qualityreportObject['protein'];
                    $lactose = $qualityreportObject['lactose'];
                    $fat = $qualityreportObject['fat'];
                    $salt = $qualityreportObject['salt'];
                    $adulterants = $qualityreportObject['adulterants'];
                    $antiboitics = $qualityreportObject['antiboitics'];
                    $message .= "\nToday Quality Report\n";
                    $message .= "Protein: ".$protein." ";
                    $message .= "Lactose: ".$lactose." ";
                    $message .= "Fat: ".$fat." ";
                    $message .= "Salt: ".$salt." ";
                    $message .= "Density: ".$adulterants."\n";
                    $message .= "No adulterants or antibiotic traces were detected";
                }
            }
            if($company_branch_id==15){
                $message .= "\nThanks for Choosing Noor
                    03 111 222 572 ";
            }else{
                $message .="\n".$companyTitle."\nFor info contact us @".$companyObject['phone_number'];
            }

            if($company_branch_id==10){
                $message .="\nIf it is not correct kindly inform on the numbers above Otherwise management will not be responsible.";
            }

        }






        if($deliverySms==1){

//             $phoneNo = $value['cell_no_1'];
//             $fullname = $value['fullname'];
//             $client_id = $value['client_id'];
//             $phoneNo = $value['cell_no_1'];


            smsLog::saveSms($clientID ,$company_branch_id ,$phoneNo ,$fullname ,$message);
            manageSendSMS::sendSMS($phoneNo , $message , $companyMask , $company_branch_id ,$network_id,$clientID);
        }

        if(false){

            $todayDate = date("Y-m-d");
            $qualityreportObject = MilkDailyQuallityreport::model()->findByAttributes(array('company_branch_id'=>$company_branch_id ,'date'=>$todayDate ));

            if($qualityreportObject){
                $protein = $qualityreportObject['protein'];
                $lactose = $qualityreportObject['lactose'];
                $fat = $qualityreportObject['fat'];
                $salt = $qualityreportObject['salt'];
                $adulterants = $qualityreportObject['adulterants'];
                $antiboitics = $qualityreportObject['antiboitics'];
                $message = "Today Quality Report\n";
                $message .= "Protein : ".$protein."\n";
                $message .= "Lactose : ".$lactose."\n";
                $message .= "fat : ".$fat."\n";
                $message .= "salt : ".$salt."\n";
                $message .= "adulterants : ".$adulterants."\n";


                manageSendSMS::sendSMS($phoneNo , $message ,$companyMask ,$company_branch_id,$clientID);
            }
        }

        return  2;
    }

    public static  function saveDeliveryForPortal($data , $totalAmount ,$companyObject){

        date_default_timezone_set("Asia/Karachi");
        $clientID = $data['client_id'];
        $riderID = $data['rider_id'];
        $latitude = $data['lat'];
        $longitude = $data['longi'];
        $productObject = $data['data'];
        $selectDate = $data['selectDate'];
        $newDate = date("d  M Y", strtotime($selectDate));
        $company_branch_id = $data['company_branch_id'];
        $product_id = $data['data'][0]['product_id'];
        $selected_date = $data['selectDate'];
        if(isset($data['remarks'])){
            $remarks = $data['remarks'];
        }else{
            $data['remarks'] = '';
        }

        if($data['deliveredQuantity'] > 0){

            $new_value = $data['data'][0]['quantity'];

            $action_name ='edit_delivery';
            $modify_table_name ='delivery_detail';
            $modify_id =$clientID;
            $clientID =$clientID;
            $selected_date =$selectDate;
            $data_befour_action =json_encode($data);


            save_every_crud_record::save_crud_record_date_waise(
                $action_name,
                $modify_table_name,
                $modify_id,
                $selected_date,
                $data_befour_action,
                $new_value,
                $clientID,
                $remarks
            );
            $product_id =  $productObject[0]['product_id'];

            $delivery = Delivery::model()->findByAttributes(array('client_id'=>$clientID, 'date'=>$selected_date));

            $delivery_id = $delivery['delivery_id'];
            $total_amount  = $delivery['total_amount'];
            $partial_amount  = $delivery['partial_amount'];
            if($total_amount == $partial_amount){
                $deliveryObject = Delivery::model()->findByAttributes(array('client_id'=>$clientID, 'date'=>$selected_date));
                $deliveryId  = $deliveryObject['delivery_id'];
                DeliveryDetail::model()->deleteAllByAttributes(array('delivery_id'=>$deliveryId ,'product_id'=>$product_id));
                $delivery->company_branch_id = $data['company_branch_id'] ;
                $delivery->client_id = $clientID;
                $delivery->rider_id = $riderID ;
                $delivery->date = $selectDate ;
                $delivery->time = date("H:i") ;
                $delivery->tax_percentage = 0 ;
                $delivery->amount_with_tax = 0 ;
                $delivery->tax_amount = 0 ;
                $delivery->latitude = $latitude ;
                $delivery->longitude = $longitude ;
                $delivery->amount = 0 ;
                $delivery->discount_percentage = Yii::app()->user->getState('user_id');
                $delivery->total_amount = ($totalAmount);
                $delivery->partial_amount = $totalAmount;
                $delivery->edit_by_user = Yii::app()->user->getState('user_id');
                if($delivery->save()){
                    //   $deliveryDetailObject = DeliveryDetail::model()->findByAttributes(array('product_id'=>$product_id , 'delivery_id'=>$delivery_id));
                    //  $deliveryDetailID = $deliveryDetailObject['delivery_detail_id'];
                    foreach($productObject as $value){
                        if($value['quantity'] != 0){
                            $deliveryDetail = new DeliveryDetail();
                            $deliveryDetail->delivery_id = $delivery_id ;
                            $deliveryDetail->product_id = $value['product_id'] ;
                            $deliveryDetail->date = $selectDate ;
                            $deliveryDetail->quantity = $value['quantity'] ;
                            $deliveryDetail->amount = $value['price'] * $value['quantity'] ;
                            $deliveryDetail->adjust_amount = 0 ;
                            if($deliveryDetail->save()){
                            }else{
                            }
                        }
                    }
                }
                $clientObject = Client::model()->findByPk(intval($clientID));
                $phoneNo =  $clientObject['cell_no_1'];
                $fullname = $clientObject['fullname'];
                $network_id = $clientObject['network_id'];
                $network_id = $clientObject['network_id'];
                $deliverySms =  $clientObject['daily_delivery_sms'];
                $qualtiyReport_sms =  $companyObject['qualtiyReport_sms'];

                $message ='';
                if($company_branch_id=='10'){
                    $change_name = str_replace("Mr.","",$fullname);
                    $change_name = str_replace("M/S","",$change_name);
                    $change_name = str_replace("Mr","",$change_name);
                    $change_name = str_replace("Mrs","",$change_name);
                    $change_name = str_replace("Dr.","",$change_name);
                    $change_name = str_replace("Miss","",$change_name);
                    $message .= 'Dear '.$change_name;
                }else{
                    $message .= 'Dear Customer '."\n";
                }
                $message .= " ,Please note correction in the supply made to you on  $newDate  ,\n";
                $message .= "Delivery Report\n@" .$newDate." ".date("H:i")."\n";
                $totalAmount = 0 ;

                foreach($productObject as $value){
                    if($value['quantity'] !=0){
                        $count_totalPrice = $value['price'] * $value['quantity'] ;
                        $productID = $value['product_id'];
                        $productName =Product::model()->findByPk(intval($productID));
                        $message .= $productName['name'].' '.$value['quantity'].' '.$productName['unit'].' ,Rs.';
                        $message .= $count_totalPrice."\n";
                        $totalAmount = $totalAmount + $count_totalPrice ;
                    }
                }

                $currentBalance = APIData::calculateFinalBalance($clientID);

                $companyMask = $companyObject['sms_mask'];
                $companyTitle = $companyObject['company_title'];
                $message .= "Total bill: Rs.".($totalAmount);
                $message .="\n Ignore previous message sent to you for this date.";
                if($company_branch_id==15){
                    $message .= "\nThanks for Choosing Noor 03 111 222 572 ";
                }
                if($company_branch_id==10){
                    $message .="\nIf it is not correct kindly inform on the numbers above Otherwise management will not be responsible.";
                }

                if($deliverySms==1){
                    /* $phoneNo = $value['cell_no_1'];
                     $fullname = $value['fullname'];
                     $client_id = $value['client_id'];
                     $phoneNo = $value['cell_no_1'];*/
                    smsLog::saveSms($clientID ,$company_branch_id ,$phoneNo ,$fullname ,$message);
                    manageSendSMS::sendSMS($phoneNo , $message , $companyMask , $company_branch_id,$network_id,$clientID);
                }
                notification_alert_data::notification_function($clientObject ,$company_branch_id  ,$currentBalance,$companyObject);
                $response = array(
                    'code' => 200,
                    'company_branch_id'=>0,
                    'delivery_time'=>date("H:i"),
                    'success' => true,
                    'message'=>'You  edit this delivery',
                    'data' => []
                );
                setTotalAmount::total_amount($deliveryId);
                return  json_encode($response);
            }else{

                $allready_piad_amunt =  $total_amount-$partial_amount;
                $new_partial_amont = $totalAmount - $allready_piad_amunt;
                /*===========================================================================================================================*/
                $deliveryObject = Delivery::model()->findByAttributes(array('client_id'=>$clientID, 'date'=>$selected_date));
                $deliveryId  = ($deliveryObject['delivery_id']);
                DeliveryDetail::model()->deleteAllByAttributes(array('delivery_id'=>$deliveryId ,'product_id'=>$product_id));
                $delivery->company_branch_id = $data['company_branch_id'] ;
                $delivery->client_id = $clientID;
                $delivery->rider_id = $riderID ;
                $delivery->date = $selectDate ;
                $delivery->time = date("H:i") ;
                $delivery->tax_percentage = 0 ;
                $delivery->amount_with_tax = 0 ;
                $delivery->tax_amount = 0 ;
                $delivery->latitude = $latitude ;
                $delivery->longitude = $longitude ;
                $delivery->amount = 0 ;
                $delivery->discount_percentage = Yii::app()->user->getState('user_id');
                $delivery->total_amount    = $totalAmount;
                $delivery->partial_amount = $new_partial_amont;
                $delivery->edit_by_user = Yii::app()->user->getState('user_id');
                if($delivery->save()){
                    //   $deliveryDetailObject = DeliveryDetail::model()->findByAttributes(array('product_id'=>$product_id , 'delivery_id'=>$delivery_id));
                    //  $deliveryDetailID = $deliveryDetailObject['delivery_detail_id'];
                    foreach($productObject as $value){
                        /* if($value['quantity'] != 0){*/
                        $deliveryDetail = new DeliveryDetail();
                        $deliveryDetail->delivery_id = $delivery_id ;
                        $deliveryDetail->product_id = $value['product_id'] ;
                        $deliveryDetail->date = $selectDate ;
                        $deliveryDetail->quantity = $value['quantity'] ;
                        $deliveryDetail->amount = $value['price'] * $value['quantity'] ;
                        $deliveryDetail->adjust_amount = 0 ;
                        if($deliveryDetail->save()){
                        }else{
                        }
                        /* }*/
                    }
                }else{
                    var_dump($delivery->getError());
                }
                $clientObject = Client::model()->findByPk(intval($clientID));
                $phoneNo =  $clientObject['cell_no_1'];
                $fullname = $clientObject['fullname'];
                $network_id = $clientObject['network_id'];
                $network_id = $clientObject['network_id'];
                $deliverySms =  $clientObject['daily_delivery_sms'];
                $qualtiyReport_sms =  $companyObject['qualtiyReport_sms'];
                $message ='';
                if($company_branch_id =='10'){
                    $change_name = str_replace("Mr.","",$fullname);
                    $change_name = str_replace("M/S","",$change_name);
                    $change_name = str_replace("Mr","",$change_name);
                    $change_name = str_replace("Mrs","",$change_name);
                    $change_name = str_replace("Dr.","",$change_name);
                    $change_name = str_replace("Miss","",$change_name);
                    $message .= 'Dear '.$change_name;
                }else{
                    $message .= 'Dear Customer'."\n";;
                }

                $message .= " ,Please note correction in the supply made to you on  $newDate  ,\n";
                $message .= "Delivery Report\n@" .$newDate." ".date("H:i")."\n";
                $totalAmount = 0 ;
                foreach($productObject as $value){
                    if($value['quantity'] !=0){
                        $count_totalPrice = $value['price'] * $value['quantity'] ;
                        $productID = $value['product_id'];
                        $productName =Product::model()->findByPk(intval($productID));
                        $message .= $productName['name'].' '.$value['quantity'].' '.$productName['unit'].' ,Rs.';
                        $message .= $count_totalPrice."\n";
                        $totalAmount = $totalAmount + $count_totalPrice ;
                    }
                }
                $currentBalance = APIData::calculateFinalBalance($clientID);
                $companyMask = $companyObject['sms_mask'];
                $companyTitle = $companyObject['company_title'];
                $message .= "Total bill: Rs.".($totalAmount);
                $message .="\n Ignore previous message sent to you for this date.";

                if($company_branch_id==10){
                    $message .="\nIf it is not correct kindly inform on the numbers above Otherwise management will not be responsible.";
                }

                if($deliverySms==1){
                    /* $phoneNo = $value['cell_no_1'];
                     $fullname = $value['fullname'];
                     $client_id = $value['client_id'];
                     $phoneNo = $value['cell_no_1'];*/

                    smsLog::saveSms($clientID ,$company_branch_id ,$phoneNo ,$fullname ,$message);
                    manageSendSMS::sendSMS($phoneNo , $message , $companyMask , $company_branch_id,$network_id,$clientID);
                }
                notification_alert_data::notification_function($clientObject ,$company_branch_id  ,$currentBalance,$companyObject);
                $response = array(
                    'code' => 200,
                    'company_branch_id'=>0,
                    'delivery_time'=>date("H:i"),
                    'success' => true,
                    'message'=>'You  edit this delivery',
                    'data' => []
                );
                setTotalAmount::total_amount($deliveryId);
                return  json_encode($response);

                /*===========================================================================================================================*/
                $response = array(
                    'code' => 200,
                    'company_branch_id'=>0,
                    'delivery_time'=>date("H:i"),
                    'success' => false,
                    'message'=>'You can not edit this delivery',
                    'data' => []
                );

            }
        }else{
            $delivery = Delivery::model()->findByAttributes(array('client_id'=>$clientID, 'date'=>$selected_date));
            if($delivery){

            }else{
                $delivery = new Delivery();
            }

            if($totalAmount==0){
                $responce=array();
                $responce['success']= false ;
                $responce['message']= "You can not deliver this product with zero rate" ;
                return json_encode($responce);
                die();
            }

            $delivery->company_branch_id = $data['company_branch_id'] ;
            $delivery->client_id = $clientID;
            $delivery->rider_id = $riderID ;
            $delivery->date = $selectDate ;
            $delivery->time = date("H:i") ;
            $delivery->tax_percentage = 0 ;
            $delivery->amount_with_tax = 0 ;
            $delivery->tax_amount = 0 ;
            $delivery->latitude = $latitude ;
            $delivery->longitude = $longitude ;
            $delivery->amount = 0 ;
            $delivery->discount_percentage = 0 ;
            $delivery->total_amount = ($totalAmount);
            $delivery->partial_amount = $totalAmount;

            if($delivery->save()){
                $deliveryID = $delivery->delivery_id ;
                foreach($productObject as $value){
                    /* if($value['quantity'] != 0){*/
                    $deliveryDetail = new DeliveryDetail();
                    $deliveryDetail->delivery_id = $deliveryID ;
                    $deliveryDetail->product_id = $value['product_id'] ;
                    $deliveryDetail->date = $selectDate ;
                    $deliveryDetail->quantity = $value['quantity'] ;
                    $deliveryDetail->amount = $value['price'] * $value['quantity'] ;
                    $deliveryDetail->adjust_amount = 0 ;
                    if($deliveryDetail->save()){
                    }else{
                        var_dump($deliveryDetail->getErrors());
                    }
                    /* }*/
                }

                 one_time_delivery_data::de_active_customer($clientID);
                setTotalAmount::total_amount($deliveryID);
                APIData::UpdatesaveDeliveryFunction($clientID);
            }else{

            }

            $response = array(
                'code' => 200,
                'company_branch_id'=>0,
                'delivery_time'=>date("H:i"),
                'success' => true,
                'message'=>'Save Delivery',
                'data' => []
            );
            $clientObject = Client::model()->findByPk(intval($clientID));
            $phoneNo =  $clientObject['cell_no_1'];
            $fullname = $clientObject['fullname'];
            $network_id = $clientObject['network_id'];
            $deliverySms =  $clientObject['daily_delivery_sms'];
            $qualtiyReport_sms =  $companyObject['qualtiyReport_sms'];
            $qualtiyReport_sms =  $companyObject['qualtiyReport_sms'];
            $message ='';
            if($company_branch_id ==10 || $company_branch_id ==2){
                $change_name = str_replace("Mr.","",$fullname);
                $change_name = str_replace("M/S","",$change_name);
                $change_name = str_replace("Mr","",$change_name);
                $change_name = str_replace("Mrs","",$change_name);
                $change_name = str_replace("Dr.","",$change_name);
                $change_name = str_replace("Dr","",$change_name);
                $change_name = str_replace("Miss","",$change_name);
                $message .= 'Dear '.$change_name;
            }
            //$message .= "Delivery Report\n@" .$newDate." ".date("H:i")."\n";

            $totalAmount = 0 ;

            if($company_branch_id ==1){

                $message .= "Milk Delivery Report\n" .$newDate."\n";
                foreach($productObject as $value){
                    if($value['quantity'] !=0){
                        $count_totalPrice = $value['price'] * $value['quantity'] ;
                        $productID = $value['product_id'];
                        $productName =Product::model()->findByPk(intval($productID));
                        $message .= "Qty- ".$value['quantity'].' '.$productName['unit'];
                        // $message .= $count_totalPrice."\n";
                        $totalAmount = $totalAmount + $count_totalPrice ;
                    }
                }
                $message .= "\nTime- ".date("h:i a");


                $currentBalance = APIData::calculateFinalBalance($clientID);
                $companyMask = $companyObject['sms_mask'];
                $companyTitle = $companyObject['company_title'];

                $message .= "\nPayment Balance- Rs".(round($currentBalance,0));
                //  $message .="\n".$companyTitle."\n In case of any difference please contact us.";
                $message .="\nTAZA\nCustomer Services\n03341118292\n03021118292";

            }else{

                $message .= "Delivery Report\n@" .$newDate." ".date("H:i")."\n";
                foreach($productObject as $value){
                    if($value['quantity'] !=0){
                        $count_totalPrice = $value['price'] * $value['quantity'] ;
                        $productID = $value['product_id'];
                        $productName =Product::model()->findByPk(intval($productID));
                        $message .= $productName['name'].' '.$value['quantity'].' '.$productName['unit'].' ,Rs.';
                        $message .= $count_totalPrice."\n";
                        $totalAmount = $totalAmount + $count_totalPrice ;
                    }
                }

                $currentBalance = APIData::calculateFinalBalance($clientID);
                $companyMask = $companyObject['sms_mask'];
                $companyTitle = $companyObject['company_title'];
                $message .= "Total bill: Rs.".($totalAmount);
                $message .= "\nCurrent balance: Rs.".($currentBalance);
                //  $message .="\n".$companyTitle."\n In case of any difference please contact us.";

                if($company_branch_id==10 || $company_branch_id==2){
                    $todayDate = date("Y-m-d");
                    foreach ($productObject as $value){
                        $product_id = $value['product_id'];
                        $productName = $value['productName'];
                        $qualityreportObject = MilkDailyQuallityreport::model()->findByAttributes(array('company_branch_id'=>$company_branch_id ,'date'=>$todayDate,'animal_type'=>$product_id ));
                        if($qualityreportObject){
                            $protein = $qualityreportObject['protein'];
                            $lactose = $qualityreportObject['lactose'];
                            $fat = $qualityreportObject['fat'];
                            $salt = $qualityreportObject['salt'];
                            $adulterants = $qualityreportObject['adulterants'];
                            $antiboitics = $qualityreportObject['antiboitics'];
                            $message .= "\nTodays Quality Report of ".$productName." \n";
                            $message .= "Protein: ".$protein." ";
                            $message .= "Lactose: ".$lactose." ";
                            $message .= "Fat: ".$fat." ";
                            $message .= "Salt: ".$salt." ";
                            $message .= "Density: ".$adulterants."\n";
                            $message .= "No adulterants or antibiotic traces were detected \n";
                        }
                    }
                }

                if($company_branch_id==15){
                    $message .= "\nThanks for Choosing Noor 03 111 222 572 ";
                }else{
                    $message .="\n".$companyTitle."\nFor info contact us @".$companyObject['phone_number'];
                }

                if($company_branch_id==10){
                    $message .="\nIf it is not correct kindly inform on the numbers above Otherwise management will not be responsible.";
                }




            }



            if($deliverySms==1){
                smsLog::saveSms($clientID ,$company_branch_id ,$phoneNo ,$fullname ,$message);
                manageSendSMS::sendSMS($phoneNo , $message , $companyMask , $company_branch_id ,$network_id,$clientID);
            }
            notification_alert_data::notification_function($clientObject ,$company_branch_id ,$currentBalance,$companyObject);
            return  json_encode($response);

        }






    }





    public static  function saveDeliveryForPortal_for_bottle($data){

        date_default_timezone_set("Asia/Karachi");

        $clientID = $data['client_id'];
        $riderID = $data['rider_id'];
        $latitude = $data['lat'];
        $longitude = $data['longi'];
        $productObject = $data['data'];

        $broken_bottle = $data['broken'];

        $selectDate = date("Y-m-d");

        $company_branch_id = $data['company_branch_id'];

        $query="SELECT p.*  from   product as p 
                where p.company_branch_id =$company_branch_id and bottle = 1";
        $product =  Yii::app()->db->createCommand($query)->queryAll();
        //   var_dump($product[0]['price']);
        //   die();
        $product_id = $product[0]['product_id'];

        $selected_date = date("Y-m-d");



        $delivery = Delivery::model()->findByAttributes(array('client_id'=>$clientID, 'date'=>$selected_date));

        $delivery_id = $delivery['delivery_id'];
        $total_amount  = $delivery['total_amount'];
        $partial_amount  = $delivery['partial_amount'];

        $deliveryObject = Delivery::model()->findByAttributes(array('client_id'=>$clientID, 'date'=>$selected_date));
        if($deliveryObject){
            $total_amount =  $deliveryObject['total_amount'];
            $partial_amount =  $deliveryObject['partial_amount'];
            $delivery->company_branch_id = $company_branch_id ;
            $delivery->client_id = $clientID;
            $delivery->rider_id = $riderID ;
            $delivery->date = $selectDate ;
            $delivery->time = date("H:i") ;
            $delivery->tax_percentage = 0 ;
            $delivery->amount_with_tax = 0 ;
            $delivery->tax_amount = 0 ;
            $delivery->latitude = $latitude ;
            $delivery->longitude = $longitude ;
            $delivery->amount = 0 ;
            $delivery->discount_percentage = 0 ;
            $delivery->total_amount = $product[0]['price'] * $broken_bottle +$total_amount;
            $delivery->partial_amount = $product[0]['price'] * $broken_bottle + $total_amount;
            if($delivery->save()) {
                $deliveryID = $delivery->delivery_id;

                $deliveryDetail = new DeliveryDetail();
                $deliveryDetail->delivery_id = $delivery_id ;
                $deliveryDetail->product_id = $product_id ;
                $deliveryDetail->date = $selectDate ;
                $deliveryDetail->quantity = $broken_bottle ;
                $deliveryDetail->amount = $product[0]['price'] * $broken_bottle;
                $deliveryDetail->adjust_amount = 0 ;

                if($deliveryDetail->save()){
                }else{
                    var_dump($deliveryDetail->getErrors());
                }
            }

        }else{
            $delivery = new Delivery();
            $delivery->company_branch_id = $company_branch_id ;
            $delivery->client_id = $clientID;
            $delivery->rider_id = $riderID ;
            $delivery->date = $selectDate ;
            $delivery->time = date("H:i") ;
            $delivery->tax_percentage = 0 ;
            $delivery->amount_with_tax = 0 ;
            $delivery->tax_amount = 0 ;
            $delivery->latitude = $latitude ;
            $delivery->longitude = $longitude ;
            $delivery->amount = 0 ;
            $delivery->discount_percentage = 0 ;
            $delivery->total_amount = $product[0]['price'] * $broken_bottle;
            $delivery->partial_amount = $product[0]['price'] * $broken_bottle;
            if($delivery->save()) {
                $deliveryID = $delivery->delivery_id;
                $deliveryDetail = new DeliveryDetail();
                $deliveryDetail->delivery_id = $deliveryID ;
                $deliveryDetail->product_id = $product_id ;
                $deliveryDetail->date = $selectDate ;
                $deliveryDetail->quantity = $broken_bottle ;
                $deliveryDetail->amount = $product[0]['price'] * $broken_bottle;
                $deliveryDetail->adjust_amount = 0 ;
                if($deliveryDetail->save()){
                }else{
                    var_dump($deliveryDetail->getErrors());
                }
            }
        }



    }

    public static function saveBottle($data){

        $broken = $data['broken'];
        if($broken >0){
            mangeDelivery::saveDeliveryForPortal_for_bottle($data);
        }



        $perfect = $data['perfect'];
        $clientID = $data['client_id'];
        $riderID = $data['rider_id'];



        $company_branch_id = $data['company_branch_id'];


        $query="SELECT p.*  from   product as p 
                where p.company_branch_id =$company_branch_id and bottle = 1";
        $product =  Yii::app()->db->createCommand($query)->queryAll();
        //   var_dump($product[0]['price']);
        //   die();
        $product_id = $product[0]['product_id'];

        $totalBottle = $broken + $perfect ;
        $bottle_record = new  BottleRecord();
        $bottle_record->client_id = $clientID;
        $bottle_record->rider_id = $riderID ;
        $bottle_record->company_id = $company_branch_id;
        $bottle_record->broken = $broken;
        $bottle_record->perfect = $perfect ;
        $bottle_record->product_id =$product_id ;
        $bottle_record->date = date("Y-m-d");
        $bottle_record->time = date("H:i") ;
        $bottle_record->save();
    }

    public static function billAssign($data , $companyObject){
        $clientID = $data['client_id'];
        $riderID = $data['rider_id'];
        $company_branch_id = $data['company_branch_id'];
        $billTransfer = new BillTransfer();
        $billTransfer->cleint_id = $clientID ;
        $billTransfer->rider_id = $riderID;
        $billTransfer->date = date("Y-m-d");
        $billTransfer->time = date("H:i") ;
        $billTransfer->save();
        $companyMask = $companyObject['sms_mask'];
        $companyTitle = $companyObject['company_title'];
        $clientObject = Client::model()->findByPk(intval($clientID));
        $fullname = $clientObject['fullname'];
        $phoneNo =  $clientObject['cell_no_1'];
        $network_id = $clientObject['network_id'];
        $deliverySms =  $clientObject['daily_delivery_sms'];
        $message = "Bill is delivered to you";
        $message .="\n".$companyTitle;


        if($deliverySms==1){

            smsLog::saveSms($clientID ,$company_branch_id ,$phoneNo ,$fullname ,$message);
            manageSendSMS::sendSMS($phoneNo , $message , $companyMask , $company_branch_id ,$network_id,$clientID);

        }
    }
}