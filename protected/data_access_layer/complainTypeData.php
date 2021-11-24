<?php
/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 3/3/2017
 * Time: 3:31 PM
 */
class complainTypeData{
    public static $response = ['success'=>FALSE , 'message'=>'','data'=>[]];

       public static function getcomplainTypeList(){
           $company_id = Yii::app()->user->getState('company_branch_id');
          $typeList = ComplainType::model()->findAll(array("condition" => "company_branch_id = $company_id"));
           $complainTypeList = array();
           foreach($typeList as $value){
               $complainTypeList[] = $value->attributes;
           }
           return json_encode($complainTypeList);
       }


       public static function saveNewComplainFunction($data){

              $zone = new ComplainType();
              $zone->name = $data['name'];
              $zone->company_branch_id = Yii::app()->user->getState('company_branch_id');
           if($zone->save()){

                   $zoneID = $zone->complain_type_id;
                   $query="Select * from   complain_type
                          where complain_type_id = $zoneID ";
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

       public static function editComplainFunction($data){

              $zone = ComplainType::model()->findByPk($data['complain_type_id']);
              $zone->company_branch_id = Yii::app()->user->getState('company_branch_id');
             $zone->name  = $data['name'];

               if($zone->save()){

                   zoneData::$response['success']=true;
                   zoneData::$response['message']='ok';


               }else{
                   zoneData::$response['success'] = false ;
                   zoneData::$response['message'] = $zone->getErrors() ;

               }
               return json_encode(zoneData::$response);

       }


       public static function deleteComplainTypeFunction($data){
              Yii::app()->db->createCommand('set foreign_key_checks=0')->execute();
              $zone = ComplainType::model()->findByPk(intval($data));

               if($zone->delete()){

                   zoneData::$response['success']=true;
                   zoneData::$response['message']='ok';


               }else{

                   zoneData::$response['success'] = false ;
                   zoneData::$response['message'] = $zone->getErrors() ;

               }
           Yii::app()->db->createCommand('set foreign_key_checks=1')->execute();
               return json_encode(zoneData::$response);

       }

}