<?php
/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 3/3/2017
 * Time: 3:31 PM
 */
class riderData{
    public static $response = ['success'=>FALSE , 'message'=>'','data'=>[]];

       public static function getRiderList_array(){
           $company_id = Yii::app()->user->getState('company_branch_id');
           $query="SELECT r.* , cb.name as company_branch_name from rider as r
               LEFT JOIN company_branch as cb ON cb.company_branch_id = r.company_branch_id
               where r.company_branch_id = $company_id
                order by r.is_active DESC , r.fullname ASC ";


            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

            return $queryResult;
       }
       public static function get_rider_of_rider_delivery(){
           $company_id = Yii::app()->user->getState('company_branch_id');

           $user_id = Yii::app()->user->getState('user_id');

            $user_object = User::model()->findByPk($user_id);

            $supper_admin_user =  $user_object['supper_admin_user'];

            if($supper_admin_user==1){
                $query="SELECT 
            r.*
            FROM user_rider_right AS ur
            LEFT JOIN rider AS r ON r.rider_id =ur.rider_id
            WHERE r.company_branch_id ='$company_id' 
            and r.is_Active =1";
            }else{
                $query="SELECT 
            r.*
            FROM user_rider_right AS ur
            LEFT JOIN rider AS r ON r.rider_id =ur.rider_id
            WHERE r.company_branch_id ='$company_id' AND ur.user_id ='$user_id'
            and r.is_Active =1";
            }




           $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

           return json_encode($queryResult);
       }
       public static function getRiderList(){
           $company_id = Yii::app()->user->getState('company_branch_id');
           $query="SELECT r.* , cb.name as company_branch_name from rider as r
               LEFT JOIN company_branch as cb ON cb.company_branch_id = r.company_branch_id
               where r.company_branch_id = $company_id
                order by r.is_active DESC , r.fullname ASC ";


            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

            return json_encode($queryResult);
       }
       public static function rider_for_allow_right($user_id){

           $company_id = Yii::app()->user->getState('company_branch_id');

            $query="SELECT 
            r.rider_id as rider_id_origional,
            r.fullname,
            o.*
            from rider AS r
            LEFT JOIN rider_delivery_right_option AS o
            ON r.rider_id =o.rider_id and o.user_id ='$user_id' 
            where r.company_branch_id = $company_id and r.is_active=1
            order by r.is_active DESC , r.fullname ASC ";

            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
             $result=[];
            foreach($queryResult as $value){
                 if($value['allow_add']==1){
                     $value['allow_add'] = true;
                 }else{
                     $value['allow_add'] = false;
                 }

                if($value['allow_edit']==1){
                    $value['allow_edit'] = true;
                }else{
                    $value['allow_edit'] = false;
                }

                if($value['allow_delete']==1){
                    $value['allow_delete'] = true;
                }else{
                    $value['allow_delete'] = false;
                }


                $value['update'] = false;




                $result[] =$value;

            }
            return $result;
       }
       public static function getdeliveryDerationData(){
           $company_id = Yii::app()->user->getState('company_branch_id');
           $query="SELECT r.* , cb.name as company_branch_name from rider as r
               LEFT JOIN company_branch as cb ON cb.company_branch_id = r.company_branch_id
               where r.company_branch_id = $company_id
                order by r.fullname ASC ";
            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

            return json_encode($queryResult);
       }
       public static function getPaymentTerm_withCustomer(){

           $company_id = Yii::app()->user->getState('company_branch_id');
           $query="select * from payment_term as d
             where d.company_id = '$company_id'";
           $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
           $final_object = [];
           forEach($queryResult as $value){
                $payment_term_id =$value['payment_term_id'];

                $clientQuery = "SELECT 
                    c.client_id
                    FROM client AS c
                    WHERE c.payment_term ='$payment_term_id' 
                    and c.company_branch_id='$company_id'";
               $cleintList =  Yii::app()->db->createCommand($clientQuery)->queryAll();
               $value['cleintList'] = json_encode($cleintList);

               $final_object[]= $value;


           }

           return json_encode($final_object);
       }
       public static function getRiderList_withCustomer(){
           $company_id = Yii::app()->user->getState('company_branch_id');
           $query="SELECT r.* from rider as r
               where r.company_branch_id = $company_id
                order by r.fullname ASC ";
            $queryResult_rider =  Yii::app()->db->createCommand($query)->queryAll();
             $final_riderList = array();
             foreach($queryResult_rider as $valueRider){
                   $oneObject = array();
                 $RiderID =  $valueRider['rider_id'];
                 $oneObject['rider_id'] =  $valueRider['rider_id'];
                 $oneObject['fullname'] =  $valueRider['fullname'];
                 $clientQuery = "Select c.client_id from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id 
                           where rz.rider_id = $RiderID
                           order by c.rout_order ASC  ";
                 $cleintList =  Yii::app()->db->createCommand($clientQuery)->queryAll();
               $oneObject['cleintList'] = json_encode($cleintList);
                 /* var_dump($oneObject);
                    die();*/
                 $final_riderList[] = $oneObject ;
             }
           return json_encode($final_riderList);
       }

