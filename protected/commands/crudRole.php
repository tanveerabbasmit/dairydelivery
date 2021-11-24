<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 2018-07-22
 * Time: 11:20 AM
 */

class crudRole
{
  public static function getCrudrole($module_action_role_id){
       //  echo  $module_action_role_id ;
          $user_id = Yii::app()->user->getState('user_id');

          $UserObject = User::model()->findByPk(intval($user_id));


          if($UserObject['supper_admin_user']==1){
              $result = array();
              $result[1] = false;
              $result[2] = false;
              $result[3] = false;
              return json_encode($result);
              die();
          }

           $role_id  = $UserObject['user_role_id'];



         $role_List = RoleCrud::model()->findAllByAttributes(array('role_id'=>$role_id , 'module_action_role_id'=>$module_action_role_id));

            $result = array();
            $result[1] = true;
            $result[2] = true;
            $result[3] = true;


          foreach ($role_List as $value){
               $crud_id = $value['crud_id'];
              $result[$crud_id] =false;
          }

          return json_encode($result);

  }
    
}