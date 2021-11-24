<?php

/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 10/31/2017
 * Time: 5:17 PM
 */

class RotainnewpaymentController extends Controller
{
     public function actionTransferpayment_farm(){
         die('one');
     }
     public function actionTranfer_vendor_payment(){
         $payment_list = VendorPayment::model()->findAll();
            die('Lock');
         foreach ($payment_list as $value){

              $vendor_payment_id = $value['vendor_payment_id'];
              $vendor_id = $value['vendor_id'];
              $amount = $value['amount'];
              $action_date = $value['action_date'];
              $reference_no = $value['reference_no'];
              $payment_mode = $value['payment_mode'];
              $company_id = $value['company_id'];
              $remarks = $value['remarks'];
              $new_payment = New  NewPayment();
             $new_payment->collection_vault_id = 0;
             $new_payment->vendor_type_id = 1;
             $new_payment->vendor_id = $vendor_id;
             $new_payment->date = $action_date;
             $new_payment->transaction_type = 'Expence';
             $new_payment->expence_type = 0;

             $new_payment->amount_paid = $amount;

             $new_payment->reference_no = $reference_no.'..';

             $new_payment->payment_or_receipt = 1;

             $new_payment->company_id = $company_id;

             if($new_payment->save()){

             }else{
                 echo "<pre>";
                 print_r($new_payment->getError());
                 die();
             }


         }
     }
}