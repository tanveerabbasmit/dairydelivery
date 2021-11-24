<?php

/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 9/19/2017
 * Time: 12:39 PM
 */
class conformPayment
{


    public static function conformPaymentMethodFromPortal($company_id ,$data,$data_discount){

        date_default_timezone_set("Asia/Karachi");
        $companyObject  =  utill::get_companyTitle($company_id);
        $companyMask = $companyObject['sms_mask'];
        $companyTitle = $companyObject['company_title'];
        $totalAmountPiad = $data['amount_paid'];
        $user_id = Yii::app()->user->getState('user_id');

        if(!isset($data["bill_year"])){
            $data["bill_year"] = date("Y");
            $data["bill_month"] = date("m");
        }
        $finalBillMothDate =$data["bill_year"]."-".$data["bill_month"]."-".'01';
        $trans_ref_no = $data['trans_ref_no'];
        if($trans_ref_no ==''){
            $trans_ref_no = date("Y-m-d")."_".$data['client_id'];
        }
        $clientID =   $data['client_id'];
        $collection_vault_id =   $data['collection_vault_id'];
        if(!isset($data["startDate"])){
            $data["startDate"] = date("Y-m-d");
        }
        $clientObject = Client::model()->findByPk(intval($clientID));
        $phoneNo =  $clientObject['cell_no_1'];

        $fullname =  $clientObject['fullname'];

        $receipt_alert =  $clientObject['receipt_alert'];






        $network_id = $clientObject['network_id'];
        $riderID = 1;
        $amountPaid = $data['amount_paid'] ;

        /*save Pyment IN Payment Master*/
        $paymentmaster = new PaymentMaster();
        $paymentmaster->date = $data["startDate"];
        $paymentmaster->client_id = $clientID;
        $paymentmaster->company_branch_id = $company_id;
        $paymentmaster->time =  date("h:i:sa");
        $paymentmaster->payment_mode = $data['payment_mode'];
        $paymentmaster->amount_paid = $amountPaid;
        $paymentmaster->remarks = $data['remarks'];
        $paymentmaster->payment_type = $data['payment_type'];
        $paymentmaster->reference_number=$trans_ref_no;
        $paymentmaster->remaining_amount= $amountPaid;
        $paymentmaster->bill_month_date=$finalBillMothDate;
        $paymentmaster->user_id= $user_id;
        $paymentmaster->collection_vault_id= $collection_vault_id;

        if($paymentmaster->save()){
            $paymentMasterID = $paymentmaster['payment_master_id'];
            foreach($data_discount as $value){

                if($value['discount_amount'] >0){
                    $discount_type_object = new DiscountList();
                    $discount_type_object->percentage_amount = $value['discount_amount'];
                    $discount_type_object->discount_type_id = $value['discount_type_id'];
                    if( $value['percentage']){
                        $discount_type_object->percentage = 1;
                    }else{
                        $discount_type_object->percentage = 0;
                    }
                    $discount_type_object->total_discount_amount = $value['calculated_discount'];
                    $discount_type_object->payment_master_id = $paymentMasterID;
                    if($discount_type_object->save()){
                    }else{
                    }
                }
            }
            /* Select UnPaid Delivery*/
            $query ="Select * from delivery as d
                       where d.client_id = $clientID  AND d.partial_amount !=0";
            $queryResult = Yii::app()->db->createCommand($query)->queryAll();
            foreach($queryResult as $value){
                $deliveryID = $value['delivery_id'] ;
                if($amountPaid > 0){
                    /*deliver Paid Completely*/
                    $deliveryPartialPyment = Delivery::model()->findByPk(intval($deliveryID));
                    $partialAmount = $deliveryPartialPyment['partial_amount'];
                    if($amountPaid >= $partialAmount){
                        $deliveryPartialPyment->partial_amount = 0 ;
                        if($deliveryPartialPyment->save()){
                            $paymentDetail = new PaymentDetail();
                            $paymentDetail->delivery_id = $value['delivery_id'];
                            $paymentDetail->delivery_date = $value['date'];
                            $paymentDetail->client_id = $clientID;
                            $paymentDetail->due_amount = 0 ;
                            $paymentDetail->amount_paid = $partialAmount ;
                            $paymentDetail->payment_master_id = $paymentMasterID ;
                            $paymentDetail->payment_date = $data["startDate"];
                            if($paymentDetail->save()){
                                $amountPaid = $amountPaid - $value['partial_amount'] ;
                                /*Update remaining amount*/
                                $updateMaster = PaymentMaster::model()->findByPk(intval($paymentMasterID));

                                $updateMaster->remaining_amount = $updateMaster['remaining_amount'] - $partialAmount ;
                                $updateMaster->save();
                            }else{
                                var_dump($paymentDetail->getErrors());
                            }
                        }
                    }else{
                        $deliveryPartialPyment->partial_amount = $partialAmount-$amountPaid ;
                        if($deliveryPartialPyment->save()){
                            $paymentDetail = new PaymentDetail();
                            $paymentDetail->delivery_id = $value['delivery_id'];
                            $paymentDetail->delivery_date = $value['date'];
                            $paymentDetail->client_id = $clientID;
                            $paymentDetail->due_amount = $partialAmount - $amountPaid;
                            $paymentDetail->amount_paid = $amountPaid ;
                            $paymentDetail->payment_master_id = $paymentMasterID ;
                            $paymentDetail->payment_date = $data["startDate"];
                            if($paymentDetail->save()){
                                $amountPaid = $amountPaid - $value['partial_amount'] ;
                                $updateMaster = PaymentMaster::model()->findByPk(intval($paymentMasterID));
                                $updateMaster->remaining_amount = 0;
                                $updateMaster->save();
                            }else{
                                $paymentDetail->getErrors();
                            }
                        }
                    }
                }
            }
            $response = array(
                'code' =>200,
                'company_branch_id'=>0,
                'success' =>true,
                'message'=>'Your payment has been processed successfully',
                'data' => ''
            );

            $message ='';

           /* if($company_id ==10){
                $message .='Dear Customer';

            }else{*/
                $message .=$fullname ;
            /*}*/
            $message .= ",\nWe have received Rs.".$totalAmountPiad." from you.\nReference no.".$trans_ref_no."\n";
            $currentBalance = APIData::calculateFinalBalance($clientID);
            $message .= "\nTotal Bill Due : Rs.".($currentBalance)."\nRegards";
            $message .="\n".$companyTitle;

             if($receipt_alert ==1 and $data['payment_type']==0){


                 manageSendSMS::sendSMS($phoneNo , $message , $companyMask , $company_id ,$network_id,$clientID);

                 smsLog::saveSms($clientID ,$company_id ,$phoneNo ,$fullname ,$message);

             }




        }else{
            $response = array(
                'code' =>200,
                'company_branch_id'=>0,
                'success' =>false,
                'message'=>'$paymentmaster->getErrors()',
                'data' => ''
            );

        }
        return  json_encode($response);

    }

