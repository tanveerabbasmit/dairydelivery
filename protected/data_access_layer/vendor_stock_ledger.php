<?php


class vendor_stock_ledger
{
    public static function vendor_purchase_item($data ,$type){

        $startDate = $data['startDate'];
        $endDate = $data['endDate'];
        $vendor_id = $data['vendor_id'];


        if($type==1){

            $query = " SELECT 
            ifnull(SUM(b.net_amount),0) AS net_amount
            FROM bill_from_vendor AS b
             WHERE b.vendor_id = '$vendor_id' ";

            $query .=" and b.action_date <'$startDate' ";
            $result = Yii::app()->db->createCommand($query)->queryscalar();
        }else{

            $query = "SELECT 
                 i.item_name,
                 b.remarks,
                b.net_amount AS net_amount
                FROM bill_from_vendor AS b
                LEFT JOIN item  AS i ON i.item_id = b.item_id
                WHERE b.vendor_id = '$vendor_id' ";

            $query .=" and b.action_date = '$startDate'";



            $result = Yii::app()->db->createCommand($query)->queryAll();



        }





        return $result;

    }

    public static function vendor_receipt($data,$type){
        $startDate = $data['startDate'];
        $endDate = $data['endDate'];
        $vendor_id = $data['vendor_id'];



        if($type==1){

            $query = " SELECT 
            nullif(sum(v.amount_paid),0) AS  amount 
            
            FROM new_payment as v 
            where v.payment_or_receipt =2 and v.vendor_id ='$vendor_id' ";
            $query .= " and v.date < '$startDate'" ;

            $result =  Yii::app()->db->createCommand($query)->queryscalar();

        }else{
            $query = " SELECT 
                    v.amount_paid AS  amount ,
                    v.reference_no as remarks ,
                     ep.type
                    FROM new_payment as v  
                     LEFT JOIN expence_type AS ep ON ep.expence_type = v.expence_type
                    where v.payment_or_receipt =2 and  v.vendor_id ='$vendor_id' ";
            $query .= " and v.date = '$startDate'" ;





            $result =  Yii::app()->db->createCommand($query)->queryAll();

        }

        return $result;
    }
    public static function vendor_payment($data,$type){

        $startDate = $data['startDate'];
        $endDate = $data['endDate'];
        $vendor_id = $data['vendor_id'];



        if($type==1){

            $query = " SELECT 
            nullif(sum(v.amount_paid),0) AS  amount 
            
            FROM new_payment as v 
            where v.payment_or_receipt =1 and v.vendor_id ='$vendor_id' ";
            $query .= " and v.date < '$startDate'" ;
            $result =  Yii::app()->db->createCommand($query)->queryscalar();

        }else{
                $query = " SELECT 
                    v.amount_paid AS  amount ,
                    v.reference_no as remarks ,
                     ep.type
                    FROM new_payment as v  
                     LEFT JOIN expence_type AS ep ON ep.expence_type = v.expence_type
                    where v.payment_or_receipt =1 and  v.vendor_id ='$vendor_id' ";
                    $query .= " and v.date = '$startDate'" ;



               $result =  Yii::app()->db->createCommand($query)->queryAll();

        }

        return $result;
    }



    public static function vendor_purchase_item_bills($vendor_id ,$data){


        $startDate = $data['startDate'];

        $endDate = $data['endDate'];
        $query = " SELECT 
            ifnull(SUM(b.net_amount),0) AS net_amount
            FROM bill_from_vendor AS b
             WHERE b.vendor_id = '$vendor_id' ";

         $query .=" and b.action_date  between  '$startDate' and '$endDate' ";
        $result = Yii::app()->db->createCommand($query)->queryscalar();








        return $result;

    }

    public static function vendor_payment_bills($vendor_id,$type){



        $query = " SELECT 
            nullif(sum(v.amount),0) AS  amount 
            
            FROM vendor_payment as v 
            where  v.vendor_id ='$vendor_id' ";
       // $query .= " and v.action_date < '$startDate'" ;
        $result =  Yii::app()->db->createCommand($query)->queryscalar();

        return $result;
    }

}