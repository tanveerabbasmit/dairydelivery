<?php

class validate_phone_number_with_code
{
     public static function validate_phone_number($num){

          $num = str_replace(" ","",$num);
          $number = substr($num,-10);
          return  '+92'.$number;

         if(substr($num, 0, 2) == "03"){
             $number = '+923' . substr($num, 2);
         }else if(substr($num, 0, 1) == "3"){
             $number = '+923' . substr($num, 1);
         }else if(substr($num, 0, 2) == "+9"){
             $number =  substr($num, 1);
         }

         return $number;
     }
}