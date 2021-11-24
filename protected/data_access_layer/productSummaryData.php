<?php
/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 3/6/2017
 * Time: 2:16 PM
 */
class productSummaryData{



    public static function getproductSummary($data){

        $start_date = $data['start_date'];
        $end_date = $data['end_date'];
        $company_id = Yii::app()->user->getState('company_branch_id');

        $query ="SELECT p.NAME AS product_name , COUNT(d.client_id) AS length, d.client_id ,dd.product_id ,sum(dd.quantity)  as quantity,
                SUM(dd.amount) AS amount ,(dd.amount/dd.quantity)  AS rate
                FROM delivery AS d
                LEFT JOIN delivery_detail AS dd ON d.delivery_id =dd.delivery_id
                LEFT JOIN client AS c ON d.client_id =c.client_id
                LEFT JOIN product AS p ON p.product_id =dd.product_id
                WHERE c.company_branch_id = '$company_id' AND d.DATE between '$start_date' AND '$end_date'
                 AND dd.amount IS NOT null
                GROUP BY (dd.amount/dd.quantity) ";

               echo $query;
                die();

        $delievryList_summary =  Yii::app()->db->createCommand($query)->queryAll();

         return $delievryList_summary;
          die();
        $client_product_price_array =  productSummaryData::client_product_price_list($delievryList_summary);
        $product_price =  productSummaryData::productPrice();



         $final_result = array();

         foreach($delievryList_summary as $value){
               $client_id = $value['client_id'];
               $product_id = $value['product_id'];

               $client_product = $client_id.$product_id ;

               if(isset($client_product_price_array[$client_product])){
                   $price = $client_product_price_array[$client_product] ;
               }else{
                   $price =  $product_price[$product_id];

               }
               $product_price_number =$product_id.$price ;

                $oneOBejct =array();
                $oneOBejct['product_name'] =$value['product_name'];
                $oneOBejct['price'] =$price;

                $oneOBejct['quantity'] =$value['quantity'];

               $final_result[$product_price_number][] =  $oneOBejct;
         }

        return $final_result;

    }
    public static function customerWithRate_data($data){

        $start_date = $data['today_date'];
        $end_date = $data['end_date'];
        $rate_get = $data['rate'];
        $company_id = Yii::app()->user->getState('company_branch_id');




        $query = " SELECT d.client_id ,(dd.amount/dd.quantity) AS rate FROM delivery AS d
        LEFT JOIN delivery_detail AS dd ON dd.delivery_id =d.delivery_id
        WHERE d.company_branch_id ='$company_id' AND d.date BETWEEN '$start_date' AND '$end_date' ";


        $delievryList_summary =  Yii::app()->db->createCommand($query)->queryAll();

        $client_id_array = array();
        $client_id_array[] =0;

        foreach ($delievryList_summary as $value){
            $rate = $value['rate'];
            if($rate ==$rate_get ){

                $client_id_array[] = $value['client_id'];

            }

        }

        $client_ids= implode(',',$client_id_array);


         $query_client = " SELECT c.client_id , c.fullname , c.last_name ,c.address ,c.cell_no_1 ,z.name as zone_name FROM client AS c
              LEFT JOIN zone AS z ON z.zone_id = c.zone_id
              WHERE c.client_id IN ($client_ids)";

        $client_result=  Yii::app()->db->createCommand($query_client)->queryAll();

        return $client_result;

        /*$final_data = array();


        foreach ($delievryList_summary as $value){
             $oneObject = array();
             $rate =$value['rate'];
             $oneObject['client_id'] =$value['client_id'];
             $oneObject['fullname'] =$value['fullname'].$value['last_name'];
             $oneObject['zone_name'] =$value['zone_name'];
             $oneObject['address'] =$value['address'];
             $oneObject['cell_no_1'] =$value['cell_no_1'];
             if($rate_get==$rate){
                 $final_data[] = $oneObject;
             }


        }
        return $final_data;*/

    }
    public static function customerWithRate_data2($data){


        $start_date = $data['start_date'];
        $end_date = $data['end_date'];

        $company_id = Yii::app()->user->getState('company_branch_id');

        $productList = Product::model()->findAllByAttributes(
            array("company_branch_id"=>$company_id)
        );

        $productObject = array();

         foreach ($productList as $value){

             $product_id = $value['product_id'];
             $product_name = $value['name'];
             $productObject[$product_id] = $product_name;
         }


        $query = "SELECT dd.product_id ,dd.amount ,dd.quantity , d.client_id ,(dd.amount/dd.quantity) AS rate FROM delivery AS d
        LEFT JOIN delivery_detail AS dd ON dd.delivery_id =d.delivery_id
        WHERE d.company_branch_id ='$company_id' AND d.date BETWEEN '$start_date' AND '$end_date' ";



        $delievryList_summary =  Yii::app()->db->createCommand($query)->queryAll();




         $ratewise_array = array();

        foreach ($delievryList_summary as $value){
             $rate = $value['rate'];

            $ratewise_array[$rate][]= $value;
        }
         $final_object = array();

        foreach ($ratewise_array as $key=>$rate_value){



             $oneObject =array();
             $count_client = array();

             $total_quantity =0 ;
             $total_amount = 0;
             $product_id = 0;
             foreach ($rate_value as $value){
                 $product_id = $value['product_id'];
                 $amount = $value['amount'];
                 $quantity = $value['quantity'];
                 $client_id = $value['client_id'];
                 $count_client[$client_id]=$client_id;

                 $total_quantity = $total_quantity + $quantity;

                 $total_amount = $total_amount+$amount;
             }

              if(isset($productObject[$product_id])){
                  $oneObject['product_name'] = $productObject[$product_id];
              }
            $oneObject['rate'] =  $key ;
            $oneObject['quantity'] =  $total_quantity ;
            $oneObject['amount'] =  $total_amount ;
            $oneObject['length'] = sizeof($count_client) ;
            $final_object[] = $oneObject;
        }


       return $final_object ;


    }

    public static function productPrice(){
        $company_id = Yii::app()->user->getState('company_branch_id');

        $query_product_pricre = "SELECT p.product_id ,p.price FROM product AS p  
          WHERE p.company_branch_id = '$company_id'";

        $productPrice_result =  Yii::app()->db->createCommand($query_product_pricre)->queryAll();

         $product_price = array();

          foreach ($productPrice_result as $value){

               $product_id = $value['product_id'];

               $product_price[$product_id] = $value['price'];


          }

          return $product_price;
    }
    public static function client_product_price_list($delievryList_summary){

        $client_ids = array();
        $client_ids[] =0;
        foreach ($delievryList_summary as $value){

            $client_id = $value['client_id'];
            $client_ids[] = $client_id;
        }

        $client_ids_list = implode(',' ,$client_ids);

        $query_product_pricre = "SELECT * FROM client_product_price 
           WHERE client_id IN ($client_ids_list)";

        $productPrice_result =  Yii::app()->db->createCommand($query_product_pricre)->queryAll();
           $client_product_price_array =array();
          foreach ($productPrice_result as $value){

              $client_id =$value['client_id'];
             $product_id =$value['product_id'];

              $client_product =  $client_id.$product_id;

              $client_product_price_array[$client_product] = $value['price'];

          }
         return $client_product_price_array;
    }


}