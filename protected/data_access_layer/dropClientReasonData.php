<?php
/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 3/3/2017
 * Time: 3:31 PM
 */
class   dropClientReasonData{
    public static $response = ['success'=>FALSE , 'message'=>'','data'=>[]];

       public static function getReasonList(){
           $company_id = Yii::app()->user->getState('company_branch_id');
           $query="select * from sample_client_drop_reason as sc
                        where sc.company_branch_id = '$company_id' 
                        ORDER BY sc.reason ASC ";


            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


            return json_encode($queryResult);
       }

       public static function getReasonList_of_dropCustomer(){
           $company_id = Yii::app()->user->getState('company_branch_id');
           $query="select * from sample_client_drop_reason as sc
                        where sc.company_branch_id = '$company_id'
                        ORDer by sc.reason ASC ";
            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


            return $queryResult;
       }

       public static function DropCustomerList(){
           $company_id = Yii::app()->user->getState('company_branch_id');
           $query="select c.fullname ,c.address , r.reason from make_regualr_drop_client as mc
                   left join client as c ON c.client_id = mc.client_id
                   left join sample_client_drop_reason as r ON r.sample_client_drop_reason_id = mc.sample_client_drop_reason_id
                   where mc.drop_or_regular = 2 and mc.company_id='$company_id'";


            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


            return json_encode($queryResult);
       }

       public static function getExpenceList_array(){

           $company_id = Yii::app()->user->getState('company_branch_id');
           $query="select * from expence_type as sc
                        where sc.company_id = '$company_id'";
            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
            return $queryResult;

       }
       public static function getExpenceList(){

           $company_id = Yii::app()->user->getState('company_branch_id');
           $query="select * from expence_type as sc
                        where sc.company_id = '$company_id'";
            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
            return json_encode($queryResult);

       }
       public static function getExpenceList_all_list(){
           $company_id = Yii::app()->user->getState('company_branch_id');
           $query="select * from expence_type as sc
                        where sc.company_id = '$company_id'";
            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


            return $queryResult;
       }

       public static function saveNewReason($data){
           $company_id = Yii::app()->user->getState('company_branch_id');

              $zone = new SampleClientDropReason();
              $zone->company_branch_id = $company_id;
             $zone->reason  = $data['reason'];

               if($zone->save()){
                   $zoneID = $zone->sample_client_drop_reason_id;
                   $query="select * from sample_client_drop_reason as sc
                     where sc.sample_client_drop_reason_id = $zoneID ";
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
       public static function saveNewExpence($data){


           $company_id = Yii::app()->user->getState('company_branch_id');

              $zone = new ExpenceType();
              $zone->company_id = $company_id;
             $zone->type  = $data['type'];

               if($zone->save()){
                   $zoneID = $zone->expence_type;
                   $query="select * from expence_type as sc
                     where sc.expence_type = $zoneID ";
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

       public static function editReasonFunction($data){


              $zone = SampleClientDropReason::model()->findByPk($data['sample_client_drop_reason_id']);

             $zone->reason  = $data['reason'];

               if($zone->save()){

                   zoneData::$response['success']=true;
                   zoneData::$response['message']='ok';


               }else{
                   zoneData::$response['success'] = false ;
                   zoneData::$response['message'] = $zone->getErrors() ;

               }
               return json_encode(zoneData::$response);

       }
       public static function editExpencetypeFunction($data){


              $zone = ExpenceType::model()->findByPk($data['expence_type']);

             $zone->type  = $data['type'];

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


              $zone = SampleClientDropReason::model()->findByPk($data['sample_client_drop_reason_id']);

              try{
                  if($zone->delete()){

                      zoneData::$response['success']=true;
                      zoneData::$response['message']='ok';


                  }else{

                      zoneData::$response['success'] = false ;
                      zoneData::$response['message'] = $zone->getErrors() ;

                  }
              }catch (Exception $e){
                  zoneData::$response['success'] = false ;
                  zoneData::$response['message'] = $e ;
              }

               return json_encode(zoneData::$response);

       }
       public static function deleteFunction_expencetype($data){

              $zone = ExpenceType::model()->findByPk($data['expence_type']);

              try{
                  if($zone->delete()){

                      zoneData::$response['success']=true;
                      zoneData::$response['message']='ok';


                  }else{

                      zoneData::$response['success'] = false ;
                      zoneData::$response['message'] = $zone->getErrors() ;

                  }
              }catch (Exception $e){
                  zoneData::$response['success'] = false ;
                  zoneData::$response['message'] = $e ;
              }

               return json_encode(zoneData::$response);

       }

       public static function get_client_list_for_payment(){


               $company_id = Yii::app()->user->getState('company_branch_id');
                $query="SELECT 
                    c.client_id,
                    CONCAT(c.client_id , ':',c.fullname) as fullname 
                    from client as c
                    where  c.company_branch_id = '$company_id'
                    Order By c.fullname ASC ";


               $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


               return $queryResult;

       }

  }