<?php


class farm_and_vendor_message_manage
{
    public static function farm_purchase_payment($data){

        $date = $data['date'];

        $productList = $data['productList'];

        $farm_id = $data['farm_id'];

        $company_id= Yii::app()->user->getState('company_branch_id');

        $companyObject  =  utill::get_companyTitle($company_id);


        $farm_id =  $data['farm_id'];

        $object = Farm::model()->findByPk($farm_id);

      $phoneNo =  $object['phone_number'];


        $farm_name =  $object['farm_name'];

        $companyTitle = $companyObject['company_title'];



        foreach($productList as $value){



            if($value['quantity']>0){
                $unit = $value['unit'];
                $quantity = $value['quantity'];
                $name = $value['name'];
                $purchase_rate = $value['purchase_rate'];

                $total_aount = $purchase_rate * $quantity;

                $message ="Purchase Alert :\n Dear ".$farm_name." on 
                        ".$date." we received ".$quantity." ".$unit.", ".$name."
                         @ ".$purchase_rate." from you Amount.Rs ".$total_aount." .".$companyTitle." ";

                $companyObject  =  utill::get_companyTitle($company_id);

                $companyMask = $companyObject['sms_mask'];


                manageSendSMS::vendor_sms_function($phoneNo , $message , $companyMask , $company_id ,0,$farm_id);

                smsLog::saveSms($farm_id ,$company_id ,$phoneNo ,$farm_name ,$message);

            }


        }




    }
}