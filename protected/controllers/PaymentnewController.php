<?php

class PaymentnewController extends Controller
{


	public function actionPaymentnew_form()
	{

	    $get_data = $_GET;
        $payment_or_receipt  = $get_data['payment_or_receipt'];
        $form_name = 'Make Payment';
        if($payment_or_receipt==2){
            $form_name = 'Make Reciept';
        }

        $farm_list =qualityListData::getFarmList_all_arry();

        $new_farm_list = [];
        foreach ($farm_list as $value){
            $farm_id =$value['farm_id'];
            $farm_name =$value['farm_name'];
            $one_object = [];
            $one_object['vendor_id'] = $farm_id;
            $one_object['vendor_name'] = $farm_name;

            $new_farm_list[] =$one_object;
        }


        $collectionvault = blockData::get_collectionvault_array_list();

        $vendor_type = vendor_type_data::get_vendor_type_list();

        $vandor_list = vendor_list_data::get_vendor_type_list($vendor_type);

        $get_expence_list = dropClientReasonData::getExpenceList_array();
        $data = [];
        $data['date'] =date("Y-m-d");
        $data['vendor_type'] =$vendor_type;
        $data['vandor_list'] =$vandor_list;

        $data['form_name'] =$form_name;
        $data['farm_list'] =$new_farm_list;

        $data['payment_or_receipt'] =$payment_or_receipt;
        $data['collectionvault'] =$collectionvault;
        $data['get_expence_list'] =$get_expence_list;
        $data['base_url']  =  Yii::app()->createAbsoluteUrl('Paymentnew/base');

        $this->render('paymentnew_form',array(
            'data'=>json_encode($data),
        ));
	}
	public function actionReceipt_form(){
        $company_id = Yii::app()->user->getState('company_branch_id');

        $todayMonth = Date('m');

        $todayYear = Date('Y');

        $data = [];
        $data['date'] =date("Y-m-d");

        $data['base_url']  =  Yii::app()->createAbsoluteUrl('Paymentnew/base');




        $this->render('Receipt_form',array(

            'data'=>json_encode($data),

        ));
    }

	public  function actionbase_master_list(){
          $farm_list =   qualityListData::getFarmList_all_arry();
          $farm_object =[];
          foreach($farm_list as $value){
              $one_object = [];
              $one_object['id'] = $value['farm_id'];
              $one_object['name'] = $value['farm_name'];
              $farm_object[] = $one_object;
          }
         $vendor_list = vendor_list_data::get_vendor_list_all();
         $vendor_object = [];
         foreach ($vendor_list as $value){
             $one_object = [];
             $one_object['id'] = $value['vendor_id'];
             $one_object['name'] = $value['vendor_name'];
             $vendor_object[] = $one_object;
         }

         $expense_list = dropClientReasonData::getExpenceList_all_list();

         $expense_object = [];

         foreach ($expense_list as $value){
             $one_object = [];
             $one_object['id'] = $value['expence_type'];
             $one_object['name'] = $value['type'];
             $expense_object[] = $one_object;
         }
        $employee_list=vendor_list_data::get_employee_list_all_type_with_array();


        $employee_object = [];
        foreach ($employee_list as $value){
            $one_object = [];
            $one_object['id'] = $value['employee_id'];
            $one_object['name'] = $value['employee_name'];
            $employee_object[] = $one_object;
        }


        $other_income_source_list=vendor_list_data::get_other_income_source_with_array();


        $other_income_source_object = [];
        foreach ($other_income_source_list as $value){
            $one_object = [];
            $one_object['id'] = $value['other_income_source_id'];
            $one_object['name'] = $value['other_income_source_name'];
            $other_income_source_object[] = $one_object;
        }




         $final_result = [];
         $final_result['farm_object'] =$farm_object ;
         $final_result['vendor_object'] =$vendor_object ;
         $final_result['expense_object'] =$expense_object ;
         $final_result['employee_object'] =$employee_object ;
         $final_result['other_income_source_object'] =$other_income_source_object ;
         echo json_encode($final_result);

    }
    public function actionbase_get_payment_list(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $vendor_payment =[];

        if(isset($data['pay_to_party_id'])){
            $type  = $data['type'];
            $pay_to_party  = $data['pay_to_party_id'];
            $vendor_payment =[];
            if($type=='vendor' ){
                $vendor_payment = payment_new::vendor_payment_list($pay_to_party);

            }elseif($type=='farm'){
                $vendor_payment = payment_new::farm_payment_list($pay_to_party);
            }else{
              $vendor_payment = payment_new::get_all_payment_list($data);
            }

        }

        echo  json_encode($vendor_payment);
    }

