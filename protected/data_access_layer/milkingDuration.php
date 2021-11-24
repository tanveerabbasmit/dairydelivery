<?php
/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 3/3/2017
 * Time: 3:31 PM
 */
class milkingDuration{
    public static $response = ['success'=>FALSE , 'message'=>'','data'=>[]];

    public static function add_new_cattle_milking_recored($cattle_record_id,$milking ,$milking_on_off_date){


        $object_cattle_duration = New CattleMilkingDuration();
        $object_cattle_duration->cattle_record_id =$cattle_record_id ;
        $object_cattle_duration->milking_off_active =1;
        $object_cattle_duration->milking_off_date =$milking_on_off_date ;

        if($object_cattle_duration->save()){

        }


    }
    public static function add_new_cattle_milking_recored_on($cattle_record_id,$milking ,$milking_on_off_date){


        $object_cattle_duration = New CattleMilkingDuration();
        $object_cattle_duration->cattle_record_id =$cattle_record_id ;
        $object_cattle_duration->milking_on_active =1;
        $object_cattle_duration->milking_on_date =$milking_on_off_date ;

        if($object_cattle_duration->save()){

        }


    }
    public static function edit_new_cattle_milking_recored($cattle_record_id,$milking,$milking_on_off_date){

        if($milking ==0){


            $Query ="select * from cattle_milking_duration as c
                     where c.cattle_record_id ='$cattle_record_id'
                     order by c.cattle_milking_duration_id DESC
                     limit 1 ";

            $object_cattle_duration = New CattleMilkingDuration();
            $object_cattle_duration->cattle_record_id =$cattle_record_id ;
            $object_cattle_duration->milking_off_date =$milking_on_off_date ;
            $object_cattle_duration->milking_off_active =1; ;
            $object_cattle_duration->save();

        }else{




            $Query ="select * from cattle_milking_duration as c
                     where c.cattle_record_id ='$cattle_record_id'
                     order by c.cattle_milking_duration_id DESC
                     limit 1 ";

            $paymentMasterResult = Yii::app()->db->createCommand($Query)->queryAll();
            if($paymentMasterResult){
                $cattle_milking_duration_id = $paymentMasterResult[0]['cattle_milking_duration_id'] ;
                $object_cattle_duration = CattleMilkingDuration::model()->findByPk(intval($cattle_milking_duration_id));
                $object_cattle_duration->milking_on_date = $milking_on_off_date;
                $object_cattle_duration->milking_on_active =1;
                $object_cattle_duration->save();
            }

        }

    }

}