<?php

class AccountingController extends Controller
{
    public function filters(){
        $company_id = Yii::app()->user->getState('company_branch_id');
        $company_object = Company::model()->findByPk($company_id);
        if($company_object['show_accounting']==0){
             die('You are not Allowed');
        }

    }
	public function actionproduct_sale()
	{
        date_default_timezone_set("Asia/Karachi");
        $today_date = date("Y-m-d");


        $data = productData::getproductList(0);



        $this->render('product_sale',array(
            'productList'=>$data,
            'productCount'=>json_encode([]),
            'today_date'=>json_encode($today_date),


        ));
	}


	public function actionbasic_get_today_sale(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $data = CJSON::decode($post, TRUE);

         $start_date = $data['start_date'];
         $product_id = $data['product_id'];

        $company_branch_id = Yii::app()->user->getState('company_branch_id');

        $query = "	SELECT 
            d.client_id, 
            dd.quantity,
            dd.amount,
            dd.product_id,
            d.date,
            p.name,
            c.fullname 
            FROM delivery AS d 
            LEFT JOIN delivery_detail AS dd
            ON d.delivery_id =dd.delivery_id
            LEFT JOIN product AS p 
            ON dd.product_id = p.product_id 
            LEFT JOIN client AS c ON c.client_id =d.client_id  
            WHERE  d.company_branch_id ='$company_branch_id'
            AND d.date ='$start_date' and dd.product_id ='$product_id' ";



        $result =  Yii::app()->db->createCommand($query)->queryAll();


        $data =[];
        $total = 0;
        foreach ($result as $value){
            $total = $total +  $value['amount'];
        }

        $data['result'] =$result;
        $data['total'] =$total;

        echo json_encode($data);


    }

    public static function actionbasic_save_vocher(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $product_sale_account_id =$data['product_sale_account_id'];
        $product_receivable_account_id =$data['product_receivable_account_id'];
        $total =$data['total'];
        $today_date =$data['today_date'];
        $product_id =$data['product_id'];


        $token = accounting_data::auth_token();

        $token_data = json_decode($token ,true);

        $token =  $token_data['data']['token'];


        $ch = curl_init();

        $debit_and_cridit_account =   accounting_data::get_assign_account_function(1);

        if($debit_and_cridit_account){

            $debit_account_id = ['debit_account_id'];
            $credit_account_id = ['credit_account_id'];


        }else{
            $responce_message =[];
            $responce_message['success'] =false;
            $responce_message['message'] ='Account are not assigned';
            echo json_encode($responce_message);
             die();
        }

        $accunt_one =['account_id'=>$credit_account_id,'amount'=>$total];

        $accunt_two =['account_id'=>$debit_account_id,'amount'=>-($total)];


        $data =[$accunt_one , $accunt_two];




       $voucher_details = json_encode($data)  ;


        // Set the url and data

        curl_setopt($ch, CURLOPT_URL, "https://jotter.logic-zone.net/demo/api/vouchers?ver=v1.2&");

        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_POSTFIELDS, "__businessId=14&__fiscalYearId=14&type=5&trans_date=$today_date&party_id=&account_id=&reference=2&narration=23&hash_amount=$total&voucher_details=$voucher_details&is_active=1");


        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' .$token,
        ]);

// Set other options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute
        $data = curl_exec($ch);

// Close connection
        curl_close($ch);

