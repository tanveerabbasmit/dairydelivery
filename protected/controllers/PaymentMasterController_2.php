<?php

class PaymentMasterController extends Controller
{
    public function filters()
    {
        $user_id = Yii::app()->user->getState('user_id');
        if(!$user_id){
            $this->redirect(Yii::app()->user->returnUrl);
        }

        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    public function actionRecivePayment()
    {

        $company_id = Yii::app()->user->getState('company_branch_id');
        $this->render('RecivePayment',array(
            'riderList'=>json_encode(riderDailyStockData::getRiderList()),
            'company_id'=>$company_id,
        ));

    }

    public function actionlastReceipt()
    {


        $company_id = Yii::app()->user->getState('company_branch_id');

        $clientQuery = "select count(*) as total from client r
               where r.company_branch_id ='$company_id' ";

        $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();
        $clientResult =  $clientResult[0]['total'];

        $this->render('lastReceipt',array(
            'riderList_list'=>json_encode(riderDailyStockData::getRiderList()),
            'riderList'=>$clientResult,
            'company_id'=>$company_id,
        ));

    }
    public function actiongetRecivePaymentCustomer_LastReceiptCustomer(){


        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
         $today = date('Y-m-d H');
        date_default_timezone_set("Asia/Karachi");
       $company_id = Yii::app()->user->getState('company_branch_id');
         $clientQuery = " select c.client_id from client c
                    where c.company_branch_id ='$company_id' and c.is_active =1
                   limit 20 OFFSET $data";
        $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();
        $cliectId_array = array();
        $cliectId_array[] = 0;
        foreach ($clientResult as $value){
            $cliectId_array[] = $value['client_id'];
       }
        $client_ids = implode(', ',$cliectId_array);
       $clientQuery = " select c.client_id ,c.address , p.amount_paid ,c.fullname,p.date from payment_master as p
                      left join client as c ON c.client_id =p.client_id
                      where p.client_id in ($client_ids)
                       order  by p.payment_master_id DESC
                      ";
     $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();
        $paymentObject = array();
         $setClient = array();
        foreach ($clientResult as $value){
            $client_id = $value['client_id'];
             if(!isset($setClient[$client_id])){
                 $setClient[$client_id] =$client_id;
                 $oneObject = array();
                 $oneObject['client_id'] = $value['client_id'];
                 $oneObject['address'] = $value['address'];
                 $oneObject['amount_paid'] = $value['amount_paid'];
                 $oneObject['fullname'] = $value['fullname'];
                 $oneObject['date'] = $value['date'];
                $date = $value['date'];
                 $now = time(); // or your date as well
                 $your_date = strtotime($date);
                 $datediff = $now - $your_date;

                 $oneObject['days'] = round($datediff / (60 * 60 * 24));

                 $oneObject['balance'] =APIData::calculateFinalBalance($client_id);;
                 $paymentObject[] =$oneObject;
             }

        }

        echo json_encode($paymentObject);

    }


    public function actiongetRecivePaymentCustomer_searchCustomer(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

         $rider_id = $data['rider_id'];


        $today = date('Y-m-d H');

        date_default_timezone_set("Asia/Karachi");

        $company_id = Yii::app()->user->getState('company_branch_id');

       /*  $clientQuery = " select c.client_id from client c
                    where c.company_branch_id ='$company_id' and c.is_active =1
                   limit 20 OFFSET $data";*/

         $clientQuery = " Select c.is_active , c.client_type , c.client_id,c.address , c.cell_no_1 ,c.fullname ,z.name as zone_name from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id 
                           left join zone as z ON z.zone_id = c.zone_id
                           where rz.rider_id = $rider_id ";

        $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();

        $cliectId_array = array();
        $cliectId_array[] = 0;

        foreach ($clientResult as $value){
            $cliectId_array[] = $value['client_id'];
        }

         $client_ids = implode(', ',$cliectId_array);

        $clientQuery = " select c.client_id ,c.address , p.amount_paid ,c.fullname,p.date from payment_master as p
                      left join client as c ON c.client_id =p.client_id
                      where p.client_id in ($client_ids)
                       order  by p.payment_master_id DESC
                     
                         ";



        $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();

        $paymentObject = array();
         $setClient = array();
        foreach ($clientResult as $value){
            $client_id = $value['client_id'];
             if(!isset($setClient[$client_id])){
                 $setClient[$client_id] =$client_id;

                 $oneObject = array();
                 $oneObject['client_id'] = $value['client_id'];
                 $oneObject['address'] = $value['address'];
                 $oneObject['amount_paid'] = $value['amount_paid'];
                 $oneObject['fullname'] = $value['fullname'];
                 $oneObject['date'] = $value['date'];
                $date = $value['date'];


                 $now = time(); // or your date as well
                 $your_date = strtotime($date);
                 $datediff = $now - $your_date;

                 $oneObject['days'] = round($datediff / (60 * 60 * 24));

                 $oneObject['balance'] =APIData::calculateFinalBalance($client_id);;
                 $paymentObject[] =$oneObject;
             }

        }

        echo json_encode($paymentObject);

    }

    public function actiongetRecivePaymentCustomer(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $riderID =  $data['RiderID'];
        $startDate =  $data['startDate'];
        $endDate =  $data['endDate'];


        $clientQuery = "Select c.client_id,c.address , c.cell_no_1 ,c.fullname from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id 
                           where rz.rider_id = $riderID  
                            order by c.rout_order ASC ,c.fullname ASC ";

        $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();
        $cientID = array();
        $cientID[] = 0;
        foreach($clientResult as $value){
            $cientID[] =  $value['client_id'];
        }
        $lientID_list = implode(',',$cientID);

        $clientPaymentListQuery = "select sum(pm.amount_paid) as total_recive , pm.client_id ,pm.date,c.fullname ,c.cell_no_1 ,c.address from payment_master as pm
                        left join client as c ON c.client_id = pm.client_id
                        where pm.client_id in ($lientID_list) and pm.date between '$startDate' and '$endDate'
                        group by pm.client_id ";

        $clientPaymentListResult =  Yii::app()->db->createCommand($clientPaymentListQuery)->queryAll();

        echo   json_encode($clientPaymentListResult);



    }

    public function actionRiderWiseCustomerLedger(){


        $today_date = date("Y-m-d");
        $fiveDayAgo = date('Y-m-d', strtotime('-5 day', strtotime($today_date)));

        $monthNum  = date('m');

        $year = intval(date('Y'));

        $company_id = Yii::app()->user->getState('company_branch_id');

        if($company_id==15){

            $view_page ="RiderWiseCustomerLedger_noorMilk";

        }else{

            $view_page ="RiderWiseCustomerLedger";

        }


        $category_list = categoryData::getCategoryList();

        $this->render($view_page,array(
            'riderList'=>json_encode(riderDailyStockData::getRiderList()),
            'category_list'=>$category_list,
            'company_id'=>$company_id,
            'fiveDayAgo'=>$fiveDayAgo,
            'monthNum'=>$monthNum,
            'year'=>$year,
        ));

    }
    public function actionRiderWiseCustomerLedger_date_range_wise(){


        $today_date = date("Y-m-d");
        $fiveDayAgo = date('Y-m-d', strtotime('-5 day', strtotime($today_date)));

        $monthNum  = date('m');

        $year = intval(date('Y'));

        $company_id = Yii::app()->user->getState('company_branch_id');

        if($company_id==15){

            $view_page ="RiderWiseCustomerLedger_noorMilk";

        }else{

            $view_page ="RiderWiseCustomerLedger";

        }


        $category_list = categoryData::getCategoryList();



        $this->render('RiderWiseCustomerLedger_date_range_wise',array(
            'riderList'=>json_encode(riderDailyStockData::getRiderList()),
            'category_list'=>$category_list,
            'company_id'=>$company_id,
            'fiveDayAgo'=>$fiveDayAgo,
            'monthNum'=>$monthNum,
            'year'=>$year,
        ));

    }
    public function actionCategoryWiseCustomerDateRangeReport(){


        $today_date = date("Y-m-d");
        $fiveDayAgo = date('Y-m-d', strtotime('-5 day', strtotime($today_date)));
        $monthNum  = date('m');
        $year = intval(date('Y'));
        $company_id = Yii::app()->user->getState('company_branch_id');
        $query_category="SELECT z.*   from customer_category as z
                          where z.company_branch_id = '$company_id'";
        $queryResult_category =  Yii::app()->db->createCommand($query_category)->queryAll();

        $company_id = Yii::app()->user->getState('company_branch_id');
         if($company_id==1){
             $view_file = 'categoryWiseCustomerDateRangeReport_taza';
         }else{
             $view_file = 'categoryWiseCustomerDateRangeReport';
         }



        $this->render($view_file,array(
            'riderList'=>json_encode(riderDailyStockData::getRiderList()),
            'company_id'=>$company_id,
            'fiveDayAgo'=>$fiveDayAgo,
            'monthNum'=>$monthNum,
            'year'=>$year,
            'customerCategory'=>json_encode($queryResult_category),
            "payment_term"=>zoneData::get_payment_term(),
        ));
    }

    public function actioncategoryWiseCustomerLedger(){
        $today_date = date("Y-m-d");
        $fiveDayAgo = date('Y-m-d', strtotime('-5 day', strtotime($today_date)));
        $monthNum  = date('m');
        $year = intval(date('Y'));
        $company_id = Yii::app()->user->getState('company_branch_id');

        $query_category="SELECT z.*   from customer_category as z
                          where z.company_branch_id = '$company_id'";

        $queryResult_category =  Yii::app()->db->createCommand($query_category)->queryAll();

        $this->render('CategoryWiseCustomerLedger',array(
            'riderList'=>json_encode(riderDailyStockData::getRiderList()),
            'company_id'=>$company_id,
            'fiveDayAgo'=>$fiveDayAgo,
            'monthNum'=>$monthNum,
            'year'=>$year,
            'customerCategory'=>json_encode($queryResult_category),
        ));

    }

    public function actionRiderWiseDateRangeCustomerLedger(){



        $rider_id = $_GET['rider_id'];
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        $paid_month = $_GET['paid_month'];



        $today_date = date("Y-m-d");
        $fiveDayAgo = date('Y-m-d', strtotime('-5 day', strtotime($today_date)));
        $company_id = Yii::app()->user->getState('company_branch_id');
        $this->render('RiderWiseDateRangeCustomerLedger',array(
            'riderList'=>json_encode(riderDailyStockData::getRiderList()),
            'company_id'=>$company_id,
            'fiveDayAgo'=>$startDate,
            'endDate'=>json_encode($endDate),
            'rider_id'=>$rider_id,
            'paid_month'=>$paid_month,
        ));

    }

    public function actionDateRangeRiderledger(){
        $today_date = date("Y-m-d");
        $fiveDayAgo = date('Y-m-d', strtotime('-5 day', strtotime($today_date)));
        $company_id = Yii::app()->user->getState('company_branch_id');
        $this->render('DateRangeRiderledger',array(
            'riderList'=>json_encode(riderDailyStockData::getRiderList()),
            'company_id'=>$company_id,
            'fiveDayAgo'=>$fiveDayAgo,
        ));
    }

    public function actiondropCustomerList_getData(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $startDate = $data['startDate'];
        $endDate = $data['endDate'];
        $status = $data['client_type'];
        $deactive_reason_id = $data['deactive_reason_id'];

        $company_id = Yii::app()->user->getState('company_branch_id');

        $clientQuery = "select c.deactive_date, c.client_id , c.fullname ,c.address 
                , c.cell_no_1 ,c.deactive_reason from client as c
                where c.deactive_date between '$startDate' and '$endDate' 
                and c.is_active =0 and c.company_branch_id ='$company_id' ";

        if($status == 1){
            $clientQuery .= " and c.client_type = 1" ;
        }
        if($status == 2){
            $clientQuery .= "  and c.client_type = 2 " ;
        }

        if($deactive_reason_id > 0){
            $clientQuery .= "  and c.deactive_reason_id = '$deactive_reason_id'  " ;
        }


        $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();


         echo json_encode($clientResult);
    }
    public function actionDateRangeRiderledger_getData(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $startDate = $data['startDate'];
        $endDate = $data['endDate'];
        $paid_month = $data['paid_month'];

        $riderList = riderDailyStockData::getRiderList();

        $finalData = array();
        foreach($riderList as $riderValue){
            $RiderID =   $riderValue['rider_id'];

            $clientQuery = "Select c.client_id,c.address , c.cell_no_1 ,c.fullname ,z.name as zone_name from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id 
                           left join zone as z ON z.zone_id = c.zone_id
                           where rz.rider_id = $RiderID  ";
            $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();
            $clientIDListOBject = array();
            $clientIDListOBject[] = 0;
            foreach($clientResult as $clientValue){
                $clientIDListOBject[] = $clientValue['client_id'];
            }

            $clientIDList = implode(",",$clientIDListOBject);


            $oneObject = array();
            $totaldeliverySum = 0;
            $totalRemaining =0;
            $oneObject['rider_id']    =  $RiderID;
            $oneObject['fullname']    =  $riderValue['fullname'];

            $queryDelivery ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d

            where d.client_id in ($clientIDList)  AND d.date < '$startDate' ";

            $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
            $totaldeliverySum = $deliveryResult[0]['deliverySum'];
            $queryDelivery2 ="Select IFNULL(sum(pm.amount_paid) , 0) as remainingAmount from payment_master as pm
            where pm.client_id in ($clientIDList) AND pm.$paid_month < '$startDate' ";

            $deliveryResult2 = Yii::app()->db->createCommand($queryDelivery2)->queryAll();
            $totalRemaining = $deliveryResult2[0]['remainingAmount'];
            $openingTotalBalance = 0;
            $openingTotalBalance =  $totaldeliverySum - $totalRemaining ;

            $query_makeTotalDelivery = "select IFNULL(sum(d.total_amount) , 0) as totalSum from delivery as d
             where d.client_id in ($clientIDList) and d.date between '$startDate' and '$endDate'";

            $totalMake_Delivery =  Yii::app()->db->createCommand($query_makeTotalDelivery)->queryScalar();
            $oneObject['totalMakeDelivery'] = $totalMake_Delivery;

            $query_makePayment = "SELECT sum(pm.amount_paid) FROM payment_master as pm
                   where pm.client_id in ($clientIDList) and pm.$paid_month between '$startDate' and '$endDate'";

             /* echo $query_makePayment ;
               die();*/
            $totalMake_Payment =  Yii::app()->db->createCommand($query_makePayment)->queryScalar();
            $oneObject['balance'] = ($openingTotalBalance + $totalMake_Delivery) - $totalMake_Payment;
            $oneObject['totalMakePayment'] = $totalMake_Payment;
            $oneObject['totaldeliverySum'] = $totaldeliverySum;
            $oneObject['totalRemaining'] = $totalRemaining;

            $oneObject['OpeningBlance'] = $openingTotalBalance;

            $finalData[] = $oneObject ;
        }

        echo json_encode($finalData);
    }
    public function actiongetCustomerLedger_working(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $company_id = Yii::app()->user->getState('company_branch_id');
       // $productList=productData::getproductList($page =false);
        if($company_id == 1){
            $start_calculation ='2018-01-01';
        }else{
            $start_calculation ='2016-01-01';
        }
        $year = $data['year'];

        $monthNum = $data['monthNum'];
        $startDate = $year.'-'.$monthNum.'-01';
        $endDate = $year.'-'.$monthNum.'-31';

        if($monthNum >1){
            $previousMonth = $monthNum - 1;
            $endDate_p = $year.'-'.$previousMonth.'-31';
        }else{
            $year_P = $year-1;
            $endDate_p = $year_P.'-12-31';
        }
        $RiderID = $data['RiderID'];
        $company_id = Yii::app()->user->getState('company_branch_id');
        if($RiderID >0){
            $clientQuery = "Select c.client_id,c.address , c.cell_no_1 ,c.fullname ,z.name as zone_name from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id 
                           left join zone as z ON z.zone_id = c.zone_id
                           where rz.rider_id = $RiderID   
                            order by c.fullname ASC  ";
        }else{
            $clientQuery = "select c.client_id,c.address , c.cell_no_1 ,c.fullname from client as c
                           where c.company_branch_id = '$company_id'    
                           order by c.fullname ASC ";
        }


    }


    public static function get_main_grand_total($finalData){


        /*find Grand Total*/

        $total_customer_list = sizeof($finalData);
        $grand_grandproduct_list_rate =0;
        $grand_product_quantity =0;
        $grand_endDateBalance =0;
        $grand_totaldeliverySum_current =0;
        $grand_final_total_amount_opening =0;
        $grand_sum_opening_current =0;
        $grand_totalMakePayment =0;
        $grand_difference =0;
        $grand_balance =0;

        foreach($finalData as $value){

            $grand_grandproduct_list_rate = $grand_grandproduct_list_rate + $value['product_list_rate'];
            $grand_product_quantity = $grand_product_quantity + $value['product_quantity'];
            $grand_endDateBalance = $grand_endDateBalance + $value['endDateBalance'];
            $grand_endDateBalance = $grand_endDateBalance +  $value['endDateBalance'];
            $grand_totaldeliverySum_current = $grand_totaldeliverySum_current + $value['totaldeliverySum_current'];
            $grand_final_total_amount_opening = $grand_final_total_amount_opening + $value['final_total_amount_opening'];
            $grand_sum_opening_current = $grand_sum_opening_current + $value['sum_opening_current'];
            $grand_sum_opening_current = $grand_sum_opening_current + $value['sum_opening_current'];
            $grand_totalMakePayment = $grand_totalMakePayment + $value['totalMakePayment'];
            $grand_totalMakePayment = $grand_totalMakePayment + $value['totalMakePayment'];
            $grand_difference = $grand_difference + $value['difference'];
            $grand_difference = $grand_difference + $value['difference'];
            $grand_balance = $grand_balance + $value['balance'];
        }
        $main_grand_total = array();
        $main_grand_total['grand_grandproduct_list_rate'] = $grand_grandproduct_list_rate/$total_customer_list ;
        $main_grand_total['grand_product_quantity'] = $grand_product_quantity ;
        $main_grand_total['grand_endDateBalance'] = $grand_endDateBalance ;
        $main_grand_total['grand_endDateBalance'] = $grand_endDateBalance ;
        $main_grand_total['grand_totaldeliverySum_current'] = $grand_totaldeliverySum_current ;
        $main_grand_total['grand_final_total_amount_opening'] = $grand_final_total_amount_opening ;
        $main_grand_total['grand_sum_opening_current'] = $grand_sum_opening_current ;
        $main_grand_total['grand_sum_opening_current'] = $grand_sum_opening_current ;
        $main_grand_total['grand_totalMakePayment'] = $grand_totalMakePayment ;
        $main_grand_total['grand_totalMakePayment'] = $grand_totalMakePayment ;
        $main_grand_total['grand_difference'] = $grand_difference ;
        $main_grand_total['grand_difference'] = $grand_difference ;
        $main_grand_total['grand_balance'] = $grand_balance ;
        return $main_grand_total ;
    }

    public function actiongetCustomerLedger_dateRangeCustomerReport(){


        //jkj
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
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

            $query_makePayment = "SELECT sum(pm.amount_paid) FROM payment_master as pm
                   where pm.client_id='$client_id' and month(pm.bill_month_date)='$monthNum'  and year(pm.bill_month_date) = '$year'";

            $totalMake_Payment =  Yii::app()->db->createCommand($query_makePayment)->queryScalar();

            /*openeing*/
            /*========Start=========== */
            //  $queryDelivery2 ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
            // where d.client_id = $client_id AND d.date  <= '$endDate' ";
            $queryDelivery2 ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
             where d.client_id = $client_id AND (d.date between '$start_calculation' and '$endDate') ";

            $deliveryResult2 = Yii::app()->db->createCommand($queryDelivery2)->queryAll();
            $totaldeliverySum2 = $deliveryResult2[0]['deliverySum'];
            $queryDelivery2 ="Select IFNULL(sum(pm.amount_paid) , 0) as remainingAmount from payment_master as pm
              where pm.client_id = $client_id AND (pm.bill_month_date  between  '$start_calculation' and '$endDate_p') ";
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
                                where d.client_id = $client_id AND  month(d.date)='$monthNum'  and year(d.date) = '$year'";

            $currentMonthResult = Yii::app()->db->createCommand($CurrentMOnthDelivery)->queryAll();

            $totaldeliverySum_current = $currentMonthResult[0]['deliverySum'];

            /* current month Delivery end*/
            /*===========================================opening Delivery Start====================================================*/
            $queryDelivery_opening ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
             where d.client_id = $client_id AND (d.date between '$start_calculation' and '$endDate_p') ";

            $deliveryResult_opening = Yii::app()->db->createCommand($queryDelivery_opening)->queryAll();

            $totaldeliverySum_opening = $deliveryResult_opening[0]['deliverySum'];


            $final_total_amount_opening = $totaldeliverySum_opening - $totalRemaining2 ;


            /*opening month Delivery end*/


            $different_between =   $openingTotalBalance2 - $totalMake_Payment ;

            $oneObject['1'] = $totaldeliverySum_opening  ;
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
        echo json_encode($finalRessult);
    }
    public function actiongetCustomerLedger_category(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $year = $data['year'];
        $monthNum = $data['monthNum'];
        $startDate = $year.'-'.$monthNum.'-01';
        $endDate = $year.'-'.$monthNum.'-31';

        $RiderID = $data['RiderID'];
       $customer_category_id = $data['customer_category_id'];

        /*==============for Category===================*/
        $company_id = Yii::app()->user->getState('company_branch_id');

        /*==============for Category===================*/

        $company_id = Yii::app()->user->getState('company_branch_id');

          if($customer_category_id ==0){

              $query_category="SELECT z.*   from customer_category as z
                          where z.company_branch_id = '$company_id'";

          }else{

              $query_category="SELECT z.*   from customer_category as z
               where z.company_branch_id = '$company_id' and z.customer_category_id ='$customer_category_id'";
          }



        $queryResult_category =  Yii::app()->db->createCommand($query_category)->queryAll();


        /*zone get*/
        $query_zone="SELECT z.* from zone as z
                              where z.company_branch_id = $company_id
                               order by z.name ASC ";
        $queryResult_zone =  Yii::app()->db->createCommand($query_zone)->queryAll();


        $finalObject = array();



        foreach($queryResult_category as $ctegoryValue) {

            $category_id = $ctegoryValue['customer_category_id'];
            $category_name = $ctegoryValue['category_name'];

            $one_Category_object = array();
            $one_Category_object['category_name'] = $category_name;

            $clientQuery = "Select c.client_id,c.address , c.cell_no_1 ,c.fullname ,z.name as zone_name from rider_zone as rz
                   Right join client as c ON c.zone_id = rz.zone_id 
                   left join zone as z ON z.zone_id = c.zone_id
                     ";

            if($customer_category_id ==0){
                $clientQuery .= " where rz.rider_id = $RiderID  AND c.is_active = 1 and c.customer_category_id ='$category_id' ";
            }else{
                $clientQuery .= "   where c.customer_category_id = $customer_category_id  AND c.is_active = 1 and c.customer_category_id ='$category_id' ";
            }
            $clientQuery .= "    group by  c.client_id  order by c.rout_order ASC ,c.fullname ASC ";

            $clientResult = Yii::app()->db->createCommand($clientQuery)->queryAll();

            $client_Object =array();
            foreach ($clientResult as $value) {

                    $oneObject = array();
                    $client_id = $value['client_id'];
                    $oneObject['client_id'] = $value['client_id'];
                    $oneObject['address'] = $value['address'];
                    $oneObject['cell_no_1'] = $value['cell_no_1'];
                    $oneObject['fullname'] = $value['fullname'];
                    $query_makePayment = "SELECT sum(pm.amount_paid) FROM payment_master as pm
                    where pm.client_id='$client_id' and month(pm.bill_month_date)='$monthNum'  and year(pm.bill_month_date) = '$year'";
                    $totalMake_Payment = Yii::app()->db->createCommand($query_makePayment)->queryScalar();

                    $queryDelivery2 = "Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
                                where d.client_id = $client_id AND d.date  <= '$endDate' ";

                    $deliveryResult2 = Yii::app()->db->createCommand($queryDelivery2)->queryAll();
                    $totaldeliverySum2 = $deliveryResult2[0]['deliverySum'];

                    $queryDelivery2 = "Select IFNULL(sum(pm.amount_paid) , 0) as remainingAmount from payment_master as pm
                               where pm.client_id = $client_id AND pm.date <= '$endDate' ";
                    $deliveryResult2 = Yii::app()->db->createCommand($queryDelivery2)->queryAll();
                    $totalRemaining2 = $deliveryResult2[0]['remainingAmount'];
                    $openingTotalBalance2 = $totaldeliverySum2 - $totalRemaining2;
                    $different_between = $openingTotalBalance2 - $totalMake_Payment;
                    $oneObject['endDateBalance'] = $totalMake_Payment;
                    $oneObject['totalMakePayment'] = $openingTotalBalance2;

                    if ($different_between > 0) {
                        $oneObject['balance'] = $different_between;
                        $oneObject['difference'] = 0;
                        $oneObject['color'] = '#0000FF';
                    } else {
                        $oneObject['balance'] = 0;
                        $oneObject['difference'] = $totalMake_Payment - $openingTotalBalance2;
                        $oneObject['color'] = '';
                    }
                $client_Object[] = $oneObject ;
            }
            $one_Category_object['customer'] = $client_Object;

            if(sizeof($one_Category_object['customer']) >0){
                $finalObject[] = $one_Category_object;
            }
        }

        echo json_encode($finalObject);
        die();
    }
    public function actiongetCustomerLedger_category_testing(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $year = '2018';
        $monthNum = '03';
        $startDate = $year.'-'.$monthNum.'-01';
        $endDate = $year.'-'.$monthNum.'-31';
        $RiderID = $data['RiderID'];

        /*==============for Category===================*/
        $company_id = Yii::app()->user->getState('company_branch_id');
        $query_category="SELECT z.*   from customer_category as z
                          LEFT JOIN company_branch as cb ON cb.company_branch_id = z.company_branch_id
                             where z.customer_category_id = '$company_id'";
        $queryResult_category =  Yii::app()->db->createCommand($query_category)->queryAll();
        /*zone get*/
        $query_zone="SELECT z.* from zone as z
                              where z.company_branch_id = $company_id
                               order by z.name ASC ";
        $queryResult_zone =  Yii::app()->db->createCommand($query_zone)->queryAll();


        $finalObject = array();

        foreach($queryResult_category as $ctegoryValue){

            $category_id = $ctegoryValue['customer_category_id'];
            $category_name = $ctegoryValue['category_name'];
            foreach($queryResult_zone as $valueZOne){

                $zone_id = $valueZOne['zone_id'];
                $zone_name = $valueZOne['name'];

                echo   $clientQuery = "Select c.client_id,c.address , c.cell_no_1 ,c.fullname ,z.name as zone_name from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id 
                           left join zone as z ON z.zone_id = c.zone_id
                           where rz.rider_id = $RiderID  AND c.is_active = 1 and c.customer_category_id ='$category_id' and c.zone_id ='$zone_id'
                            order by c.rout_order ASC ,c.fullname ASC ";
                die();
                $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();
                foreach($clientResult as $value) {
                    $oneObject = array();
                    $client_id = $value['client_id'];
                    $oneObject['client_id'] = $value['client_id'];
                    $oneObject['address'] = $value['address'];
                    $oneObject['cell_no_1'] = $value['cell_no_1'];
                    $oneObject['fullname'] = $value['fullname'];
                    $query_makePayment = "SELECT sum(pm.amount_paid) FROM payment_master as pm
                              where pm.client_id='$client_id' and month(pm.bill_month_date)='$monthNum'  and year(pm.bill_month_date) = '$year'";
                    $totalMake_Payment =  Yii::app()->db->createCommand($query_makePayment)->queryScalar();


                    $queryDelivery2 ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
                                where d.client_id = $client_id AND d.date  <= '$endDate' ";


                    $deliveryResult2 = Yii::app()->db->createCommand($queryDelivery2)->queryAll();
                    $totaldeliverySum2 = $deliveryResult2[0]['deliverySum'];

                    $queryDelivery2 ="Select IFNULL(sum(pm.amount_paid) , 0) as remainingAmount from payment_master as pm
                               where pm.client_id = $client_id AND pm.date <= '$endDate' ";

                    $deliveryResult2 = Yii::app()->db->createCommand($queryDelivery2)->queryAll();
                    $totalRemaining2 = $deliveryResult2[0]['remainingAmount'];

                    $openingTotalBalance2 =  $totaldeliverySum2 - $totalRemaining2 ;


                    $different_between =   $openingTotalBalance2 - $totalMake_Payment ;
                    $oneObject['endDateBalance'] = $totalMake_Payment ;
                    $oneObject['totalMakePayment'] = $openingTotalBalance2;

                    if($different_between > 0){
                        $oneObject['balance'] = $different_between;
                        $oneObject['difference'] = 0;
                        $oneObject['color'] = '#0000FF';
                    }else{
                        $oneObject['balance'] = 0;
                        $oneObject['difference'] = $totalMake_Payment-$openingTotalBalance2;
                        $oneObject['color'] = '';
                    }
                    var_dump($oneObject);
                    die();
                }
            }



        }

        die();

        /*==============for Category===================*/

        $RiderID = $data['RiderID'];
        $clientQuery = "Select c.client_id,c.address , c.cell_no_1 ,c.fullname ,z.name as zone_name from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id 
                           left join zone as z ON z.zone_id = c.zone_id
                           where rz.rider_id = $RiderID  AND c.is_active = 1
                            order by c.rout_order ASC ,c.fullname ASC ";
        $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();
        $finalData = array();
        foreach($clientResult as $value){
            $oneObject = array();
            $client_id = $value['client_id'];
            $oneObject['client_id'] = $value['client_id'];
            $oneObject['address'] = $value['address'];
            $oneObject['cell_no_1'] = $value['cell_no_1'];
            $oneObject['fullname'] = $value['fullname'];
            $query_makePayment = "SELECT sum(pm.amount_paid) FROM payment_master as pm
                   where pm.client_id='$client_id' and month(pm.bill_month_date)='$monthNum'  and year(pm.bill_month_date) = '$year'";
            $totalMake_Payment =  Yii::app()->db->createCommand($query_makePayment)->queryScalar();
            /*openeing*/


            /*========Start=========== */

            $queryDelivery2 ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
            where d.client_id = $client_id AND d.date  <= '$endDate' ";


            $deliveryResult2 = Yii::app()->db->createCommand($queryDelivery2)->queryAll();
            $totaldeliverySum2 = $deliveryResult2[0]['deliverySum'];

            $queryDelivery2 ="Select IFNULL(sum(pm.amount_paid) , 0) as remainingAmount from payment_master as pm
             where pm.client_id = $client_id AND pm.date <= '$endDate' ";
            $deliveryResult2 = Yii::app()->db->createCommand($queryDelivery2)->queryAll();
            $totalRemaining2 = $deliveryResult2[0]['remainingAmount'];

            $openingTotalBalance2 =  $totaldeliverySum2 - $totalRemaining2 ;


            //tan


            $queryDelivery ="Select IFNULL(sum(pm.amount_paid) , 0) as remainingAmount from payment_master as pm
            where pm.client_id = $client_id AND pm.date <= '$endDate' ";

            $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();

            $totalRemaining = $deliveryResult[0]['remainingAmount'];
            $openingTotalBalance =  $totaldeliverySum2 - $totalRemaining ;

            /*openeing*/
            $different_between =   $openingTotalBalance2 - $totalMake_Payment ;
            $oneObject['endDateBalance'] = $totalMake_Payment ;
            $oneObject['totalMakePayment'] = $openingTotalBalance2;
            $oneObject['difference'] = 'line 218' ;
            if($different_between > 0){
                $oneObject['balance'] = $different_between;
                $oneObject['difference'] = 0;
                $oneObject['color'] = '#0000FF';
            }else{
                $oneObject['balance'] = 0;
                $oneObject['difference'] = $totalMake_Payment-$openingTotalBalance2;
                $oneObject['color'] = '';
            }
            $finalData[] =$oneObject ;

        }

        echo json_encode($finalData);
    }
    public function actiongetCustomerLedger_copy(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $startDate = $data['startDate'];
        $endDate = $data['endDate'];
        $RiderID = $data['RiderID'];

        $company_id = Yii::app()->user->getState('company_branch_id');
        if($RiderID >0){
            $clientQuery = "Select c.client_id,c.address , c.cell_no_1 ,c.fullname ,z.name as zone_name from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id 
                           left join zone as z ON z.zone_id = c.zone_id
                           where rz.rider_id = $RiderID  AND c.is_active = 1
                            order by c.rout_order ASC ,c.fullname ASC ";
        }else{

            $clientQuery = "select c.client_id,c.address , c.cell_no_1 ,c.fullname from client as c
                           where c.company_branch_id = '$company_id'  AND c.is_active = 1
                           order by c.rout_order ASC ,c.fullname ASC ";

        }

        $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();
        $finalData = array();
        foreach($clientResult as $value){
            $oneObject = array();
            $client_id = $value['client_id'];
            $oneObject['client_id'] = $value['client_id'];
            $oneObject['address'] = $value['address'];
            $oneObject['cell_no_1'] = $value['cell_no_1'];
            $oneObject['fullname'] = $value['fullname'];
            $oneObject['balance'] = APIData::calculateFinalBalance($value['client_id']);

            $query_makePayment = "SELECT sum(pm.amount_paid) FROM payment_master as pm
                   where pm.client_id='$client_id' and pm.date BETWEEN '$startDate' and '$endDate'";


            $totalMake_Payment =  Yii::app()->db->createCommand($query_makePayment)->queryScalar();
            /*openeing*/
            $queryDelivery ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
            where d.client_id = $client_id AND d.date <= '$endDate' ";
            $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
            $totaldeliverySum = $deliveryResult[0]['deliverySum'];

            $queryDelivery ="Select IFNULL(sum(pm.amount_paid) , 0) as remainingAmount from payment_master as pm
            where pm.client_id = $client_id AND pm.date <= '$endDate' ";

            $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();

            $totalRemaining = $deliveryResult[0]['remainingAmount'];



            $openingTotalBalance =  $totaldeliverySum - $totalRemaining ;

            /*openeing*/

            $oneObject['endDateBalance'] = $openingTotalBalance;
            $oneObject['difference'] = $oneObject['balance'] -$openingTotalBalance ;
            if($totalMake_Payment){
                $oneObject['totalMakePayment'] = $totalMake_Payment;
                $oneObject['color'] = '#0000FF';
            }else{
                $oneObject['totalMakePayment'] = $totalMake_Payment;
                $oneObject['color'] = '';
            }

            $finalData[] =$oneObject ;

        }

        echo json_encode($finalData);
    }

    public function actionOneRiderCustomer(){

    }

    public function actiongetDateRangeCustomerLedger(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $startDate = $data['startDate'];
        $endDate = $data['endDate'];
        $RiderID = $data['RiderID'];
        $paid_month = $data['paid_month'];
        $payment_term_id = $data['payment_term_id'];




        $clientQuery = "Select c.client_id,c.address , c.cell_no_1 ,c.fullname ,z.name as zone_name from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id 
                           left join zone as z ON z.zone_id = c.zone_id
                           where rz.rider_id = $RiderID  ";

        if($payment_term_id>0){
            $clientQuery .= " 	AND c.payment_term ='$payment_term_id' ";
        }

        $clientQuery .= "  order by c.rout_order ASC ,c.fullname ASC ";



        $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();
        $finalData = array();
        foreach($clientResult as $value){
            $oneObject = array();
            $client_id = $value['client_id'];
            $oneObject['client_id'] = $value['client_id'];
            $oneObject['address'] = $value['address'];
            $oneObject['cell_no_1'] = $value['cell_no_1'];
            $oneObject['fullname'] = $value['fullname'];


            $queryDelivery ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
            where d.client_id = $client_id AND d.date < '$startDate' ";
            $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
            $totaldeliverySum = $deliveryResult[0]['deliverySum'];

            $queryDelivery ="Select IFNULL(sum(pm.amount_paid) , 0) as remainingAmount from payment_master as pm
            where pm.client_id = $client_id AND pm.$paid_month < '$startDate' ";
            $deliveryResult = Yii::app()->db->createCommand($queryDelivery)->queryAll();
            $totalRemaining = $deliveryResult[0]['remainingAmount'];
            $openingTotalBalance =  $totaldeliverySum - $totalRemaining ;
            $oneObject['OpeningBlance'] = $openingTotalBalance;
            // $oneObject['balance'] = APIData::calculateFinalBalance($value['client_id']);
            $query_makePayment = "SELECT sum(pm.amount_paid) FROM payment_master as pm
                   where pm.client_id='$client_id' and pm.$paid_month between '$startDate' and '$endDate'";

            $totalMake_Payment =  Yii::app()->db->createCommand($query_makePayment)->queryScalar();

            $query_makeTotalDelivery = "select IFNULL(sum(d.total_amount) , 0) as totalSum from delivery as d
             where d.client_id ='$client_id' and d.date between '$startDate' and '$endDate'";

            $totalMake_Delivery =  Yii::app()->db->createCommand($query_makeTotalDelivery)->queryScalar();
            $oneObject['balance'] = ($openingTotalBalance + $totalMake_Delivery) - $totalMake_Payment;
            $oneObject['totalMakeDelivery'] = $totalMake_Delivery;

            if($totalMake_Payment){
                $oneObject['totalMakePayment'] = $totalMake_Payment;
                $oneObject['color'] = '#0000FF';
            }else{
                $oneObject['totalMakePayment'] = $totalMake_Payment;
                $oneObject['color'] = '';
            }

            $finalData[] =$oneObject ;

        }

        echo json_encode($finalData);
    }

    public function actiongetCustomerLedger(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $company_id = Yii::app()->user->getState('company_branch_id');
        $productList=productData::getproductList($page =false);
        if($company_id == 1){
            $start_calculation ='2018-01-01';
        }else{
            $start_calculation ='2016-01-01';
        }
        $year = $data['year'];
        $monthNum = $data['monthNum'];
        $startDate = $year.'-'.$monthNum.'-01';
        $endDate = $year.'-'.$monthNum.'-31';
        if($monthNum >1){
            $previousMonth = $monthNum - 1;
            $endDate_p = $year.'-'.$previousMonth.'-31';
        }else{
            $year_P = $year-1;
            $endDate_p = $year_P.'-12-31';
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
                            z.name as zone_name 
                            from rider_zone as rz
                            Right join client as c ON c.zone_id = rz.zone_id 
                            left join zone as z ON z.zone_id = c.zone_id
                            where rz.rider_id = $RiderID    
                            order by c.fullname ASC  ";

        }elseif($customer_category_id>0){
            $clientQuery ="SELECT 
                c.client_id,
                c.address ,
                c.cell_no_1 ,
                c.fullname ,
                z.name as zone_name 
                FROM  client AS c
                LEFT JOIN zone AS z ON z.zone_id =c.zone_id
                WHERE c.customer_category_id ='$customer_category_id'
                AND c.company_branch_id  = '$company_id' ";
        } else{

            $clientQuery = "select c.client_id,c.address , c.cell_no_1 ,c.fullname from client as c
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

        foreach($clientResult as $value){
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

            $query_makePayment = "SELECT sum(pm.amount_paid) as amount_paid ,u.full_name,r.fullname FROM payment_master as pm
                                  LEFT JOIN     user AS u ON u.user_id = pm.user_id
                                  LEFT JOIN rider AS r ON r.rider_id = pm.rider_id AND r.rider_id !=0
                                  where pm.client_id='$client_id' and month(pm.bill_month_date)='$monthNum' 
                                  and year(pm.bill_month_date) = '$year'";
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



            $queryDelivery2 ="Select IFNULL(sum(pm.amount_paid) , 0) as remainingAmount from payment_master as pm
              where pm.client_id = $client_id AND (pm.bill_month_date  between  '$start_calculation' and '$endDate_p') ";

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
                                where d.client_id = $client_id AND  month(d.date)='$monthNum'  and year(d.date) = '$year'";
            $currentMonthResult = Yii::app()->db->createCommand($CurrentMOnthDelivery)->queryAll();
            $totaldeliverySum_current = $currentMonthResult[0]['deliverySum'];

            /* current month Delivery end*/
            /*===========================================opening Delivery Start====================================================*/

            if($company_id==1){
                $queryDelivery_opening ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
                  where d.client_id = $client_id AND (d.date between '$start_calculation' and '$endDate_p') ";

            }else{
                $queryDelivery_opening ="Select IFNULL(sum(d.total_amount),0) as deliverySum from delivery as d
                  where d.client_id = $client_id AND d.date  <= '$endDate_p' ";

            }



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
        echo json_encode($result);
    }

    public function actiongetCustomerLedger_date_range_wise(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        
      

        echo recovery_data_between_date_range::get_recovery_data($data);


    }


}