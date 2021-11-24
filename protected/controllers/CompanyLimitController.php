<?php

class CompanyLimitController extends Controller
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
        $user_id = Yii::app()->user->getState('user_id');
        if(!$user_id){
            $this->redirect(Yii::app()->user->returnUrl);
        }

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
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view' ,'customerList' ,'nextPagePagination','startDateSearchCustomerList','startDateSearchCustomerList_all'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actioncustomerList()
    {
             $chekall = false;
           if(isset($_GET['all'])){
               $chekall = true;
           }
        $company_id = Yii::app()->user->getState('company_branch_id');
        if(isset($_POST['companyLimit'])){
             $companyLimit = $_POST['companyLimit'];
            $companyLimitObject =Company::model()->findByPk($company_id);
            $companyLimitObject->limit_amount = $companyLimit ;
            $companyLimitObject->save();
            if($companyLimitObject->save()){

            }else{

            }

        }
         date_default_timezone_set("Asia/Karachi");

         $todaydate =Date("Y-m-d");

         $count = Client::model()->findAll(array("condition"=>"company_branch_id=$company_id"));
          $total = count($count);
 		  $this->render('customerList',array(
                'customerList'=>customerListData::getcompanyLimitcustomerList($chekall , $todaydate),
                'companyLimit'=>customerListData::getCommpanyLimit(),
               'totalResult'=>$total ,
          ));
	}

	public function actionstartDateSearchCustomerList(){
	  $post = file_get_contents("php://input");


       echo customerListData::getcompanyLimitcustomerList($chekall=false , $post);
    }

    public function actionstartDateSearchCustomerList_all(){
	  $post = file_get_contents("php://input");


       echo customerListData::getcompanyLimitcustomerList_all($chekall=false , $post);
    }
    public function actionnextPagePagination(){
	  $post = file_get_contents("php://input");
       echo customerListData::getcompanyLimitcustomerList($post ,$customerName = false );
    }

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new CompanyLimit;


		if(isset($_POST['CompanyLimit']))
		{
			$model->attributes=$_POST['CompanyLimit'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->company_limit_id));
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

		if(isset($_POST['CompanyLimit']))
		{
			$model->attributes=$_POST['CompanyLimit'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->company_limit_id));
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
		$dataProvider=new CActiveDataProvider('CompanyLimit');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}


	public function actionAdmin()
	{
		$model=new CompanyLimit('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CompanyLimit']))
			$model->attributes=$_GET['CompanyLimit'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function loadModel($id)
	{
		$model=CompanyLimit::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}


	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='company-limit-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
