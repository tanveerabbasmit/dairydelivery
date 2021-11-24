<?php
/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 3/3/2017
 * Time: 3:31 PM
 */
class qualityListData{
    public static $response = ['success'=>FALSE , 'message'=>'','data'=>[]];

    public static function getqualityList(){
        $company_id = Yii::app()->user->getState('company_branch_id');
        $query="select q.quality_list_id , q.quality_name  
                      from quality_list as q
                     where q.company_id ='$company_id' ";
        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
        $finalResult = array();
        foreach ($queryResult as $key=>$value){
            $oneObject =array();
            $oneObject['quality_list_id'] ='';
            $oneObject['quality_name'] ='Total solids';
            if($key==2){
                $finalResult[]=$oneObject;
            }
            $finalResult[] = $value;
        }
        return json_encode($finalResult);
    }

    public static function getFarmList_all_arry(){
        $company_id = Yii::app()->user->getState('company_branch_id');
        $query="select * 
                          from farm as q
                         where q.company_id ='$company_id' ";
        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


        return ($queryResult);
    }
    public static function getFarmList_all(){
        $company_id = Yii::app()->user->getState('company_branch_id');
        $query="select * 
                          from farm as q
                         where q.company_id ='$company_id' ";
        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


        return json_encode($queryResult);
    }
    public static function getFarmList(){
        $company_id = Yii::app()->user->getState('company_branch_id');
        $query="select * 
                          from farm as q
                         where q.company_id ='$company_id' and q.is_active=1";
        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


        return json_encode($queryResult);
    }
    public static function getFarmList_for_drop_down(){

        $company_id = Yii::app()->user->getState('company_branch_id');

        $query="select * 
                          from farm as q
                         where q.company_id ='$company_id' and q.is_active=1 ";
        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


        return $queryResult;
    }
    public static function getFarmList_with_quality_value($data){


        $start_date= $data['date'];
        $end_date= $data['end_date'];

        $x= strtotime($start_date);
        $y= strtotime($end_date);

        $company_id = Yii::app()->user->getState('company_branch_id');
        $query="select * 
                          from farm as q
                         where q.company_id ='$company_id' and q.is_active=1 ";

        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


        $end_result = array();

        while($x < ($y+8640)){
            $one_result_oneObject = array();

            $finalResult = array();
            $selectDate = date("Y-m-d", $x);

            $x += 86400;

            $one_result_oneObject["slect_date"]=$selectDate ;



            foreach ($queryResult as $value){
                $oneObject =array();
                $oneObject['selectDate']=$selectDate ;
                $oneObject['fram'] =$value;
                $farm_id =$value['farm_id'];
                $query_value="select $selectDate as 'select_date' ,
                    ql.quality_list_id ,
                    ql.quality_name ,
                    ifnull(fq.quantity_value ,'') as quantity_value 
                    from quality_list as ql
                    left join farm_quality as fq on ql.quality_list_id = fq.quality_list_id
                    and fq.date ='$selectDate' and fq.farm_id = '$farm_id'
                    where ql.company_id ='$company_id' ";
                $queryResult_value =  Yii::app()->db->createCommand($query_value)->queryAll();
                $queryResult_value_new = array();
                $sum_total_solid =0;
                foreach ($queryResult_value as $key=>$value){

                    if($key ==0 || $key ==1){
                        $sum_total_solid = $sum_total_solid +  (float)($value['quantity_value']);
                    }
                    if($key ==2){
                        if($sum_total_solid>0){
                            $oneObject_total  =array();
                            $oneObject_total['quantity_value'] =number_format($sum_total_solid, 2);
                            $queryResult_value_new[] =$oneObject_total ;
                        }else{
                            $oneObject_total  =array();
                            $oneObject_total['quantity_value'] = '';
                            $queryResult_value_new[] =$oneObject_total ;
                        }
                    }
                    if($key >2){
                        $sum_total_solid =0;
                    }
                    $queryResult_value_new[] =$value ;
                }
                $oneObject['value'] =$queryResult_value_new;

                $total_value =0;
                foreach ($oneObject['value'] as $value){
                    if($value['quantity_value']!=''){
                        $total_value = $total_value +  $value['quantity_value'] ;
                    }
                }
                if($total_value>0){
                    $finalResult[] = $oneObject;
                }
            }
            $one_result_oneObject["record"]=sizeof($finalResult);

            $one_result_oneObject['data']=$finalResult;
            if(isset($finalResult[0]['value'])){
                $end_result[] = $one_result_oneObject;
            }

        }

        return json_encode($end_result);
    }
    public static function getFarmList_with_quality_value2($data){

        $start_date= $data['date'];
        $end_date= $data['end_date'];
        $company_id = Yii::app()->user->getState('company_branch_id');
        $query="select * 
                          from farm as q
                         where q.company_id ='$company_id' and q.is_active=1 ";

        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();



        $finalResult = array();
        foreach ($queryResult as $value){
            $oneObject =array();
            $oneObject['fram'] =$value;

            $farm_id =$value['farm_id'];

            $query_value="select  ql.quality_list_id ,ql.quality_name , ifnull(fq.quantity_value ,'') as quantity_value  from quality_list as ql
                left join farm_quality as fq on ql.quality_list_id = fq.quality_list_id
                and fq.date ='$start_date' and fq.farm_id = '$farm_id'
                where ql.company_id ='$company_id' ";



            $queryResult_value =  Yii::app()->db->createCommand($query_value)->queryAll();

            $queryResult_value_new = array();
            $sum_total_solid =0;
            foreach ($queryResult_value as $key=>$value){


                if($key ==0 || $key ==1){
                    $sum_total_solid = $sum_total_solid +  (float)($value['quantity_value']);
                }
                if($key ==2){
                    if($sum_total_solid>0){
                        $oneObject_total  =array();
                        $oneObject_total['quantity_value'] =number_format($sum_total_solid, 2);
                        $queryResult_value_new[] =$oneObject_total ;
                    }else{
                        $oneObject_total  =array();
                        $oneObject_total['quantity_value'] = '';
                        $queryResult_value_new[] =$oneObject_total ;
                    }

                }
                if($key >2){
                    $sum_total_solid =0;
                }
                $queryResult_value_new[] =$value ;
            }

            $oneObject['value'] =$queryResult_value_new;

            $total_value =0;
            foreach ($oneObject['value'] as $value){
                if($value['quantity_value']!=''){
                    $total_value = $total_value +  $value['quantity_value'] ;
                }
            }
            if($total_value>0){
                $finalResult[] = $oneObject;
            }


        }

        return json_encode($finalResult);
    }

