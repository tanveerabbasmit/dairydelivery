<?php
/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 3/3/2017
 * Time: 3:31 PM
 */
class apiTazaData{
    public static $response = ['success'=>FALSE , 'message'=>'','data'=>[]];

       public static function get_change_scheduler_client_list($client_ids_list){

           $company_id = Yii::app()->user->getState('company_branch_id');
           date_default_timezone_set("Asia/Karachi");
           $today = date("Y-m-d");

           $previousdate =  date('Y-m-d', strtotime(' -1 day'));

           $query="select c.client_id from change_scheduler_record as c

           where c.date in ('$today','$previousdate') and c.client_id in ($client_ids_list) ";



           $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
            $result = array();
            foreach ($queryResult as $value){
               $client_id = $value['client_id'];
               $result[$client_id] = true;
            }
            return $result;
       }

}
