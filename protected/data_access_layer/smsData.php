<?php
/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 3/3/2017
 * Time: 3:31 PM
 */
class smsData{
    public static $response = ['success'=>FALSE , 'message'=>'','data'=>[]];



       public static function getLastPaymentRecord($client_id){

           $company_id = Yii::app()->user->getState('company_branch_id');

            $query="SELECT  p.bill_month_date from payment_master AS p
                WHERE p.client_id ='$client_id' 
                ORDER BY p.bill_month_date DESC
                LIMIT 1 ";



             $queryResult =  Yii::app()->db->createCommand($query)->queryScalar();




           $result =true;
           if($queryResult){
                 $now = time(); // or your date as well
                 $your_date = strtotime($queryResult);
                 $datediff = $now - $your_date;
                $days_diff =  round($datediff / (60 * 60 * 24));

                 if($days_diff <30){
                       $result =false;
                 }
           }


            return $result;
       }


}