<?php

class CustomerSourceController extends Controller
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


    public function actionmanage_source()
    {
        $this->render('manage_source' , array(
            "zoneList"=>customer_source::getCustomer_source_list(),
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

	public function actiondelete(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $customer_source_id = $data['customer_source_id'];
        $object = CustomerSource::model()->findByPk($customer_source_id);
        $object->delete();



    }
    public function actionsaveNewTagColor_save_new()
    {
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $customer_source_id =  $data['customer_source_id'];

        if($customer_source_id >0){

            $zone = CustomerSource::model()->findByPk($customer_source_id);
            $zone->customer_source_name = $data['customer_source_name'];

            $zone->company_id = Yii::app()->user->getState('company_branch_id');
            if($zone->save()){
                zoneData::$response['success']=true;
                zoneData::$response['message']=$zone->customer_source_id ;
            }else{
                zoneData::$response['success'] = false ;
                zoneData::$response['message'] = $zone->getErrors() ;
            }
            echo json_encode(zoneData::$response);

        }else{


            $zone=new CustomerSource();
            $zone->customer_source_name= $data['customer_source_name'];

            $zone->company_id = Yii::app()->user->getState('company_branch_id');

            if($zone->save()){

                zoneData::$response['success'] = true ;
                zoneData::$response['message'] = $zone->customer_source_id;

            }else{
                zoneData::$response['success'] = false ;
                zoneData::$response['message'] = $zone->getErrors() ;
            }
            echo json_encode(zoneData::$response);

        }


    }
}
