<?php


class check_default_product
{
    public  static function get_product_id(){
       $company_branch_id =  Yii::app()->user->getState('company_branch_id');

       $default_product = [
           '18'=>'79'
       ];
       if(isset($default_product[$company_branch_id])){
           return $default_product[$company_branch_id];
       }else{
           return  0;
       }
    }
}