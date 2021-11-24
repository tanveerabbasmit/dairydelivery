<?php
/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 3/3/2017
 * Time: 3:31 PM
 */
class common_data{
    public static $response = ['success'=>FALSE , 'message'=>'','data'=>[]];

    public static function find_days_beteen_two_date($startDate,$endDate){

        $interval= date_diff(date_create($startDate),date_create($endDate));

        return (intval($interval->format('%R%a'))+1);

    }
    public static function new_add_customer_list($startDate,$endDate){
        $company_id = Yii::app()->user->getState('company_branch_id');
        $query=" SELECT c.new_create_date ,c.client_id FROM client AS c
          WHERE c.company_branch_id ='$company_id' AND 
          c.new_create_date BETWEEN '$startDate' AND '$endDate' ";
        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
        $client_list_object = array();
        foreach ($queryResult as $value){
            $client_id = $value['client_id'];
            $new_create_date = $value['new_create_date'];
            $interval= date_diff(date_create($new_create_date),date_create($endDate));
            $client_list_object[$client_id] = $interval->format('%R%a');
        }

        return $client_list_object ;
    }


}