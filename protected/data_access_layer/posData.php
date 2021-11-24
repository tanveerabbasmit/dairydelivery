<?php
/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 3/3/2017
 * Time: 3:31 PM
 */
class posData{
    public static $response = ['success'=>FALSE , 'message'=>'','data'=>[]];



       public static function getposTodayData($todayDate){

           $company_id = Yii::app()->user->getState('company_branch_id');


           $query="SELECT
                 ps.pos_id,  
                 ps.invoice , 
                 ps.quantity ,
                 ps.total_price ,
                 p.name AS product_name, 
                 p.price  
                 FROM pos AS ps
                 LEFT JOIN product AS p ON p.product_id =ps.product_id 
                 where  ps.company_id = '$company_id' and ps.date ='$todayDate' ";


            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


            return json_encode($queryResult);
       }
       public static function getposInvoiceData($post){

           $company_id = Yii::app()->user->getState('company_branch_id');
            $query="SELECT p.price ,p.name , po.* FROM pos AS po 
            LEFT JOIN product AS p ON p.product_id = po.product_id
            WHERE po.invoice ='$post' ";

            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


            return json_encode($queryResult);
       }



}