    public static function conformPaymentMethodFromApp($company_id ,$data){

        date_default_timezone_set("Asia/Karachi");
        $companyObject  =  utill::get_companyTitle($company_id);
        $companyMask = $companyObject['sms_mask'];
        $companyTitle = $companyObject['company_title'];
        $totalAmountPiad = $data['amount_paid'];
        if(!isset($data["month"])){
            $data["year"] = date("Y");
            $data["month"] = date("m");
        }

        $finalBillMothDate =$data["year"]."-".$data["month"]."-".'01';

        if(!isset($data["refrence_no"])){
            $data['refrence_no'] = '';
        }
        $trans_ref_no = $data['refrence_no'];
        if($trans_ref_no ==''){
            $trans_ref_no = date("Y-m-d")."_".$data['client_id'];
        }
        $clientID =   $data['client_id'];
        if(!isset($data["startDate"])){
            $data["startDate"] = date("Y-m-d");
        }
        $clientObject = Client::model()->findByPk(intval($clientID));
        $phoneNo =  $clientObject['cell_no_1'];
        $fullname =  $clientObject['fullname'];
        $network_id = $clientObject['network_id'];
        $riderID = 1;
        $amountPaid = $data['amount_paid'] ;
        if(isset($data['rider_id'])){
            $rider_id=$data['rider_id'] ;
        }else{
            $rider_id =0;
        }
        /*save Pyment IN Payment Master*/
        $paymentmaster = new PaymentMaster();
        $paymentmaster->date = $data["startDate"];
        $paymentmaster->client_id = $clientID;
        $paymentmaster->company_branch_id = $company_id;
        $paymentmaster->time =  date("h:i:sa");
        $paymentmaster->payment_mode = $data['payment_mode'];
        $paymentmaster->amount_paid = $amountPaid;
        $paymentmaster->remarks  = $data['remarks'];
        $paymentmaster->reference_number  = $trans_ref_no;
        $paymentmaster->remaining_amount  = $amountPaid;
        $paymentmaster->bill_month_date  = $finalBillMothDate;
        $paymentmaster->rider_id  = $rider_id;
        $paymentmaster->pp_RetreivalReferenceNo  = isset($data['pp_RetreivalReferenceNo'])?$data['pp_RetreivalReferenceNo']:'0';
        if($paymentmaster->save()){

            $paymentMasterID = $paymentmaster['payment_master_id'];
            if(isset($data['discount_amount']) && isset($data['discount_type_id'])){

                $discount_type_object = new DiscountList();
                $discount_type_object->percentage_amount = $data['discount_amount'];
                $discount_type_object->discount_type_id = $data['discount_type_id'];
                $discount_type_object->percentage = 0;
                $discount_type_object->total_discount_amount = $data['discount_amount'];
                $discount_type_object->payment_master_id = $paymentMasterID;
                if($discount_type_object->save()){
                }else{
                }

            }
            /* Select UnPaid Delivery*/
            $query ="Select * from delivery as d
                       where d.client_id = $clientID  AND d.partial_amount !=0";
            $queryResult = Yii::app()->db->createCommand($query)->queryAll();
            foreach($queryResult as $value){
                $deliveryID = $value['delivery_id'] ;
                if($amountPaid > 0){
                    /*deliver Paid Completely*/
                    $deliveryPartialPyment = Delivery::model()->findByPk(intval($deliveryID));
                    $partialAmount = $deliveryPartialPyment['partial_amount'];
                    if($amountPaid >= $partialAmount){
                        $deliveryPartialPyment->partial_amount = 0 ;
                        if($deliveryPartialPyment->save()){
                            $paymentDetail = new PaymentDetail();
                            $paymentDetail->delivery_id = $value['delivery_id'];
                            $paymentDetail->delivery_date = $value['date'];
                            $paymentDetail->client_id = $clientID;
                            $paymentDetail->due_amount = 0 ;
                            $paymentDetail->amount_paid = $partialAmount ;
                            $paymentDetail->payment_master_id = $paymentMasterID ;
                            $paymentDetail->payment_date = $data["startDate"];
                            if($paymentDetail->save()){
                                $amountPaid = $amountPaid - $value['partial_amount'] ;
                                /*Update remaining amount*/
                                $updateMaster = PaymentMaster::model()->findByPk(intval($paymentMasterID));

                                $updateMaster->remaining_amount = $updateMaster['remaining_amount'] - $partialAmount ;
                                $updateMaster->save();
                            }else{
                                var_dump($paymentDetail->getErrors());
                            }
                        }
                    }else{
                        $deliveryPartialPyment->partial_amount = $partialAmount-$amountPaid ;
                        if($deliveryPartialPyment->save()){
                            $paymentDetail = new PaymentDetail();
                            $paymentDetail->delivery_id = $value['delivery_id'];
                            $paymentDetail->delivery_date = $value['date'];
                            $paymentDetail->client_id = $clientID;
                            $paymentDetail->due_amount = $partialAmount - $amountPaid;
                            $paymentDetail->amount_paid = $amountPaid ;
                            $paymentDetail->payment_master_id = $paymentMasterID ;
                            $paymentDetail->payment_date = $data["startDate"];
                            if($paymentDetail->save()){
                                $amountPaid = $amountPaid - $value['partial_amount'] ;
                                $updateMaster = PaymentMaster::model()->findByPk(intval($paymentMasterID));
                                $updateMaster->remaining_amount = 0;
                                $updateMaster->save();
                            }else{
                                $paymentDetail->getErrors();
                            }
                        }
                    }
                }
            }


            $response = array(
                'code' =>200,
                'company_branch_id'=>0,
                'success' =>true,
                'message'=>'Your payment has been processed successfully',
                'data' => ''
            );


            $message ='';

            if($company_id ==10){
                $message .='Dear Customer';

            }else{
                $message .=$fullname ;
            }

            $message .= ",\nWe have received Rs.".$totalAmountPiad." from you.\nReference no.".$trans_ref_no."\n";

            $currentBalance = APIData::calculateFinalBalance($clientID);
            $message .= "\nTotal Bill Due : Rs.".($currentBalance)."\nRegards";
            $message .="\n".$companyTitle;

            manageSendSMS::sendSMS($phoneNo , $message , $companyMask , $company_id ,$network_id);
            smsLog::saveSms($clientID ,$company_id ,$phoneNo ,$fullname ,$message);



        }else{

            var_dump($paymentmaster->getErrors());
             die();
            $response = array(
                'code' =>200,
                'company_branch_id'=>0,
                'success' =>false,
                'message'=>'$paymentmaster->getErrors()',
                'data' => ''
            );

        }


        return  json_encode($response);

    }
    public static function conformPaymentMethodFromAppUbl($company_id ,$data){
        date_default_timezone_set("Asia/Karachi");
        $companyObject  =  utill::get_companyTitle($company_id);
        $companyMask = $companyObject['sms_mask'];
        $companyTitle = $companyObject['company_title'];
        $totalAmountPiad = $data['amount_paid'];
        if(!isset($data["month"])){
            $data["year"] = date("Y");
            $data["month"] = date("m");
        }

        $finalBillMothDate =$data["year"]."-".$data["month"]."-".'01';

        if(!isset($data["refrence_no"])){
            $data['refrence_no'] = '';
        }
        $trans_ref_no = $data['refrence_no'];
        if($trans_ref_no ==''){
            $trans_ref_no = date("Y-m-d")."_".$data['client_id'];
        }
        $clientID =   $data['client_id'];
        if(!isset($data["startDate"])){
            $data["startDate"] = date("Y-m-d");
        }
        $clientObject = Client::model()->findByPk(intval($clientID));
        $phoneNo =  $clientObject['cell_no_1'];
        $fullname =  $clientObject['fullname'];
        $network_id = $clientObject['network_id'];
        $riderID = 1;
        $amountPaid = $data['amount_paid'] ;
        /*save Pyment IN Payment Master*/
        $paymentmaster = new PaymentMaster();
        $paymentmaster->date = $data["startDate"];
        $paymentmaster->client_id = $clientID;
        $paymentmaster->company_branch_id = $company_id;
        $paymentmaster->time =  date("h:i:sa");
        $paymentmaster->payment_mode = $data['payment_mode'];
        $paymentmaster->amount_paid = $amountPaid;
        $paymentmaster->remarks  = $data['remarks'];
        $paymentmaster->reference_number  = $trans_ref_no;
        $paymentmaster->remaining_amount  = $amountPaid;
        $paymentmaster->bill_month_date  = $finalBillMothDate;
        if($paymentmaster->save()){
            $paymentMasterID = $paymentmaster['payment_master_id'];
            /* Select UnPaid Delivery*/
            $query ="Select * from delivery as d
                       where d.client_id = $clientID  AND d.partial_amount !=0";
            $queryResult = Yii::app()->db->createCommand($query)->queryAll();
            foreach($queryResult as $value){
                $deliveryID = $value['delivery_id'] ;
                if($amountPaid > 0){
                    /*deliver Paid Completely*/
                    $deliveryPartialPyment = Delivery::model()->findByPk(intval($deliveryID));
                    $partialAmount = $deliveryPartialPyment['partial_amount'];
                    if($amountPaid >= $partialAmount){
                        $deliveryPartialPyment->partial_amount = 0 ;
                        if($deliveryPartialPyment->save()){

                            $paymentDetail = new PaymentDetail();
                            $paymentDetail->delivery_id = $value['delivery_id'];
                            $paymentDetail->delivery_date = $value['date'];
                            $paymentDetail->client_id = $clientID;
                            $paymentDetail->due_amount = 0 ;
                            $paymentDetail->amount_paid = $partialAmount ;
                            $paymentDetail->payment_master_id = $paymentMasterID ;
                            $paymentDetail->payment_date = $data["startDate"];
                            if($paymentDetail->save()){
                                $amountPaid = $amountPaid - $value['partial_amount'] ;
                                /*Update remaining amount*/
                                $updateMaster = PaymentMaster::model()->findByPk(intval($paymentMasterID));

                                $updateMaster->remaining_amount = $updateMaster['remaining_amount'] - $partialAmount ;
                                $updateMaster->save();
                            }else{
                                var_dump($paymentDetail->getErrors());
                            }
                        }
                    }else{
                        $deliveryPartialPyment->partial_amount = $partialAmount-$amountPaid ;
                        if($deliveryPartialPyment->save()){
                            $paymentDetail = new PaymentDetail();
                            $paymentDetail->delivery_id = $value['delivery_id'];
                            $paymentDetail->delivery_date = $value['date'];
                            $paymentDetail->client_id = $clientID;
                            $paymentDetail->due_amount = $partialAmount - $amountPaid;
                            $paymentDetail->amount_paid = $amountPaid ;
                            $paymentDetail->payment_master_id = $paymentMasterID ;
                            $paymentDetail->payment_date = $data["startDate"];
                            if($paymentDetail->save()){
                                $amountPaid = $amountPaid - $value['partial_amount'] ;
                                $updateMaster = PaymentMaster::model()->findByPk(intval($paymentMasterID));
                                $updateMaster->remaining_amount = 0;
                                $updateMaster->save();
                            }else{
                                $paymentDetail->getErrors();
                            }
                        }

                    }

                }


            }


            $response = array(
                'code' =>200,
                'company_branch_id'=>0,
                'success' =>true,
                'message'=>'Your payment has been processed successfully',
                'data' => ''
            );
            $message ='';

            if($company_id ==10){
                $message .='Dear Customer';

            }else{
                $message .=$fullname ;
            }

            $message = ",\nWe have received Rs.".$totalAmountPiad." from you.\nReference no.".$trans_ref_no."\n";

            $currentBalance = APIData::calculateFinalBalance($clientID);
            $message .= "\nTotal Bill Due : Rs.".($currentBalance)."\nRegards";
            $message .="\n".$companyTitle;

            manageSendSMS::sendSMS($phoneNo , $message , $companyMask , $company_id ,$network_id);
            smsLog::saveSms($clientID ,$company_id ,$phoneNo ,$fullname ,$message);



        }else{


            $response = array(
                'code' =>200,
                'company_branch_id'=>0,
                'success' =>false,
                'message'=>'$paymentmaster->getErrors()',
                'data' => ''
            );

        }




    }

}