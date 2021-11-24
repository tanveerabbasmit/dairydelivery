<?php


class payment_new
{

     public static function vendor_payment_list($pay_to_party){


         $object = "SELECT * FROM vendor_payment AS p
             LEFT join vendor AS f ON p.vendor_id = f.vendor_id
             WHERE p.vendor_id = '$pay_to_party'
             ORDER BY p.action_date DESC";



         $payment = Yii::app()->db->createCommand($object)->queryAll();

          $final_list = [];
         foreach ($payment as $value){
             $one_object = [];

             $one_object['id'] = $value['vendor_payment_id'];
             $one_object['amount'] = $value['amount'];
             $one_object['action_date'] = $value['action_date'];
             $one_object['reference_no'] = $value['reference_no'];

             $one_object['payment_mode'] =self::get_payment_mode($value['payment_mode']);

             $final_list[] = $one_object;

         }

         return $final_list;
     }
     public static function farm_payment_list($pay_to_party){


         $object = "SELECT * FROM farm_payment AS p
            LEFT join farm AS f ON p.farm_id = f.farm_id
            WHERE p.farm_id = '$pay_to_party'
            ORDER BY p.action_date DESC";



         $payment = Yii::app()->db->createCommand($object)->queryAll();

          $final_list = [];
         foreach ($payment as $value){



             $one_object = [];

             $one_object['id'] = $value['farm_payment_id'];
             $one_object['amount'] = $value['amount'];
             $one_object['action_date'] = $value['action_date'];
             $one_object['reference_no'] = $value['reference_no'];

             $one_object['payment_mode'] =self::get_payment_mode($value['payment_mode']);

             $final_list[] = $one_object;

         }

         return $final_list;
     }

     public static function get_all_payment_list($data){

         $type = $data['type'];
         $pay_to_party_id = $data['pay_to_party_id'];
         $company_id = Yii::app()->user->getState('company_branch_id');

         if($type=='expense'){
             $query = "SELECT
            mp.*,
            et.type
            FROM main_payment AS mp
            LEFT JOIN expence_type AS et 
            ON mp.pay_to_party_id  = et.expence_type 
            WHERE mp.company_id = '$company_id' AND mp.type ='$type'
            AND mp.pay_to_party_id ='$pay_to_party_id' ";

             $payment = Yii::app()->db->createCommand($query)->queryAll();

             $final_list = [];

             foreach ($payment as $value){

                 $one_object = [];
                 $one_object['id'] = $value['main_payment_id'];
                 $one_object['amount'] = $value['amount_paid'];
                 $one_object['action_date'] = $value['date'];
                 $one_object['reference_no'] = $value['reference_no'];
                 $one_object['payment_mode'] =self::get_payment_mode($value['payment_mode']);
                 $final_list[] = $one_object;
             }
         }
         if($type=='employee'){
             $query = "SELECT
            mp.*,
            e.employee_name
            FROM main_payment AS mp
            LEFT JOIN employee AS e 
            ON mp.pay_to_party_id  = e.employee_id 
            WHERE mp.company_id = '$company_id' AND mp.type ='$type'
            AND mp.pay_to_party_id ='$pay_to_party_id' ";


             $payment = Yii::app()->db->createCommand($query)->queryAll();

             $final_list = [];

             foreach ($payment as $value){

                 $one_object = [];
                 $one_object['id'] = $value['main_payment_id'];
                 $one_object['amount'] = $value['amount_paid'];
                 $one_object['action_date'] = $value['date'];
                 $one_object['reference_no'] = $value['reference_no'];
                 $one_object['payment_mode'] =self::get_payment_mode($value['payment_mode']);
                 $final_list[] = $one_object;
             }
         }

         if($type=='receipt_vendor'){

             $query = "SELECT
                    mp.*,
                    v.vendor_name
                    FROM main_payment AS mp
                    LEFT JOIN vendor AS v 
                    ON mp.pay_to_party_id  = v.vendor_id  
                    WHERE 
                    mp.company_id = '$company_id'
                    AND mp.type ='$type'
                    AND mp.pay_to_party_id ='$pay_to_party_id' ";


             $payment = Yii::app()->db->createCommand($query)->queryAll();

             $final_list = [];

             foreach ($payment as $value){

                 $one_object = [];
                 $one_object['id'] = $value['main_payment_id'];
                 $one_object['amount'] = $value['amount_paid'];
                 $one_object['action_date'] = $value['date'];
                 $one_object['reference_no'] = $value['reference_no'];
                 $one_object['payment_mode'] =self::get_payment_mode($value['payment_mode']);
                 $final_list[] = $one_object;
             }
         }
         if($type=='other_income_source'){

             $query = "SELECT
                     mp.*,
                     o.other_income_source_name
                     FROM main_payment AS mp
                     LEFT JOIN other_income_source AS o 
                     ON mp.pay_to_party_id  = o.other_income_source_id 
                    WHERE mp.company_id = '$company_id' AND mp.type ='$type'
                    AND mp.pay_to_party_id ='$pay_to_party_id' ";




             $payment = Yii::app()->db->createCommand($query)->queryAll();

             $final_list = [];

             foreach ($payment as $value){

                 $one_object = [];
                 $one_object['id'] = $value['main_payment_id'];
                 $one_object['amount'] = $value['amount_paid'];
                 $one_object['action_date'] = $value['date'];
                 $one_object['reference_no'] = $value['reference_no'];
                 $one_object['payment_mode'] =self::get_payment_mode($value['payment_mode']);
                 $final_list[] = $one_object;
             }
         }


         return $final_list;

     }

     public static function get_payment_mode($mode){
         $mode_name = [
             '2'=>'cheque',
             '3'=>'Cash',
             '5'=>'Bank Transaction',
             '6'=>'Card Transaction',
         ];

         if(isset($mode_name[$mode])){
             return $mode_name[$mode];
         }else{
             return '';
         }


     }
}