// Print the data...
      // print_r($data);

        $responce = json_decode($data,true);

       if($responce['success']){

            $id = $responce['data'];

            accounting_data::save_jv_with_sale($today_date,$product_id ,$id);

          // $today_date


       }

        $responce_message =[];
        $responce_message['success'] =true;
        $responce_message['message'] ='Account are not assigned';
        echo json_encode($responce_message);
        die();
    }
    public function actionReceipt_from_customer(){
        $company_id = Yii::app()->user->getState('company_branch_id');

        $this->render('receipt_from_customer',array(
            'riderList'=>json_encode(riderDailyStockData::getRiderList()),
            'company_id'=>$company_id,
        ));
    }

    public function actionget_today_recovery_report_data(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);


        $startDate  =  $data['startDate'];
        $endDate  =  $data['endDate'];
        $RiderID = $data['RiderID'];
        $payment_mode = $data['payment_mode'];
        $payment_type_id = $data['payment_type_id'];

        $rider_user_object =   riderData::get_user_rider_name();

        $user_object = $rider_user_object['user_object'];
        $rider_object = $rider_user_object['rider_object'];


        $company_id = Yii::app()->user->getState('company_branch_id');
        if($payment_type_id==1){
            if($RiderID >0){

                $clientQuery = "Select c.client_id,c.address , c.cell_no_1 ,c.fullname ,z.name as zone_name from rider_zone as rz
                           Right join client as c ON c.zone_id = rz.zone_id 
                           left join zone as z ON z.zone_id = c.zone_id
                           where rz.rider_id = $RiderID  
                            order by c.fullname ASC ";
            }else{

                $clientQuery = "select c.client_id,c.address , c.cell_no_1 ,c.fullname from client as c
                           where c.company_branch_id = '$company_id'  
                           order by c.fullname ASC ";


            }
            $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();

            $client_array = [];
            $client_array[]= '-1';
            foreach ($clientResult as $value){
                $client_id =  $value['client_id'];
                $client_array[] = $client_id;
            }

            $client_ids = implode(',',$client_array);

            $payment_list = daily_recovery_report_data::get_payment_of_client_list_of_one_date($client_ids,$data);



            $finalData = array();
            $count = 0;
            $totol_discount = 0;


            foreach($clientResult as $value) {

                $oneObject = array();
                $client_id = $value['client_id'];
                $oneObject['client_id'] = $value['client_id'];
                $oneObject['address'] = $value['address'];
                $oneObject['fullname'] = $value['fullname'];

                $query_makePayment = "SELECT pm.date , pm.payment_master_id, pm.user_id,
                   pm.rider_id,pm.reference_number,
                    pm.payment_mode ,IFNULL((pm.amount_paid) ,0)  as amount_paid FROM payment_master as pm
                   where pm.client_id='$client_id' and pm.date ='$startDate' ";
                if($payment_mode>0){
                    $query_makePayment .=" and pm.payment_mode='$payment_mode' ";
                }

                $totalMake_object = [];

                if(isset($payment_list[$client_id])){

                    $totalMake_object = $payment_list[$client_id];
                }



                foreach ($totalMake_object as $value){

                    $totalMake_Payment = $value['amount_paid'];
                    $payment_mode_new =$value['payment_mode'];
                    $payment_master_id =$value['payment_master_id'];

                    $date =$value['date'];


                    $payment_master_id = $value['payment_master_id'];
                    $reference_number = $value['reference_number'];


                    $oneObject['amountpaid'] = $totalMake_Payment;
                    $oneObject['reference_number'] = $reference_number;

                    $count = $count +  $totalMake_Payment;
                    if($totalMake_Payment >0){
                        if($payment_mode_new==2){
                            $oneObject['payment_mode'] ="Check";
                        }elseif($payment_mode_new==3){
                            $oneObject['payment_mode'] ="Cash";
                        }elseif($payment_mode_new==5){
                            $oneObject['payment_mode'] ="Bank Transaction";
                        }elseif($payment_mode_new==6){
                            $oneObject['payment_mode'] ="Card Transaction";
                        }else{
                            $oneObject['payment_mode'] = "Other";
                        }


                        $user_id =$value['user_id'];
                        $rider_id =$value['rider_id'];

                        if($user_id>0){
                            if(isset($user_object[$user_id])){
                                $oneObject['payment_user_name'] = $user_object[$user_id];
                            }
                        }
                        if($rider_id>0){
                            if(isset($rider_object[$rider_id])){
                                $oneObject['payment_rider_name'] = $rider_object[$rider_id];
                            }
                        }

                        $oneObject['date'] =$date;

                        $oneObject['payment_master_id'] =$payment_master_id;
                        $oneObject['discount_amount'] =riderData::get_discount_amount($payment_master_id);
                        $totol_discount = $totol_discount +  $oneObject['discount_amount'];
                        $oneObject['net_amount'] = $totalMake_Payment - $oneObject['discount_amount'];

                        if($oneObject['discount_amount']==0){
                            $oneObject['discount_amount']='-';
                        }



                        $finalData[] =$oneObject;
                    }

                }



            }
            $result= array();
            $result['data'] = $finalData;
            $result['count'] = $count;
            $result['totol_discount'] = $totol_discount;
            $result['totol_net'] = $count -$totol_discount;
        }

        if($payment_type_id==2){

                $object = "SELECT 
                    vendor_payment_id,
                    v.vendor_id AS client_id,
                    v.vendor_name AS fullname,
                    p.payment_mode,
                    p.remarks AS reference_number,
                    
                    p.amount AS amountpaid
                    
                    FROM vendor_payment AS p
                    LEFT join vendor AS v ON p.vendor_id = v.vendor_id
                    WHERE p.company_id = '$company_id' AND p.action_date ='$startDate'";

                    $payment = Yii::app()->db->createCommand($object)->queryAll();
                    $result= array();
                    $result['data'] = $payment;
                    $result['count'] = accounting_data::find_payment_count($payment);
                    $result['totol_discount'] = 0;
                    $result['totol_net'] = 0;
        }
        if($payment_type_id==3){

            $object = "SELECT
                farm_payment_id,
                f.farm_id AS client_id,
                f.farm_name AS fullname,
                p.payment_mode,
                p.remarks AS reference_number,
                p.amount AS  amountpaid
                
                FROM
                farm_payment AS p
                LEFT join farm AS f ON p.farm_id = f.farm_id
                WHERE p.company_id = '$company_id' AND p.action_date ='$startDate' ";


                $payment = Yii::app()->db->createCommand($object)->queryAll();
                $result= array();
                $result['data'] = $payment;
                $result['count'] = accounting_data::find_payment_count($payment);
                $result['totol_discount'] = 0;
                $result['totol_net'] = 0;
        }

        echo json_encode($result);
        die();
    }

    public function actionget_today_recovery_report_data_receipt_from_customer_save_vocher(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);


        $today_date = $data['start_date'];
        $customer_list = $data['customer_list'];
        $total_amount = $data['total_amount'];

        $payment_type_id = $data['payment_type_id'];

        $payment_type_id;





       /* $product_sale_account_id =$data['product_sale_account_id'];
        $product_receivable_account_id =$data['product_receivable_account_id'];
        $total =$data['total'];
        $today_date =$data['today_date'];
        $product_id =$data['product_id'];*/

        if($payment_type_id ==1){


            $token = accounting_data::auth_token();

            $token_data = json_decode($token ,true);

            $token =  $token_data['data']['token'];



            $ch = curl_init();


            $debit_and_cridit_account =   accounting_data::get_assign_account_function(2);

            if($debit_and_cridit_account){

                $debit_account_id = ['debit_account_id'];
                $credit_account_id = ['credit_account_id'];


            }else{
                $responce_message =[];
                $responce_message['success'] =false;
                $responce_message['message'] ='Account are not assigned';
                echo json_encode($responce_message);
                die();
            }


            $accunt_one =['account_id'=>$credit_account_id,'amount'=>$total_amount];

            $accunt_two =['account_id'=>$debit_account_id,'amount'=>-($total_amount)];


            $data =[$accunt_one , $accunt_two];




            $voucher_details = json_encode($data)  ;


            // Set the url and data

            curl_setopt($ch, CURLOPT_URL, "https://jotter.logic-zone.net/demo/api/vouchers?ver=v1.2&");

            curl_setopt($ch, CURLOPT_POST, 1);

            curl_setopt($ch, CURLOPT_POSTFIELDS, "__businessId=14&__fiscalYearId=14&type=5&trans_date=$today_date&party_id=&account_id=&reference=2&narration=23&hash_amount=$total_amount&voucher_details=$voucher_details&is_active=1");


            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' .$token,
            ]);