       public static function savePickAmount($data){
            $todayDate = $data['todayDate'];

           $list = $data['data'];
           foreach ($list as $value){

               $rider_id = $value['rider_id'];
               $paymentObject = RecivePaymentByAdmin::model()->findByAttributes(array('rider_id'=>$rider_id ,'date'=>$todayDate));
                if($paymentObject){

                    $totalAmount = intval($value['pick_by_admin']) + $paymentObject['submit_amount'] ;
                    $paymentObject->submit_amount = $totalAmount ;
                     if($paymentObject->save()){
                     }else{
                     }

                }else{
                    $object = new RecivePaymentByAdmin();
                    $object->date =$todayDate;
                    $object->rider_id = $rider_id ;
                    $object->user_id =Yii::app()->user->getState('user_id') ;
                    $object->company_id =Yii::app()->user->getState('company_branch_id') ;
                    $object->submit_amount = $value['pick_by_admin'];
                    $object->save();
                }
           }

       }

       public static function saveNewRiderFunction($data){

           $company_id = Yii::app()->user->getState('company_branch_id');
            $companBranchID =    Yii::app()->user->getState('company_branch_id');
            $loginID =    Yii::app()->user->getState('user_id');
               $zoneList = $data['zone'];

           $rider = new Rider();
           $rider->company_branch_id =$company_id ;
           $rider->user_id =$loginID;
           $rider->fullname =$data['fullname'];
           $rider->father_name=$data['father_name'];
           $rider->userName =$data['userName'];
           $rider->password =$data['password'];
           $rider->email =$data['email'];
           $rider->pos_shop_id =$data['pos_shop_id'];
           $rider->cnic =$data['cnic'];
           $rider->cell_no_1 =$data['cell_no_1'];
           $rider->cell_no_2 =$data['cell_no_2'];
           $rider->residence_phone_no =$data['residence_phone_no'];
           $rider->address =$data['address'];
           $rider->is_active =$data['is_active'];
           $rider->is_deleted =$data['is_deleted'];
           $rider->show_customers_in_app =$data['show_customers_in_app'];
           $rider->can_add_payment =$data['can_add_payment'];
           $rider->created_by =$loginID;
           $rider->created_at = "null";
               if($rider->save()){
                     $riderID = $rider->rider_id ;
                      foreach($zoneList as $value){
                          if($value['isselected']){
                              $zoneRider = new RiderZone();
                              $zoneRider->rider_id = $riderID ;
                              $zoneRider->zone_id = $value['zone_id'];
                              $zoneRider->save();
                          }
                      }

                   $riderID = $rider->rider_id;
                   $query="SELECT z.* , cb.name as company_branch_name from rider as z
                  LEFT JOIN company_branch as cb ON cb.company_branch_id = z.company_branch_id
                     where z.rider_id = $riderID ";
                   $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
                   riderData::$response['success']=true;
                   riderData::$response['message']='ok';
                   riderData::$response['rider']=$queryResult;

               }else{
                   riderData::$response['success'] = false ;
                   riderData::$response['message'] = $rider->getErrors() ;


               }
               return json_encode(riderData::$response);

       }
       public static function getZoneAgainstRiderFunction($id){
           $query ="Select * from rider_zone as rz
             left join zone as z ON z.zone_id = rz.zone_id
              where rz.rider_id = $id";
             $queryResult = Yii::app()->db->createCommand($query)->queryAll();
            return json_encode($queryResult);
       }

