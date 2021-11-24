<?php


class getCustomerLedger_dateRangeCustomerReport_data
{

    public static function get_report_data($data){

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
        $customer_category_id = $data['customer_category_id'];
        $payment_term_id = $data['payment_term_id'];
        if($monthNum >1){
            $previousMonth = $monthNum - 1;
            $endDate_p = $year.'-'.$previousMonth.'-31';
        }else{
            $year_P = $year-1;
            $endDate_p = $year_P.'-12-31';
        }
        $RiderID = $data['RiderID'];
        $company_id = Yii::app()->user->getState('company_branch_id');
        if($RiderID >0  ){
            $clientQuery = "Select c.client_id,c.address , c.cell_no_1 ,c.fullname ,z.name as zone_name from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id 
                           left join zone as z ON z.zone_id = c.zone_id
                           where rz.rider_id = $RiderID   ";
            if($customer_category_id > 0){
                $clientQuery .=" and  c.customer_category_id = '$customer_category_id' ";
            }
            if($payment_term_id > 0){
                $clientQuery .=" and  c.payment_term = '$payment_term_id' ";
            }
            $clientQuery .=" order by c.fullname ASC  ";

        }else{

            $clientQuery = "select c.client_id,c.address , c.cell_no_1 ,c.fullname from client as c
                           where c.company_branch_id = '$company_id' ";
            if($customer_category_id > 0){
                $clientQuery .="  and  c.customer_category_id = '$customer_category_id' ";
            }

            if($payment_term_id > 0){
                $clientQuery .=" and  c.payment_term = '$payment_term_id' ";
            }
            $clientQuery .=" order by c.fullname ASC  ";

        }



        $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();

        $client_list = array();
        $client_list[] = 0;
        foreach ($clientResult as $value){
            $client_list[] =$value['client_id'];
        }

        $client_id_list = implode(",",$client_list);

        $product_rate_client_wise = getRateOfProduct::getCrudrole(implode(",",$client_list));

        $product_list = getRateOfProduct::getProductList();


        $product_list_price = array();
        foreach ($product_list as $value){
            $product_id = $value['product_id'];
            $product_list_price[$product_id] = $value['price'];
        }

        $product_quantity_client_wise = getRateOfProduct::product_quantity_client_wise_rate_quantity(implode(",",$client_list) ,$startDate,$endDate );



        //  $product_quantity_client_wise_total_sum = getRateOfProduct::product_quantity_client_wise_total_sum(implode(",",$client_list) ,$startDate,$endDate );


        $product_quantity_total = getRateOfProduct::product_quantity_total(implode(",",$client_list) ,$startDate,$endDate );

        $finalData = array();
        foreach($clientResult as $value){
            $oneObject = array();
            $client_id = $value['client_id'];
            $product_price = '';
            $product_quantity = '';
            $comma = false;

            $check_is_dlivered_any_quantity = false ;
            foreach ($product_list as $value_product){
                if($comma){
                    $product_price.=',';
                    $product_quantity.=',';
                }
                $comma = true ;
                $product_id = $value_product['product_id'];
                $number = $client_id.$product_id;

                // $product_price .=$value_product['name'].':';
                if(isset($product_rate_client_wise[$number])){
                    // $product_price.=$product_rate_client_wise[$number];
                }else{
                    // $product_price.= $product_list_price[$product_id] ;
                }

                // $product_quantity .=$value_product['name'].':';
                if(isset($product_quantity_client_wise[$number])){
                    $product_quantity.=$product_quantity_client_wise[$number]['quantity'];
                    $product_price.=$product_quantity_client_wise[$number]['rate'];

                    $check_is_dlivered_any_quantity = true ;
                }else{
                    $product_quantity.= '0' ;
                    $product_price.= '0' ;
                }

            }
            //   $client_id = '432';

            // echo $product_price;
            // die();

            $oneObject['product_list_rate'] = $product_price;
            // $oneObject['product_list_rate'] = 999999;
            $oneObject['product_quantity'] = $product_quantity;
            // $oneObject['product_quantity'] = '888888';
            $oneObject['client_id'] = $value['client_id'];
            $oneObject['address'] = $value['address'];
            $oneObject['cell_no_1'] = $value['cell_no_1'];
            $oneObject['fullname'] = $value['fullname'];

            $query_makePayment = "SELECT 
                    sum(pm.amount_paid) FROM payment_master as pm
                   where pm.client_id='$client_id' 
                             and month(pm.bill_month_date)='$monthNum' 
                   and year(pm.bill_month_date) = '$year'";

            $totalMake_Payment =  Yii::app()->db->createCommand($query_makePayment)->queryScalar();

            /*openeing*/
            /*========Start=========== */
            //  $queryDelivery2 ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
            // where d.client_id = $client_id AND d.date  <= '$endDate' ";

            if($company_id==1){
                $queryDelivery2 ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
                 where d.client_id = $client_id AND (d.date between '$start_calculation' and '$endDate') ";

            }else{
                $queryDelivery2 ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
                 where d.client_id = $client_id AND d.date < '$startDate'";

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
            //mit


            $CurrentMOnthDelivery = "Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
                                where d.client_id = $client_id AND  d.date between '$startDate' and '$endDate' ";




            $currentMonthResult = Yii::app()->db->createCommand($CurrentMOnthDelivery)->queryAll();

            $totaldeliverySum_current = $currentMonthResult[0]['deliverySum'];

            /* current month Delivery end*/
            /*   /*===========================================opening Delivery Start====================================================*/
            /*  $queryDelivery_opening ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
          where d.client_id = $client_id AND (d.date between '$start_calculation' and '$endDate_p') ";

              $deliveryResult_opening = Yii::app()->db->createCommand($queryDelivery_opening)->queryAll();

              $totaldeliverySum_opening = $deliveryResult_opening[0]['deliverySum'];*/


            $final_total_amount_opening = $totaldeliverySum2 - $totalRemaining2 ;


            /*opening month Delivery end*/


            $different_between =   $openingTotalBalance2 - $totalMake_Payment ;

            $oneObject['1'] = $totaldeliverySum2  ;
            $oneObject['2'] = $totalRemaining2  ;

            $oneObject['endDateBalance'] = $totalMake_Payment  ;

            // $oneObject['endDateBalance'] = '99999'  ;

            $oneObject['totaldeliverySum_current'] =$totaldeliverySum_current  ;
            $oneObject['final_total_amount_opening'] =$final_total_amount_opening  ;

            $oneObject['sum_opening_current'] =$final_total_amount_opening + $totaldeliverySum_current  ;

            $oneObject['totalMakePayment'] = $openingTotalBalance2;


            $oneObject['difference'] = 'line 218' ;

            if($totalMake_Payment == 0 ){
                $oneObject['color'] = 'red';
            }


            if($different_between > 0){

                $oneObject['balance'] = $different_between;
                $oneObject['difference'] = 0;
                $oneObject['color'] = 'blue';
                if($totalMake_Payment ==0){

                }
            }else{
                $oneObject['balance'] = 0;
                $oneObject['difference'] = $totalMake_Payment-$openingTotalBalance2;
                $oneObject['color'] = 'green';
            }

            if($different_between == 0){
                $oneObject['balance'] = 0;
                $oneObject['difference'] = 0;
                $oneObject['color'] = '	black';
            }

            if($totalMake_Payment == 0 ){
                $oneObject['color'] = 'red';
            }

            if( $oneObject['totaldeliverySum_current'] !=0 || $oneObject['totalMakePayment'] !=0 || $oneObject['endDateBalance']!=0  || $oneObject['balance'] !=0  ){

                if($check_is_dlivered_any_quantity){
                    $finalData[] =$oneObject ;
                }


            }else{

                // $finalData[] =$oneObject ;
            }
        }
        $cout_rate = 0;
        $sum_rate = 0;

        $company_id = Yii::app()->user->getState('company_branch_id');

        if($company_id==1){
            foreach($finalData as $value){
                $cout_rate = $cout_rate+1;
                $product_list_rate = $value['product_list_rate'];
                $sum_rate = $sum_rate +   $product_list_rate ;
            }
        }


        if($cout_rate>0){
            $vag_rate = ($sum_rate/$cout_rate);
        }else{
            $vag_rate = 0;
        }


        $finalRessult = array();
        $finalRessult['product_quantity_total'] = $product_quantity_total;
        $finalRessult['finalData'] = $finalData;
        $finalRessult['vag_rate'] = round($vag_rate,2);
        return json_encode($finalRessult);
    }

    public static function get_report_data_date_range_raej($data){

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
        $customer_category_id = $data['customer_category_id'];
        $payment_term_id = $data['payment_term_id'];
        if($monthNum >1){
            $previousMonth = $monthNum - 1;
            $endDate_p = $year.'-'.$previousMonth.'-31';
        }else{
            $year_P = $year-1;
            $endDate_p = $year_P.'-12-31';
        }
        $RiderID = $data['RiderID'];
        $company_id = Yii::app()->user->getState('company_branch_id');
        if($RiderID >0  ){
            $clientQuery = "Select c.client_id,c.address , c.cell_no_1 ,c.fullname ,z.name as zone_name from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id 
                           left join zone as z ON z.zone_id = c.zone_id
                           where rz.rider_id = $RiderID   ";
            if($customer_category_id > 0){
                $clientQuery .=" and  c.customer_category_id = '$customer_category_id' ";
            }
            if($payment_term_id > 0){
                $clientQuery .=" and  c.payment_term = '$payment_term_id' ";
            }
            $clientQuery .=" order by c.fullname ASC  ";

        }else{

            $clientQuery = "select c.client_id,c.address , c.cell_no_1 ,c.fullname from client as c
                           where c.company_branch_id = '$company_id' ";
            if($customer_category_id > 0){
                $clientQuery .="  and  c.customer_category_id = '$customer_category_id' ";
            }

            if($payment_term_id > 0){
                $clientQuery .=" and  c.payment_term = '$payment_term_id' ";
            }
            $clientQuery .=" order by c.fullname ASC  ";

        }



        $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();

        $client_list = array();
        $client_list[] = 0;
        foreach ($clientResult as $value){
            $client_list[] =$value['client_id'];
        }

        $client_id_list = implode(",",$client_list);

        $product_rate_client_wise = getRateOfProduct::getCrudrole(implode(",",$client_list));

        $product_list = getRateOfProduct::getProductList();


        $product_list_price = array();
        foreach ($product_list as $value){
            $product_id = $value['product_id'];
            $product_list_price[$product_id] = $value['price'];
        }

        $product_quantity_client_wise = getRateOfProduct::product_quantity_client_wise_rate_quantity(implode(",",$client_list) ,$startDate,$endDate );



        //  $product_quantity_client_wise_total_sum = getRateOfProduct::product_quantity_client_wise_total_sum(implode(",",$client_list) ,$startDate,$endDate );


        $product_quantity_total = getRateOfProduct::product_quantity_total(implode(",",$client_list) ,$startDate,$endDate );

        $finalData = array();
        foreach($clientResult as $value){
            $oneObject = array();
            $client_id = $value['client_id'];
            $product_price = '';
            $product_quantity = '';
            $comma = false;

            $check_is_dlivered_any_quantity = false ;
            foreach ($product_list as $value_product){
                if($comma){
                    $product_price.=',';
                    $product_quantity.=',';
                }
                $comma = true ;
                $product_id = $value_product['product_id'];
                $number = $client_id.$product_id;

                // $product_price .=$value_product['name'].':';
                if(isset($product_rate_client_wise[$number])){
                    // $product_price.=$product_rate_client_wise[$number];
                }else{
                    // $product_price.= $product_list_price[$product_id] ;
                }

                // $product_quantity .=$value_product['name'].':';
                if(isset($product_quantity_client_wise[$number])){
                    $product_quantity.=$product_quantity_client_wise[$number]['quantity'];
                    $product_price.=$product_quantity_client_wise[$number]['rate'];

                    $check_is_dlivered_any_quantity = true ;
                }else{
                    $product_quantity.= '0' ;
                    $product_price.= '0' ;
                }

            }
            //   $client_id = '432';

            // echo $product_price;
            // die();

            $oneObject['product_list_rate'] = $product_price;
            // $oneObject['product_list_rate'] = 999999;
            $oneObject['product_quantity'] = $product_quantity;
            // $oneObject['product_quantity'] = '888888';
            $oneObject['client_id'] = $value['client_id'];
            $oneObject['address'] = $value['address'];
            $oneObject['cell_no_1'] = $value['cell_no_1'];
            $oneObject['fullname'] = $value['fullname'];

            $query_makePayment = "SELECT 
                    sum(pm.amount_paid) FROM payment_master as pm
                   where pm.client_id='$client_id' 
                       and pm.date between
                       '$startDate' and '$endDate' ";

            $totalMake_Payment =  Yii::app()->db->createCommand($query_makePayment)->queryScalar();

            /*openeing*/
            /*========Start=========== */
            //  $queryDelivery2 ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
            // where d.client_id = $client_id AND d.date  <= '$endDate' ";

            if($company_id==1){
                $queryDelivery2 ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
                 where d.client_id = $client_id AND (d.date between '$start_calculation' and '$endDate') ";

            }else{
                $queryDelivery2 ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
                 where d.client_id = $client_id AND d.date < '$startDate'";

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


            $queryDelivery ="Select 
               IFNULL(sum(pm.amount_paid) , 0) as remainingAmount
                from payment_master as pm
            where pm.client_id = $client_id AND pm.date <= '$endDate' ";

            $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();

            $totalRemaining = $deliveryResult[0]['remainingAmount'];
            $openingTotalBalance =  $totaldeliverySum2 - $totalRemaining ;

            /*==============================current month Delivery Start==========================================*/
            //mit


            $CurrentMOnthDelivery = "Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
                                where d.client_id = $client_id AND  d.date between '$startDate' and '$endDate' ";




            $currentMonthResult = Yii::app()->db->createCommand($CurrentMOnthDelivery)->queryAll();

            $totaldeliverySum_current = $currentMonthResult[0]['deliverySum'];

            /* current month Delivery end*/
            /*   /*===========================================opening Delivery Start====================================================*/
            /*  $queryDelivery_opening ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
          where d.client_id = $client_id AND (d.date between '$start_calculation' and '$endDate_p') ";

              $deliveryResult_opening = Yii::app()->db->createCommand($queryDelivery_opening)->queryAll();

              $totaldeliverySum_opening = $deliveryResult_opening[0]['deliverySum'];*/


            $final_total_amount_opening = $totaldeliverySum2 - $totalRemaining2 ;


            /*opening month Delivery end*/


            $different_between =   $openingTotalBalance2 - $totalMake_Payment ;

            $oneObject['1'] = $totaldeliverySum2  ;
            $oneObject['2'] = $totalRemaining2  ;

            $oneObject['endDateBalance'] = $totalMake_Payment  ;

            // $oneObject['endDateBalance'] = '99999'  ;

            $oneObject['totaldeliverySum_current'] =$totaldeliverySum_current  ;
            $oneObject['final_total_amount_opening'] =$final_total_amount_opening  ;

            $oneObject['sum_opening_current'] =$final_total_amount_opening + $totaldeliverySum_current  ;


            $openingTotalBalance2 = $oneObject['final_total_amount_opening'] + $oneObject['totaldeliverySum_current'];

            $different_between =   $openingTotalBalance2 - $totalMake_Payment ;

            $oneObject['totalMakePayment'] = $openingTotalBalance2;



            $oneObject['difference'] = 'line 218' ;

            if($totalMake_Payment == 0 ){
                $oneObject['color'] = 'red';
            }


            if($different_between > 0){

                $oneObject['balance'] = $different_between;
                $oneObject['difference'] = 0;
                $oneObject['color'] = 'blue';
                if($totalMake_Payment ==0){

                }
            }else{
                $oneObject['balance'] = 0;
                $oneObject['difference'] = $openingTotalBalance2 - $totalMake_Payment;
                $oneObject['color'] = 'green';
            }

            if($different_between == 0){
                $oneObject['balance'] = 0;
                $oneObject['difference'] = 0;
                $oneObject['color'] = '	black';
            }

            if($totalMake_Payment == 0 ){
                $oneObject['color'] = 'red';
            }

            if( $oneObject['totaldeliverySum_current'] !=0 || $oneObject['totalMakePayment'] !=0 || $oneObject['endDateBalance']!=0  || $oneObject['balance'] !=0  ){

                if($check_is_dlivered_any_quantity){
                    $finalData[] =$oneObject ;
                }


            }else{

                // $finalData[] =$oneObject ;
            }
        }
        $cout_rate = 0;
        $sum_rate = 0;

        $company_id = Yii::app()->user->getState('company_branch_id');

        if($company_id==1){
            foreach($finalData as $value){
                $cout_rate = $cout_rate+1;
                $product_list_rate = $value['product_list_rate'];
                $sum_rate = $sum_rate +   $product_list_rate ;
            }
        }


        if($cout_rate>0){
            $vag_rate = ($sum_rate/$cout_rate);
        }else{
            $vag_rate = 0;
        }


        $finalRessult = array();
        $finalRessult['product_quantity_total'] = $product_quantity_total;
        $finalRessult['finalData'] = $finalData;
        $finalRessult['vag_rate'] = round($vag_rate,2);
        return json_encode($finalRessult);
    }
    public static function get_report_data_date_range($data){

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
        $customer_category_id = $data['customer_category_id'];
        $payment_term_id = $data['payment_term_id'];
        if($monthNum >1){
            $previousMonth = $monthNum - 1;
            $endDate_p = $year.'-'.$previousMonth.'-31';
        }else{
            $year_P = $year-1;
            $endDate_p = $year_P.'-12-31';
        }
        $RiderID = $data['RiderID'];
        $company_id = Yii::app()->user->getState('company_branch_id');
        if($RiderID >0  ){
            $clientQuery = "Select c.client_id,c.address , c.cell_no_1 ,c.fullname ,z.name as zone_name from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id 
                           left join zone as z ON z.zone_id = c.zone_id
                           where rz.rider_id = $RiderID   ";
            if($customer_category_id > 0){
                $clientQuery .=" and  c.customer_category_id = '$customer_category_id' ";
            }
            if($payment_term_id > 0){
                $clientQuery .=" and  c.payment_term = '$payment_term_id' ";
            }
            $clientQuery .=" order by c.fullname ASC  ";

        }else{

            $clientQuery = "select c.client_id,c.address , c.cell_no_1 ,c.fullname from client as c
                           where c.company_branch_id = '$company_id' ";
            if($customer_category_id > 0){
                $clientQuery .="  and  c.customer_category_id = '$customer_category_id' ";
            }

            if($payment_term_id > 0){
                $clientQuery .=" and  c.payment_term = '$payment_term_id' ";
            }
            $clientQuery .=" order by c.fullname ASC  ";

        }



        $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();

        $client_list = array();
        $client_list[] = 0;
        foreach ($clientResult as $value){
            $client_list[] =$value['client_id'];
        }

        $client_id_list = implode(",",$client_list);

        $product_rate_client_wise = getRateOfProduct::getCrudrole(implode(",",$client_list));

        $product_list = getRateOfProduct::getProductList();


        $product_list_price = array();
        foreach ($product_list as $value){
            $product_id = $value['product_id'];
            $product_list_price[$product_id] = $value['price'];
        }

        $product_quantity_client_wise = getRateOfProduct::product_quantity_client_wise_rate_quantity(implode(",",$client_list) ,$startDate,$endDate );



        //  $product_quantity_client_wise_total_sum = getRateOfProduct::product_quantity_client_wise_total_sum(implode(",",$client_list) ,$startDate,$endDate );


        $product_quantity_total = getRateOfProduct::product_quantity_total(implode(",",$client_list) ,$startDate,$endDate );

        $finalData = array();
        foreach($clientResult as $value){
            $oneObject = array();
            $client_id = $value['client_id'];
            $product_price = '';
            $product_quantity = '';
            $comma = false;

            $check_is_dlivered_any_quantity = false ;
            foreach ($product_list as $value_product){
                if($comma){
                    $product_price.=',';
                    $product_quantity.=',';
                }
                $comma = true ;
                $product_id = $value_product['product_id'];
                $number = $client_id.$product_id;

                // $product_price .=$value_product['name'].':';
                if(isset($product_rate_client_wise[$number])){
                    // $product_price.=$product_rate_client_wise[$number];
                }else{
                    // $product_price.= $product_list_price[$product_id] ;
                }

                // $product_quantity .=$value_product['name'].':';
                if(isset($product_quantity_client_wise[$number])){
                    $product_quantity.=$product_quantity_client_wise[$number]['quantity'];
                    $product_price.=$product_quantity_client_wise[$number]['rate'];

                    $check_is_dlivered_any_quantity = true ;
                }else{
                    $product_quantity.= '0' ;
                    $product_price.= '0' ;
                }

            }
            //   $client_id = '432';

            // echo $product_price;
            // die();

            $oneObject['product_list_rate'] = $product_price;
            // $oneObject['product_list_rate'] = 999999;
            $oneObject['product_quantity'] = $product_quantity;
            // $oneObject['product_quantity'] = '888888';
            $oneObject['client_id'] = $value['client_id'];
            $oneObject['address'] = $value['address'];
            $oneObject['cell_no_1'] = $value['cell_no_1'];
            $oneObject['fullname'] = $value['fullname'];

            $query_makePayment = "SELECT 
                    sum(pm.amount_paid) FROM payment_master as pm
                   where pm.client_id='$client_id' 
                       and pm.bill_month_date between
                       '$startDate' and '$endDate' ";

            $totalMake_Payment =  Yii::app()->db->createCommand($query_makePayment)->queryScalar();

            /*openeing*/
            /*========Start=========== */
            //  $queryDelivery2 ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
            // where d.client_id = $client_id AND d.date  <= '$endDate' ";

            if($company_id==1){
                $queryDelivery2 ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
                 where d.client_id = $client_id AND (d.date between '$start_calculation' and '$endDate') ";

            }else{
                $queryDelivery2 ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
                 where d.client_id = $client_id AND d.date < '$startDate'";

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
            //mit


            $CurrentMOnthDelivery = "Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
                                where d.client_id = $client_id AND  d.date between '$startDate' and '$endDate' ";




            $currentMonthResult = Yii::app()->db->createCommand($CurrentMOnthDelivery)->queryAll();

            $totaldeliverySum_current = $currentMonthResult[0]['deliverySum'];

            /* current month Delivery end*/
            /*   /*===========================================opening Delivery Start====================================================*/
            /*  $queryDelivery_opening ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
          where d.client_id = $client_id AND (d.date between '$start_calculation' and '$endDate_p') ";

              $deliveryResult_opening = Yii::app()->db->createCommand($queryDelivery_opening)->queryAll();

              $totaldeliverySum_opening = $deliveryResult_opening[0]['deliverySum'];*/


            $final_total_amount_opening = $totaldeliverySum2 - $totalRemaining2 ;


            /*opening month Delivery end*/


            $different_between =   $openingTotalBalance2 - $totalMake_Payment ;

            $oneObject['1'] = $totaldeliverySum2  ;
            $oneObject['2'] = $totalRemaining2  ;

            $oneObject['endDateBalance'] = $totalMake_Payment  ;

            // $oneObject['endDateBalance'] = '99999'  ;

            $oneObject['totaldeliverySum_current'] =$totaldeliverySum_current  ;
            $oneObject['final_total_amount_opening'] =$final_total_amount_opening  ;

            $oneObject['sum_opening_current'] =$final_total_amount_opening + $totaldeliverySum_current  ;

            $oneObject['totalMakePayment'] = $openingTotalBalance2;


            $oneObject['difference'] = 'line 218' ;

            if($totalMake_Payment == 0 ){
                $oneObject['color'] = 'red';
            }


            if($different_between > 0){

                $oneObject['balance'] = $different_between;
                $oneObject['difference'] = 0;
                $oneObject['color'] = 'blue';
                if($totalMake_Payment ==0){

                }
            }else{
                $oneObject['balance'] = 0;
                $oneObject['difference'] = $totalMake_Payment-$openingTotalBalance2;
                $oneObject['color'] = 'green';
            }

            if($different_between == 0){
                $oneObject['balance'] = 0;
                $oneObject['difference'] = 0;
                $oneObject['color'] = '	black';
            }

            if($totalMake_Payment == 0 ){
                $oneObject['color'] = 'red';
            }

            if( $oneObject['totaldeliverySum_current'] !=0 || $oneObject['totalMakePayment'] !=0 || $oneObject['endDateBalance']!=0  || $oneObject['balance'] !=0  ){

                if($check_is_dlivered_any_quantity){
                    $finalData[] =$oneObject ;
                }


            }else{

                // $finalData[] =$oneObject ;
            }
        }
        $cout_rate = 0;
        $sum_rate = 0;

        $company_id = Yii::app()->user->getState('company_branch_id');

        if($company_id==1){
            foreach($finalData as $value){
                $cout_rate = $cout_rate+1;
                $product_list_rate = $value['product_list_rate'];
                $sum_rate = $sum_rate +   $product_list_rate ;
            }
        }


        if($cout_rate>0){
            $vag_rate = ($sum_rate/$cout_rate);
        }else{
            $vag_rate = 0;
        }


        $finalRessult = array();
        $finalRessult['product_quantity_total'] = $product_quantity_total;
        $finalRessult['finalData'] = $finalData;
        $finalRessult['vag_rate'] = round($vag_rate,2);
        return json_encode($finalRessult);
    }

}