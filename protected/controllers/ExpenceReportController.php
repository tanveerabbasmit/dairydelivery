<?php

class ExpenceReportController extends Controller
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

	public function actionAddExpence()
	{
	    die('locked this page');
	    $get_data = $_GET;

	    if(isset($get_data['start_date'])){

        }


        date_default_timezone_set("Asia/Karachi");

        $todayDate = Date('Y-m-d');
        $end_date = Date('Y-m-d');

        $date_objetc =[];
        $date_objetc['start_date'] =$todayDate;
        $date_objetc['end_date'] =$end_date;

        if(isset($get_data['start_date'])){

            $date_objetc['start_date'] =$get_data['start_date'];
            $date_objetc['end_date'] =$get_data['end_date'];
        }



        $this->render('addExpence',array(

            'expencetype'=>dropClientReasonData::getExpenceList(),
            'expenceRecord'=>expenceReportData::getExpenceRecord($expenceType=false , $todayDate, $todayDate),
            'todayDate'=>json_encode($date_objetc)

        ));
	}
	public function actionsaveNewExpencesearchExpense(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);


        $startDate = $data['startDate'];
        $endDate = $data['endDate'];
       $expenses_type_id = $data['expenses_type_id'];



      echo   expenceReportData::getExpenceRecord($expenses_type_id, $startDate,$endDate);

    }

    public function actionsaveNewExpence(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);


        $expence_record_id = $data['expence_record_id'];

        $companBranchID =    Yii::app()->user->getState('company_branch_id');
          if($expence_record_id>0){

              $expenceObject = ExpenceReport::model()->findByPk($expence_record_id);

          }else{

              $expenceObject = new ExpenceReport();

          }

          $expenceObject->expenses_type_id  =$data['expence_type'] ;
          $expenceObject->activity  =$data['activity'] ;
          $expenceObject->date  =$data['date'] ;
          $expenceObject->remarks  =$data['remarks'] ;
          $expenceObject->amount  =$data['amount'] ;
          $expenceObject->company_id  = $companBranchID ;
          if( $expenceObject->save()){
              $riderID = $expenceObject->expence_record_id ;
              $query="SELECT * FROM expence_report AS er
               left join expence_type as et ON er.expenses_type_id = et.expence_type
               where er.expence_record_id = $riderID ";
              $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
               $responce = array();
               $responce['success'] =true ;
               $responce['message'] ='ok' ;
               $responce['rider'] =$queryResult ;

          }else{

              $responce = array();
              $responce['success'] =true ;
              $responce['message'] ='ok' ;
              $responce['rider'] =$expenceObject->getErrors() ;
          }
        echo json_encode($responce);
    }

    public function actioncheckDuplicateRiderUseName_delete_expence(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $expence_record_id = $data['expence_record_id'];

        $expence_object = ExpenceReport::model()->findByPk($expence_record_id);

        $expence_object->delete();



    }

    public function actionexpenses_summary(){

        $today_date = date("Y-m-d");
        $fiveDayAgo = date('Y-m-d', strtotime('-5 day', strtotime($today_date)));
        $company_id = Yii::app()->user->getState('company_branch_id');

        $copany_object = Company::model()->findByPk($company_id)->attributes;

        $query="select * 
                          from vendor as q
                         where q.company_id ='$company_id' ";
        $vendor_list =  Yii::app()->db->createCommand($query)->queryAll();


        $data = [] ;
        $data['today_date'] = $today_date ;
        $data['five_day_ago'] = $fiveDayAgo ;
        $data['vendor_list'] = $vendor_list ;
        $data['company_id'] = $company_id ;
        $data['copany_object'] = $copany_object ;

        $this->render('expenses_summary',array(
            'data'=>json_encode($data),
        ));
    }
    public function actionBase_get_expenses_summary_list(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $startDate = $data['startDate'];
        $endDate = $data['endDate'];


        $company_id = Yii::app()->user->getState('company_branch_id');
        $query="SELECT 
                et.type, 
                SUM(er.amount) as total_amount
             FROM expence_report AS er
            left join expence_type as et ON er.expenses_type_id = et.expence_type
            where er.company_id = '$company_id' ";


        $query .=" and er.date between '$startDate' and '$endDate'
          group BY er.expenses_type_id
          ";



        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
        $final_result = [];

        $grand_total_amount = 0;
        foreach ($queryResult as $value){
            $grand_total_amount = $grand_total_amount + $value['total_amount'];

        }
        $final_result['list_data'] = $queryResult;
        $final_result['grand_total_amount'] = $grand_total_amount;

        echo json_encode($final_result);

    }
    public function actiontransfer_expense(){
        $query="SELECT * FROM expence_report ";
             die('lock');
        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

         foreach($queryResult as $value){


            $payment_new_object  =New NewPayment();

             if($value['company_id']=='18'){

                 $payment_new_object->collection_vault_id ='2';

                 $payment_new_object->vendor_id ='61';
             }else{
                 $payment_new_object->collection_vault_id =0;

                 $payment_new_object->vendor_id =0;
             }

             $payment_new_object->vendor_type_id ='1';
             $payment_new_object->date =$value['date'];
             $payment_new_object->transaction_type ='Expence';
             $payment_new_object->expence_type =$value['expenses_type_id'];
             $payment_new_object->amount_paid =$value['amount'];
             $payment_new_object->reference_no =$value['remarks'];
             $payment_new_object->payment_or_receipt ='1';
             $payment_new_object->company_id =$value['company_id'];
             if($payment_new_object->save()){

             }else{
                 echo "<pre>";
                 print_r($payment_new_object->getErrors());
                 die();
             }
         }
    }

}