<?php
/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 3/3/2017
 * Time: 3:31 PM
 */
class cattleData{
    public static $response = ['success'=>FALSE , 'message'=>'','data'=>[]];

    public static function getCattleList(){
           $company_id = Yii::app()->user->getState('company_branch_id');
           $query="Select  * from cattle_record as c
                  where c.company_id = $company_id  
                  order by c.number ASC";
            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


            return json_encode($queryResult);
     }
    public static function getCattleList_production($todaydate){


        $query="Select c.cattle_record_id from cattle_milking_duration as c
             where  (c.milking_off_date <='$todaydate' and c.milking_on_date >'$todaydate') 
             OR c.milking_on_active =0";


        $result =  Yii::app()->db->createCommand($query)->queryAll();

        $demilking_cattle =array();

        foreach ($result as $value){
            $cattle_record_id = $value['cattle_record_id'];
            $demilking_cattle[$cattle_record_id] = true;
        }


           $company_id = Yii::app()->user->getState('company_branch_id');

            $query="Select c.*, '$todaydate' as date ,
            ifnull(cp.morning ,0) as  morning , ifnull(cp.afternoun ,0) as  afternoun 
            , ifnull(cp.evenining ,0) as  evenining
            from cattle_record as c
            left join cattle_production as cp ON 
            c.cattle_record_id = cp.cattle_record_id and cp.date='$todaydate'
            where c.company_id = $company_id  and c.create_date <='$todaydate'
            order by c.number ASC ";


            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

            $finalResult =array();

            foreach ($queryResult as $value){
                $cattle_record_id =$value['cattle_record_id'];
                $number =$value['number'];
                $type =$value['type'];
                $is_active =$value['is_active'];
                $milking =$value['milking'];
                $milking_time_morning =$value['milking_time_morning'];
                $milking_time_afternoun =$value['milking_time_afternoun'];
                $milking_time_evening =$value['milking_time_evening'];
                $picture =$value['picture'];
                $company_id =$value['company_id'];
                $date =$value['date'];
                $morning =$value['morning'];
                $afternoun =$value['afternoun'];
                $evenining =$value['evenining'];

                $oneObject =array();
                $oneObject['cattle_record_id']=$cattle_record_id;
                $oneObject['number']=$number;
                $oneObject['type']=$type;
                $oneObject['is_active']=$is_active;
                $oneObject['milking']=$milking;
                $oneObject['milking_time_morning']=$milking_time_morning;
                $oneObject['milking_time_afternoun']=$milking_time_afternoun;
                $oneObject['milking_time_evening']=$milking_time_evening;
                $oneObject['picture']=$picture;
                $oneObject['company_id']=$company_id;
                $oneObject['date']=$date;
                $oneObject['morning']=$morning;
                $oneObject['afternoun']=$afternoun;
                $oneObject['evenining']=$evenining;
                $total_milk_quantity = $morning + $afternoun + $evenining ;


                if(isset($demilking_cattle[$cattle_record_id])){

                    if($total_milk_quantity >0){
                         $finalResult[] =$oneObject ;
                    }
                }else{
                    $finalResult[] =$oneObject ;
                }

            }
           return json_encode($finalResult);
     }
     public static function  addCattle($cattle){


         $company_id = Yii::app()->user->getState('company_branch_id');
        if($cattle ==0){
            $result = [
                'cattle_record_id'=>0,
                'number'=>'',
                'create_date'=>date("Y-m-d"),
                'milking_on_off_date'=>date("Y-m-d"),
                'use_affective'=>false,
                'milking'=>'1',
                'is_active'=>'1',
                'milking_time_morning'=>false,
                'milking_time_afternoun'=>false,
                'milking_time_evening'=>false,
                'picture'=>'noImage.jpg',
                'type'=>'Cow',
                'company_id'=>$company_id,

            ];

             return json_encode($result);

        }else{


            $cattle=CattleRecord::model()->findByPk(intval($cattle));



            if($cattle->milking_time_morning ==1){
                  $milking_time_morning = true;
            }else{
                  $milking_time_morning = false;
            }

            if($cattle->is_active ==1){
                  $is_active = true;
            }else{
                  $is_active = false;
            }


            if($cattle->milking_time_afternoun ==1){
                $milking_time_afternoun = true;
            }else{
                $milking_time_afternoun = false;
            }


            if($cattle->milking_time_evening ==1){
                $milking_time_evening= true;
            }else{
                $milking_time_evening = false;
            }

            $result = [
                'cattle_record_id'=>$cattle->cattle_record_id,
                'number'=>$cattle->number,
                'create_date'=>$cattle->create_date,
                'milking'=>$cattle->milking,
                'milking_time_morning'=>$milking_time_morning,
                'milking_time_afternoun'=>$milking_time_afternoun,
                'milking_time_evening'=>$milking_time_evening,
                'picture'=>$cattle->picture,
                'type'=>$cattle->type,
                'is_active'=>$is_active,
                'company_id'=>$company_id,

            ];


            return json_encode($result);
        }
     }



}