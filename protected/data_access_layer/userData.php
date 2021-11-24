<?php
/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 3/3/2017
 * Time: 3:31 PM
 */
class userData{
    public static $response = ['success'=>FALSE , 'message'=>'','data'=>[]];



       public static function getUSerList_array(){

           $company_id = Yii::app()->user->getState('company_branch_id');

            $query="Select u.* ,r.* from user as u
            LEFT JOIN role as r ON r.role_id = u.user_role_id
            where u.company_id ='$company_id' ";

            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


            return $queryResult;
       }
       public static function getUSerList(){

           $company_id = Yii::app()->user->getState('company_branch_id');

            $query="Select u.* ,r.* from user as u
            LEFT JOIN role as r ON r.role_id = u.user_role_id
            where u.company_id ='$company_id' ";

            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


            return json_encode($queryResult);
       }
       public static function getUSerList_except_sadmin(){

           $company_id = Yii::app()->user->getState('company_branch_id');

            $query="Select
                   u.user_id, 
                   u.full_name, 
                   u.phone_number, 
                   u.supper_admin_user, 
                   u.receipt_add, 
                   u.receipt_edit, 
                   u.receipt_delete, 
                   u.delivery_add, 
                   u.receipt_add, 
                   u.receipt_delete, 
                   u.delivery_add, 
                   u.delivery_edit, 
                   u.delivery_delete
                from user as u
          
            where u.company_id ='$company_id' and u.supper_admin_user='0' and u.is_active='1' ";

            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


            return $queryResult;
       }

       public static function getRoleList(){

           $company_id = Yii::app()->user->getState('company_branch_id');

           $query="Select *  from role as r 
                     where r.company_branch_id = $company_id";
            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
            return json_encode($queryResult);
       }

       public static function saveNewUserFunction($data){
              $company_id = Yii::app()->user->getState('company_branch_id');
              $zone = new User();
              $zone->full_name = $data['full_name'];
             $zone->user_name  = $data['user_name'];
              $zone->phone_number = $data['phone_number'];
              $zone->user_role_id = $data['user_role_id'];
              $zone->email = $data['email'];
              $zone->password = $data['Password'];
              $zone->pos_shop_id = $data['pos_shop_id'];
              $zone->is_active = $data['is_active'];
              $zone->allow_delete = $data['allow_delete'];
              $zone->is_deleted = 0;
              $zone->company_branch_id = $company_id ;
              $zone->company_id = $company_id ;
               if($zone->save()){
                    $zoneID = $zone->user_id;
                    $query="Select u.* ,r.* from user as u
                         LEFT JOIN role as r ON r.role_id = u.user_role_id
                         where u.user_id = $zoneID ";
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

       public static function editUserFunction($data){

              $zone = User::model()->findByPk($data['user_id']);
           $zone->full_name = $data['full_name'];
           $zone->user_name  = $data['user_name'];
           $zone->phone_number = $data['phone_number'];
           $zone->user_role_id = $data['user_role_id'];
           $zone->email = $data['email'];
           $zone->password = $data['password'];
           $zone->pos_shop_id = $data['pos_shop_id'];
           $zone->allow_delete = $data['allow_delete'];
           $zone->is_active = $data['is_active'];
           $zone->is_deleted = $data['is_deleted'];
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
              $zone = User::model()->findByPk($data['user_id']);
             //   $zone->is_deleted = 1 ;
               if($zone->delete()){
                   zoneData::$response['success']=true;
                   zoneData::$response['message']='ok';
               }else{
                   zoneData::$response['success'] = false ;
                   zoneData::$response['message'] = $zone->getErrors() ;

               }
               return json_encode(zoneData::$response);

       }
       public static function checkAlredyExistFunction($name){



           $billTResult = User::model()->findByattributes(array('user_name'=>$name));
           $result = 'no' ;
           if($billTResult){
               $result = 'yes';
           }
           return $result ;
       }
       public static function viewRoleFunction($roleID){

           $sql = "SELECT mar.* from role_muduleactionrole as rm
                      LEFT JOIN module_action_role as mar ON mar.module_action_role_id = rm.module_action_role_id
                      where rm.role_id = $roleID ";
           $menuList = Yii::app()->db->createCommand($sql)->queryAll();

           return json_encode($menuList);
       }

}