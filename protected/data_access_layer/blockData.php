<?php
/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 3/3/2017
 * Time: 3:31 PM
 */
class blockData{
    public static $response = ['success'=>FALSE , 'message'=>'','data'=>[]];

     public static function get_collectionvault_array(){
         $company_id = Yii::app()->user->getState('company_branch_id');

         $query="select * from collection_vault as a
                     where a.company_id =$company_id
                     order by a.collection_vault_name ASC ";

         $queryResult =  Yii::app()->db->createCommand($query)->queryAll();




         return $queryResult;
     }
     public static function get_collectionvault_array_list_search($data){



         $collection_vault_id = $data['collection_vault_id'];
         $company_id = Yii::app()->user->getState('company_branch_id');

         $query="select * from collection_vault as a
                     where a.company_id =$company_id ";

         if($collection_vault_id>0){

             $query .="  and a.collection_vault_id ='$collection_vault_id' ";

         }
         $query .=" order by a.collection_vault_name ASC ";
         $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

         return $queryResult;
     }

     public static function get_collectionvault_array_list(){

         $company_id = Yii::app()->user->getState('company_branch_id');

         $query="select * from collection_vault as a
                     where a.company_id =$company_id
                     order by a.collection_vault_name ASC ";

         $queryResult =  Yii::app()->db->createCommand($query)->queryAll();




         return $queryResult;
     }
     public static function get_collectionvault(){
         $company_id = Yii::app()->user->getState('company_branch_id');

         $query="select * from collection_vault as a
                     where a.company_id =$company_id
                     order by a.collection_vault_name ASC ";

         $queryResult =  Yii::app()->db->createCommand($query)->queryAll();




         return json_encode($queryResult);
     }
     public static function getBlockList(){
           $company_id = Yii::app()->user->getState('company_branch_id');

           $query="select * from block as a
                     where a.company_id =$company_id
                     order by a.block_name ASC ";

            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();




            return json_encode($queryResult);
       }

    public static function getZoneList_zoneName(){
        $company_id = Yii::app()->user->getState('company_branch_id');
        $query="SELECT z.zone_id ,z.name from zone as z
                
                  where z.company_branch_id = $company_id
                   order by z.name ASC ";
        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


        return json_encode($queryResult);
    }

       public static function saveNewZoneFunction($data){
           $company_id = Yii::app()->user->getState('company_branch_id');

              $zone = new Zone();
              $zone->company_branch_id = $company_id;
              $zone->name  = $data['name'];
              $zone->commission  = $data['commission'];
              $zone->is_active = $data['is_active'];
              $zone->is_deleted = $data['is_deleted'];
               if($zone->save()){
                   $zoneID = $zone->zone_id;
                   $query="SELECT z.* , cb.name as companyBranchName from zone as z
                  LEFT JOIN company_branch as cb ON cb.company_branch_id = z.company_branch_id
                     where z.zone_id = $zoneID ";
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

       public static function editZoneFunction($data){

              $zone = Zone::model()->findByPk($data['zone_id']);
              $zone->company_branch_id = Yii::app()->user->getState('company_branch_id');
             $zone->name  = $data['name'];
           $zone->commission  = $data['commission'];
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


              $zone = Zone::model()->findByPk($data['zone_id']);
              $zone->is_deleted = 1 ;
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

}