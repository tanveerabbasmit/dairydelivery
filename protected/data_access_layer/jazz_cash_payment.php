<?php


class jazz_cash_payment
{
   public static function save_jazz_payment_reponce($type,$responce){

            $object = New JazzCashPaymentResponce();
            $object->responce_type =$type;

            $object->responce_data =json_encode($responce);
            $object->action_date =date("Y-d-m H:i:s");

            if($object->save()){

            }else{
                echo "echo";
                print_r($object->getErrors());
                die();
            }
   }
   public static function ger_var_function($name,$defaultValue=''){
       return isset($_GET[$name]) ? $_GET[$name] : $defaultValue;
   }
}