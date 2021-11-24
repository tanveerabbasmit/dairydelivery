<?php
/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 3/3/2017
 * Time: 3:31 PM
 */
class intervalDefalutData{
    public static $response = ['success'=>FALSE , 'message'=>'','data'=>[]];

       public static function intervalDefault($client_id){

           $company_id = Yii::app()->user->getState('company_branch_id');
           $query="SELECT i.client_id ,i.default_value ,i.product_id
                  FROM interval_scheduler AS i
                  WHERE i.default_value >0 AND i.client_id IN ($client_id) ";

           $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
           $finalResult =array();
           foreach ($queryResult as $value){
                $client_id = $value['client_id'];
                $product_id = $value['product_id'];
                $default_value = $value['default_value'];
                $client_product = $client_id.$product_id;
                $finalResult[$client_product] =$default_value;
           }
           return $finalResult;
       }

}