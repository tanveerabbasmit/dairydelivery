<?php
/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 3/3/2017
 * Time: 3:31 PM
 */
class companyData{
    public static $response = ['success'=>FALSE , 'message'=>'','data'=>[]];

       public static function getCompanyList(){
           $query="SELECT * from company";
            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


            return json_encode($queryResult);
       }

       public static function saveNewCompanyFunction($data){



              $zone = new Company();

             $zone->company_name  = $data['company_name'];
             $zone->subdomain  = $data['subdomain'];
             $zone->logo  = $data['logo'];
             $zone->address  = $data['address'];
             $zone->phone_number  = $data['phone_number'];
             $zone->contact_person  = $data['contact_person'];
              $zone->is_active = $data['is_active'];
              $zone->is_deleted = 0;
              $zone->created_by = 0;
               if($zone->save()){
                   $zoneID = $zone->company_id;
                   $query="SELECT * from company 
                       where company_id = $zoneID ";
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

       public static function editCompanyFunction($data){

              $zone = Company::model()->findByPk($data['company_id']);

             // $zone->company_branch_id = Yii::app()->user->getState('company_branch_id');
                $zone->company_name  = $data['company_name'];
                $zone->subdomain  = $data['subdomain'];
               $zone->logo  = $data['logo'];
           $zone->address  = $data['address'];
           $zone->phone_number  = $data['phone_number'];
           $zone->contact_person  = $data['contact_person'];
               $zone->is_active = $data['is_active'];
               $zone->is_deleted = 0;
               $zone->created_by = 0;
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


              $zone = Company::model()->findByPk($data['company_id']);
              $zone->is_deleted = 1 ;
               if($zone->save()){

                   zoneData::$response['success']=true;
                   zoneData::$response['message']='ok';


               }else{

                   zoneData::$response['success'] = false ;
                   zoneData::$response['message'] = $zone->getErrors() ;

               }
               return json_encode(zoneData::$response);

       }

}