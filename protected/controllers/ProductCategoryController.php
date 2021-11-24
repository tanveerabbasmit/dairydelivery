<?php

class ProductCategoryController extends Controller
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
		$model=new ProductCategory;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ProductCategory']))
		{
			$model->attributes=$_POST['ProductCategory'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->product_category_id));
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

		if(isset($_POST['ProductCategory']))
		{
			$model->attributes=$_POST['ProductCategory'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->product_category_id));
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
	public function actionProduct_category_manage()
	{

		$this->render('product_category_manage',array(

            "zoneList"=>zoneData::get_product_category_list(),
            "companyBranchList"=>companyBranchData::getCompanyBranchList(),

		));
	}

	public function actionsaveNewZone_save_new(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $product_category_id = $data['product_category_id'];

        $object = New ProductCategory();
        if($product_category_id>0){
            $object =ProductCategory::model()->findByPk($product_category_id);
        }
        $company_id = Yii::app()->user->getState('company_branch_id');
        $object->company_id = $company_id;

        $object->category_name = $data['category_name'];

        $object->save();

    }

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new ProductCategory('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ProductCategory']))
			$model->attributes=$_GET['ProductCategory'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return ProductCategory the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=ProductCategory::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param ProductCategory $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='product-category-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
