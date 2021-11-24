<?php

class ComplainTypeController extends Controller
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
				'actions'=>array('index','view'  , 'saveNewComplian' , 'EditComplain' , 'deleteComplainType'),
				'users'=>array('*'),
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

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionmanageComplainType()
	{
		$this->render('manageComplainType',array(
			'ComplainTypeList'=>complainTypeData::getcomplainTypeList(),
		));
	}

    public function actionsaveNewComplian(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo complainTypeData::saveNewComplainFunction($data);

    }

    public function actionEditComplain(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo complainTypeData::editComplainFunction($data);

    }
    public function actiondeleteComplainType(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo complainTypeData::deleteComplainTypeFunction($post);

    }

}
