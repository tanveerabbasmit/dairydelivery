<?php

class BlockController extends Controller
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

	public function actiondelete(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $block_id = $data['block_id'];
        $object = Block::model()->findByPk($block_id);
        $object->delete();


    }
	public function actionsaveNewZone_save_new(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);


        $company_id = Yii::app()->user->getState('company_branch_id');
        $block_id = $data['block_id'];
        if($block_id>0){
            $object =Block::model()->findByPk($block_id);
        }else{
            $object = New Block();
        }

        $object->block_name = $data['block_name'];
        $object->company_id =$company_id;
        $object->save();


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
		$model=new Block;

		if(isset($_POST['Block']))
		{
			$model->attributes=$_POST['Block'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->block_id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}


	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);



		if(isset($_POST['Block']))
		{
			$model->attributes=$_POST['Block'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->block_id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}




	/**
	 * Lists all models.
	 */
	public function actionblock_create()
	{
        $this->render('block_create' , array(
            "zoneList"=>blockData::getBlockList(),
            "companyBranchList"=>companyBranchData::getCompanyBranchList(),
        ));
	}

	public function actionsaveNewZone_get_block_list(){
        $zoneList=blockData::getBlockList();
        echo  $zoneList;
    }

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Block('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Block']))
			$model->attributes=$_GET['Block'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Block the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Block::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Block $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='block-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
