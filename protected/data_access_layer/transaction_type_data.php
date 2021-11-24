<?php


class transaction_type_data
{
     public static function transaction_type_list(){
         $reslt =[
             [
                'id'=>1,
                'name'=>'liabilities'
             ],
             [
                'id'=>2,
                'name'=>'Asset'
             ],
         ];
         return $reslt;
     }
}