// Set other options
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute
            $data = curl_exec($ch);

// Close connection
            curl_close($ch);

// Print the data...
            // print_r($data);

            $responce = json_decode($data,true);

            if($responce['success']){

                $id = $responce['data'];

                accounting_data::save_jv_with_customer_payment($today_date,$customer_list ,$id);

                // $today_date


            }


        }
        if($payment_type_id ==2){


            $token = accounting_data::auth_token();

            $token_data = json_decode($token ,true);

            $token =  $token_data['data']['token'];



            $ch = curl_init();
            $debit_and_cridit_account =   accounting_data::get_assign_account_function(3);

            if($debit_and_cridit_account){

                $debit_account_id = ['debit_account_id'];
                $credit_account_id = ['credit_account_id'];


            }else{
                $responce_message =[];
                $responce_message['success'] =false;
                $responce_message['message'] ='Account are not assigned';
                echo json_encode($responce_message);
                die();
            }
            $accunt_one =['account_id'=>$credit_account_id,'amount'=>$total_amount];

            $accunt_two =['account_id'=>$debit_account_id,'amount'=>-($total_amount)];


            $data =[$accunt_one , $accunt_two];




            $voucher_details = json_encode($data)  ;


            // Set the url and data

            curl_setopt($ch, CURLOPT_URL, "https://jotter.logic-zone.net/demo/api/vouchers?ver=v1.2&");

            curl_setopt($ch, CURLOPT_POST, 1);

            curl_setopt($ch, CURLOPT_POSTFIELDS, "__businessId=14&__fiscalYearId=14&type=5&trans_date=$today_date&party_id=&account_id=&reference=2&narration=23&hash_amount=$total_amount&voucher_details=$voucher_details&is_active=1");


            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' .$token,
            ]);

