<?php

class PosShopController extends Controller
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


    public function actioncrudPosShop()
    {
        $this->render('crudPosShop' , array(
            "zoneList"=>colorTagData::getShopList(),
            "companyBranchList"=>companyBranchData::getCompanyBranchList(),
        ));
    }

    public function actionsaveNewPosShop_save_new(){

        $response = array(
            'success'=>false,
            'message'=>'',
        );

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $pos_shop_id =  $data['pos_shop_id'];

        if($pos_shop_id >0){

            $zone = PosShop::model()->findByPk($pos_shop_id);
            $zone->shop_name = $data['shop_name'];
            $zone->address = $data['address'];
            $zone->company_id = Yii::app()->user->getState('company_branch_id');
            if($zone->save()){

                $response['success']=true;
                $response['message']=$zone->pos_shop_id ;
            }else{
                $response['success'] = false ;
                $response['message'] = $zone->getErrors() ;
            }
            echo json_encode($response);

        }else {


            $zone = new PosShop();
            $zone->shop_name = $data['shop_name'];
            $zone->address = $data['address'];

            $zone->company_id = Yii::app()->user->getState('company_branch_id');

            if ($zone->save()) {

                $response['success']=true;
                $response['message']=$zone->pos_shop_id ;

            } else {
                $response['success'] = false ;
                $response['message'] = $zone->getErrors() ;
            }
            echo json_encode($response);
        }
    }
}
