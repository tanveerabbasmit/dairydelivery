<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 2018-07-22
 * Time: 11:20 AM
 */

class getRateOfProduct
{
  public static function getCrudrole($client){

      $query = "select c.price ,c.product_id,c.client_id from client_product_price as c
                where c.client_id in ($client) ";

      $clientResult =  Yii::app()->db->createCommand($query)->queryAll();
        $product_rate_list = array();
       foreach ($clientResult as $value){
           $client_id = $value['client_id'];
           $product_id = $value['product_id'];
           $number = $client_id.$product_id;

           $product_rate_list[$number] = $value['price'];

       }
       return $product_rate_list ;

  }
  public static function product_quantity_client_wise($client,$startDate,$endDate){

      $query = "select   d.client_id , ifnull(dd.product_id ,0) as product_id , 
        ifnull(sum(dd.quantity) ,0) as total_quantity   from delivery as d
        left join delivery_detail as dd ON dd.delivery_id =d.delivery_id
        where d.date between '$startDate' and '$endDate' 
        and d.client_id in ($client)        
        group by d.client_id ,dd.product_id ";



      $clientResult =  Yii::app()->db->createCommand($query)->queryAll();


        $product_quantity_list = array();
       foreach ($clientResult as $value){
           $client_id = $value['client_id'];
           $product_id = $value['product_id'];
           $number = $client_id.$product_id;

           $product_quantity_list[$number] = $value['total_quantity'];

       }
       return $product_quantity_list ;

  }
  public static function product_quantity_client_wise_total_sum($client,$startDate,$endDate){

      $query = "select   p.NAME AS product_name ,  d.client_id , ifnull(dd.product_id ,0) as product_id , 
        ifnull(sum(dd.quantity) ,0) as total_quantity  ,sum(dd.amount) AS total_amount    from delivery as d
        left join delivery_detail as dd ON dd.delivery_id =d.delivery_id
        LEFT JOIN product AS p ON p.product_id = dd.product_id
        where d.date between '$startDate' and '$endDate' 
        and d.client_id in ($client)        
        group by dd.product_id ";



      $clientResult =  Yii::app()->db->createCommand($query)->queryAll();

      $product_price = '';
      $product_quantity = '';
      $comma = false;
       foreach($clientResult as $value){
           $total_quantity = $value['total_quantity'] ;
           $total_amount = $value['total_amount'] ;
           $product_name = $value['product_name'] ;
           if($comma){
               $product_price.=',';
               $product_quantity.=',';
           }
           $product_quantity .= $product_name.':' .$total_quantity;
           $product_price .= $product_name.':' .$total_amount;
           $comma = true;

       }
       $count_Result = array();
       $count_Result['product_quantity'] =$product_quantity ;
       $count_Result['product_price'] =$product_price ;
       return json_encode($count_Result);


  }
  public static function product_quantity_total($client,$startDate,$endDate){

        $query = "select  p.name as product_name  , 
        ifnull(sum(dd.quantity) ,0) as total_quantity   from delivery as d
        left join delivery_detail as dd ON dd.delivery_id =d.delivery_id
        left join product as p ON p.product_id = dd.product_id
        where d.date between '$startDate' and '$endDate' 
        and d.client_id in ($client) and dd.product_id is not null    
        group by dd.product_id ";



        $productResult =  Yii::app()->db->createCommand($query)->queryAll();

        $total_count = '';
        $comma = false;
        foreach ($productResult as $value){
          $name = $value['product_name'];

          $total_quantity = $value['total_quantity'];
           if($comma){
               $total_count .=  ',';
           }
         // $total_count .= $name." : ".$total_quantity;
         $total_count .= $total_quantity;

         $comma = true ;
        }


        return $total_count ;

  }

  public  static function getProductList(){

      $company_id = Yii::app()->user->getState('company_branch_id');


      $query="SELECT p.product_id,p.price,p.name  from   product as p 
               where p.company_branch_id =$company_id and bottle = 0 ";
      $productList =  Yii::app()->db->createCommand($query)->queryAll();

      return $productList;

  }


    public static function product_quantity_client_wise_rate_quantity($client,$startDate,$endDate){

        $query = "select   d.client_id , ifnull(dd.product_id ,0) as product_id , 
        ifnull(sum(dd.quantity) ,0) as total_quantity ,ifnull(sum(dd.amount),0) AS amount  from delivery as d
        left join delivery_detail as dd ON dd.delivery_id =d.delivery_id
        where d.date between '$startDate' and '$endDate' 
        and d.client_id in ($client)        
        group by d.client_id ,dd.product_id ";

        // echo $query;
         // die();


        $clientResult =  Yii::app()->db->createCommand($query)->queryAll();


        $product_quantity_list = array();
        foreach ($clientResult as $value){
            $client_id = $value['client_id'];
            $product_id = $value['product_id'];
            $amount = $value['amount'];
            $number = $client_id.$product_id;
            $total_quantity = $value['total_quantity'];
            $product_quantity_list[$number]['quantity'] = $value['total_quantity'];
            if($total_quantity>0){
                $product_quantity_list[$number]['rate'] = $amount/$total_quantity;
            }else{
                $product_quantity_list[$number]['rate'] ='';
            }
        }



        return $product_quantity_list ;

    }


}