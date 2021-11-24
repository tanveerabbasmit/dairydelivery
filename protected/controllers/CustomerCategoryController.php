<?php

class CustomerCategoryController extends Controller
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
				'actions'=>array('managecategory','view' ,'saveNewCategory','editZone' ,'delete'),
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
	public function actionmanagecategory()
	{

        $this->render('manageCategory' , array(
            "zoneList"=>categoryData::getCategoryList(),
            "companyBranchList"=>companyBranchData::getCompanyBranchList(),
        ));
	}

    public function actionsaveNewCategory(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);


        $company_id = Yii::app()->user->getState('company_branch_id');

        $category = new CustomerCategory();
        $category->company_branch_id = $company_id;
        $category->category_name  = $data['category_name'];
        $category->color_name  = $data['color_name'];

        if($category->save()){
            $zoneID = $category->customer_category_id;
            $query="SELECT z.*   from customer_category as z
                  LEFT JOIN company_branch as cb ON cb.company_branch_id = z.company_branch_id
                     where z.customer_category_id = $zoneID ";
            $queryResult =  Yii::app()->db->createCommand($query)->queryAll();
            zoneData::$response['success']=true;
            zoneData::$response['message']='ok';
            zoneData::$response['zone']=$queryResult;

        }else{
            zoneData::$response['success'] = false ;
            zoneData::$response['message'] = $category->getErrors() ;

        }
        echo  json_encode(zoneData::$response);

    }

    public function actioneditZone(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $zone = CustomerCategory::model()->findByPk($data['customer_category_id']);
        $zone->company_branch_id = Yii::app()->user->getState('company_branch_id');
        $zone->category_name  = $data['category_name'];
        $zone->color_name  = $data['color_name'];

        if($zone->save()){

            zoneData::$response['success']=true;
            zoneData::$response['message']='ok';


        }else{
            zoneData::$response['success'] = false ;
            zoneData::$response['message'] = $zone->getErrors() ;

        }
        echo  json_encode(zoneData::$response);
    }

    public function actiondelete(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $zone = CustomerCategory::model()->findByPk($data['customer_category_id']);
         if($zone->delete()){
             zoneData::$response['success']=true;
             zoneData::$response['message']='ok';
         }else{
             zoneData::$response['success'] = false ;
             zoneData::$response['message'] = $zone->getErrors() ;
         }

         echo json_encode(zoneData::$response);

    }

}
