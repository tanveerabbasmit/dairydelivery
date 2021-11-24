<?php

class VendorController extends Controller
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


	public function actionCreate()
	{
		$model=new Vendor;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Vendor']))
		{
			$model->attributes=$_POST['Vendor'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->vendor_id));
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

		if(isset($_POST['Vendor']))
		{
			$model->attributes=$_POST['Vendor'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->vendor_id));
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
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Vendor');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Vendor('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Vendor']))
			$model->attributes=$_GET['Vendor'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Vendor the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Vendor::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Vendor $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='vendor-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    public function actionvendor_list()
    {
        $vendor_type_list = vendor_type_data::get_vendor_type_list();

        $this->render('vendor_list' , array(
            "vendor_type_list"=>json_encode($vendor_type_list),
            "zoneList"=>vendor_list_data::get_vendor_list_all_type(),
            "companyBranchList"=>companyBranchData::getCompanyBranchList(),
        ));
    }
    public function actionsave_vendor_save_new(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $phone_no = $data['phone_no'];


        $company_id = Yii::app()->user->getState('company_branch_id');

        $vendor_id = $data['vendor_id'];
        if($vendor_id>0){
            $object =Vendor::model()->findByPk($vendor_id);
        }else{
            $object = New Vendor();
        }

        $object->vendor_name = $data['vendor_name'];
        $object->phone_no = $data['phone_no'];

        $object->payment_alert = $data['payment_alert'];
        $object->provide_bill_alert = $data['provide_bill_alert'];
        $object->is_active = $data['is_active'];
        $object->vendor_type_id = $data['vendor_type_id'];

        $object->company_id =$company_id;

        $responce = [];

        if(strlen($phone_no) !='13'){
            $responce['success'] =false;
            $responce['message'] ="number invalid";
            echo json_encode($responce);
            die();
        }
        if($object->save()){
            $responce['success'] =true;
        }else{
            $responce['success'] =true;
            $responce['message'] =$object->getErrors();
        }

        echo json_encode($responce);
    }

    public function actionsave_vendor_get_vendor_list(){
        $zoneList=vendor_list_data::get_vendor_list_all_type();
        echo $zoneList;
    }

    public function actionvendor_payasble_summary(){
        $today_date = date("Y-m-d");
        $fiveDayAgo = date('Y-m-d', strtotime('-5 day', strtotime($today_date)));
        $company_id = Yii::app()->user->getState('company_branch_id');

        $copany_object = Company::model()->findByPk($company_id)->attributes;



        $data = [] ;
        $data['today_date'] = $today_date ;
        $data['five_day_ago'] = $fiveDayAgo ;
        $data['vendor_list'] = [];
        $data['company_id'] = $company_id ;
        $data['copany_object'] = $copany_object ;

        $this->render('vendor_payasble_summary',array(
            'data'=>json_encode($data),
        ));
    }

    public function actionbase_get_vendor_payasble_summary(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $company_id = Yii::app()->user->getState('company_branch_id');

        $query="select * from vendor as a
                     where a.company_id =$company_id
                     order by a.vendor_name ASC ";

        $vendor_list =  Yii::app()->db->createCommand($query)->queryAll();

        $list = [];

        $total_opening = 0;
        $total_bill_amount = 0;
        $total_payment = 0;
        $total_balance = 0;
        foreach ($vendor_list as $value){
           $vendor_id = $value['vendor_id'];
           $vendor_name = $value['vendor_name'];

            $data['vendor_id'] = $vendor_id;
           $opening_purchase = vendor_stock_ledger::vendor_purchase_item($data,1);
           $opening_payment =  vendor_stock_ledger::vendor_payment($data,1);

           $opening_stock = $opening_purchase - $opening_payment;
          $purchse_date_range =  vendor_payasble_summary_data::vendor_purchase_date_range($data);


          $vendor_payment= vendor_payasble_summary_data::vendor_payment($data);

          $date_range_net = $purchse_date_range -$vendor_payment;

           $opening = $opening_purchase - $opening_payment;

           $balance = $date_range_net + $opening;

            $one_object = [];
            $one_object['opening_stock'] = $opening_stock;
            $one_object['purchse_date_range'] = $purchse_date_range;
            $one_object['vendor_payment'] = $vendor_payment;
            $one_object['balance'] = $balance;
            $one_object['vendor_name'] = $vendor_name;
            $list[] = $one_object;

            $total_opening =$total_opening +$opening_stock;

            $total_bill_amount = $total_bill_amount +$purchse_date_range;

            $total_payment = $total_payment + $vendor_payment;
            $total_balance = $total_balance+$balance;

        }

        $result =[];
        $result['data'] =$list;
        $result['total_opening'] =$total_opening;
        $result['total_bill_amount'] =$total_bill_amount;
        $result['total_payment'] =$total_payment;
        $result['total_balance'] =$total_balance;
        echo  json_encode($result);
    }
}
