<?php
/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 3/3/2017
 * Time: 3:31 PM
 */
class clientComplainData{
    public static $response = ['success'=>FALSE , 'message'=>'','data'=>[]];

       public static function getclientComplainDataList($data){

           $company_id = Yii::app()->user->getState('company_branch_id');

            $type = $data['type'];
            $page =  $data['page'];
            $status_id =  $data['status_id'];
            $page = $page - 1;
            $offset = $page * 10;

            $query="SELECT com.* ,
                   cl.fullname , 
                  s.status_name  ,
                  comT.name from  complain as com
                LEFT JOIN  client as cl ON cl.client_id = com.client_id
                LEFT JOIN status as s ON s.status_id = com.status_id
                LEFT JOIN complain_type as comT ON comT.complain_type_id = com.complain_type_id 
                where com.type = '$type'  and com.company_branch_id = $company_id";

                if($status_id>0){
                    $query .=" and  com.status_id ='$status_id' ";
                }

               $query .=" order by com.complain_id DESC
                LIMIT 10 OFFSET $offset ";






            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

            $countQuery = " select count(*) as Totalcount from complain as c
                             where 
                            c.type =$type  
                            and  c.company_branch_id = $company_id  " ;

           if($status_id>0){
               $countQuery .=" and  c.status_id ='$status_id' ";
           }


           $countResult = Yii::app()->db->createCommand($countQuery)->queryAll();
            $totalRecord = $countResult[0]['Totalcount'];
             $finalResult =array();
            $finalResult['data'] = $queryResult ;
            $finalResult['totalRecord'] = $totalRecord ;
             echo  json_encode($finalResult);
       }

       public static function totalComplainOfOneCustomerFunction($clientID){
                $result = array();
           $query="SELECT com.* , cl.fullname , s.status_name  ,  comT.name from  complain as com
                  LEFT JOIN  client as cl ON cl.client_id = com.client_id
                 LEFT JOIN status as s ON s.status_id = com.status_id
                 LEFT JOIN complain_type as comT ON comT.complain_type_id = com.complain_type_id 
                   where com.client_id =  $clientID And com.type = '1' ";
            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
           $result["result"] = $queryResult ;
           $result["count"] = count($queryResult);

            return json_encode($result);
       }

       public static function searchComplainFunction($search){

           $query="SELECT com.* , cl.fullname , s.status_name  ,  comT.name from  complain as com
                  LEFT JOIN  client as cl ON cl.client_id = com.client_id
                 LEFT JOIN status as s ON s.status_id = com.status_id
                 LEFT JOIN complain_type as comT ON comT.complain_type_id = com.complain_type_id 
                  where cl.fullname LIKE '$search%'   ";
            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
            return json_encode($queryResult);
       }


       public static function getclientComplainDataCOUNT(){

           $query="SELECT com.*  from  complain as com
                  where com.type = '1' ";
            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
            return count($queryResult);
       }
     public static function getStatusList(){
         $company_id = Yii::app()->user->getState('company_branch_id');
         $status = Status::model()->findAll();
         $statusList = array();
         foreach($status as $value){
             $statusList[] = $value->attributes;
         }
         return json_encode($statusList);
     }

     public static function saveStatus($reciveData){


         $company_id = Yii::app()->user->getState('company_branch_id');

         $data = $reciveData['statusObject'];
         $statusID = $data['status_id'];
          $response = $data['response'];
          $clientID = $data['client_id'];
         $page = $reciveData['page'];

           $getStatusID = $data['status_id'];

         $status =Complain::model()->findByPk(intval($data['complain_id']));
         $status->status_id = $data['status_id'];
         $status->response = $data['response'];
          if($status->save()){
                $offset = 0 ;

                if($page){
                    $page = $page-1;
                $offset = $page * 10;
                }

                $query="SELECT com.* , cl.fullname , s.status_name  ,  comT.name from  complain as com
                LEFT JOIN  client as cl ON cl.client_id = com.client_id
                LEFT JOIN status as s ON s.status_id = com.status_id
                LEFT JOIN complain_type as comT ON comT.complain_type_id = com.complain_type_id 
                 where com.type = '1' and com.company_branch_id = $company_id
                LIMIT 10 OFFSET $offset";

                $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

                clientComplainData::$response['success']=true;
                clientComplainData::$response['message']= $queryResult ;
                if($getStatusID == 2){
                  $companyObject  =  utill::get_companyTitle($company_id);
                    $companyMask = $companyObject['sms_mask'];
                    $getmessage = $companyObject['company_title'];





                    $clientObject = Client::model()->findByPk(intval($clientID));
                     $phoneNO = $clientObject['cell_no_1'];
                     $message = "Dear Customer,\nYour complaint has been resolved.\n";

                    $message .= $response."\n\n".$getmessage;

                    utill::sendSMS2($phoneNO , $message , $companyMask ,$company_id,1);
                }

          }else{
              clientComplainData::$response['success']=true;
              clientComplainData::$response['message']='not save';
          }
                return json_encode(clientComplainData::$response);
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