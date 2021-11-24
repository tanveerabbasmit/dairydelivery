<?php

class BillFromVendorController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

    public function actionbill_from_vendor_create(){

        $data = [];
        $get_data = $_GET;
        $bill_from_vendor_id =0;
        $object = [];
        $company_id = Yii::app()->user->getState('company_branch_id');
        $button_text = "Save";
        if(isset($get_data['bill_from_vendor_id'])){

            $bill_from_vendor_id =$get_data['bill_from_vendor_id'];

            $object = BillFromVendor::model()->findByPk($bill_from_vendor_id)->attributes;

            $button_text = "Update";
            if($object['company_id']!=$company_id){
                $object =[];
                $bill_from_vendor_id =0;
            }

        }

        $company_id = Yii::app()->user->getState('company_branch_id');

        $query="select * 
                          from vendor as q
                         where q.company_id ='$company_id' ";
        $farm_list =  Yii::app()->db->createCommand($query)->queryAll();


        //$item_list =  item_list_data::get_item_array();
        $item_list =  [];
        $get_expence_list = dropClientReasonData::getExpenceList_array();

        foreach ($get_expence_list as $value){
            $one_object = [];
            $one_object['item_id'] = $value['expence_type'];
            $one_object['item_name'] = $value['type'];
            $one_object['company_id'] = $value['company_id'];
            $item_list[] =$one_object;
        }



        $data['farm_list'] =$farm_list;
        $data['today_date'] = date("Y-m-d");
        $data['item_list'] = $item_list;
        $data['bill_from_vendor_id'] = $bill_from_vendor_id;
        $data['object'] = $object;
        $data['button_text'] = $button_text;


        $this->render('bill_from_vendor_create' , array(
            "zoneList"=>zoneData::getZoneList(),
            "companyBranchList"=>json_encode($data),
        ));

    }

    public function actionbase_url_save_payment(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
         $company_id = Yii::app()->user->getState('company_branch_id');
         $bill_from_vendor_id = $data['bill_from_vendor_id'];
         if($bill_from_vendor_id>0){

             $object = BillFromVendor::model()->findByPk($bill_from_vendor_id);

             $action_date_perious = $data['action_date'];

         }else{
             $object = New BillFromVendor();
         }
         $object->action_date = $data['action_date'];
         $object->item_id = $data['item_id'];
         $object->company_id = $company_id;
         $object->vendor_id = $data['vendor_id'];
         $object->price = $data['price'];
         $object->quantity = $data['quantity'];
         $object->gross_amount = $data['gross_amount'];
         $object->tax_amount = $data['tax_amount'];
         $object->discount_amount = $data['discount_amount'];
         $object->net_amount = $data['net_amount'];
         $object->remarks = $data['remarks'];
         $responce = [];
         if($object->save()){
             $responce['success'] =true;


             $item_id =  $data['item_id'];

             $item_object = Item::model()->findByPk($item_id);

             $item_name =  $item_object['item_name'];

             $vendor_id =  $data['vendor_id'];

             $vendor_object = Vendor::model()->findByPk($vendor_id);



             $phoneNo = $vendor_object['phone_no'];

             $action_date =  $data['action_date'];

             $net_amount = $data['net_amount'];

             $vendor_name = $vendor_object['vendor_name'];
             $companyObject  =  utill::get_companyTitle($company_id);
             $companyMask = $companyObject['sms_mask'];
             $companyTitle = $companyObject['company_title'];
             $action_date = $data['action_date'] ;
             $amount =   $data['net_amount'];


             if($bill_from_vendor_id>0){


                 $message = " Bill Alert : Dear $vendor_name
                  change have been made in your bill on $action_date_perious.
                  ignore pervious SMS from for this date. 
                  A bill of Rs $net_amount against $item_name has been
                 Recevied from you. ";
                 //$message = "We have paid Rs.".$data['amount']."  you.\nReference no.".$data['reference_no']."\n";
                 $message .="\n".$companyTitle;
             }else{


                 $message = " Bill Alert : Dear $vendor_name on  $action_date 
                 a bill of Rs $net_amount against $item_name has been
                 Recevied from you. ";
                 //$message = "We have paid Rs.".$data['amount']."  you.\nReference no.".$data['reference_no']."\n";
                 $message .="\n".$companyTitle;

             }

             if($vendor_object['provide_bill_alert']==1){
                 manageSendSMS::vendor_sms_function($phoneNo , $message , $companyMask , $company_id ,0,$vendor_id);
                 smsLog::saveSms($vendor_id ,$company_id ,$phoneNo ,$vendor_name ,$message);

             }



         }else{
             $responce['success'] =false;
             $responce['message'] =false;

         }
         echo json_encode($responce);
    }


    public function actionvendor_ledger_export(){

        $data = $_GET;
        $startDate = $data['startDate'];
        $endDate = $data['endDate'];
        $vendor_id = $data['vendor_id'];

        $opening_purchase = vendor_stock_ledger::vendor_purchase_item($data,1);

        $opening_payment =  vendor_stock_ledger::vendor_payment($data,1);

        $final_result = [];

        $one_object= [];
        $one_object['puchase'] = $opening_purchase;
        $one_object['payment'] = $opening_payment;
        $one_object['item_name'] = '';
        $one_object['remarks'] = '';

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
                $one_object['item_name'] ='';
                if($opening_purchase>0){
                    // $one_object['remarks'] =vendor_stock_ledger::vendor_purchase_item_remarks($data);
                }
                $final_result[] =$one_object;

            }

        }

        header("Content-type: application/vnd.ms-excel; name='excel'");
        header("Content-Disposition: attachment; filename=vendor_ledger.csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo  "#,Date, Bill From Vendor,Item,Payment , Reference No, Balance";
        echo "\r\n";

        foreach ($final_result as $key=>$value){



            echo ($key+1).",";
            echo $value['date'].",";
            echo $value['puchase'].",";
            echo $value['item_name'].",";
            echo $value['payment'].",";
            echo $value['remarks'].",";
            echo $value['balance'].",";
            echo "\r\n";

        }
    }
    public function actionvendor_ledger(){

        $today_date = date("Y-m-d");
        $fiveDayAgo = date('Y-m-d', strtotime('-5 day', strtotime($today_date)));
        $company_id = Yii::app()->user->getState('company_branch_id');

        $copany_object = Company::model()->findByPk($company_id)->attributes;

        $query="select * 
                          from vendor as q
                         where q.company_id ='$company_id'  ";
        $vendor_list =  Yii::app()->db->createCommand($query)->queryAll();


        $data = [] ;
        $data['today_date'] = $today_date ;
        $data['five_day_ago'] = $fiveDayAgo ;
        $data['vendor_list'] = $vendor_list ;
        $data['company_id'] = $company_id ;
        $data['copany_object'] = $copany_object ;

        $this->render('vendor_ledger',array(
            'data'=>json_encode($data),
        ));
    }
    public function actionvendor_bills(){

        $today_date = date("Y-m-d");
        $fiveDayAgo = date('Y-m-d', strtotime('-5 day', strtotime($today_date)));
        $company_id = Yii::app()->user->getState('company_branch_id');

        $copany_object = Company::model()->findByPk($company_id)->attributes;


        $vendor_list = [];
        $data = [] ;
        $data['today_date'] = $today_date ;
        $data['five_day_ago'] = $fiveDayAgo ;
        $data['vendor_list'] = $vendor_list ;
        $data['company_id'] = $company_id ;
        $data['copany_object'] = $copany_object ;

        $this->render('vendor_bills',array(
            'data'=>json_encode($data),
        ));
    }

    public function actionbase_get_vendor_ledger(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $startDate = $data['startDate'];

        $endDate = $data['endDate'];

        $vendor_id = $data['vendor_id'];

        $opening_purchase = vendor_stock_ledger::vendor_purchase_item($data,1);


        $opening_recipt =  vendor_stock_ledger::vendor_receipt($data,1);



        $opening_payment =  vendor_stock_ledger::vendor_payment($data,1);

        $final_result = [];

        $one_object= [];
        $one_object['puchase'] = '';
        $one_object['payment'] = '';

        $balance = $opening_recipt + $opening_purchase -  $opening_payment;

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

           // $opening_payment =  vendor_stock_ledger::vendor_payment($data,2);

            foreach($opening_purchase as $value){



                $opening_purchase_new =  $value['net_amount'];
                $opening_payment =0;
                $one_object= [];
                $one_object['item_name'] = $value['item_name'];
                $one_object['puchase'] = $opening_purchase_new;
                $one_object['payment'] = $opening_payment;
                $one_object['head_name'] = '';
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

            $opening_recipt =  vendor_stock_ledger::vendor_receipt($data,2);

           foreach ($opening_recipt as $value){
               $opening_purchase_new =  $value['amount'];
               $opening_payment =0;
               $one_object= [];
               $one_object['item_name'] = $value['type'];
               $one_object['puchase'] = $opening_purchase_new;
               $one_object['payment'] = $opening_payment;
               $one_object['head_name'] = '';
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
                $type =  $value['type'];



                $opening_purchase_new =0;
                $one_object= [];
                $one_object['puchase'] = $opening_purchase_new;
                $one_object['payment'] = $opening_payment;
                $one_object['head_name'] = $type;
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
    public function actionbase_get_vendor_bills(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);



        $vendor_list = vendor_list_data::get_vendor_list_all();
        $result =[];
        $total =0;
        foreach ($vendor_list as $value){
           $vendor_id=  $value['vendor_id'];
           $vendor_name=  $value['vendor_name'];
            $opening_purchase = vendor_stock_ledger::vendor_purchase_item_bills($vendor_id,$data);

           // $opening_payment =  vendor_stock_ledger::vendor_payment_bills($vendor_id,1);

            $balance = $opening_purchase;
            $one_object = [];
            $one_object['vendor_id'] = $vendor_id;
            $one_object['vendor_name'] = $vendor_name;
            $one_object['balance'] = $balance;
            $total = $total +$balance;
            $result[]=$one_object;

        }

        $data =[];
        $data['list'] =$result;
        $data['total'] =$total;

        echo json_encode($data);
    }

    public function actionBill_from_vendor_report_view(){
        $today_date = date("Y-m-d");
        $fiveDayAgo = date('Y-m-d', strtotime('-5 day', strtotime($today_date)));
        $company_id = Yii::app()->user->getState('company_branch_id');
        $this->render('bill_from_vendor_report_view',array(
            'riderList'=>vendor_list_data::get_vendor_list(),
            'company_id'=>$company_id,
            'fiveDayAgo'=>$fiveDayAgo,
        ));
    }

    public function actionbase_url_bill_from_vendor_report_view_report(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);


        $vendor_id =$data['vendor_id'];
        $page_number =$data['page_number'];

        $page = 20 *$page_number ;

        $company_id = Yii::app()->user->getState('company_branch_id');

        $query = " SELECT 
                b.bill_from_vendor_id,
                b.action_date,
                b.net_amount,
                b.remarks,
                i.type as item_name,
                v.vendor_name
                FROM bill_from_vendor AS b
                  LEFT JOIN expence_type AS i ON i.expence_type =b.item_id
                LEFT JOIN vendor AS v ON v.vendor_id =b.vendor_id 
                WHERE  b.company_id ='$company_id' 
                 and b.vendor_id = '$vendor_id'
                  ORDER BY b.action_date DESC
                LIMIT $page,20";




        $vendor_list =  Yii::app()->db->createCommand($query)->queryAll();

        $data =[];
        $data['list'] =$vendor_list;

        echo json_encode($data);

    }
    public function actionBill_from_vendor_report_view_report(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $company_id = Yii::app()->user->getState('company_branch_id');

        $start_date = $data['start_date'];
        $end_date = $data['end_date'];
        $vendor_id = $data['vendor_id'];

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
                WHERE b.action_date between '$start_date' 
                and '$end_date' and b.company_id ='$company_id'  ";

            if($vendor_id>0){
                $query .=" and b.vendor_id = '$vendor_id' ";
            }


        $vendor_list =  Yii::app()->db->createCommand($query)->queryAll();

        $data =[];
        $data['list'] =$vendor_list;

        echo json_encode($data);

    }
    public function actionbase_url_delete_vendor_bill(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $company_id = Yii::app()->user->getState('company_branch_id');

        $bill_from_vendor_id = $data['bill_from_vendor_id'];


        $object =BillFromVendor::model()->findByPk($bill_from_vendor_id);

        if($object['company_id']==$company_id){
            $object->delete();
        }
    }
    public function actionsaveDeliveryFromPortal_vendor_report_view_delete(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $company_id = Yii::app()->user->getState('company_branch_id');

        $bill_from_vendor_id = $data['bill_from_vendor_id'];


        $object =BillFromVendor::model()->findByPk($bill_from_vendor_id);

        if($object['company_id']==$company_id){
            $object->delete();
        }

    }
}
