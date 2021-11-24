<?php

class one_time_delivery_data
{
        public static function de_active_customer($client_id){
            $object = Client::model()->findByPk($client_id);
            if($object['one_time_delivery']==1){
               // $object->is_active=0;
               // $object->save();

            }
        }
}