    public static function editQualityFunction($data){

        if($data['quality_list_id'] >0){
            $zone = QualityList::model()->findByPk($data['quality_list_id']);

            $zone->quality_name  = $data['quality_name'];

            if($zone->save()){

                zoneData::$response['success']=true;
                zoneData::$response['message']=$zone->quality_list_id;


            }else{
                zoneData::$response['success'] = false ;
                zoneData::$response['message'] = $zone->getErrors() ;

            }
        }else{
            $zone =new QualityList();

            $zone->quality_name  = $data['quality_name'];
            $zone->company_id  = Yii::app()->user->getState('company_branch_id');;

            if($zone->save()){

                zoneData::$response['success']=true;
                zoneData::$response['message']=$zone->quality_list_id;


            }else{
                zoneData::$response['success'] = false ;
                zoneData::$response['message'] = $zone->getErrors() ;

            }
        }


        return json_encode(zoneData::$response);

    }
    public static function editFarmFunction($data){


        $phone_number = $data['phone_number'];

        if(strlen($phone_number) !='13'){
            $responce['success'] =false;
            $responce['message'] ="number invalid";
            echo json_encode($responce);
            die();
        }

        if($data['farm_id'] >0){
            $zone = Farm::model()->findByPk($data['farm_id']);

            $zone->farm_name  = $data['farm_name'];
            $zone->phone_number  = $data['phone_number'];
            $zone->payment_alert  = $data['payment_alert'];
            $zone->purchase_alert  = $data['purchase_alert'];
            $zone->is_active  = $data['is_active'];

            if($zone->save()){

                zoneData::$response['success']=true;
                zoneData::$response['message']=$zone->farm_id;


            }else{
                zoneData::$response['success'] = false ;
                zoneData::$response['message'] = $zone->getErrors() ;

            }
        }else{
            $zone =new Farm();

            $zone->farm_name  = $data['farm_name'];
            $zone->phone_number  = $data['phone_number'];
            $zone->payment_alert  = $data['payment_alert'];
            $zone->purchase_alert  = $data['purchase_alert'];
            $zone->is_active  = $data['is_active'];
            $zone->company_id  = Yii::app()->user->getState('company_branch_id');;

            if($zone->save()){

                zoneData::$response['success']=true;
                zoneData::$response['message']=$zone->farm_id;


            }else{
                zoneData::$response['success'] = false ;
                zoneData::$response['message'] = $zone->getErrors() ;

            }
        }


        return json_encode(zoneData::$response);

    }

    public static function deleteFunction($data){

        $zone = QualityList::model()->findByPk($data['quality_list_id']);
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
    public static function deleteFunction_farm($data){

        $zone = QualityList::model()->findByPk($data['farm_id']);
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