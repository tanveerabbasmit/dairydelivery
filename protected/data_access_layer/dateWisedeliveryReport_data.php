<?php

/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 11/7/2017
 * Time: 4:30 PM
 */
class dateWisedeliveryReport_data
{
    public static function dateWisedeliveryReport_allData($selectDate , $client_id_List){
        if($selectDate){
            $todayDate = $selectDate;
        }else{
            $todayDate = date('Y-m-d');
        }


        $company_id = Yii::app()->user->getState('company_branch_id');

        $query="SELECT p.product_id ,p.name ,p.unit  from   product as p 
                where p.company_branch_id =$company_id and bottle = 0  ";

        $productList =  Yii::app()->db->createCommand($query)->queryAll();
             $end_total_sum = array();
        foreach($productList as $value){



              $product_id = $value['product_id'];
             $queryTotalCount = " select ifnull(sum(dd.quantity) ,0) as quantity , ifnull(sum(dd.amount) ,0) as amount  from delivery as d
                    left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                    where d.company_branch_id = '$company_id' and d.date ='$todayDate' and dd.product_id ='$product_id' ";
              if($client_id_List){
                  $queryTotalCount .= " and d.client_id in ($client_id_List)";
              }
            $queryResult =  Yii::app()->db->createCommand($queryTotalCount)->queryAll();

            $quantity = $queryResult[0]['quantity'];
            $amount = $queryResult[0]['amount'];

            $oneSumObject = array();
            if($quantity>0){
                $oneSumObject['quantity'] = $amount/$quantity ;
            }else{
                $oneSumObject['quantity'] =0;
            }


            $end_total_sum[] =$oneSumObject;

            $oneSumObject = array();
            $oneSumObject['quantity'] = $quantity ;

            $end_total_sum[] =$oneSumObject;
            $oneSumObject = array();
            $oneSumObject['quantity'] = $amount ;
            $end_total_sum[] =$oneSumObject;

        }


       $query = " select ifnull(sum(dd.quantity) ,0) as quantity , ifnull(sum(dd.amount) ,0) as amount ,d.client_id 
                    ,c.fullname ,c.address , dd.product_id from delivery as d
                    left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                    left join client as c ON c.client_id = d.client_id
                    where d.company_branch_id = '$company_id' and d.date ='$todayDate' and  dd.delivery_id is not null " ;
               if($client_id_List){
                   $query .="  and d.client_id in ($client_id_List) ";
                }
        $query .=" group by dd.product_id,d.client_id ";
        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();


           $clientProduct_quantity = array();
           $clientProduct_amount = array();
         foreach ($queryResult as $value){
             $product_id = $value['product_id'];
              $client_id = $value['client_id'];
              $cleint_product = $product_id.$client_id ;
              $clientProduct_quantity[$cleint_product] =$value['quantity'];
              $clientProduct_amount[$cleint_product] = $value['amount'];
         }
        $finalResultObject = array();
         $query_client = " select d.client_id 
                    ,c.fullname ,c.cell_no_1,c.address  from delivery as d
                    left join client as c ON c.client_id = d.client_id
                    where d.company_branch_id = '$company_id' and d.date ='$todayDate' ";

                 if($client_id_List){
                     $query_client .="  and d.client_id in ($client_id_List) ";
                }
        $query_client .="  group by d.client_id  ";
        $queryResult_client =  Yii::app()->db->createCommand($query_client)->queryAll();
        foreach ($queryResult_client as $clientValue){
            $client_id = $clientValue['client_id'];
            $oneObject = array();
            $oneObject['client_id'] = $client_id;
            $oneObject['fullname'] = $clientValue['fullname'];
            $oneObject['address'] = $clientValue['address'];
            $oneObject['cell_no_1'] = $clientValue['cell_no_1'];
            $oneProductObject = array();
           foreach ($productList as $productValue){
               $product_id = $productValue['product_id'];
               $cleint_product = $product_id.$client_id ;
               $quantity =$product_id.'quantity' ;
               $amount =$product_id.'amount' ;
               $oneObject[$quantity] = 0;
               $oneObject[$amount] = 0;
               if(isset($clientProduct_quantity[$cleint_product]) || isset($clientProduct_amount[$cleint_product])){
                    $quantity = $clientProduct_quantity[$cleint_product] ;
                   $amount = $clientProduct_amount[$cleint_product] ;
                   if($quantity ==0){
                       $oneProductObject[] = 0;
                   }else{
                       $oneProductObject[] =$amount/$quantity;
                   }


               }else{
                   $oneProductObject[] =0;
               }


               if(isset($clientProduct_quantity[$cleint_product])){
                   $oneObject[$quantity] = $clientProduct_quantity[$cleint_product] ;
                   $oneProductObject[] =$clientProduct_quantity[$cleint_product] ;
               }else{
                   $oneProductObject[] = 0;
               }
               if(isset($clientProduct_amount[$cleint_product])){
                   $oneObject[$amount] = $clientProduct_amount[$cleint_product] ;
                   $oneProductObject[] = $clientProduct_amount[$cleint_product] ;
               }else{
                   $oneProductObject[] = 0;
               }
           }
            $oneObject['productData'] = $oneProductObject ;
            $finalResultObject[] =$oneObject ;
        }




        $data = array();
        $data['end_total_sum'] = $end_total_sum ;
        $data['report_data'] = $finalResultObject ;
        return json_encode($data);

    }
    public static function dateWisedeliveryReport_allData_of_one_product($startDate , $endDate , $client_id_List,$product_id){



        $company_id = Yii::app()->user->getState('company_branch_id');
        $query="SELECT p.product_id ,p.name ,p.unit  from   product as p 
                where p.company_branch_id =$company_id and bottle =0  and p.product_id='$product_id'";
        $productList =  Yii::app()->db->createCommand($query)->queryAll();
             $end_total_sum = array();
        foreach($productList as $value){
              $product_id = $value['product_id'];
                $queryTotalCount = " select 
                    ifnull(sum(dd.quantity) ,0) as quantity ,
                    ifnull(sum(dd.amount) ,0) as amount 
                    from delivery as d
                    left join delivery_detail as dd 
                    ON dd.delivery_id = d.delivery_id
                    where d.company_branch_id = '$company_id' 
                    and  d.date 
                    BETWEEN '$startDate' and '$endDate' 
                    and dd.product_id ='$product_id'  ";
                if($client_id_List !='no'){
                         $queryTotalCount .= " and d.client_id in ($client_id_List)";
                }

            $queryResult =  Yii::app()->db->createCommand($queryTotalCount)->queryAll();



            $quantity = $queryResult[0]['quantity'];
            $amount = $queryResult[0]['amount'];
            $oneSumObject = array();
            if($quantity >0){
                $oneSumObject['quantity'] = $amount/$quantity ;
            }else{
                $oneSumObject['quantity'] =0;
            }
            $end_total_sum[] =$oneSumObject;
            $oneSumObject = array();
            $oneSumObject['quantity'] = $quantity ;
            $end_total_sum[] =$oneSumObject;
             $oneSumObject = array();
            $oneSumObject['quantity'] = $amount ;
            $end_total_sum[] =$oneSumObject;

        }
        $query = " select 
            ifnull(sum(dd.quantity) ,0) as quantity ,
            ifnull(sum(dd.amount) ,0) as amount ,
            d.client_id ,
            c.fullname,
            c.address ,
            c.zone_id,
            dd.product_id 
            from delivery as d
            left join delivery_detail as dd 
            ON dd.delivery_id = d.delivery_id
            left join client as c 
            ON c.client_id = d.client_id
            where d.company_branch_id = '$company_id'
              and  d.date BETWEEN '$startDate' and '$endDate' 
              and  dd.delivery_id is not null 
               AND dd.product_id ='$product_id' " ;
        if($client_id_List !='no'){
           $query .="  and d.client_id in ($client_id_List) ";
        }
        $query .=" group by dd.product_id,d.client_id ";


        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
        $clientProduct_quantity = array();
        $clientProduct_amount = array();

        foreach ($queryResult as $value){

              $product_id = $value['product_id'];
              $client_id = $value['client_id'];
              $cleint_product = $product_id.$client_id ;
              $clientProduct_quantity[$cleint_product] =$value['quantity'];
              $clientProduct_amount[$cleint_product] = $value['amount'];
        }
        $finalResultObject = array();

        $query_client = "select 
            d.client_id ,
            c.zone_id,
            c.fullname,
            c.cell_no_1 ,
            c.address  from delivery as d
            left join client as c ON c.client_id = d.client_id
            where d.company_branch_id = '$company_id' and  d.date BETWEEN '$startDate' and '$endDate' ";

        if($client_id_List !='no'){

            $query_client .="  and d.client_id in ($client_id_List) ";

        }

        $query_client .="  group by d.client_id  ";


        $queryResult_client =  Yii::app()->db->createCommand($query_client)->queryAll();


        foreach ($queryResult_client as $clientValue){



            $client_id = $clientValue['client_id'];
            $oneObject = array();
            $oneObject['client_id'] = $client_id;

            $oneObject['zone_id'] = $clientValue['zone_id'];

             $zone_object = Zone::model()->findByPk($clientValue['zone_id']);

            $oneObject['zone_name'] = $zone_object['name'];

            $oneObject['fullname'] = $clientValue['fullname'];
            $oneObject['address'] = $clientValue['address'];
            $oneObject['cell_no_1'] = $clientValue['cell_no_1'];
            $oneProductObject = array();
           foreach ($productList as $productValue){
               $product_id = $productValue['product_id'];
               $cleint_product = $product_id.$client_id ;
               $quantity =$product_id.'quantity' ;
               $amount =$product_id.'amount' ;
               $oneObject[$quantity] = 0;
               $oneObject[$amount] = 0;
               if(isset($clientProduct_quantity[$cleint_product]) || isset($clientProduct_amount[$cleint_product])){
                   $quantity = $clientProduct_quantity[$cleint_product] ;
                   $amount = $clientProduct_amount[$cleint_product] ;
                   if($quantity==0){
                       $oneProductObject[] =0;
                   }else{
                       $oneProductObject[] =$amount/$quantity;
                   }
               }else{
                   $oneProductObject[] =0;
               }
               if(isset($clientProduct_quantity[$cleint_product])){
                   $oneObject[$quantity] = $clientProduct_quantity[$cleint_product] ;
                   $oneProductObject[] =$clientProduct_quantity[$cleint_product] ;
               }else{
                   $oneProductObject[] = 0;
               }
               if(isset($clientProduct_amount[$cleint_product])){
                   $oneObject[$amount] = $clientProduct_amount[$cleint_product] ;
                   $oneProductObject[] = $clientProduct_amount[$cleint_product] ;
               }else{
                   $oneProductObject[] = 0;
               }
           }
           $oneObject['productData'] = $oneProductObject ;

           if( $oneObject[$quantity]>0){
                 $finalResultObject[] =$oneObject ;
           }

        }

        $data = array();
        $data['end_total_sum'] = $end_total_sum ;
        $data['report_data'] = $finalResultObject ;
        return json_encode($data);

    }
    public static function dateWisedeliveryReport_allData2($startDate , $endDate , $client_id_List){



        $company_id = Yii::app()->user->getState('company_branch_id');
        $query="SELECT p.product_id ,p.name ,p.unit  from   product as p 
                where p.company_branch_id =$company_id and bottle =0 ";
        $productList =  Yii::app()->db->createCommand($query)->queryAll();
             $end_total_sum = array();
        foreach($productList as $value){
              $product_id = $value['product_id'];
             $queryTotalCount = " select ifnull(sum(dd.quantity) ,0) as quantity , ifnull(sum(dd.amount) ,0) as amount  from delivery as d
                    left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                    where d.company_branch_id = '$company_id' and  d.date BETWEEN '$startDate' and '$endDate' and dd.product_id ='$product_id' ";
             if($client_id_List !='no'){
                  $queryTotalCount .= " and d.client_id in ($client_id_List)";


             }

            $queryResult =  Yii::app()->db->createCommand($queryTotalCount)->queryAll();



            $quantity = $queryResult[0]['quantity'];
            $amount = $queryResult[0]['amount'];
            $oneSumObject = array();
            if($quantity >0){
                $oneSumObject['quantity'] = $amount/$quantity ;
            }else{
                $oneSumObject['quantity'] =0;
            }
            $end_total_sum[] =$oneSumObject;
            $oneSumObject = array();
            $oneSumObject['quantity'] = $quantity ;
            $end_total_sum[] =$oneSumObject;
             $oneSumObject = array();
            $oneSumObject['quantity'] = $amount ;
            $end_total_sum[] =$oneSumObject;

        }
       $query = " select ifnull(sum(dd.quantity) ,0) as quantity , ifnull(sum(dd.amount) ,0) as amount ,d.client_id 
                    ,c.fullname ,c.address , dd.product_id from delivery as d
                    left join delivery_detail as dd ON dd.delivery_id = d.delivery_id
                    left join client as c ON c.client_id = d.client_id
                    where d.company_branch_id = '$company_id' and  d.date BETWEEN '$startDate' and '$endDate' and  dd.delivery_id is not null " ;
                if($client_id_List !='no'){
                   $query .="  and d.client_id in ($client_id_List) ";
                }
        $query .=" group by dd.product_id,d.client_id ";

        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
        $clientProduct_quantity = array();
        $clientProduct_amount = array();

        foreach ($queryResult as $value){

              $product_id = $value['product_id'];
              $client_id = $value['client_id'];
              $cleint_product = $product_id.$client_id ;
              $clientProduct_quantity[$cleint_product] =$value['quantity'];
              $clientProduct_amount[$cleint_product] = $value['amount'];
        }
        $finalResultObject = array();
         $query_client = " select d.client_id 
                    ,c.fullname,c.cell_no_1 ,c.address  from delivery as d
                    left join client as c ON c.client_id = d.client_id
                    where d.company_branch_id = '$company_id' and  d.date BETWEEN '$startDate' and '$endDate' ";

               if($client_id_List !='no'){
                     $query_client .="  and d.client_id in ($client_id_List) ";
                }
        $query_client .="  group by d.client_id  ";


        $queryResult_client =  Yii::app()->db->createCommand($query_client)->queryAll();


        foreach ($queryResult_client as $clientValue){

            $client_id = $clientValue['client_id'];
            $oneObject = array();
            $oneObject['client_id'] = $client_id;
            $oneObject['fullname'] = $clientValue['fullname'];
            $oneObject['address'] = $clientValue['address'];
            $oneObject['cell_no_1'] = $clientValue['cell_no_1'];
            $oneProductObject = array();
           foreach ($productList as $productValue){
               $product_id = $productValue['product_id'];
               $cleint_product = $product_id.$client_id ;
               $quantity =$product_id.'quantity' ;
               $amount =$product_id.'amount' ;
               $oneObject[$quantity] = 0;
               $oneObject[$amount] = 0;
               if(isset($clientProduct_quantity[$cleint_product]) || isset($clientProduct_amount[$cleint_product])){
                   $quantity = $clientProduct_quantity[$cleint_product] ;
                   $amount = $clientProduct_amount[$cleint_product] ;
                   if($quantity==0){
                       $oneProductObject[] =0;
                   }else{
                       $oneProductObject[] =$amount/$quantity;
                   }
               }else{
                   $oneProductObject[] =0;
               }
               if(isset($clientProduct_quantity[$cleint_product])){
                   $oneObject[$quantity] = $clientProduct_quantity[$cleint_product] ;
                   $oneProductObject[] =$clientProduct_quantity[$cleint_product] ;
               }else{
                   $oneProductObject[] = 0;
               }
               if(isset($clientProduct_amount[$cleint_product])){
                   $oneObject[$amount] = $clientProduct_amount[$cleint_product] ;
                   $oneProductObject[] = $clientProduct_amount[$cleint_product] ;
               }else{
                   $oneProductObject[] = 0;
               }
           }
            $oneObject['productData'] = $oneProductObject ;
            $finalResultObject[] =$oneObject ;
        }
        $data = array();
        $data['end_total_sum'] = $end_total_sum ;
        $data['report_data'] = $finalResultObject ;
        return json_encode($data);

    }

