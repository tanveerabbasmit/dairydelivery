<?php
/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 3/3/2017
 * Time: 3:31 PM
 */
class shop_list_data{
      public static $response = ['success'=>FALSE , 'message'=>'','data'=>[]];

       public static function get_shop_list(){
           $company_id = Yii::app()->user->getState('company_branch_id');

           $query="select * from pos_shop as a
                     where a.company_id =$company_id
                     order by a.shop_name ASC ";

            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();




            return $queryResult;
       }


}