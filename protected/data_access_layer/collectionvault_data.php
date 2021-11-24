<?php
class collectionvault_data{
    public static function get_total_amount($data,$name){

        $total_amount = 0;
       foreach ($data as $value){
           $total_amount =$total_amount +  $value[$name];
       }
       return $total_amount;
    }

}