<?php

class CollectionvaultController extends Controller
{
	public function actionCollectionvault_view()
	{
        $this->render('collectionvault_view' , array(
            "zoneList"=>blockData::get_collectionvault(),
            "companyBranchList"=>companyBranchData::getCompanyBranchList(),
        ));


	}

	public function actionsave_new_collectionvault_get_block_list(){
       echo  blockData::get_collectionvault();
    }
	public function actionsave_new_collectionvault_save_new(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);


        $company_id = Yii::app()->user->getState('company_branch_id');
        $collection_vault_id  = $data['collection_vault_id'];
        if($collection_vault_id>0){
            $object =CollectionVault::model()->findByPk($collection_vault_id);
        }else{
            $object = New CollectionVault();
        }

        $object->collection_vault_name = $data['collection_vault_name'];
        $object->company_id =$company_id;
        if($object->save()){

        }else{
            echo "<pre>";
            print_r($object->getErrors());
            die();
        }

    }

    public function actionCollectionvault_legder(){

        $today_date = date("Y-m-d");
        $fiveDayAgo = date('Y-m-d', strtotime('-5 day', strtotime($today_date)));
        $company_id = Yii::app()->user->getState('company_branch_id');
        $copany_object = Company::model()->findByPk($company_id)->attributes;
        $data = [] ;
        $data['today_date'] = $today_date ;
        $data['five_day_ago'] = $fiveDayAgo ;
        $data['collection_vault_list'] =blockData::get_collectionvault_array();
        $data['company_id'] = $company_id ;
        $data['copany_object'] = $copany_object ;

        $this->render('collectionvault_legder',array(
            'data'=>json_encode($data),
        ));
    }

   public function actionbase_get_collectionvault_get_list(){
       $post = file_get_contents("php://input");
       $data = CJSON::decode($post, TRUE);

       $collection_vault_id = $data['collection_vault_id'];

       $company_id = Yii::app()->user->getState('company_branch_id');
       $start_date = $data['start_date'];
       $end_date = $data['end_date'];


        $query = " select pm.remarks,
                    c.fullname,
                    c.client_id,
                    cv.collection_vault_name,
                    pm.amount_paid,
                    pm.date,
                    'Customer' as source
                    FROM payment_master AS pm
                    LEFT join client AS c ON pm.client_id  = c.client_id  
                    LEFT JOIN collection_vault AS cv ON cv.collection_vault_id =pm.collection_vault_id
                    WHERE pm.company_branch_id='$company_id'
                    and pm.collection_vault_id !=0
                     and pm.date BETWEEN '$start_date' and '$end_date' ";
        if($collection_vault_id>0){
            $query  .=" and pm.collection_vault_id ='$collection_vault_id' ";
        }

       $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

        $result= [];
        $result['list']=$queryResult;
        $result['total_amount']=collectionvault_data::get_total_amount($queryResult,'amount_paid');

        echo  json_encode($result);

   }

   public function actioncollection_vault_ledger(){
	   $get_data = $_GET;

	   $data =[];
	   $data['collection_vault_id'] = $get_data['collection_vault_id'];
	   $data['startDate'] =$get_data['startDate'];
	   $data['endDate'] =$get_data['endDate'];
	   $data['collection_vault_list'] = blockData::get_collectionvault_array_list();
       $this->render('collection_vault_ledger',array(
           'data'=>json_encode($data)
       ));
   }
   public function actioncollection_vault_ledger_summary(){
	   $data =[];
	   $data['collection_vault_list'] = blockData::get_collectionvault_array_list();

       $this->render('collection_vault_ledger_summary',array(

           'data'=>json_encode($data)

       ));
   }

   public  function actioncollection_vault_ledger_report_list_data_summary_data(){

       $post = file_get_contents("php://input");
       $data = CJSON::decode($post, TRUE);
       $collection_vault =  blockData::get_collectionvault_array_list_search($data);
       $result = [];
       $grand_payment = 0;
       $grand_receipt = 0;
       $grand_balance = 0;
       foreach($collection_vault as $value) {
           $collection_vault_id = $value['collection_vault_id'];
           $collection_vault_name = $value['collection_vault_name'];
           $one_object = [];
           $one_object['collection_vault_id'] = $collection_vault_id;
           $one_object['collection_vault_name'] = $collection_vault_name;
           $payment_list = new_payment_data::get_new_payment_date_rang(1,$collection_vault_id, $data);
           $receipt_list = new_payment_data::get_new_payment_date_rang(2,$collection_vault_id, $data);
           $cutomer_receipt_amount = new_payment_data::receipt_from_customer_dae_range($collection_vault_id, $data);

           $farm_payment_list_between_date_rang = new_payment_data::farm_payment_list_between_date_rang($collection_vault_id, $data);

           $total_payment = $payment_list + $farm_payment_list_between_date_rang;
           $total_receipt = $receipt_list + $cutomer_receipt_amount;

           $one_object['total_payment'] =$total_payment;
           $one_object['total_receipt'] =$total_receipt;
           $one_object['balance'] =$total_receipt - $total_payment;
           $result[] = $one_object;

           $grand_payment = $grand_payment + $total_payment;
           $grand_receipt = $grand_receipt+ $total_receipt;
           $grand_balance = $grand_balance+  $one_object['balance'];
       }
       $end_result = [];
       $end_result['result'] =$result ;

       $end_result['total_payment'] =$grand_payment ;
       $end_result['total_receipt'] =$grand_receipt ;
       $end_result['total_balance'] =$grand_balance ;
       echo json_encode($end_result);


   }
   public  function actioncollection_vault_ledger_report_list_data(){

       $post = file_get_contents("php://input");
       $data = CJSON::decode($post, TRUE);

       $collection_vault_id = $data['collection_vault_id'];
       $startDate = $data['startDate'];

       $endDate = $data['endDate'];

       $payment_list = new_payment_data::opening_get_new_payment_date_rang(1,$collection_vault_id, $data);

       $receipt_list = new_payment_data::opening_get_new_payment_date_rang(2,$collection_vault_id, $data);

       $cutomer_receipt_amount = new_payment_data::opening_receipt_from_customer_dae_range($collection_vault_id, $data);

       $farm_payment_list_between_date_rang = new_payment_data::opening_farm_payment_list_between_date_rang($collection_vault_id, $data);



       $payment = $payment_list + $farm_payment_list_between_date_rang;
       $recipt = $receipt_list + $cutomer_receipt_amount;
       $balance = $recipt - $payment ;
       $one_object = [];
       $one_object['date'] = 'Opening Blance';
       $one_object['payment'] = '';
       $one_object['recipt'] = '';
       $one_object['balance'] = $balance;



       $result = [];
       $result[] = $one_object;


       $x= strtotime($startDate);
       $y= strtotime($endDate);


       $total_payment = 0;
       $total_receipt = 0;

       while($x < ($y+8640)) {
           $one_object = array();
           $selectDate = date("Y-m-d", $x);
           $data['startDate'] = $selectDate;
           $x += 86400;

           $payment_list = new_payment_data::get_new_payment_one_day(1,$collection_vault_id, $data);

           foreach ($payment_list as $value){

               $payment = $value['amount_paid'];
               $vendor_name = $value['vendor_name'];
               $reference_no = $value['reference_no'];
               $recipt = 0;
               $balance = $balance + $recipt - $payment ;
               $one_object = [];
               $one_object['date'] = $selectDate;
               $one_object['vendor_name'] = $vendor_name;
               $one_object['reference_no'] = $reference_no;
               $one_object['payment'] = $payment;
               $one_object['recipt'] = $recipt;
               $one_object['balance'] = $balance;
               $result[] = $one_object;

               $total_payment = $total_payment + $payment;
               $total_receipt = $total_receipt + $recipt;
           }

           $receipt_list = new_payment_data::get_new_payment_one_day(2,$collection_vault_id, $data);


           foreach ($receipt_list as $value){

               $payment = 0;
               $vendor_name = $value['vendor_name'];
               $reference_no = $value['reference_no'];
               $recipt = $value['amount_paid'];
               $balance = $balance + $recipt - $payment ;
               $one_object = [];
               $one_object['date'] = $selectDate;
               $one_object['vendor_name'] = $vendor_name;
               $one_object['reference_no'] = $reference_no;
               $one_object['payment'] = $payment;
               $one_object['recipt'] = $recipt;
               $one_object['balance'] = $balance;
               $result[] = $one_object;
               $total_payment = $total_payment + $payment;
               $total_receipt = $total_receipt + $recipt;

           }


           $cutomer_receipt_amount = new_payment_data::receipt_from_customer_one_day($collection_vault_id, $data);

           foreach ($cutomer_receipt_amount as $value){

               $payment = 0;
               $vendor_name = $value['fullname'].'(Customer)';
               $reference_no = $value['remarks'];
               $recipt = $value['amount_paid'];
               $balance = $balance + $recipt - $payment ;
               $one_object = [];
               $one_object['date'] = $selectDate;
               $one_object['vendor_name'] = $vendor_name;
               $one_object['reference_no'] = $reference_no;
               $one_object['payment'] = $payment;
               $one_object['recipt'] = $recipt;
               $one_object['balance'] = $balance;
               $result[] = $one_object;

               $total_payment = $total_payment + $payment;
               $total_receipt = $total_receipt + $recipt;

           }


           $farm_payment_list = new_payment_data::farm_payment_list_between_one_day($collection_vault_id, $data);

           foreach ($farm_payment_list as $value){

               $payment = $value['amount'];
               $vendor_name = $value['farm_name'].'(Farm)';
               $reference_no = $value['remarks'];
               $recipt = 0;
               $balance = $balance + $recipt - $payment ;
               $one_object = [];
               $one_object['date'] = $selectDate;
               $one_object['vendor_name'] = $vendor_name;
               $one_object['reference_no'] = $reference_no;
               $one_object['payment'] = $payment;
               $one_object['recipt'] = $recipt;
               $one_object['balance'] = $balance;
               $result[] = $one_object;

               $total_payment = $total_payment + $payment;
               $total_receipt = $total_receipt + $recipt;

           }

          /* $payment = $payment_list + $farm_payment_list;
           $recipt = $receipt_list + $cutomer_receipt_amount;
           $balance = $balance + $recipt - $payment ;
           $one_object = [];
           $one_object['date'] = $selectDate;
           $one_object['payment'] = $payment;
           $one_object['recipt'] = $recipt;
           $one_object['balance'] = $balance;
           $result[] = $one_object;*/

       }

       $final_data = [];
       $final_data['list'] =$result;
       $final_data['total_payment'] =$total_payment;
       $final_data['total_receipt'] =$total_receipt;
       echo  json_encode($final_data);
      



   }


}