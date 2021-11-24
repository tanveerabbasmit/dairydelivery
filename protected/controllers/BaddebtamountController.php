<?php

class BaddebtamountController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	public function actionBaddebtamount_form(){


        $company_id = Yii::app()->user->getState('company_branch_id');



        $query_discount = "select * from discount_type as dt
                 where dt.company_id ='$company_id'";

        $discount_type_result = Yii::app()->db->createCommand($query_discount)->queryAll();

        $collectionvault_list = blockData::get_collectionvault();



        $final_discount = array();
        $number =0;
        foreach ($discount_type_result as $value){
            $number++;
            $oneObject = array();
            $oneObject['discount_type_id'] = $value['discount_type_id'];
            $oneObject['discount_type_name'] = $value['discount_type_name'];
            $oneObject['discount_amount'] = '';
            $oneObject['discount_percentage'] = '';
            $oneObject['percentage'] = false;
            $oneObject['calculated_discount'] = '';
            $final_discount[] =$oneObject;
        }
        $todayMonth = Date('m');

        $todayYear = Date('Y');

        $required_name ='';
        if($company_id==18 or $company_id==20 or $company_id==21 or $company_id==22 ){
            $required_name='required';
        }
        $this->render('makePayment_bad_debt',array(
            // 'clientList'=>clientData::getActiveClientList_forLedger(),
            'clientList'=>json_encode(array()),
            'todayMonth'=>$todayMonth ,
            'todayYear'=>$todayYear ,

            'required_name'=>$required_name ,

            'discount_type'=>json_encode($final_discount),
            'collectionvault_list'=>$collectionvault_list,
            'crud_role'=>crudRole::getCrudrole(16) ,
        ));

    }
    public function actionbaddebtamount_save(){
        $post = file_get_contents("php://input");
         $data = CJSON::decode($post, TRUE);
        $company_id = Yii::app()->user->getState('company_branch_id');
        $object = new BadDebtAmount();
        $object->amount = $data['amount_paid'];
        $object->client_id = $data['client_id'];
        $object->date = $data['startDate'];
        $object->reference_no = $data['trans_ref_no'];
        $object->company_id = $company_id;

        if($object->save()){
            $success = true;
            $message = '';
        }else{
            $success = false;
            $message = $object->getErrors();
        }
        $responce = [];
        $responce['success'] = $success;
        $responce['message'] = $message;

        echo json_encode($responce);
    }



    public function actionbase__bad_debt_payment(){
        $post = file_get_contents("php://input");
       // $data = CJSON::decode($post, TRUE);

        $query = "SELECT 
            b.bad_debt_amount_id,
            b.amount,
            b.reference_no,
            b.date,
            c.fullname
            FROM bad_debt_amount  AS b
            LEFT JOIN client AS c ON c.client_id =b.client_id
            WHERE b.client_id = '$post' ";

        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

        echo json_encode($queryResult);
    }
    public function actionbase__bad_debt_update(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $bad_debt_amount_id = $data['bad_debt_amount_id'];

        $object = BadDebtAmount::model()->findByPk($bad_debt_amount_id);
        $object->amount = $data['amount'];

        $object->date = $data['startDate'];
        $object->reference_no = $data['trans_ref_no'];


        if($object->save()){
            $success = true;
            $message = '';
        }else{
            $success = false;
            $message = $object->getError();
        }
        $responce = [];
        $responce['success'] = $success;
        $responce['message'] = $message;

        echo json_encode($responce);
    }
    public function actionbase__delete_bad_payment(){
        $post = file_get_contents("php://input");
        $object = BadDebtAmount::model()->findByPk($post);
        if($object->delete()){
            $success = true;
            $message = '';
        }else{
            $success = false;
            $message = $object->getError();
        }
        $responce = [];
        $responce['success'] = $success;
        $responce['message'] = $message;

        echo json_encode($responce);
    }

    public function actionbaddebtamount_payment_report(){
	    $data =[];
	    $data['start_date'] =date('Y-m').'-01';
	    $data['end_date'] =date('Y-m-d');
        $this->render('baddebtamount_payment_report',array(
            'data'=>json_encode($data),
        ));
    }

    public function actionbase_bad_payment_list_all(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);



        $start_date  = $data['start_date '];
        $end_date  = $data['end_date '];
        $company_id = Yii::app()->user->getState('company_branch_id');
        $query = "SELECT 
            b.bad_debt_amount_id,
            b.client_id,
            b.amount,
            b.reference_no,
            b.date,
            c.fullname
            FROM bad_debt_amount  AS b
            LEFT JOIN client AS c ON c.client_id =b.client_id
            WHERE b.date between '$start_date' and '$end_date' and b.company_id ='$company_id'  ";

        $queryResult =  Yii::app()->db->createCommand($query)->queryAll();

         $result = [];
         $result['list'] = $queryResult;

        echo json_encode($result);
    }
    public function actiontransfer_badamount(){

	    $object =PaymentMaster::model()->findAllByAttributes([
	        'payment_type'=>1
        ]);

	    foreach ($object as $value){

	        $bad_object = new BadDebtAmount();
	        $bad_object->amount=intval($value['amount_paid']);
	        $bad_object->reference_no=$value['reference_number'];
	        $bad_object->date=$value['date'];
	        $bad_object->client_id=$value['client_id'];
	        $bad_object->company_id=$value['company_branch_id'];
	        if($bad_object->save()){

            }else{
	             echo "<pre>";
	             print_r($value);

	            echo "<pre>";
	            print_r($bad_object->getErrors());

            }
        }

    }
}
