<?php

class AreaController extends Controller
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


	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionManageArea()
	{
        $this->render('ManageArea' , array(
            "zoneList"=>colorTagData::getAreaList(),
            "companyBranchList"=>companyBranchData::getCompanyBranchList(),
        ));
	}

    public function actionsaveNewTagColor_save_new()
    {
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $area_id =  $data['area_id'];

        if($area_id >0){

            $zone = Area::model()->findByPk($area_id);
            $zone->area_name = $data['area_name'];

            $zone->company_id = Yii::app()->user->getState('company_branch_id');
            if($zone->save()){
                zoneData::$response['success']=true;
                zoneData::$response['message']=$zone->area_id ;
            }else{
                zoneData::$response['success'] = false ;
                zoneData::$response['message'] = $zone->getErrors() ;
            }
            echo json_encode(zoneData::$response);

        }else{


            $zone=new Area();
            $zone->area_name= $data['area_name'];

            $zone->company_id = Yii::app()->user->getState('company_branch_id');

            if($zone->save()){

                zoneData::$response['success'] = true ;
                zoneData::$response['message'] = $zone->area_id;

            }else{
                zoneData::$response['success'] = false ;
                zoneData::$response['message'] = $zone->getErrors() ;
            }
            echo json_encode(zoneData::$response);

        }


    }

    public function actiondelete(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $sales_reps_id = $data['area_id'];


        $zone = Area::model()->findByPk($sales_reps_id);
        if($zone){

            if($zone->delete()){

                zoneData::$response['success'] = true ;

            }else{
                zoneData::$response['success'] = true ;
            }
        }else{
            zoneData::$response['success'] = true ;
        }


        echo json_encode(zoneData::$response);
    }
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Area;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Area']))
		{
			$model->attributes=$_POST['Area'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->area_id));
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

		if(isset($_POST['Area']))
		{
			$model->attributes=$_POST['Area'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->area_id));
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


	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Area');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Area('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Area']))
			$model->attributes=$_GET['Area'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Area the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Area::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Area $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='area-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
