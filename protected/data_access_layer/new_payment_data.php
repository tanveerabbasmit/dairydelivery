<?php


class new_payment_data
{
   public static function get_new_payment($payment_or_receipt,$collection_vault_id,$data){

         $start_date = $data['startDate'];


        $query = "SELECT 
            sum(np.amount_paid) AS total_amount
            FROM  new_payment AS np
            WHERE np.collection_vault_id = '$collection_vault_id'
            AND np.payment_or_receipt = '$payment_or_receipt'
            AND np.date < '$start_date' ";



       $queryResult =  Yii::app()->db->createCommand($query)->queryscalar();

       return $queryResult;


   }

   public static function get_new_paymewnt_and_receipt_date_range_of_vault($collection_vault_id,$data){
       $start_date = $data['startDate'];
       $end_date = $data['endDate'];


       $query = "SELECT 
            np.*,
            ep.type,
            v.vendor_name
            
            FROM new_payment AS np
            LEFT JOIN expence_type AS ep ON np.expence_type = ep.expence_type
            LEFT JOIN vendor AS  v ON v.vendor_id = np.vendor_id 
            WHERE np.collection_vault_id = '$collection_vault_id'
            AND np.date BETWEEN '$start_date' AND '$end_date' ";
       $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

       return $queryResult;
   }

    public static function  get_new_payment_one_day($payment_or_receipt , $collection_vault_id,$data){

        $start_date = $data['startDate'];
        $end_date = $data['endDate'];

        $query = "SELECT
            p.amount_paid,
            v.vendor_name,
            p.reference_no
            FROM new_payment p
            LEFT JOIN vendor AS v on v.vendor_id =p.vendor_id
                WHERE p.date = '$start_date'
                and p.collection_vault_id ='$collection_vault_id'
                AND p.payment_or_receipt ='$payment_or_receipt' ";



        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
        return $queryResult;

    }
    public static function  opening_get_new_payment_date_rang($payment_or_receipt , $collection_vault_id,$data){

        $start_date = $data['startDate'];
        $end_date = $data['endDate'];
        $query = "SELECT
             sum(p.amount_paid)
            FROM new_payment p
             WHERE p.date < '$start_date'
            and p.collection_vault_id ='$collection_vault_id'
            AND p.payment_or_receipt ='$payment_or_receipt' ";
        $queryResult =  Yii::app()->db->createCommand($query)->queryscalar();
        return $queryResult;

    }
    public static function get_new_payment_date_rang($payment_or_receipt , $collection_vault_id,$data){

        $start_date = $data['startDate'];
        $end_date = $data['endDate'];
        $query = "SELECT
             sum(p.amount_paid)
            FROM new_payment p
             WHERE p.date BETWEEN  '$start_date' AND '$end_date'
            and p.collection_vault_id ='$collection_vault_id'
            AND p.payment_or_receipt ='$payment_or_receipt' ";
        $queryResult =  Yii::app()->db->createCommand($query)->queryscalar();
        return $queryResult;

    }

    public static function opening_receipt_from_customer_dae_range($collection_vault_id,$data){

        $start_date = $data['startDate'];
        $end_date = $data['endDate'];

        $query = "SELECT 
            sum(p.amount_paid ) AS amount_paid
            FROM payment_master p
            WHERE p.date < '$start_date' 
            AND p.collection_vault_id = '$collection_vault_id' ";

        $queryResult =  Yii::app()->db->createCommand($query)->queryscalar();
        return $queryResult;

    }
    public static function receipt_from_customer_one_day($collection_vault_id,$data){

        $start_date = $data['startDate'];
        $end_date = $data['endDate'];

        $query = "SELECT 
            p.amount_paid ,
            c.fullname ,
            p.remarks
            FROM payment_master p
            LEFT JOIN client AS c ON c.client_id = p.client_id
            WHERE p.date = '$start_date' 
            AND p.collection_vault_id = '$collection_vault_id' ";



        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
        return $queryResult;

    }
    public static function receipt_from_customer_dae_range($collection_vault_id,$data){
        $start_date = $data['startDate'];
        $end_date = $data['endDate'];

        $query = "SELECT 
            sum(p.amount_paid ) AS amount_paid
            FROM payment_master p
            WHERE p.date BETWEEN '$start_date' AND '$end_date'
            AND p.collection_vault_id = '$collection_vault_id' ";

        $queryResult =  Yii::app()->db->createCommand($query)->queryscalar();
        return $queryResult;

    }
    public static function farm_payment_list_between_date_rang($collection_vault_id,$data){

        $start_date = $data['startDate'];
        $end_date = $data['endDate'];

        $query = "SELECT 
            sum(f.amount) AS  total_amount
            FROM farm_payment AS f 
            WHERE f.action_date 
            BETWEEN '$start_date' AND '$end_date'
            AND f.collection_vault_id ='$collection_vault_id' ";

        $queryResult =  Yii::app()->db->createCommand($query)->queryscalar();
        return $queryResult;
       
    }
    public static function opening_farm_payment_list_between_date_rang($collection_vault_id,$data){

        $start_date = $data['startDate'];
        $end_date = $data['endDate'];

        $query = "SELECT 
            sum(f.amount) AS  total_amount
            FROM farm_payment AS f 
            WHERE f.action_date < '$end_date'
            AND f.collection_vault_id ='$collection_vault_id' ";

        $queryResult =  Yii::app()->db->createCommand($query)->queryscalar();
        return $queryResult;

    }
    public static function farm_payment_list_between_one_day($collection_vault_id,$data){

        $start_date = $data['startDate'];
        $end_date = $data['endDate'];

        $query = "SELECT 
            f.amount,
            f.remarks,
            fm.farm_name
            FROM farm_payment AS f 
            LEFT JOIN farm AS fm ON fm.farm_id = f.farm_id 
            WHERE f.action_date = '$start_date'
            AND f.collection_vault_id ='$collection_vault_id' ";



        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
        return $queryResult;

    }
}