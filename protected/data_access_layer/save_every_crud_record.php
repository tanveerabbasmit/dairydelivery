<?php
/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 3/3/2017
 * Time: 3:31 PM
 */
class save_every_crud_record{
    public static $response = ['success'=>FALSE , 'message'=>'','data'=>[]];



       public static function save_crud_record_date_waise(
           $action_name,
           $modify_table_name,
           $modify_id,
           $selected_date,
           $data_befour_action,
           $new_value,
           $client_id,
           $rearks =''

       ){

           date_default_timezone_set("Asia/Karachi");
           $today_date= date("Y-m-d");

           $today_time= date("H:i");

           $company_id = Yii::app()->user->getState('company_branch_id');
           $user_id = Yii::app()->user->getState('user_id');
           $table_object = New CrudOptionHistory();

           $table_object->action_name =$action_name;
           $table_object->data_befour_action ='';
           $table_object->user_id =$user_id;
           $table_object->action_date =$today_date;
           $table_object->data_befour_action =$data_befour_action;
           $table_object->action_time =$today_time;
           $table_object->company_id =$company_id;
           $table_object->modify_table_name =$modify_table_name;
           $table_object->modify_id =$modify_id;
           $table_object->client_id =$client_id;
           $table_object->selected_date =$selected_date;
           $table_object->new_value =$new_value;
           $table_object->rearks =$rearks;

           if($table_object->save()){

           }else{

           }


       }



}