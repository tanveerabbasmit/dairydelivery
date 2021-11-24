<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of appConstants
 *
 * @author ahmed.fraz
 */
class appConstants {
		
   public static function getActionList(){

       $roleID =  Yii::app()->user->getState('role_id');

       $sql = "SELECT mar.* from role_muduleactionrole as rm
                      LEFT JOIN module_action_role as mar ON mar.module_action_role_id = rm.module_action_role_id
                      where rm.role_id = $roleID";
       $actions = Yii::app()->db->createCommand($sql)->queryAll();
       $actionsList=array();
       foreach ($actions as $action)
       {
           $actionsList[] =  $action['action'];
       }
       return $actionsList ;
   }

}