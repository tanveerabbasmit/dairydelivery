<?php


class recovery_data_between_date_range
{
    public static function get_recovery_data($data){


        $company_id = Yii::app()->user->getState('company_branch_id');
        $productList=productData::getproductList($page =false);

        if($company_id == 1){
            $start_calculation ='2018-01-01';
        }else{
            $start_calculation ='2016-01-01';
        }

        $year = $data['year'];
        $monthNum = $data['monthNum'];
        $startDate = $data['startDate'];
        $endDate = $data['endDate'];

        $payment_term_id = $data['payment_term_id'];


        if($monthNum >1){
            //  $previousMonth = $monthNum - 1;
            // $endDate_p = $year.'-'.$previousMonth.'-31';
        }else{
            // $year_P = $year-1;
            // $endDate_p = $year_P.'-12-31';
        }

        $client_payment_type = $data['client_payment_type'];




        $RiderID = $data['RiderID'];
        $customer_category_id = $data['customer_category_id'];
        $company_id = Yii::app()->user->getState('company_branch_id');
        if($RiderID >0){

            $clientQuery = "Select 
                           c.client_id,
                           c.address ,
                            c.cell_no_1 ,
                            c.fullname ,
                            c.customer_category_id ,
       
                            p.payment_term_name,
       
                            z.name as zone_name 
                            from rider_zone as rz
                            Right join client as c ON c.zone_id = rz.zone_id
                                
                            left join zone as z ON z.zone_id = c.zone_id LEFT JOIN payment_term AS p
                                ON p.payment_term_id = c.payment_term

                            where rz.rider_id = '$RiderID' ";



                          $clientQuery .=  " order by c.fullname ASC ";



        }elseif($customer_category_id>0){
            $clientQuery ="SELECT 
                c.client_id,
                c.address ,
                c.cell_no_1 ,
                c.customer_category_id ,
                 p.payment_term_name,
                c.fullname ,
                z.name as zone_name 
                FROM  client AS c
                LEFT JOIN zone AS z ON z.zone_id =c.zone_id

              
                    LEFT JOIN payment_term AS p
                                ON p.payment_term_id = c.payment_term
                WHERE c.customer_category_id ='$customer_category_id'
                AND c.company_branch_id  = '$company_id' ";

            if($payment_term_id>0){
                $clientQuery .=  " and c.payment_term = '$payment_term_id' ";
            }
            $clientQuery .=  " order by c.fullname ASC ";
        }elseif($payment_term_id>0){
            $clientQuery = "select 
                          c.client_id,c.address , 
                          c.customer_category_id ,
                          c.cell_no_1 ,
                         p.payment_term_name,
                             c.customer_category_id ,
                           c.fullname from client as c
                          left join zone as z ON z.zone_id = c.zone_id LEFT JOIN payment_term AS p
                                ON p.payment_term_id = c.payment_term
                           where c.payment_term = '$payment_term_id' 
                          
                           order by c.fullname ASC ";

            $clientQuery .=  " order by c.fullname ASC ";



        } else{

            $clientQuery = "select 
                          c.client_id,c.address , 
                          c.customer_category_id ,
                          c.cell_no_1 ,
                         p.payment_term_name,
                             c.customer_category_id ,
                           c.fullname from client as c
                          left join zone as z ON z.zone_id = c.zone_id LEFT JOIN payment_term AS p
                                ON p.payment_term_id = c.payment_term
                           where c.company_branch_id = '$company_id' 
                          
                           order by c.fullname ASC ";



        }
        $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();


        $client_list = array();
        $client_list[] = 0;

        foreach ($clientResult as $value){
            $client_list[] =$value['client_id'];
        }

        $product_rate_client_wise = getRateOfProduct::getCrudrole(implode(",",$client_list));

        $product_list = getRateOfProduct::getProductList();


        $product_list_price = array();
        foreach ($product_list as $value){
            $product_id = $value['product_id'];
            $product_list_price[$product_id] = $value['price'];
        }
        $product_quantity_client_wise = getRateOfProduct::product_quantity_client_wise(implode(",",$client_list) ,$startDate,$endDate );

        $finalData = array();

        $getCategoryList = categoryData::getCategoryList_for_recory_report();





        foreach($clientResult as $value){

            $customer_category_id = $value['customer_category_id'];


            $oneObject = array();
            $client_id = $value['client_id'];
            $product_price = '';
            $product_quantity = '';
            $comma = false;
            foreach ($product_list as $value_product){
                if($comma){
                    $product_price.=',';
                    $product_quantity.=',';
                }
                $comma = true ;
                $product_id = $value_product['product_id'];
                $number = $client_id.$product_id;
                if($company_id !=1){
                    $product_price .=$value_product['name'].':';
                }
                $rate_collume_array = array();
                if(isset($product_rate_client_wise[$number])){
                    if($company_id ==1){
                        $product_price.= floor($product_rate_client_wise[$number]);
                        $rate_collume_array[] =floor($product_rate_client_wise[$number]);
                    }else{
                        $product_price.=$product_rate_client_wise[$number];
                    }
                }else{
                    if($company_id ==1){
                        $product_price.= floor($product_list_price[$product_id]);

                    }else{
                        $product_price.= $product_list_price[$product_id] ;
                    }
                }
                if($company_id !=1){
                    $product_quantity .=$value_product['name'].':';
                }
                if(isset($product_quantity_client_wise[$number])){
                    $product_quantity.=$product_quantity_client_wise[$number];
                }else{
                    $product_quantity.= 0 ;
                }

            }
            //   $client_id = '432';
            $oneObject['company_id'] = $company_id;
            $oneObject['product_list_rate'] = $product_price;

            // $oneObject['product_list_rate'] = 999911;

            $oneObject['product_quantity'] = $product_quantity;
            $oneObject['client_id'] = $value['client_id'];
            $oneObject['address'] = $value['address'];
            $oneObject['cell_no_1'] = $value['cell_no_1'];
            $oneObject['fullname'] = $value['fullname'];
            $oneObject['payment_term_name'] = $value['payment_term_name'];


            $query_makePayment = "SELECT 
                                 sum(pm.amount_paid) as amount_paid ,
                                  u.full_name,r.fullname FROM payment_master as pm
                                  LEFT JOIN     user AS u ON u.user_id = pm.user_id
                                  LEFT JOIN rider AS r ON r.rider_id = pm.rider_id AND r.rider_id !=0
                                  where pm.client_id='$client_id' and pm.date >='$startDate'";


            $Payment_result =  Yii::app()->db->createCommand($query_makePayment)->queryAll();



            if(sizeof($Payment_result)){
                if($Payment_result[0]['amount_paid']){
                    $totalMake_Payment =intval($Payment_result[0]['amount_paid']);
                    $payment_add_by_user = $Payment_result[0]['full_name'].$Payment_result[0]['fullname'] ;

                }else{
                    $totalMake_Payment = 0;
                    $payment_add_by_user ='';
                }

            }else{
                $totalMake_Payment =0 ;
                $payment_add_by_user ='';
            }


            /*openeing*/

            /*========Start=========== */

            //  $queryDelivery2 ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
            // where d.client_id = $client_id AND d.date  <= '$endDate' ";

            if($company_id==1){
                $queryDelivery2 ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
                 where d.client_id = $client_id AND (d.date between '$start_calculation' and '$endDate') ";

            }else{
                $queryDelivery2 ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
                 where d.client_id = $client_id AND d.date <= '$endDate'";

            }





            $deliveryResult2 = Yii::app()->db->createCommand($queryDelivery2)->queryAll();
            $totaldeliverySum2 = $deliveryResult2[0]['deliverySum'];



            $queryDelivery2 ="Select 
                           IFNULL(sum(pm.amount_paid) , 0) as remainingAmount
                            from payment_master as pm          
                              where pm.client_id = $client_id
                                AND pm.date < '$startDate' ";



            $deliveryResult2 = Yii::app()->db->createCommand($queryDelivery2)->queryAll();
            $totalRemaining2 = $deliveryResult2[0]['remainingAmount'];
            $openingTotalBalance2 =  $totaldeliverySum2 - $totalRemaining2 ;


            //tan
            $queryDelivery ="Select IFNULL(sum(pm.amount_paid) , 0) as remainingAmount from payment_master as pm
            where pm.client_id = $client_id AND pm.date <= '$endDate' ";

            $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();

            $totalRemaining = $deliveryResult[0]['remainingAmount'];

            $openingTotalBalance =  $totaldeliverySum2 - $totalRemaining ;




            /*==============================current month Delivery Start==========================================*/

            $CurrentMOnthDelivery = "Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
                                where d.client_id = $client_id AND  d.date between  '$startDate' and '$endDate' ";


            $currentMonthResult = Yii::app()->db->createCommand($CurrentMOnthDelivery)->queryAll();
            $totaldeliverySum_current = $currentMonthResult[0]['deliverySum'];



            /* current month Delivery end*/
            /*===========================================opening Delivery Start====================================================*/


            $queryDelivery_opening ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
                  where d.client_id = $client_id AND d.date  < '$startDate' ";





            $deliveryResult_opening = Yii::app()->db->createCommand($queryDelivery_opening)->queryAll();

            $totaldeliverySum_opening = $deliveryResult_opening[0]['deliverySum'];



            $final_total_amount_opening = $totaldeliverySum_opening - $totalRemaining2 ;




            /*opening month Delivery end*/

            $different_between =   $openingTotalBalance2 - $totalMake_Payment ;

            $oneObject['1'] = $totaldeliverySum_opening  ;
            $oneObject['2'] = $totalRemaining2  ;

            $oneObject['endDateBalance'] = $totalMake_Payment  ;

            // $oneObject['endDateBalance'] = $totalMake_Payment  ;

            $oneObject['payment_add_by_user'] =$payment_add_by_user;

            $oneObject['totaldeliverySum_current'] =$totaldeliverySum_current  ;
            $oneObject['final_total_amount_opening'] =$final_total_amount_opening  ;

            $oneObject['sum_opening_current'] =$final_total_amount_opening + $totaldeliverySum_current  ;

            $oneObject['totalMakePayment'] = $openingTotalBalance2;

            $oneObject['difference'] = 'line 218' ;



            if($different_between > 0){
                $oneObject['balance'] = $different_between;
                $oneObject['difference'] = 0;
                $oneObject['color'] = 'blue';

            }else{
                $oneObject['balance'] = 0;
                $oneObject['difference'] = $totalMake_Payment-$openingTotalBalance2;
                $oneObject['color'] = 'Violet';
            }


            if($different_between == 0){
                $oneObject['balance'] = 0;
                $oneObject['difference'] = 0;
                $oneObject['color'] = 'black';
            }
            if($different_between > 0 && $totalMake_Payment==0 ){
                $oneObject['color'] = 'red';
            }

            $select_all_payment_mode = false;


            foreach($client_payment_type as $value){
                if($value=='0'){
                    $select_all_payment_mode =true;
                }
            }


            if(isset($getCategoryList[$customer_category_id])){

                $oneObject['customer_category'] = $getCategoryList[$customer_category_id];

            }else{
                $oneObject['customer_category'] ='NA';
            }





            if( $oneObject['totaldeliverySum_current'] !=0 || $oneObject['totalMakePayment'] !=0 || $oneObject['endDateBalance']!=0  || $oneObject['balance'] !=0  ){
                if($select_all_payment_mode){

                    $finalData[] =$oneObject ;
                }else{

                    foreach($client_payment_type as $value){
                        if($oneObject['color'] ==$value){
                            $finalData[] =$oneObject ;
                        }
                    }
                    /*if($oneObject['color'] ==$client_payment_type){
                        $finalData[] =$oneObject ;
                    }*/
                }


            }else{
                // $finalData[] =$oneObject ;
            }
        }
        $result = array();
        $sum = 0;
        $count = 0;
        $quantity = 0;
        if($company_id ==1){
            foreach ($finalData as $value){
                $quantity = $quantity + $value['product_quantity'] ;
                $sum = $sum +$value['product_list_rate'];
                $count++;
            }
        }

        //  $grandTotal = PaymentMasterController::get_main_grand_total($finalData);


        $finalData_array = array();

        /*green*/


        $totaldeliverySum_current_total =0;
        $final_total_amount_opening_total =0;
        $sum_opening_current_total =0;
        $totalMakePayment_total =0;
        $difference_total =0;
        $balance_total =0;
        $check_empty_data_of_this_group = false;
        $index = 0;

        $product_quantity_total = 0;

        $product_list_rate_total =0 ;

        $total_endDateBalance = 0 ;



        foreach($finalData as $value){



            if($value['color'] =='Violet'){
                $index ++;
                $one_object_data = array();
                $one_object_data['index'] = $index;
                $one_object_data['payment_add_by_user'] = $value['payment_add_by_user'];
                $one_object_data['company_id'] = $value['company_id'];
                $one_object_data['product_list_rate'] = $value['product_list_rate'];

                $one_object_data['product_quantity'] = $value['product_quantity'];
                $one_object_data['client_id'] = $value['client_id'];
                $one_object_data['fullname'] = $value['fullname'];
                $one_object_data['address'] = $value['address'];
                $one_object_data['cell_no_1'] = $value['cell_no_1'];
                $one_object_data['1'] = $value['1'];
                $one_object_data['2'] = $value['2'];
                $one_object_data['endDateBalance'] = $value['endDateBalance'];
                $one_object_data['totaldeliverySum_current'] = $value['totaldeliverySum_current'];
                $one_object_data['final_total_amount_opening'] = $value['final_total_amount_opening'];
                $one_object_data['sum_opening_current'] = $value['sum_opening_current'];
                $one_object_data['totalMakePayment'] = $value['totalMakePayment'];
                $one_object_data['difference'] = $value['difference'];
                $one_object_data['balance'] = $value['balance'];
                $one_object_data['color'] = $value['color'];
                $one_object_data['payment_term_name'] = $value['payment_term_name'];
                $one_object_data['customer_category'] = $value['customer_category'];
                // $one_object_data['sum_record'] = "sum_record";
                $finalData_array[] = $one_object_data;

                $totaldeliverySum_current_total =$totaldeliverySum_current_total + $value['totaldeliverySum_current'];
                $final_total_amount_opening_total =$final_total_amount_opening_total + $value['final_total_amount_opening'];
                $sum_opening_current_total =$sum_opening_current_total + $value['sum_opening_current'];
                $totalMakePayment_total =$totalMakePayment_total + $value['totalMakePayment'];
                $difference_total =$difference_total + $value['difference'];
                $balance_total =$balance_total + $value['balance'];

                $product_quantity_total = intval($product_quantity_total) + intval($value['product_quantity']);

                $check_empty_data_of_this_group = true;

                $total_endDateBalance = $total_endDateBalance +$value['endDateBalance'];
            }
        }
        $oneObject =array();

        $oneObject['client_id'] =  "Sub Total";
        $oneObject['colspan'] =  '3';

        $oneObject['fullname'] =  false;

        $oneObject['endDateBalance'] =  $total_endDateBalance;

        $oneObject['address'] =  "LightGreen";
        $oneObject['totaldeliverySum_current'] =  $totaldeliverySum_current_total;
        $oneObject['final_total_amount_opening'] =  $final_total_amount_opening_total;
        $oneObject['sum_opening_current'] =  $sum_opening_current_total;
        $oneObject['totalMakePayment'] =  $totalMakePayment_total;
        $oneObject['difference'] =  $difference_total;
        $oneObject['sum_record'] = "sum_record";

        $oneObject['balance'] =  $balance_total;
        $oneObject['product_quantity'] =  $product_quantity_total;
        if($index >0){
            $oneObject['product_list_rate'] = $product_list_rate_total/$index;
        }else{
            $oneObject['product_list_rate'] =0;
        }
        if($check_empty_data_of_this_group){
            $finalData_array[] = $oneObject;
        }

        /*black*/
        $totaldeliverySum_current_total =0;
        $final_total_amount_opening_total =0;
        $sum_opening_current_total =0;
        $totalMakePayment_total =0;
        $difference_total =0;
        $balance_total =0;

        $check_empty_data_of_this_group = false ;
        $index =0;
        $product_quantity_total=0;

        $product_list_rate_total =0 ;

        $total_endDateBalance = 0;

        foreach($finalData as $value){
            if($value['color'] =='black'){
                $index++;
                $one_object_data = array();
                $one_object_data['index'] = $index;
                $one_object_data['payment_add_by_user'] = $value['payment_add_by_user'];
                $one_object_data['fullname'] = $value['fullname'];
                $one_object_data['company_id'] = $value['company_id'];
                $one_object_data['product_list_rate'] = $value['product_list_rate'];
                $one_object_data['product_quantity'] = $value['product_quantity'];
                $one_object_data['client_id'] = $value['client_id'];
                $one_object_data['address'] = $value['address'];
                $one_object_data['cell_no_1'] = $value['cell_no_1'];
                $one_object_data['1'] = $value['1'];
                $one_object_data['2'] = $value['2'];
                $one_object_data['endDateBalance'] = $value['endDateBalance'];
                $one_object_data['totaldeliverySum_current'] = $value['totaldeliverySum_current'];
                $one_object_data['final_total_amount_opening'] = $value['final_total_amount_opening'];
                $one_object_data['sum_opening_current'] = $value['sum_opening_current'];
                $one_object_data['totalMakePayment'] = $value['totalMakePayment'];
                $one_object_data['difference'] = $value['difference'];
                $one_object_data['balance'] = $value['balance'];
                $one_object_data['color'] = $value['color'];
                $one_object_data['customer_category'] = $value['customer_category'];
                $one_object_data['payment_term_name'] = $value['payment_term_name'];
                // $one_object_data['sum_record'] = "sum_record";
                $finalData_array[] = $one_object_data;

                $totaldeliverySum_current_total =$totaldeliverySum_current_total + $value['totaldeliverySum_current'];
                $final_total_amount_opening_total =$final_total_amount_opening_total + $value['final_total_amount_opening'];
                $sum_opening_current_total =$sum_opening_current_total + $value['sum_opening_current'];
                $totalMakePayment_total =$totalMakePayment_total + $value['totalMakePayment'];
                $difference_total =$difference_total + $value['difference'];
                $balance_total =$balance_total + $value['balance'];

                $check_empty_data_of_this_group =true;

                $product_quantity_total = intval($value['product_quantity']) + intval($product_quantity_total);

                $product_list_rate_total =intval($value['product_list_rate']) +intval($product_list_rate_total);

                $total_endDateBalance = $total_endDateBalance +$value['endDateBalance'];
            }
        }

        $oneObject =array();
        $oneObject['client_id'] =  "Sub Total";
        $oneObject['colspan'] =  '3';
        $oneObject['fullname'] =  false;

        $oneObject['endDateBalance'] = $total_endDateBalance;

        $oneObject['address'] =  "LightGreen";
        $oneObject['totaldeliverySum_current'] =  $totaldeliverySum_current_total;
        $oneObject['final_total_amount_opening'] =  $final_total_amount_opening_total;
        $oneObject['sum_opening_current'] =  $sum_opening_current_total;
        $oneObject['totalMakePayment'] =  $totalMakePayment_total;
        $oneObject['difference'] =  $difference_total;
        $oneObject['balance'] =  $balance_total;
        $oneObject['sum_record'] = "sum_record";
        $oneObject['product_quantity'] =  $product_quantity_total;

        if($index >0){
            $oneObject['product_list_rate'] = $product_list_rate_total/$index;
        }else{
            $oneObject['product_list_rate'] =0;
        }

        if($check_empty_data_of_this_group){
            $finalData_array[] = $oneObject;
        }

        /*blue*/

        $totaldeliverySum_current_total =0;
        $final_total_amount_opening_total =0;
        $sum_opening_current_total =0;
        $totalMakePayment_total =0;
        $difference_total =0;
        $balance_total =0;

        $check_empty_data_of_this_group = false ;
        $index=0;
        $product_quantity_total =0;

        $product_list_rate_total =0 ;

        $total_endDateBalance = 0;

        foreach($finalData as $value){
            if($value['color'] =='blue'){
                $index++;
                $one_object_data = array();
                $one_object_data['index'] = $index;
                $one_object_data['payment_add_by_user'] = $value['payment_add_by_user'];
                $one_object_data['company_id'] = $value['company_id'];
                $one_object_data['product_list_rate'] = $value['product_list_rate'];
                $one_object_data['product_quantity'] = $value['product_quantity'];
                $one_object_data['fullname'] = $value['fullname'];
                $one_object_data['client_id'] = $value['client_id'];
                $one_object_data['address'] = $value['address'];
                $one_object_data['cell_no_1'] = $value['cell_no_1'];
                $one_object_data['1'] = $value['1'];
                $one_object_data['2'] = $value['2'];
                $one_object_data['endDateBalance'] = $value['endDateBalance'];
                $one_object_data['totaldeliverySum_current'] = $value['totaldeliverySum_current'];
                $one_object_data['final_total_amount_opening'] = $value['final_total_amount_opening'];
                $one_object_data['sum_opening_current'] = $value['sum_opening_current'];
                $one_object_data['totalMakePayment'] = $value['totalMakePayment'];
                $one_object_data['difference'] = $value['difference'];
                $one_object_data['balance'] = $value['balance'];
                $one_object_data['color'] = $value['color'];
                $one_object_data['customer_category'] = $value['customer_category'];
                $one_object_data['payment_term_name'] = $value['payment_term_name'];
                // $one_object_data['sum_record'] = "sum_record";
                $finalData_array[] = $one_object_data;
                $totaldeliverySum_current_total =$totaldeliverySum_current_total + $value['totaldeliverySum_current'];
                $final_total_amount_opening_total =$final_total_amount_opening_total + $value['final_total_amount_opening'];
                $sum_opening_current_total =$sum_opening_current_total + $value['sum_opening_current'];
                $totalMakePayment_total =$totalMakePayment_total + $value['totalMakePayment'];
                $difference_total =$difference_total + $value['difference'];
                $balance_total =$balance_total + $value['balance'];
                $check_empty_data_of_this_group = true ;

                $product_quantity_total =intval($value['product_quantity']) + intval($product_quantity_total);

                $product_list_rate_total =intval($value['product_list_rate']) +intval($product_list_rate_total);

                $total_endDateBalance = $total_endDateBalance +$value['endDateBalance'];
            }
        }
        $oneObject =array();
        $oneObject['client_id'] =  "Sub Total";
        $oneObject['colspan'] =  '3';
        $oneObject['fullname'] =  false;

        $oneObject['endDateBalance'] =  $total_endDateBalance;

        $oneObject['address'] =  "LightGreen";
        $oneObject['totaldeliverySum_current'] =  $totaldeliverySum_current_total;
        $oneObject['final_total_amount_opening'] =  $final_total_amount_opening_total;
        $oneObject['sum_opening_current'] =  $sum_opening_current_total;
        $oneObject['totalMakePayment'] =  $totalMakePayment_total;
        $oneObject['difference'] =  $difference_total;
        $oneObject['balance'] =  $balance_total;
        $oneObject['product_quantity'] =  $product_quantity_total;
        $oneObject['endDateBalance'] =  $total_endDateBalance;
        $oneObject['sum_record'] = "sum_record";

        if($index >0){
            $oneObject['product_list_rate'] = $product_list_rate_total/$index;
        }else{
            $oneObject['product_list_rate'] =0;
        }

        if($check_empty_data_of_this_group){
            $finalData_array[] = $oneObject;
        }
        /*red*/

        $totaldeliverySum_current_total =0;
        $final_total_amount_opening_total =0;
        $sum_opening_current_total =0;
        $totalMakePayment_total =0;
        $difference_total =0;
        $balance_total =0;
        $check_empty_data_of_this_group = false ;
        $index=0;
        $product_quantity_total =0;

        $product_list_rate_total =0 ;
        $total_endDateBalance =0;
        foreach($finalData as $value){
            if($value['color'] =='red'){
                $index++;
                $one_object_data = array();
                $one_object_data['index'] = $index;
                $one_object_data['payment_add_by_user'] = $value['payment_add_by_user'];
                $one_object_data['company_id'] = $value['company_id'];
                $one_object_data['product_list_rate'] = $value['product_list_rate'];
                $one_object_data['product_quantity'] = $value['product_quantity'];
                $one_object_data['fullname'] = $value['fullname'];
                $one_object_data['client_id'] = $value['client_id'];
                $one_object_data['address'] = $value['address'];
                $one_object_data['cell_no_1'] = $value['cell_no_1'];
                $one_object_data['1'] = $value['1'];
                $one_object_data['2'] = $value['2'];
                $one_object_data['endDateBalance'] = $value['endDateBalance'];
                $one_object_data['totaldeliverySum_current'] = $value['totaldeliverySum_current'];
                $one_object_data['final_total_amount_opening'] = $value['final_total_amount_opening'];
                $one_object_data['sum_opening_current'] = $value['sum_opening_current'];
                $one_object_data['totalMakePayment'] = $value['totalMakePayment'];
                $one_object_data['difference'] = $value['difference'];
                $one_object_data['balance'] = $value['balance'];
                $one_object_data['color'] = $value['color'];
                $one_object_data['customer_category'] = $value['customer_category'];
                $one_object_data['payment_term_name'] = $value['payment_term_name'];
                $finalData_array[] = $one_object_data;
                $totaldeliverySum_current_total =$totaldeliverySum_current_total + $value['totaldeliverySum_current'];
                $final_total_amount_opening_total =$final_total_amount_opening_total + $value['final_total_amount_opening'];
                $sum_opening_current_total =$sum_opening_current_total + $value['sum_opening_current'];
                $totalMakePayment_total =$totalMakePayment_total + $value['totalMakePayment'];
                $difference_total =$difference_total + $value['difference'];
                $balance_total =$balance_total + $value['balance'];
                $check_empty_data_of_this_group = true;
                $product_quantity_total =intval($product_quantity_total) + intval($value['product_quantity']);
                $product_list_rate_total =intval($value['product_list_rate']) +intval($product_list_rate_total);
                $total_endDateBalance =$total_endDateBalance + $value['endDateBalance'];
            }
        }

        $oneObject =array();
        $oneObject['client_id'] =  "Sub Total";
        $oneObject['fullname'] =  false;
        $oneObject['endDateBalance'] =  $total_endDateBalance;
        $oneObject['colspan'] =  '3';
        $oneObject['address'] =  "LightGreen";

        $oneObject['totaldeliverySum_current'] =  $totaldeliverySum_current_total;
        $oneObject['final_total_amount_opening'] =  $final_total_amount_opening_total;
        $oneObject['sum_opening_current'] =  $sum_opening_current_total;
        $oneObject['totalMakePayment'] =  $totalMakePayment_total;
        $oneObject['difference'] =  $difference_total;
        $oneObject['balance'] =  $balance_total;
        $oneObject['sum_record'] = "sum_record";
        $oneObject['product_quantity'] =  $product_quantity_total;
        if($index >0){
            $oneObject['product_list_rate'] = $product_list_rate_total/$index;
        }else{
            $oneObject['product_list_rate'] =0;
        }
        if($check_empty_data_of_this_group){
            $finalData_array[] = $oneObject;
        }
        $result['finalData'] = $finalData_array;
        if($company_id ==1){
            if($count==0){
                $result['avgRate']=0;
            }else{
                $result['avgRate'] = number_format(($sum/$count), 2, '.', '');
            }

            $result['quantity'] = $quantity;
        }else{
            $result['avgRate'] = '';
            $result['quantity'] = '';
        }
        // $result['grandTotal'] = $grandTotal ;
        return json_encode($result);
    }
}