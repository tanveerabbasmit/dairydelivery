<?php

class VendorPaymentController extends Controller
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

	public function actionvendor_payment_create(){

        return $this->redirect(['Paymentnew/paymentnew_form','payment_or_receipt'=>1]);
        $data = [];
        $company_id = Yii::app()->user->getState('company_branch_id');
        $query="select * 
                          from vendor as q
                         where q.company_id ='$company_id' ";
        $farm_list =  Yii::app()->db->createCommand($query)->queryAll();


        $data['farm_list'] =$farm_list;
        $data['today_date'] = date("Y-m-d");
       // Yii::app()->session["view"] = 0;
        $this->render('vendor_payment_create' , array(
            "zoneList"=>zoneData::getZoneList(),
            "companyBranchList"=>json_encode($data),
        ));

    }

    public function actionbase_url_get_one_farm_payment(){

        $farm_id = file_get_contents("php://input");

        $object = "SELECT * FROM vendor_payment AS p
             LEFT join vendor AS f ON p.vendor_id = f.vendor_id
             WHERE p.vendor_id = '$farm_id'
             ORDER BY p.action_date DESC";



        $payment = Yii::app()->db->createCommand($object)->queryAll();
        echo json_encode($payment);

    }

    public  function actionbase_url_save_payment(){


        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $vendor_payment_id = $data['vendor_payment_id'];

        $company_id = Yii::app()->user->getState('company_branch_id');

        $responce = [];
        $responce['success']=true;
        if($vendor_payment_id>0){

            $security_code = $data['security_code'];
            if($security_code !='74123'){
                $responce['success']=false;
                $responce['message']='Security Code is ';
                echo json_encode($responce);
                die();

            }
            $object = VendorPayment::model()->findByPk($vendor_payment_id);
            $new_object = $object->attributes;
        }else{
            $object = New VendorPayment();
        }

        $object->vendor_id = $data['vendor_id'];
        $object->amount = $data['amount'];
        $object->action_date = $data['action_date'];
        $object->remarks = $data['remarks'];
        $object->reference_no = $data['reference_no'];
        $object->payment_mode = $data['payment_mode'];
        $object->company_id = $company_id;

        if($object->save()){
            $responce['success']=true;


            $vendor_id =  $data['vendor_id'];
            $vendor_object = Vendor::model()->findByPk($vendor_id);
            $phoneNo = $vendor_object['phone_no'];
            $vendor_name = $vendor_object['vendor_name'];
            $companyObject  =  utill::get_companyTitle($company_id);
            $companyMask = $companyObject['sms_mask'];
            $companyTitle = $companyObject['company_title'];
            $action_date = $data['action_date'] ;
            $amount =   $data['amount'];
            $reference_no =   $data['reference_no'];


            if($vendor_payment_id>0){

                save_every_crud_record::save_crud_record_date_waise(
                    'farm_payment',
                    'vendor_payment',
                    $vendor_payment_id,
                    $data['action_date'],
                    json_encode($new_object),
                    $data['amount'],
                    $data['vendor_id'],
                    $data['remarks']
                );

                $message = "Payment Alert: Dear $vendor_name, Changes have been
                 on $action_date.Ignore pervious SMS for this date.A payment 
                 of Rs $amount ,Refernce : $reference_no  made to you .";
                //$message = "We have paid Rs.".$data['amount']."  you.\nReference no.".$data['reference_no']."\n";
                $message .="\n".$companyTitle;

            }else{
                $message = "Payment Alert: Dear $vendor_name on $action_date payment 
                 of Rs $amount ,Refernce : $reference_no  made to you .";
                //$message = "We have paid Rs.".$data['amount']."  you.\nReference no.".$data['reference_no']."\n";
                $message .="\n".$companyTitle;


            }

            if($vendor_object['payment_alert']==1){
                manageSendSMS::vendor_sms_function($phoneNo , $message , $companyMask , $company_id ,0,$vendor_id);
                smsLog::saveSms($vendor_id ,$company_id ,$phoneNo ,$vendor_name ,$message);

            }




        }else{

            $responce['success']=false;
            $responce['message']=$object->getErrors();
        }
        echo json_encode($responce);
        die();
    }
    public  function actionbase_url_delete_payment(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $vendor_payment_id = $data['vendor_payment_id'];

        $company_id = Yii::app()->user->getState('company_branch_id');
        $responce = [];
        $responce['success']=true;


        $security_code = $data['security_code'];
        if($security_code !='74123'){
            $responce['success']=false;
            $responce['message']='Security Code is ';
            echo json_encode($responce);
            die();

        }
        $object = VendorPayment::model()->findByPk($vendor_payment_id);





        if($object->delete()){
            $responce['success']=true;


        }else{

            $responce['success']=false;
            $responce['message']=$object->getErrors();
        }
        echo json_encode($responce);
        die();
    }


	public function loadModel($id)
	{
		$model=VendorPayment::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param VendorPayment $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='vendor-payment-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
