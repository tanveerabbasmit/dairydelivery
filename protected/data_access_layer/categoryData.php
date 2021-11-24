<?php
/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 3/3/2017
 * Time: 3:31 PM
 */
class   categoryData{
    public static $response = ['success'=>FALSE , 'message'=>'','data'=>[]];



       public static function getCategoryList_for_recory_report(){

           $company_id = Yii::app()->user->getState('company_branch_id');

           $query="SELECT z.* , cb.name as companyBranchName from customer_category as z
                  LEFT JOIN company_branch as cb ON cb.company_branch_id = z.company_branch_id
                  where z.company_branch_id = $company_id
                   ORDER BY z.category_name ";

            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

            $final_data = [];
           $final_data[0] = '';
            foreach($queryResult as $value){
                $customer_category_id =  $value['customer_category_id'];
                $category_name =  $value['category_name'];
                $final_data[$customer_category_id] = $category_name;


            }
            return $final_data;

       }
       public static function getCategoryList(){

           $company_id = Yii::app()->user->getState('company_branch_id');

           $query="SELECT
                   z.* , cb.name as companyBranchName from customer_category as z
                  LEFT JOIN company_branch as cb ON cb.company_branch_id = z.company_branch_id
                  where z.company_branch_id = $company_id
                   ORDER BY z.category_name ";

            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


            return json_encode($queryResult);
       }
       public static function getCategoryList_array(){

           $company_id = Yii::app()->user->getState('company_branch_id');

           $query="SELECT z.* , cb.name as companyBranchName 
                   from customer_category as z
                  LEFT JOIN company_branch as cb 
                      ON cb.company_branch_id = z.company_branch_id
                  where z.company_branch_id = $company_id
                   ORDER BY z.category_name ";

            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

            $array_size =  sizeof($queryResult);

            $one_object =[];
            $one_object['customer_category_id'] =0;
            $one_object['category_name'] ='NA';
            $queryResult[$array_size] =$one_object;



            $category_list =[];
            foreach ($queryResult as $value){
                $customer_category_id = $value['customer_category_id'];
                $category_name = $value['category_name'];

                $client_ids =  clientData::getActiveClientLis_category_wise($customer_category_id);

                $one_obect= [];
                $one_obect['customer_category_id']= $customer_category_id;
                $one_obect['category_name']= $category_name;
                $one_obect['client_ids']= $client_ids;

                $category_list[] = $one_obect;
            }

            return $category_list;
       }




       public static function getCategoryList_group_by(){
           $company_id = Yii::app()->user->getState('company_branch_id');
           $query="SELECT z.* , cb.name as companyBranchName from customer_category as z
                  LEFT JOIN company_branch as cb ON cb.company_branch_id = z.company_branch_id
                  where z.company_branch_id = $company_id 
                  ORDER BY z.category_name ASC ";
          $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
          return json_encode($queryResult);
       }

       public static function saveNewZoneFunction($data){
           $company_id = Yii::app()->user->getState('company_branch_id');

              $zone = new Zone();
              $zone->company_branch_id = $company_id;
             $zone->name  = $data['name'];
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