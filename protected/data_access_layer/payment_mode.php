<?php


class payment_mode
{
    public static function get_payment_mode_name($mode_id){
         if($mode_id==2){
             return  'Cheque';
         }elseif ($mode_id==3){
            return  "Cash";
         }elseif ($mode_id==5){
             return  "Bank Transaction";
         }elseif ($mode_id==6){
             return  "Card Transaction";
         }else{
             return 'Other';
         }

    }


}