    public function actionbase_save_payment_new_other(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $vendor_type_id = $data['vendor_type_id'];

        $company_id = Yii::app()->user->getState('company_branch_id');
        
        if($vendor_type_id==3){
            $object = New FarmPayment();

            $object->farm_id = $data['vendor_id'];
            $object->amount = $data['amount_paid'];
            $object->action_date = $data['date'];
            $object->remarks = $data['reference_no'];
            $object->reference_no = $data['reference_no'];
            $object->collection_vault_id =$data['collection_vault_id'];
            $object->vendor_type_id =$data['vendor_type_id'];
            $object->transaction_type =$data['transaction_type'];
            $object->expence_type =$data['expence_type'];
            $object->company_id = $company_id;

            if($object->save()){
                $success = true;
                $message = '';
            }else{
                $success = false;
                $message = $object->getErrors();
            }


        }else{
            $object =New NewPayment();
            $object->collection_vault_id =$data['collection_vault_id'];
            $object->vendor_type_id =$data['vendor_type_id'];
            $object->vendor_id =$data['vendor_id'];
            $object->date =$data['date'];
            $object->transaction_type =$data['transaction_type'];
            $object->expence_type =$data['expence_type'];
            $object->amount_paid =$data['amount_paid'];
            $object->reference_no =$data['reference_no'];
            $object->company_id =$company_id;

            $object->payment_or_receipt =$data['payment_or_receipt'];
            if($object->save()){
                $success = true;
                $message = '';
            }else{
                $success = false;
                $message = $object->getErrors();

            }
        }


        $responce = [];
        $responce['success'] =$success;
        $responce['message'] =$message;
        echo json_encode($responce);
    }

    public function actionbase_update_new_payment(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $list = $data['list'];



        $amount_paid  = $list['amount_paid'];
        $collection_vault_id  = $list['collection_vault_id'];

        $date  = $list['date'];
        $transaction_type  = $list['transaction_type'];
        $reference_no  = $list['reference_no'];
        $expence_type  = $list['expence_type'];


        $main_object = $data['main_object'];

        $vendor_type_id = $main_object['vendor_type_id'];

        $new_payment_id  = $list['new_payment_id'];

        $company_id = Yii::app()->user->getState('company_branch_id');
        $message = '';
        if($vendor_type_id==3){

            $object = FarmPayment::model()->findByPk($new_payment_id);

            $object->amount = $amount_paid;


            $object->action_date = $date;
            $object->remarks = $reference_no;
            $object->reference_no = $reference_no;
            $object->collection_vault_id =$collection_vault_id;

            $object->transaction_type =$transaction_type;
            $object->expence_type =$expence_type;

            if($object->Save()){
               $success = true;
            }else{
                $success = false;
                $message =  $message = $object->getError();
            }

        }else{
            $object =NewPayment::model()->findByPk($new_payment_id);

            $vendor_id  = $list['vendor_id'];

            $object->collection_vault_id =$collection_vault_id;

            $object->vendor_id =$vendor_id;
            $object->date =$date;

            $object->amount_paid = $amount_paid;
            $object->reference_no =$reference_no;
            $object->transaction_type =$transaction_type;
            $object->expence_type =$expence_type;

            if($object->save()){
                $success = true;
            }else{
                $success = false;
                $message = $object->getError();
            }
        }
        $responce = [];
        $responce['success'] = $success;
        $responce['message'] = $message;

        echo json_encode($responce);
    }

