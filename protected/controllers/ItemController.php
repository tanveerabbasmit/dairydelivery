<?php

class ItemController extends Controller
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

    public function actiondelete_delete_item(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $item_id =  $data['item_id'];

        $object = Item::model()->findByPk($item_id);

        $object->delete();

    }

    public function actionsaveNewTagColor_save_new()
    {
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $area_id =  $data['item_id'];

        if($area_id >0){

            $zone = Item::model()->findByPk($area_id);
            $zone->item_name = $data['item_name'];

            $zone->company_id = Yii::app()->user->getState('company_branch_id');
            if($zone->save()){
                zoneData::$response['success']=true;
                zoneData::$response['message']=$zone->item_id ;
            }else{
                zoneData::$response['success'] = false ;
                zoneData::$response['message'] = $zone->getErrors() ;
            }
            echo json_encode(zoneData::$response);

        }else{


            $zone=new Item();
            $zone->item_name= $data['item_name'];

            $zone->company_id = Yii::app()->user->getState('company_branch_id');

            if($zone->save()){

                zoneData::$response['success'] = true ;
                zoneData::$response['message'] = $zone->item_id;

            }else{
                zoneData::$response['success'] = false ;
                zoneData::$response['message'] = $zone->getErrors() ;
            }
            echo json_encode(zoneData::$response);

        }


    }

    public function actionitem_create()
    {

            die("This page locked");

        $this->render('manage_item' , array(
            "zoneList"=>item_list_data::get_item_List(),
            "companyBranchList"=>companyBranchData::getCompanyBranchList(),

        ));
    }


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
		$model=new Item;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Item']))
		{
			$model->attributes=$_POST['Item'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->item_id));
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

		if(isset($_POST['Item']))
		{
			$model->attributes=$_POST['Item'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->item_id));
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
		$dataProvider=new CActiveDataProvider('Item');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Item('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Item']))
			$model->attributes=$_GET['Item'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Item the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Item::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Item $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='item-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionTransfer_item(){
           die("locked");
	    $item = Item::model()->findAll();

	    foreach ($item as $value){
              $object = New ExpenceType();
              $object->type =$value['item_name'];
              $object->company_id =$value['company_id'];

              if($object->save()){

              }else{
                   echo "<pre>";
                   print_r($object->getErrors());
                   die();
              }

        }
    }
}
