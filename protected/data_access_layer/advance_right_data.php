<?php


class advance_right_data
{
    public static function check_advance_right_funation($type){

        $user_id = Yii::app()->user->getState('user_id');
        $user_object = User::model()->findByPk($user_id);

        $user_role_id = $user_object['user_role_id'];


        $role_object = Role::model()->findByPk($user_role_id);

        $sadmin_yes_or_no =  $role_object['sadmin_yes_or_no'];

        if($sadmin_yes_or_no==1){
            return true;
        }else{
           return $user_object[$type];
        }

    }
}