    public function actionbase_save_payment(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $type  = $data['type'];
        $pay_to_party_id  = $data['pay_to_party_id'];
        $date  = $data['date'];
        $head  = $data['head'];
        $amount_paid  = $data['amount_paid'];
        $payment_type_id  = $data['payment_type_id'];
        $reference_no  = $data['reference_no'];
        $payment_mode  = $data['payment_mode'];

        $company_id = Yii::app()->user->getState('company_branch_id');



        if($type=='vendor') {

            $object = new VendorPayment();

            $object->vendor_id = $pay_to_party_id;
            $object->amount = $amount_paid;
            $object->action_date = $date;
            $object->remarks = 'new farm';
            $object->reference_no = $reference_no;
            $object->payment_mode = $payment_mode;
            $object->company_id = $company_id;

            if ($object->save()) {
                $responce['success'] = true;


                $vendor_id =  $pay_to_party_id;

                $vendor_object = Vendor::model()->findByPk($vendor_id);

                $phoneNo = $vendor_object['phone_no'];
                $vendor_name = $vendor_object['vendor_name'];
                $companyObject  =  utill::get_companyTitle($company_id);
                $companyMask = $companyObject['sms_mask'];
                $companyTitle = $companyObject['company_title'];
                $action_date = $date ;
                $amount =   $amount_paid;
                $reference_no =   $reference_no;

                $message = "Payment Alert: Dear $vendor_name, Changes have been
                 on $action_date.Ignore pervious SMS for this date.A payment 
                 of Rs $amount ,Refernce : $reference_no  made to you .";
                //$message = "We have paid Rs.".$data['amount']."  you.\nReference no.".$data['reference_no']."\n";
                 $message .="\n".$companyTitle;



                if($vendor_object['payment_alert']==1){
                    manageSendSMS::vendor_sms_function($phoneNo , $message , $companyMask , $company_id ,0,$vendor_id);
                    smsLog::saveSms($vendor_id ,$company_id ,$phoneNo ,$vendor_name ,$message);

                }



            }else{
                $responce['success'] = false;
                $responce['message'] = $object->getErrors();
            }

        }elseif ($type=='farm') {
            $object = New FarmPayment();
            $object->farm_id = $pay_to_party_id;
            $object->amount = $amount_paid;
            $object->action_date = $date;
            $object->remarks = 'remarks';
            $object->reference_no = $reference_no;
            $object->payment_mode = $data['payment_mode'];
            $object->company_id = $company_id;

            if ($object->save()) {
                $responce['success'] = true;


                $companyObject  =  utill::get_companyTitle($company_id);
                $companyMask = $companyObject['sms_mask'];
                $farm_id =  $pay_to_party_id;
                $object = Farm::model()->findByPk($farm_id);
                $phoneNo =  $object['phone_number'];
                $farm_name =  $object['farm_name'];
                $companyTitle = $companyObject['company_title'];
                $amount = $amount_paid;
                $reference_no =  $data['reference_no'];
                $message = "Payment Alert: Dear $farm_name on $date
                 payment of Rs $amount ,$reference_no made to you. ";
                if($object['payment_alert']==1){
                    manageSendSMS::vendor_sms_function($phoneNo , $message , $companyMask , $company_id ,0,$farm_id);
                    smsLog::saveSms($farm_id ,$company_id ,$phoneNo ,$farm_name ,$message);
                }


            }else{
                $responce['success'] = false;
                $responce['message'] = $object->getErrors();
            }

        }else {


            $object = New MainPayment();
            $object->type = $type;
            $object->pay_to_party_id = $pay_to_party_id;
            $object->payment_type_id = $payment_type_id;
            $object->company_id = $company_id;
            $object->date = $date;
            $object->head = $head;
            $object->payment_mode = $payment_mode;
            $object->amount_paid = $amount_paid;
            $object->reference_no = $reference_no;

            if($object->save()){
                $responce['success'] = true;
            }else{
                $responce['success'] = false;
                $responce['message'] = $object->getErrors();
            }

        }

        echo json_encode($responce);
    }

