<?php
/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 3/3/2017
 * Time: 3:31 PM
 */
class assignRoleData{
    public static $response = ['success'=>FALSE , 'message'=>'','data'=>[]];

       public static function getRoleList(){
           $company_id = Yii::app()->user->getState('company_branch_id');
           $query="SELECT * from role as r 
            where r.company_branch_id = $company_id ";
            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

            return json_encode($queryResult);
       }

       public static function getAssignRoleManuFunction($data){
             $roleID = $data['role_id'];
            $query="SELECT rm.module_action_role_id from role_muduleactionrole as rm
               LEFT JOIN module_action_role as mar ON mar.module_action_role_id = rm.module_action_role_id
               where rm.role_id = $roleID";
              $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
            return json_encode($queryResult);
       }

       public static function getMenuList(){
           $query="SELECT * from module_action_role";
            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
            return json_encode($queryResult);
       }

        public static function getMenuList_for_Crud($data){

            $roleID = $data['role_id'];

            $query="select mar.menu_name, mar.module_action_role_id ,ifnull(rm.role_muduleActionRole ,0 ) as assignTo from module_action_role as mar
                  left join role_muduleactionrole as rm ON mar.module_action_role_id = rm.module_action_role_id and rm.role_id ='$roleID'
                  order by mar.menu_name Asc ";
                 $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
                  return ($queryResult);
        }

        public static function crudList($data){

            $roleID = $data['role_id'];

            $query="select * from role_crud as rd
                   where rd.role_id ='$roleID'";
             $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
             $result = array();
             foreach($queryResult as $value){
                 $module_action_role_id =  $value['module_action_role_id'];
                  $crud_id =  $value['crud_id'];
                 $result[$module_action_role_id][$crud_id] =true;

             }
             return $result ;

        }

       public static function saveNewRoleFunction($data){

              $company_id = Yii::app()->user->getState('company_branch_id');
              $zone = new Role();
             $zone->role_name  = $data['role_name'];
              $zone->role_key = $data['role_key'];
              $zone->company_branch_id = $company_id;

               if($zone->save()){
                   $zoneID = $zone->role_id;
                   $query="SELECT * from role 
                      where role_id = $zoneID ";
                   $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
                   zoneData::$response['success']=true;
                   zoneData::$response['message']='ok';
                   zoneData::$response['zone']=$queryResult;

               }else{
                   zoneData::$response['success'] = false ;
                   zoneData::$response['message'] = $zone->getErrors() ;

               }
               return json_encode(zoneData::$response);

       }

       public static function saveChangeRoleFunction($data){


               $roleID = $data['role_id'];

                $menu = $data['data'];

                //  echo json_encode($menu);
                //  die();

                RoleMuduleactionrole::model()->DeleteAllByAttributes(array('role_id'=>$roleID));

                RoleCrud::model()->DeleteAllByAttributes(array('role_id'=>$roleID));

                 foreach($menu as $value){


                         $crud = $value['crud'];

                         foreach ($crud as $valueCrud){

                              if($valueCrud['selected']){
                                  $crud = New RoleCrud();
                                  $crud->role_id = $roleID;
                                  $crud->crud_id = $valueCrud['crud_id'];
                                  $crud->module_action_role_id = $valueCrud['module_action_role_id'];
                                  $crud->save();
                              }
                         }
                        if(($value['menu']['assignTo'])){

                                $task = new RoleMuduleactionrole();
                                $task->role_id = $roleID ;
                                $task->module_action_role_id = $value['menu']['module_action_role_id'] ;
                                if($task->save()){

                                }else{
                                    var_dump($task->getErrors());
                                }
                        }
                 }

               return json_encode(zoneData::$response);

       }

       public static function editRoleFunction($data){

              $zone = Role::model()->findByPk($data['role_id']);

           $zone->role_name  = $data['role_name'];
           $zone->role_key = $data['role_key'];
               if($zone->save()){

                   zoneData::$response['success']=true;
                   zoneData::$response['message']='ok';


               }else{
                   zoneData::$response['success'] = false ;
                   zoneData::$response['message'] = $zone->getErrors() ;

               }
               return json_encode(zoneData::$response);

       }


       public static function deleteFunction($data){


              $zone = Role::model()->findByPk($data['role_id']);


               if($zone->delete()){

                   zoneData::$response['success']=true;
                   zoneData::$response['message']='ok';


               }else{

                   zoneData::$response['success'] = false ;
                   zoneData::$response['message'] = $zone->getErrors() ;

               }
               return json_encode(zoneData::$response);

       }

}