// Set other options
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute
            $data = curl_exec($ch);

// Close connection
            curl_close($ch);

// Print the data...
            // print_r($data);

            $responce = json_decode($data,true);

            if($responce['success']){

                $id = $responce['data'];

                accounting_data::save_jv_with_vendor_payment($today_date,$customer_list ,$id);

                // $today_date


            }


        }
        if($payment_type_id ==3){


            $token = accounting_data::auth_token();

            $token_data = json_decode($token ,true);

            $token =  $token_data['data']['token'];



            $ch = curl_init();

            $debit_and_cridit_account =   accounting_data::get_assign_account_function(4);

            if($debit_and_cridit_account){

                $debit_account_id = ['debit_account_id'];
                $credit_account_id = ['credit_account_id'];


            }else{
                $responce_message =[];
                $responce_message['success'] =false;
                $responce_message['message'] ='Account are not assigned';
                echo json_encode($responce_message);
                die();
            }

            $accunt_one =['account_id'=>$credit_account_id,'amount'=>$total_amount];

            $accunt_two =['account_id'=>$debit_account_id,'amount'=>-($total_amount)];


            $data =[$accunt_one , $accunt_two];




            $voucher_details = json_encode($data)  ;


            // Set the url and data

            curl_setopt($ch, CURLOPT_URL, "https://jotter.logic-zone.net/demo/api/vouchers?ver=v1.2&");

            curl_setopt($ch, CURLOPT_POST, 1);

            curl_setopt($ch, CURLOPT_POSTFIELDS, "__businessId=14&__fiscalYearId=14&type=5&trans_date=$today_date&party_id=&account_id=&reference=2&narration=23&hash_amount=$total_amount&voucher_details=$voucher_details&is_active=1");


            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' .$token,
            ]);

// Set other options
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute
            $data = curl_exec($ch);

// Close connection
            curl_close($ch);