    public function actionBase_get_new_payment_list(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $payment_or_receipt = $data['payment_or_receipt'];
        $vendor_id = $data['vendor_id'];
        $page = $data['page']*20;
        $vendor_type_id = $data['vendor_type_id'];

        if($vendor_type_id==3){

            $query = "SELECT 
                p.farm_payment_id AS new_payment_id,
                p.amount AS amount_paid,
                p.action_date AS date,
                p.reference_no,
                p.expence_type,
                f.farm_name,
                p.transaction_type,
                cv.collection_vault_name,
                p.collection_vault_id,
                ep.type
                FROM farm_payment AS p
                LEFT join farm AS f ON p.farm_id = f.farm_id
                LEFT JOIN collection_vault AS cv ON cv.collection_vault_id = p.collection_vault_id
                LEFT JOIN expence_type AS ep ON p.expence_type = ep.expence_type
                WHERE p.farm_id = '$vendor_id'
                ORDER BY p.action_date DESC 
                limit $page, 20";



        }else{

            $query = "SELECT 
            np.*,
            ep.type,
            co.collection_vault_name
            
            FROM new_payment AS np
            LEFT JOIN expence_type AS ep ON np.expence_type = ep.expence_type
            LEFT JOIN collection_vault AS co ON co.collection_vault_id = np.collection_vault_id
            WHERE np.vendor_id = '$vendor_id' and np.payment_or_receipt ='$payment_or_receipt'
              ORDER BY np.date DESC
              limit $page, 20";





        }

        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

        $result = [];
        $result['list'] = $queryResult;

        echo  json_encode($result);

    }

    public function actionPayment_ledger(){

        $today_date = date("Y-m-d");
        $fiveDayAgo = date('Y-m-d', strtotime('-5 day', strtotime($today_date)));
        $company_id = Yii::app()->user->getState('company_branch_id');

        $copany_object = Company::model()->findByPk($company_id)->attributes;

        $query="select * 
                          from vendor as q
                         where q.company_id ='$company_id' ";
       // $vendor_list =  Yii::app()->db->createCommand($query)->queryAll();

        $vendor_type = vendor_type_data::get_vendor_type_list();
        $vandor_list = vendor_list_data::get_vendor_type_list($vendor_type);

        $data = [] ;
        $data['vendor_type'] = $vendor_type ;
        $data['today_date'] = $today_date ;
        $data['five_day_ago'] = $fiveDayAgo ;
        $data['vendor_list'] = $vandor_list ;
        $data['company_id'] = $company_id ;
        $data['copany_object'] = $copany_object ;


        $this->render('payment_ledger',array(
            'data'=>json_encode($data),
        ));
    }

    public function actionbase_get_paymentnew_ledger(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $startDate = $data['startDate'];

        $endDate = $data['endDate'];

        $vendor_id = $data['vendor_id'];
        $opening_purchase = vendor_stock_ledger::vendor_purchase_item($data,1);


        $opening_payment =  vendor_stock_ledger::vendor_payment($data,1);

        $final_result = [];

        $one_object= [];
        $one_object['puchase'] = '';
        $one_object['payment'] = '';

        $balance = $opening_purchase -  $opening_payment;

        $one_object['balance'] =$balance;
        $one_object['date'] ='Opening';

        $final_result[] =$one_object;

        $net_balance = $balance;


        $x= strtotime($startDate);
        $y= strtotime($endDate);

        while($x < ($y+8640)) {
            $oneDayData = array();
            $selectDate = date("Y-m-d", $x);
            $data['startDate'] = $selectDate;
            $x += 86400;
            $opening_purchase = vendor_stock_ledger::vendor_purchase_item($data,2);

            $opening_payment =  vendor_stock_ledger::vendor_payment($data,2);

            foreach($opening_purchase as $value){



                $opening_purchase_new =  $value['net_amount'];
                $opening_payment =0;
                $one_object= [];
                $one_object['item_name'] = $value['item_name'];
                $one_object['puchase'] = $opening_purchase_new;
                $one_object['payment'] = $opening_payment;
                $balance = $opening_purchase_new -  $opening_payment;
                $net_balance =$net_balance + $balance;
                $one_object['balance'] =$net_balance;
                $one_object['date'] =$selectDate;
                $one_object['remarks'] =$value['remarks'];
                if($opening_purchase>0){
                    // $one_object['remarks'] =vendor_stock_ledger::vendor_purchase_item_remarks($data);
                }
                $final_result[] =$one_object;


            }



            $opening_payment =  vendor_stock_ledger::vendor_payment($data,2);


            foreach($opening_payment as $value){


                $opening_payment =  $value['amount'];
                $remarks =  $value['remarks'];



                $opening_purchase_new =0;
                $one_object= [];
                $one_object['puchase'] = $opening_purchase_new;
                $one_object['payment'] = $opening_payment;
                $balance = $opening_purchase_new -  $opening_payment;
                $net_balance =$net_balance + $balance;
                $one_object['balance'] =$net_balance;
                $one_object['date'] =$selectDate;
                $one_object['remarks'] =$remarks;
                if($opening_purchase>0){
                    // $one_object['remarks'] =vendor_stock_ledger::vendor_purchase_item_remarks($data);
                }
                $final_result[] =$one_object;

            }




        }

        $total_purchase = 0;
        $total_payment = 0;
        $total_balance = 0;



        foreach ($final_result as $value){


            if($value['date'] !='Opening'){

                $total_purchase = $total_purchase + $value['puchase'];
                $total_payment = $total_payment + $value['payment'];
                $total_balance = $total_balance + $value['balance'];
            }

        }

        $data = [];
        $data['list']= $final_result;
        $data['total_purchase']= $total_purchase;
        $data['total_payment']= $total_payment;
        $data['total_balance']= $total_balance;

        echo json_encode($data);
    }

