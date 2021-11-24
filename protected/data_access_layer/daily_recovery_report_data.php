<?php

class daily_recovery_report_data{

    public static function get_payment_of_client_list_and_discount($client_ids,$data){


        $startDate  =  $data['startDate'];
        $endDate  =  $data['endDate'];

        $payment_mode = $data['payment_mode'];
        $payment_type = $data['payment_type'];

        $company_id = Yii::app()->user->getState('company_branch_id');
        $query = "SELECT 
            pm.client_id,
            pm.date ,
            pm.payment_master_id,
            pm.user_id,
            pm.rider_id,
            pm.reference_number,
            pm.payment_mode ,
            IFNULL((pm.amount_paid) ,0) 
                as amount_paid FROM payment_master as pm
            where  pm.payment_type = '$payment_type' and pm.company_branch_id = '$company_id'
            and pm.client_id in ($client_ids) and pm.date
            between '$startDate' and '$endDate' ";
            if($payment_mode>0){
              $query .= " and pm.payment_mode='$payment_mode' ";
            }



        $totalMake_object  =  Yii::app()->db->createCommand($query)->queryAll();
        $final_payment =[];

        $payment_mater_ids_list = [];
        $payment_mater_ids_list[] =0;
        foreach($totalMake_object as $value){
            $client_id = $value['client_id'];
            $final_payment[$client_id][] = $value;

            $payment_mater_ids_list[] = $value['payment_master_id'];

        }


        $discount_list = self::get_discount_list_of_payment($payment_mater_ids_list);
        $discount_list_string = self::get_discount_list_of_payment_string($payment_mater_ids_list);



        $reuslt = [];
        $reuslt['final_payment']= $final_payment;
        $reuslt['discount_list']= $discount_list;
        $reuslt['discount_list_string']= $discount_list_string;

        return $reuslt;

    }
    public static function get_discount_list_of_payment($payment_mater_ids_list){
        $ids = implode(',',$payment_mater_ids_list);

        $query = "SELECT 
            dl.payment_master_id,
            dl.total_discount_amount,
            dt.discount_type_name
            FROM discount_list AS dl
            LEFT JOIN discount_type AS dt ON dl.discount_type_id = dt.discount_type_id
            WHERE dl.payment_master_id IN ($ids) ";
        $discount_abject  =  Yii::app()->db->createCommand($query)->queryAll();

        $list = [];

        foreach ($discount_abject as $value){
            $payment_master_id = $value['payment_master_id'];
            $total_discount_amount = $value['total_discount_amount'];
            $discount_type_name = $value['discount_type_name'];
            if(isset($list[$payment_master_id])){
                $list[$payment_master_id] = $list[$payment_master_id]+ $total_discount_amount;
            }else{
                $list[$payment_master_id] = $total_discount_amount;
            }
        }
        return $list;


    }
    public static function get_discount_list_of_payment_string($payment_mater_ids_list){
        $ids = implode(',',$payment_mater_ids_list);

        $query = "SELECT 
            dl.payment_master_id,
            dl.total_discount_amount,
            dt.discount_type_name
            FROM discount_list AS dl
            LEFT JOIN discount_type AS dt ON dl.discount_type_id = dt.discount_type_id
            WHERE dl.payment_master_id IN ($ids) ";
        $discount_abject  =  Yii::app()->db->createCommand($query)->queryAll();

        $list = [];
        $discount_string = '';
        foreach ($discount_abject as $value){
            $payment_master_id = $value['payment_master_id'];
            $total_discount_amount = $value['total_discount_amount'];
            $discount_type_name = $value['discount_type_name'];
            if(isset($list[$payment_master_id])){

                $list[$payment_master_id] = $list[$payment_master_id] .','. $discount_type_name.":". $total_discount_amount."";;

            }else{

                $list[$payment_master_id] = $discount_type_name.":". $total_discount_amount."";

            }
        }
        return $list;


    }
    public static function get_payment_of_client_list($client_ids,$data){


        $startDate  =  $data['startDate'];
        $endDate  =  $data['endDate'];

        $payment_mode = $data['payment_mode'];

        $company_id = Yii::app()->user->getState('company_branch_id');
        $query = "SELECT 
            pm.client_id,
            pm.date ,
            pm.payment_master_id,
            pm.user_id,
            pm.rider_id,
            pm.reference_number,
            pm.payment_mode ,
            IFNULL((pm.amount_paid) ,0) 
                as amount_paid FROM payment_master as pm
            where   pm.company_branch_id = '$company_id'
            and pm.client_id in ($client_ids) and pm.date
            between '$startDate' and '$endDate' ";
            if($payment_mode>0){
              $query .= " and pm.payment_mode='$payment_mode' ";
            }



        $totalMake_object  =  Yii::app()->db->createCommand($query)->queryAll();
        $final_payment =[];
        foreach($totalMake_object as $value){
            $client_id = $value['client_id'];
            $final_payment[$client_id][] = $value;

        }
        return $final_payment;

    }
    public static function get_payment_of_client_list_of_one_date($client_ids,$data){


        $startDate  =  $data['startDate'];
        $endDate  =  $data['endDate'];

        $payment_mode = $data['payment_mode'];

        $company_id = Yii::app()->user->getState('company_branch_id');
        $query = "SELECT pm.client_id, pm.date , pm.payment_master_id, pm.user_id,
            pm.rider_id,pm.reference_number,
            pm.payment_mode ,IFNULL((pm.amount_paid) ,0)  as amount_paid FROM payment_master as pm
            where   pm.company_branch_id = '$company_id'
            and pm.client_id in ($client_ids) and pm.date = '$startDate' ";
        if($payment_mode>0){
            $query .= " and pm.payment_mode='$payment_mode' ";
        }

        $totalMake_object  =  Yii::app()->db->createCommand($query)->queryAll();
        $final_payment =[];
        foreach($totalMake_object as $value){
            $client_id = $value['client_id'];
            $final_payment[$client_id][] = $value;

        }
        return $final_payment;

    }

}