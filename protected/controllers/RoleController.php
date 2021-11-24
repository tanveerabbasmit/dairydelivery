<?php

class RoleController extends Controller
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
				'actions'=>array('index','view' , 'saveNewRole' ,'editRole' ,'delete' ,'getAssignRoleManu' ,'changeRole'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
			//	'actions'=>$actionsList,
		//		'users'=>array('@'),
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
	public function actionassignRole()
	{
		$this->render('assignRole',array(
			'assignRole'=>assignRoleData::getRoleList(),
			'menuList'=>assignRoleData::getMenuList(),
		));
	}

    public function actionsaveNewRole(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo assignRoleData::saveNewRoleFunction($data);
    }

    public function actioneditRole(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo assignRoleData::editRoleFunction($data);
    }
    public function actiondelete(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo assignRoleData::deleteFunction($data);
    }

    public function actiongetAssignRoleManu(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $menuList= assignRoleData::getMenuList_for_Crud($data);
        $crudList= assignRoleData::crudList($data);

         // echo json_encode($crudList);
          // die();

        $crudObject = [

             ['name'=>'Create' ,'crud_id'=>1,'selected'=>false],
             ['name'=>'Delete' ,'crud_id'=>2,'selected'=>false],
             ['name'=>'Update' ,'crud_id'=>3,'selected'=>false],

        ];


         $result = array();
        foreach($menuList as $value){

                  $oneObjectMenu = array();
                  $oneObjectMenu['menu_name'] = $value['menu_name'];
                  $oneObjectMenu['module_action_role_id'] = $value['module_action_role_id'];
                   if($value['assignTo']){
                       $value['assignTo'] = true ;
                   }else{
                       $value['assignTo'] = false ;
                   }



                $module_action_role_id= $value['module_action_role_id'];
                $crudObject_one_menu = array();
               foreach ($crudObject as $crudValue){
                   $crudOneObject = array();
                  $crud_id = $crudValue['crud_id'];

                   $crudOneObject['name'] = $crudValue['name'] ;
                   $crudOneObject['crud_id'] = $crudValue['crud_id'];
                   $crudOneObject['module_action_role_id'] = $module_action_role_id;
                    if(isset($crudList[$module_action_role_id][$crud_id])){

                        $crudOneObject['selected'] = true ;
                    }else{
                        $crudOneObject['selected'] = false ;
                    }
                   $crudObject_one_menu[] = $crudOneObject;
               }
            $oneobject = array();
            $oneobject['menu']= $value ;
            $oneobject['crud']=$crudObject_one_menu ;

             $result[]=$oneobject ;
        }


           echo json_encode($result);
            die();


        echo assignRoleData::getAssignRoleManuFunction($data);
    }
    public function actionchangeRole(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        echo assignRoleData::saveChangeRoleFunction($data);
    }
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='role-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