    public function actionbase_delete_new_payment(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

         $list = $data['list'];
         $main_object = $data['main_object'];

          $vendor_type_id = $main_object['vendor_type_id'];
          $new_payment_id = $list['new_payment_id'];

          if($vendor_type_id==3){
              $object = FarmPayment::model()->findByPk($new_payment_id);
          }else{
              $object = NewPayment::model()->findByPk($new_payment_id);
          }

          if($object->delete()){
              $success = true;
              $message = '';
          }else{
              $success = true;
              $message = $object->getError();
          }

          $responce = [];
          $responce['success'] =$success ;
          $responce['message'] =$message ;

          echo  json_encode($responce);
    }
    public function actionAll_type_payment_report(){
        $today_date = date("Y-m-d");
        $fiveDayAgo = date('Y-m-d', strtotime('-5 day', strtotime($today_date)));
        $company_id = Yii::app()->user->getState('company_branch_id');
        $this->render('all_type_payment_report',array(
            'riderList'=>vendor_list_data::get_vendor_list(),
            'company_id'=>$company_id,
            'fiveDayAgo'=>$fiveDayAgo,
        ));
    }

    public function actionall_type_payment_report_list(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $start_date = $data['start_date'];
        $end_date = $data['end_date'];


        $company_id = Yii::app()->user->getState('company_branch_id');


       $query = "SELECT 
                p.farm_payment_id AS new_payment_id,
                p.amount AS amount_paid,
                p.action_date AS date,
                p.reference_no,
                p.expence_type,
                f.farm_name,
                p.transaction_type,
                cv.collection_vault_name,
                p.collection_vault_id,
                ep.type
                FROM farm_payment AS p
                LEFT join farm AS f ON p.farm_id = f.farm_id
                LEFT JOIN collection_vault AS cv ON cv.collection_vault_id = p.collection_vault_id
                LEFT JOIN expence_type AS ep ON p.expence_type = ep.expence_type
                WHERE p.action_date BETWEEN '$start_date' AND  '$end_date'
                 and p.company_id = '$company_id'
                ORDER BY p.action_date DESC  ";

        $farm_result  =  Yii::app()->db->createCommand($query)->queryAll();



            $query = "SELECT 
            np.*,
            ep.type,
            co.collection_vault_name
            
            FROM new_payment AS np
            LEFT JOIN expence_type AS ep ON np.expence_type = ep.expence_type
            LEFT JOIN collection_vault AS co ON co.collection_vault_id = np.collection_vault_id
            WHERE  np.date BETWEEN '$start_date' AND '$end_date'  
              and np.payment_or_receipt ='1'
              and np.company_id = '$company_id'
		    ORDER BY np.date DESC ";

        $payment_new_list  =  Yii::app()->db->createCommand($query)->queryAll();

        $payment_list = array_merge($farm_result,$payment_new_list);



        $result = [];
        $result['list'] = $payment_list;

        echo  json_encode($result);

    }
}