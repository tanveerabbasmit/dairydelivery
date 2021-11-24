<?php


class pos_stock_ledger
{
    public static function pos_opening_sale($data ,$type){

        $startDate = $data['startDate'];
        $endDate = $data['endDate'];
        $pos_shop_id = $data['pos_shop_id'];
        $product_id = $data['product_id'];

        $query = " SELECT SUM(p.quantity) FROM pos p
            where  p.pos_shop_id = '$pos_shop_id'
            and  p.product_id ='$product_id' 
            and  ";
        if($type==1){
            $query .="  p.date <'$startDate' ";
        }else{
            $query .="  p.date = '$startDate'";
        }



        $result = Yii::app()->db->createCommand($query)->queryscalar();


        return $result;

    }

    public static function pos_opening_issue_return_demage($data,$type){

        $startDate = $data['startDate'];
        $endDate = $data['endDate'];
        $pos_shop_id = $data['pos_shop_id'];
        $product_id = $data['product_id'];

        $query = "SELECT
            ifnull(sum(p.quantity),0) AS quantity,
            ifnull(sum(p.stock_return),0) AS stock_return,
            ifnull(sum(p.stock_damage),0) AS stock_damage
            FROM pos_stock_received AS p
            WHERE p.pos_shop_id = '$pos_shop_id'
            and p.product_id = '$product_id'
            and ";

        if($type==1){
            $query .= " p.date < '$startDate'" ;
        }else{
            $query .= " p.date = '$startDate'" ;
        }

        $result =  Yii::app()->db->createCommand($query)->queryAll();

        return $result[0];
    }

}