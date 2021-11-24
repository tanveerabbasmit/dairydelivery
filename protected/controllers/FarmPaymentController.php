<?php

class FarmPaymentController extends Controller
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

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */


	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new FarmPayment;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['FarmPayment']))
		{
			$model->attributes=$_POST['FarmPayment'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->farm_payment_id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['FarmPayment']))
		{
			$model->attributes=$_POST['FarmPayment'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->farm_payment_id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionFarm_make_payment()
	{
	    $data = [];
        $company_id = Yii::app()->user->getState('company_branch_id');
        $query="select * 
                          from farm as q
                         where q.company_id ='$company_id' ";
        $farm_list =  Yii::app()->db->createCommand($query)->queryAll();


	    $data['farm_list'] =$farm_list;
	    $data['today_date'] = date("Y-m-d");
        Yii::app()->session["view"] = 0;
        $this->render('farm_make_payment' , array(
            "zoneList"=>zoneData::getZoneList(),
            "companyBranchList"=>json_encode($data),
        ));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new FarmPayment('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['FarmPayment']))
			$model->attributes=$_GET['FarmPayment'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
    public function actionbase_url_get_one_farm_payment(){

        $farm_id = file_get_contents("php://input");

        $object = "SELECT * FROM farm_payment AS p
            LEFT join farm AS f ON p.farm_id = f.farm_id
            WHERE p.farm_id = '$farm_id'
            ORDER BY p.action_date DESC";




        $payment = Yii::app()->db->createCommand($object)->queryAll();

       echo json_encode($payment);

    }

    public function actionbase_url_farm_payment_delete(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $company_id = Yii::app()->user->getState('company_branch_id');
        $farm_payment_id = $data['farm_payment_id'];

        $object= FarmPayment::model()->findByPk($farm_payment_id);

        $object->delete();



    }
	public  function actionbase_url_save_payment(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $farm_payment_id = $data['farm_payment_id'];

        $farm_id = $data['farm_id'];

        $company_id = Yii::app()->user->getState('company_branch_id');
        $responce = [];
        $responce['success']=true;
        if($farm_payment_id>0){

            $security_code = $data['security_code'];
            if($security_code !='74123'){
                $responce['success']=false;
                $responce['message']='Security Code is ';
                echo json_encode($responce);
                die();

            }
            $object = FarmPayment::model()->findByPk($farm_payment_id);
            $new_object = $object->attributes;
        }else{
            $object = New FarmPayment();
        }

        $action_date =  $data['action_date'];

        $object->farm_id = $data['farm_id'];
        $object->amount = $data['amount'];
        $object->action_date = $data['action_date'];
        $object->remarks = $data['remarks'];
        $object->reference_no = $data['reference_no'];
        $object->payment_mode = $data['payment_mode'];
        $object->company_id = $company_id;

        if($object->save()){
            $responce['success']=true;


            if($farm_payment_id>0){

                save_every_crud_record::save_crud_record_date_waise(
                    'farm_payment',
                    'farm_payment',
                    $farm_payment_id,
                    $data['action_date'],
                    json_encode($new_object),
                    $data['amount'],
                    $data['farm_id'],
                    $data['remarks']
                );


            }else{

                /*$companyObject  =  utill::get_companyTitle($company_id);
                $companyMask = $companyObject['sms_mask'];
                $companyTitle = $companyObject['company_title'];
                $message = ",\nWe have received Rs.". $data['amount']." from you.\nReference no.".$data['reference_no']."\n";

                $message .="\n".$companyTitle;

                $farm_object = Farm::model()->findByPk($farm_id);

                echo "<pre>";
                print_r($farm_object);
                die();

                manageSendSMS::vendor_sms_function($num, $message , $companyMask , $company_id , 1,$cleint_id=false);*/


                $companyObject  =  utill::get_companyTitle($company_id);

                $companyMask = $companyObject['sms_mask'];

                $farm_id =  $data['farm_id'];


                $object = Farm::model()->findByPk($farm_id);

                $phoneNo =  $object['phone_number'];
                $farm_name =  $object['farm_name'];

                $companyTitle = $companyObject['company_title'];

                $amount = $data['amount'];
                $reference_no =  $data['reference_no'];
                $message = "Payment Alert: Dear $farm_name on $action_date
                 payment of Rs $amount ,$reference_no made to you. ";


                if($object['payment_alert']==1){

                    manageSendSMS::vendor_sms_function($phoneNo , $message , $companyMask , $company_id ,0,$farm_id);

                    smsLog::saveSms($farm_id ,$company_id ,$phoneNo ,$farm_name ,$message);

                }


            }
        }else{

            $responce['success']=false;
            $responce['message']=$object->getErrors();
        }
        echo json_encode($responce);
        die();
    }

    public function actionfarm_ledger(){

        $data = [];
        $company_id = Yii::app()->user->getState('company_branch_id');
        $copany_object = Company::model()->findByPk($company_id)->attributes;
        $query="select * 
                          from farm as q
                         where q.company_id ='$company_id' ";
        $farm_list =  Yii::app()->db->createCommand($query)->queryAll();
        $data['farm_list'] =$farm_list;
        $data['today_date'] = date("Y-m-d");
        $data['copany_object'] = $copany_object;

        $this->render('farm_ledger',array(
            'clientList'=>json_encode($data),
        ));
    }

    public function actiongetClientLedgherReport(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post ,True);

        echo farm_payment_data::payment_ladger($data);
    }



}