       public static function editRiderFunction($data){

            $companBranchID =    Yii::app()->user->getState('company_branch_id');
            $loginID =    Yii::app()->user->getState('user_id');
           $zoneList = $data['zone'];

              $rider =Rider::model()->findByPk(intval($data['rider_id']));
           $rider->company_branch_id =$companBranchID ;
           $rider->user_id =$loginID;
           $rider->fullname =$data['fullname'];
           $rider->userName =$data['userName'];
           $rider->password =$data['password'];
           $rider->father_name=$data['father_name'];
           $rider->email =$data['email'];
           $rider->pos_shop_id =$data['pos_shop_id'];
           $rider->cnic =$data['cnic'];
           $rider->cell_no_1 =$data['cell_no_1'];
           $rider->cell_no_2 =$data['cell_no_2'];
           $rider->residence_phone_no =$data['residence_phone_no'];
           $rider->address =$data['address'];
           $rider->is_active =$data['is_active'];
           $rider->is_deleted =$data['is_deleted'];
           $rider->show_customers_in_app =$data['show_customers_in_app'];
           $rider->can_add_payment =$data['can_add_payment'];
           $rider->created_by =$loginID;
           $rider->created_at = "null";
               if($rider->save()){

                   riderData::$response['success']=true;
                   riderData::$response['message']='ok';
                   RiderZone::model()->deleteAllByAttributes(array('rider_id'=>$data['rider_id']));
                   foreach($zoneList as $value){
                       if($value['isselected']){
                           $zoneRider = new RiderZone();
                           $zoneRider->rider_id = $data['rider_id'] ;
                           $zoneRider->zone_id = $value['zone_id'];
                           $zoneRider->save();
                       }
                   }


               }else{
                   riderData::$response['success'] = false ;
                   riderData::$response['message'] = $rider->getErrors() ;


               }
               return json_encode(riderData::$response);

       }
       public static function deleteFunction($data){
           // var_dump($data);
         //     die();
            $companBranchID =    Yii::app()->user->getState('company_branch_id');
            $loginID =    Yii::app()->user->getState('user_id');

              $rider =Rider::model()->findByPk(intval($data['rider_id']));
               $rider->is_deleted = 1;
               try{
                   if($rider->delete()){

                       riderData::$response['success']=true;
                       riderData::$response['message']='ok';


                   }else{
                       riderData::$response['success'] = false ;
                       riderData::$response['message'] = $rider->getErrors() ;


                   }
               }catch(Exception $e){

                   riderData::$response['success'] = false ;
                   riderData::$response['message'] = $e ;
               }

               return json_encode(riderData::$response);

       }

       public static function get_user_rider_name(){

            $companBranchID =    Yii::app()->user->getState('company_branch_id');

            $query_rider ="SELECT 
                r.rider_id,
                r.fullname
                FROM rider  AS r
                WHERE r.company_branch_id = '$companBranchID' ";
           $queryResult = Yii::app()->db->createCommand($query_rider)->queryAll();

           $rider_object = [] ;

           foreach ($queryResult as $value){
               $rider_id = $value['rider_id'];
               $rider_object[$rider_id]=$value['fullname'];
           }



           /*User list*/


            $query_user ="SELECT 
                u.user_id,
                u.full_name
                from user AS u
                WHERE u.company_id = '$companBranchID' ";
           $queryResult_user = Yii::app()->db->createCommand($query_user)->queryAll();

           $user_object = [] ;

           foreach ($queryResult_user as $value){
               $user_id = $value['user_id'];
               $user_object[$user_id]=$value['full_name'];
           }

          $final_result =[];

           $final_result['user_object'] =$user_object;
           $final_result['rider_object'] =$rider_object;
              return $final_result;

       }

       public static function get_discount_amount($payment_master_id){
           $object = DiscountList::model()->findByAttributes([
               'payment_master_id'=>$payment_master_id
           ]);
           if($object){
              return $object['total_discount_amount'];
           }else{
               return 0;
           }
       }

}