// Print the data...
            // print_r($data);

            $responce = json_decode($data,true);

            if($responce['success']){

                $id = $responce['data'];

                accounting_data::save_jv_with_farm_payment($today_date,$customer_list ,$id);

                // $today_date


            }


        }

        $responce_message =[];
        $responce_message['success'] =true;

        echo json_encode($responce_message);
        die();
    }

    public function actionPurchase_voucher(){
        $company_id = Yii::app()->user->getState('company_branch_id');

        $this->render('purchase_voucher',array(
            'riderList'=>json_encode(riderDailyStockData::getRiderList()),
            'company_id'=>$company_id,
        ));
    }

    public function actionget_today_recovery_report_data_acount_purchase(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $payment_type_id =  $data['payment_type_id'];
        $start_date =  $data['startDate'];
        $company_id = Yii::app()->user->getState('company_branch_id');


        if($payment_type_id==1){

             $query = "select
                s.daily_stock_id,
                s.purchase_rate,
                s.daily_stock_id ,
                s.description ,
                  p.name as product_name ,
                  ifnull(f.farm_name, 'All')  as farm_name ,
                s.quantity ,
                s.wastage,
                s.return_quantity ,
                s.description from daily_stock as s
                left join farm as f ON f.farm_id =s.farm_id 
                left join product as p ON p.product_id =s.product_id
                where s.company_branch_id ='$company_id' and s.date = '$start_date' ";


                $product_purchase_list =  Yii::app()->db->createCommand($query)->queryAll();
                $quantity = 0;

                $wastage = 0;

                $return_quantity = 0 ;

                $final_result = [];

                foreach ($product_purchase_list as $key => $value) {



                    $purchase_rate = $value['purchase_rate'];

                    $quantity =$quantity + $value['quantity'];
                    $wastage =$wastage + $value['wastage'];
                    $return_quantity =$return_quantity + $value['return_quantity'];
                    $net_quantity = $quantity- $return_quantity;
                    $net_amount = $net_quantity * $purchase_rate;
                    $one_object = [];
                    $one_object['daily_stock_id'] = $value['daily_stock_id'];
                    $one_object['purchase_rate'] = $purchase_rate;
                    $one_object['net_amount'] = $net_amount;
                    $one_object['net_quantity'] = $net_quantity;
                    $one_object['product_name'] = $value['product_name'];
                    $one_object['farm_name'] = $value['farm_name'];

                    $final_result[] =$one_object;

                }



              $total_amount = 0;
              foreach ($final_result as $value){
                 $purchase_rate = $value['purchase_rate'];
                 $quantity = $value['net_quantity'];
                 $total_amount = $purchase_rate * $quantity;

              }
              $lable =[
                  'Farm Name','Product','Quantity','Amount'
              ];
              $result = [];
              $result['list'] =$final_result;
              $result['total'] = $total_amount;
              $result['lable'] = $lable;
             echo json_encode($result);
        }
        if($payment_type_id==2){

             $query = " SELECT 
                b.bill_from_vendor_id,
                b.action_date,
                b.net_amount,
                b.remarks,
                i.item_name,
                v.vendor_name
                FROM bill_from_vendor AS b
                LEFT JOIN item AS i ON i.item_id =b.item_id
                LEFT JOIN vendor AS v ON v.vendor_id =b.vendor_id 
                WHERE b.action_date ='$start_date'  and b.company_id ='$company_id'  ";

            $list =  Yii::app()->db->createCommand($query)->queryAll();

            $total_amount = 0;
            $final_result = [];
            foreach ($list as $value){
                $net_amount = $value['net_amount'];
                $total_amount  = $total_amount+$net_amount;

                $one_object = [];
                $one_object['bill_from_vendor_id'] = $value['bill_from_vendor_id'];

                $one_object['net_amount'] = $value['net_amount'];
                $one_object['net_quantity'] = $value['item_name'];
                $one_object['product_name'] = $value['item_name'];
                $one_object['farm_name'] = $value['vendor_name'];
                $one_object['purchase_rate'] = $value['action_date'];

                $final_result[] =$one_object;
            }


            $lable =[
                'Vendor Name','Item Name','Date','Amount'
            ];

            $result = [];
            $result['list'] =$final_result;
            $result['total'] = $total_amount;
            $result['lable'] = $lable;
            echo json_encode($result);




        }

    }

    public function actionget_today_recovery_report_data_purchase_payment_save_vocher(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $today_date = $data['start_date'];
        $customer_list = $data['list_data'];
        $total_amount = $data['total_amount'];

        $payment_type_id = $data['payment_type_id'];



        if($payment_type_id ==1){



            $token = accounting_data::auth_token();

            $token_data = json_decode($token ,true);

            $token =  $token_data['data']['token'];



            $ch = curl_init();

            $debit_and_cridit_account =   accounting_data::get_assign_account_function(5);

            if($debit_and_cridit_account){

                $debit_account_id = ['debit_account_id'];
                $credit_account_id = ['credit_account_id'];


            }else{
                $responce_message =[];
                $responce_message['success'] =false;
                $responce_message['message'] ='Account are not assigned';
                echo json_encode($responce_message);
                die();
            }

            $accunt_one =['account_id'=>$credit_account_id,'amount'=>$total_amount];

            $accunt_two =['account_id'=>$debit_account_id,'amount'=>-($total_amount)];


            $data =[$accunt_one , $accunt_two];




            $voucher_details = json_encode($data)  ;


            // Set the url and data

            curl_setopt($ch, CURLOPT_URL, "https://jotter.logic-zone.net/demo/api/vouchers?ver=v1.2&");

            curl_setopt($ch, CURLOPT_POST, 1);

            curl_setopt($ch, CURLOPT_POSTFIELDS, "__businessId=14&__fiscalYearId=14&type=5&trans_date=$today_date&party_id=&account_id=&reference=2&narration=23&hash_amount=$total_amount&voucher_details=$voucher_details&is_active=1");


            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' .$token,
            ]);