    public static function  dateWisedeliveryReport_sampleCustomer($data){


       $RiderID = $data['RiderID'];
       $startDate = $data['startDate'];
       $endDate = $data['endDate'];
       $company_id = Yii::app()->user->getState('company_branch_id');

        if($RiderID ==0){
            $clientQuery = "Select c.client_id ,c.fullname ,c.address from client as c
                              where c.company_branch_id ='$company_id' and c.client_type ='2' ";


            $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();
        }else{
            $clientQuery = "Select c.client_id ,c.fullname ,c.address from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id 
                           where rz.rider_id = $RiderID  and c.client_type ='2' ";
            $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();
        }

         $client_id_object = array();
        $client_id_object[] =0;
        foreach ($clientResult as $value){
             $client_id_object[] = $value['client_id'];
        }

        $client_id_list =  implode(',',$client_id_object);

        $quantity_query = "select p.name as product_name ,sum(dd.quantity) as total_quantity ,d.date from delivery as d
                left join delivery_detail as dd ON d.delivery_id = dd.delivery_id
                left join product as p ON p.product_id =dd.product_id
                where d.client_id in ($client_id_list) and d.date between '$startDate' and '$endDate'
                group by dd.product_id";

        $quantity_Result =  Yii::app()->db->createCommand($quantity_query)->queryAll();

          $total_quantity_sum = '';

          $use_comma = false ;

         foreach($quantity_Result as $value){
              if($use_comma){
                  $total_quantity_sum .=' , ';
              }
              $product_name = $value['product_name'];
              $total_quantity = $value['total_quantity'];
              $total_quantity_sum .= $product_name." : ".$total_quantity;

             $use_comma = true ;
         }



         $finalResult = array();
         $totalAmount = 0;
         foreach ($clientResult as $value){
                $oneObject =array();
                $client_id =  $value['client_id'];
                $oneObject['client_id'] = $value['client_id'];
                $oneObject['fullname'] = $value['fullname'];
                $oneObject['address'] = $value['address'];

               $clientQuery = " select ifnull(sum(d.total_amount),0) as total_amount from delivery as d
                              where d.client_id = '$client_id' and d.date between '$startDate' and '$endDate' ";


               $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryScalar();
               $oneObject['amount'] = $clientResult;

                $quantity_query = "select p.name as product_name , d.client_id ,dd.product_id,sum(dd.quantity) as total_quantity ,d.date from delivery as d
                left join delivery_detail as dd ON d.delivery_id = dd.delivery_id
                left join product as p ON p.product_id =dd.product_id
                where d.client_id ='$client_id' and d.date between '$startDate' and '$endDate'
                group by dd.product_id";

               $quantity_Result =  Yii::app()->db->createCommand($quantity_query)->queryAll();

                 $quantity = '';
                 $put_comma  =false ;
                foreach ($quantity_Result as $value){

                      if($put_comma){
                          $quantity .=',';
                      }
                     $product_name = $value['product_name'];
                     $total_quantity = $value['total_quantity'];

                    $quantity .= $product_name." : ".$total_quantity ;

                    $put_comma  =true ;
                }


                $oneObject['quantity'] = $quantity;

             if($oneObject['amount']> 0){
                 $finalResult[]= $oneObject;
             }
               $totalAmount = $totalAmount + $oneObject['amount'];

         }

          $result = array();
          $result['customer_list'] =$finalResult ;
          $result['total'] = $totalAmount ;

          $result['reciable'] = (85/100)*$totalAmount ;

          $result['total_quantity_sum'] = $total_quantity_sum ;

          echo json_encode($result);
          die();

       //   return json_encode($data);

    }
    public static function  dateWisedeliveryReport_sampleCustomer_new($data){


       $RiderID = $data['RiderID'];
       $startDate = $data['startDate'];
       $endDate = $data['endDate'];
       $product_id = $data['product_id'];
       $company_id = Yii::app()->user->getState('company_branch_id');

        if($RiderID ==0){
            $clientQuery = "Select c.client_id ,c.fullname ,c.address from client as c
                              where c.company_branch_id ='$company_id' and c.client_type ='2' ";


            $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();
        }else{
            $clientQuery = "Select c.client_id ,c.fullname ,c.address from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id 
                           where rz.rider_id = $RiderID  and c.client_type ='2' ";
            $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();
        }

         $client_id_object = array();
        $client_id_object[] =0;
        foreach ($clientResult as $value){
             $client_id_object[] = $value['client_id'];
        }

        $client_id_list =  implode(',',$client_id_object);

        $quantity_query = "select p.name as product_name ,
            sum(dd.quantity) as total_quantity 
            ,d.date from delivery as d
            left join delivery_detail as dd ON d.delivery_id = dd.delivery_id
            left join product as p ON p.product_id =dd.product_id
            where d.client_id in ($client_id_list) and d.date 
                between '$startDate' and '$endDate'
            AND dd.product_id= '$product_id'
            group by dd.product_id";



        $quantity_Result =  Yii::app()->db->createCommand($quantity_query)->queryAll();


          $total_quantity_sum = '';

          $use_comma = false ;

         foreach($quantity_Result as $value){
              if($use_comma){
                  $total_quantity_sum .=' , ';
              }
              $product_name = $value['product_name'];
              $total_quantity = $value['total_quantity'];
              $total_quantity_sum .= $product_name." : ".$total_quantity;

             $use_comma = true ;
         }



         $finalResult = array();
         $totalAmount = 0;
         foreach ($clientResult as $value){
                $oneObject =array();
                $client_id =  $value['client_id'];
                $oneObject['client_id'] = $value['client_id'];
                $oneObject['fullname'] = $value['fullname'];
                $oneObject['address'] = $value['address'];

                $clientQuery = " select 
                    ifnull(sum(d.total_amount),0) as total_amount
                    from delivery as d
                    LEFT JOIN delivery_detail AS dd 
                     ON dd.delivery_id =d.delivery_id
                    where d.client_id = '$client_id'
                    and d.date between '$startDate' and '$endDate'
                     and  dd.product_id ='$product_id' ";



               $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryScalar();
               $oneObject['amount'] = $clientResult;

             $quantity_query = "select 
                p.name as product_name , 
                d.client_id ,dd.product_id,sum(dd.quantity) as total_quantity ,
                d.date from delivery as d
                left join delivery_detail as dd ON d.delivery_id = dd.delivery_id
                left join product as p ON p.product_id =dd.product_id
                where d.client_id ='$client_id' and d.date between '$startDate' and '$endDate'
                and  dd.product_id ='$product_id' 
                group by dd.product_id";


               $quantity_Result =  Yii::app()->db->createCommand($quantity_query)->queryAll();

                 $quantity = '';
                 $put_comma  =false ;
                foreach ($quantity_Result as $value){

                      if($put_comma){
                          $quantity .=',';
                      }
                     $product_name = $value['product_name'];
                     $total_quantity = $value['total_quantity'];

                    $quantity .= $product_name." : ".$total_quantity ;

                    $put_comma  =true ;
                }


                $oneObject['quantity'] = $quantity;

             if($oneObject['amount']> 0){
                 $finalResult[]= $oneObject;
             }
               $totalAmount = $totalAmount + $oneObject['amount'];

         }

          $result = array();
          $result['customer_list'] =$finalResult ;
          $result['total'] = $totalAmount ;

          $result['reciable'] = (85/100)*$totalAmount ;

          $result['total_quantity_sum'] = $total_quantity_sum ;

          return $result;


       //   return json_encode($data);

    }
    public static function not_dateWisedeliveryReport_allData($data){

        $company_id = Yii::app()->user->getState('company_branch_id');
        $riderID =$data['RiderID'];
        $todaydate =  $data['date'];
        $timestamp = strtotime($todaydate);
        $day = date('D', $timestamp);
        $todayfrequencyID = '';
        if($day == 'Mon'){
            $todayfrequencyID = 1 ;
        }elseif($day == 'Tue'){
            $todayfrequencyID = 2;
        }elseif($day == 'Wed'){
            $todayfrequencyID = 3 ;
        }elseif($day == 'Thu'){
            $todayfrequencyID = 4 ;
        }elseif($day == 'Fri'){
            $todayfrequencyID = 5 ;
        }elseif($day == 'Sat'){
            $todayfrequencyID = 6 ;
        }else{
            $todayfrequencyID = 7 ;
        }




        $query="SELECT p.*  from   product as p 
                where p.company_branch_id =$company_id and p.bottle = 0";
        $productList =  Yii::app()->db->createCommand($query)->queryAll();

       $total_sum_cunt_object = array();
       foreach ($productList as $product){
             $product_id = $product['product_id'];
           $total_sum_cunt_object[$product_id] = 0;
       }


        $clientQuery = "Select c.client_id,c.address , c.cell_no_1 ,c.fullname , z.name as zone_name from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id 
                           left join zone as z ON z.zone_id = c.zone_id
                           where rz.rider_id = $riderID  AND c.is_active = 1
                            order by c.rout_order ASC ,c.fullname ASC ";

        $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();

        $cientID = array();
        $cientID[] = 0;
        foreach($clientResult as $value){
            $cientID[] =  $value['client_id'];
        }
        $lientID_list = implode(',',$cientID);

        $notDelivery_finalResult = todayDeliveryQuantityCountForApi::getNotDeliveryTodayResult($lientID_list ,$todaydate);


        $clientPriceObject  = todayDeliveryQuantityCountForApi::getClientBaseProductPRize($lientID_list);

      //  $clientPriceObject_not_delivery_reasonType  = todayDeliveryQuantityCountForApi::getClientBaseNot_delivery_reason_type($lientID_list,$todaydate);

        $clientTotalDeliveredToday = todayDeliveryQuantityCountForApi::getDeliveryTodayAllClient($lientID_list ,$todaydate);

        //   var_dump($clientTotalDeliveredToday);
        //      die();
        $final_view_result = array();

        foreach($clientResult as $clientValue){
            $one_array_result = array();
            $one_array_result['client_id'] = $clientValue['client_id'];
            $one_array_result['zone_name'] = $clientValue['zone_name'];
            $one_array_result['address'] = $clientValue['address'] ;
            $one_array_result['cell_no_1'] = $clientValue['cell_no_1'] ;
            $one_array_result['fullname'] = $clientValue['fullname'] ;

             $totalDelivery_schedule = 0 ;
             $check_totalDelivery = 0;

             $product_SchedualrQuantity = array();
            foreach($productList as $productvalue){
                 $one_product_quantity = 0 ;

                    $get_product_id = $productvalue['product_id'] ;
                    $oneProduct_SchedualrQuantity = array();
                $product_incex = $clientValue['client_id']."_".$productvalue['product_id'];
                $client_id = $clientValue['client_id'];
                $one_array_result['product_id'] = $productvalue['product_id'];
                $one_array_result['productName'] = $productvalue['name'];
                if(isset($clientPriceObject[$product_incex])){
                   $one_array_result['price'] = $clientPriceObject[$product_incex];

                }else{

                    $one_array_result['price'] = $productvalue['price'];

                }
                //  $totalInterval_quantity =  utill::getOneCustomerTodayIntervalSceduler_with_date( $clientValue['client_id'],$productvalue['product_id'] ,$todaydate);
                $totalInterval_quantity =  future_selet_date::getOneCustomerTodayIntervalSceduler_with_date_future_date($clientValue['client_id'],$productvalue['product_id'] ,$todaydate);
                $totalDelivery_schedule = $totalDelivery_schedule  + $totalInterval_quantity ;
                $one_product_quantity = $one_product_quantity + $totalInterval_quantity ;
                $totalWeekly_quantity  =    todayDeliveryQuantityCountForApi::getTodayDeliveryCountWeeklyRegularAndSpecial($clientValue['client_id'] ,$productvalue['product_id'], $todaydate);

                $totalDelivery_schedule = $totalDelivery_schedule  + $totalWeekly_quantity ;

                $one_product_quantity = $one_product_quantity + $totalWeekly_quantity ;

                $one_array_result['regularQuantity'] = $totalInterval_quantity + $totalWeekly_quantity ;

                $totalSpecialToday_quantity  =    todayDeliveryQuantityCountForApi::getTodaySpecialOrder($clientValue['client_id'] ,$productvalue['product_id'] ,$todaydate);

                $totalDelivery_schedule = $totalDelivery_schedule  + $totalSpecialToday_quantity ;

                $one_product_quantity = $one_product_quantity + $totalSpecialToday_quantity ;

                $one_array_result['totalSpecialQuantity'] = $totalSpecialToday_quantity;


                if($totalDelivery_schedule > 0 ){
                    if($check_totalDelivery == 0){

                    }
                }



                if(isset($clientTotalDeliveredToday[$product_incex])){
                    $one_array_result['deliveredQuantity'] = $clientTotalDeliveredToday[$product_incex]['deliveredQuantity'] ;
                    $one_array_result['time'] = $clientTotalDeliveredToday[$product_incex]['time'] ;

                    $check_totalDelivery = $clientTotalDeliveredToday[$product_incex]['deliveredQuantity'] ;
                }else{
                    $one_array_result['deliveredQuantity'] = 0;
                }

                if(isset($notDelivery_finalResult[$client_id]) ){
                    // var_dump($notDelivery_finalResult[$client_id]['time']);
                     // die();
                    $one_array_result['time'] = $notDelivery_finalResult[$client_id]['time'];
                    $one_array_result['reasonType_name'] = $notDelivery_finalResult[$client_id]['reasonType_name'];
                    $one_array_result['reject_delivery'] = true;

                }else{
                    //   $one_array_result['time'] = $value['time'] ;
                    $one_array_result['reasonType_name'] = '';
                    $one_array_result['reasonType_name'] = '';
                    $one_array_result['reject_delivery'] = false;

                }
                $oneProduct_SchedualrQuantity['quantity']= $one_product_quantity ;
                $product_SchedualrQuantity[] = $oneProduct_SchedualrQuantity;

                if($totalDelivery_schedule > 0 ){
                    if($check_totalDelivery == 0){
                        $total_sum_cunt_object[$get_product_id] = $total_sum_cunt_object[$get_product_id] + $one_product_quantity;

                    }
                }

            }



             if($totalDelivery_schedule > 0 ){
                 if($check_totalDelivery == 0){
                      $one_object = array();
                       $one_object['client'] = $one_array_result ;
                       $one_object['product'] = $product_SchedualrQuantity ;
                     $final_view_result[] = $one_object;
                 }
             }

        }
        // var_dump($total_sum_cunt_object);
         // die();
           $last_object_count_sum = array();
        foreach ($productList as $product){
            $product_id = $product['product_id'];
            $last_object_count_sum [] = $total_sum_cunt_object[$product_id];
        }
         $List_record = array();
        $List_record['list'] = $final_view_result ;
        $List_record['totalSum'] = $last_object_count_sum ;

        echo json_encode($List_record);


    }
}