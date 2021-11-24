<?php

class ComplainController extends Controller
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
        $actionsList =appConstants::getActionList();
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view' , 'saveStatus' ,'nextPageForpagination' ,
                    'searchComplain' , 'totalComplainOfOneCustomer' ,'getComplainType'),
				'users'=>array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>$actionsList,
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


	public function actionmanageComplains()
	{


		$this->render('manageComplains',array(
		//	'clientComplainList'=>clientComplainData::getclientComplainDataList($page = 1),
		//	'clientComplainCount'=>clientComplainData::getclientComplainDataCOUNT(),

			'statusList'=>clientComplainData::getStatusList(),

		));
	}

	public function actiongetComplainType(){

	    $post = file_get_contents("php://input");

        $data = CJSON::decode($post , true);

        echo clientComplainData::getclientComplainDataList($data);

    }

	public function actionnextPageForpagination(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        echo clientComplainData::getclientComplainDataList($post );
    }

    public function actionsaveStatus(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo clientComplainData::saveStatus($data);
    }
    public function actionsearchComplain(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo clientComplainData::searchComplainFunction($post);
    }

    public function actiontotalComplainOfOneCustomer(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo clientComplainData::totalComplainOfOneCustomerFunction($post);

    }


	public function actionCreate()
	{
		$model=new Complain;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Complain']))
		{
			$model->attributes=$_POST['Complain'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->complain_id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}



	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Complain']))
		{
			$model->attributes=$_POST['Complain'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->complain_id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}




	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}



	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Complain');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Complain('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Complain']))
			$model->attributes=$_GET['Complain'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}


	public function loadModel($id)
	{
		$model=Complain::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Complain $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='complain-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
