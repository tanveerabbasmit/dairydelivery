<?php


class sales_purchase_summary_data
{
   public static function get_total_sale_between_date_range($data,$selectDate){


       $start_date = $data['start_date'];
       $end_date = $data['end_date'];
       $product_id = $data['product_id'];

       $company_id = Yii::app()->user->getState('company_branch_id');

       $queryTotalCount = " select ifnull(sum(dd.quantity) ,0) as quantity ,
                ifnull(sum(dd.amount) ,0) as amount  from delivery as d
                left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                where d.company_branch_id = '$company_id' and 
                d.date ='$selectDate' 
                and dd.product_id ='$product_id' ";

       $queryResult =  Yii::app()->db->createCommand($queryTotalCount)->queryAll();

       $quantity = $queryResult[0]['quantity'];
       $amount = $queryResult[0]['amount'];
       $rate = 0;
       if($quantity >0){
           $rate = round(($amount/$quantity),2) ;
       }
       $one_object = [];

       $one_object['product_id'] =$product_id;

       $one_object['quantity'] =$quantity;
       $one_object['amount'] =round($amount,0);
       $one_object['rate'] =$rate;



       return $one_object;
   }
   public static function get_purchase_list_between_date_range($data,$selectDate){

       $start_date = $data['start_date'];
       $end_date = $data['end_date'];
       $product_id = $data['product_id'];

       $company_id = Yii::app()->user->getState('company_branch_id');

       $query = "SELECT 
        ds.date,
        sum(ds.purchase_rate * (ds.quantity)) AS total_price,
        ds.purchase_rate,
        sum(ds.quantity) AS quantity ,
        sum(ds.wastage) AS wastage,
        sum(ds.return_quantity) AS return_quantity,
        p.name AS product_name
        FROM daily_stock AS ds 
        LEFT JOIN  product AS p ON p.product_id = ds.product_id
        WHERE 
         ds.date= '$selectDate' 
          and ds.company_branch_id='$company_id' 
          and ds.product_id = '$product_id' ";



       $result = Yii::app()->db->createCommand($query)->queryAll();

       $purchase_rate = $result[0]['purchase_rate'];
       $purchase_rate = $result[0]['total_price'];
       $total_quantity =$result[0]['quantity'] -$result[0]['wastage'] -$result[0]['return_quantity'];
       $total_price = $result[0]['total_price'];

       $one_object = [];
       $one_object['purchase_rate'] =0;
       if($total_quantity>0){
           $one_object['purchase_rate']=round(($total_price/$total_quantity),0);
       }

       $one_object['total_quantity'] =$total_quantity;
       $one_object['total_price'] =$total_price;

       return $one_object;



   }

   public static function get_total_expence($data,$selectDate){
       $start_date = $data['start_date'];
       $end_date = $data['end_date'];

       $company_id = Yii::app()->user->getState('company_branch_id');
       $query="SELECT 
                et.type, 
                SUM(er.amount) as total_amount
             FROM expence_report AS er
            left join expence_type as et ON er.expenses_type_id = et.expence_type
            where er.company_id = '$company_id' ";


       $query .=" and er.date ='$selectDate' ";

        $query = "SELECT 
            ifnull(sum(n.amount_paid),0) AS amount
             FROM new_payment AS n
            WHERE n.company_id ='$company_id'
            AND n.transaction_type = 'Expense'
            AND n.date= '$selectDate'";



       $queryResult =  Yii::app()->db->createCommand($query)->queryscalar();

        return $queryResult;

   }
}