// Set other options
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute
            $data = curl_exec($ch);

// Close connection
            curl_close($ch);

// Print the data...
            // print_r($data);

            $responce = json_decode($data,true);



            if($responce['success']){

                $id = $responce['data'];
                accounting_data::save_jv_with_farm_purchase($today_date,$customer_list ,$id);

                // $today_date


            }


        }


        if($payment_type_id ==2){



            $token = accounting_data::auth_token();

            $token_data = json_decode($token ,true);

            $token =  $token_data['data']['token'];



            $ch = curl_init();

            $debit_and_cridit_account =   accounting_data::get_assign_account_function(6);

            if($debit_and_cridit_account){

                $debit_account_id = ['debit_account_id'];
                $credit_account_id = ['credit_account_id'];


            }else{
                $responce_message =[];
                $responce_message['success'] =false;
                $responce_message['message'] ='Account are not assigned';
                echo json_encode($responce_message);
                die();
            }

            $accunt_one =['account_id'=>$credit_account_id,'amount'=>$total_amount];

            $accunt_two =['account_id'=>$debit_account_id,'amount'=>-($total_amount)];


            $data =[$accunt_one , $accunt_two];




            $voucher_details = json_encode($data)  ;


            // Set the url and data

            curl_setopt($ch, CURLOPT_URL, "https://jotter.logic-zone.net/demo/api/vouchers?ver=v1.2&");

            curl_setopt($ch, CURLOPT_POST, 1);

            curl_setopt($ch, CURLOPT_POSTFIELDS, "__businessId=14&__fiscalYearId=14&type=5&trans_date=$today_date&party_id=&account_id=&reference=2&narration=23&hash_amount=$total_amount&voucher_details=$voucher_details&is_active=1");


            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' .$token,
            ]);

// Set other options
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute
            $data = curl_exec($ch);

// Close connection
            curl_close($ch);

// Print the data...
            // print_r($data);

            $responce = json_decode($data,true);



            if($responce['success']){

                $id = $responce['data'];
                accounting_data::save_jv_with_vendor_purchase($today_date,$customer_list ,$id);

                // $today_date


            }


        }
        $responce_message =[];
        $responce_message['success'] =true;

        echo json_encode($responce_message);
        die();
    }

}