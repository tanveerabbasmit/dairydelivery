<?php

class DiscountTypeController extends Controller
{
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

	public function actionIndex()
	{
       $this->render('discountType' , array(
            "zoneList"=>zoneData::getDiscountList(),
            "companyBranchList"=>companyBranchData::getCompanyBranchList(),
        ));
	}
	public function actionsaveNewZone_save_new()
	{
        $post = file_get_contents("php://input");

        $data = CJSON::decode($post, TRUE);



        $discount_type_id =  $data['discount_type_id'];

        if($discount_type_id >0){

            $zone = DiscountType::model()->findByPk($discount_type_id);
           // $zone->company_id = Yii::app()->user->getState('company_branch_id');
            $zone->discount_type_name  = $data['discount_type_name'];

            if($zone->save()){
                zoneData::$response['success']=true;
                zoneData::$response['message']=0;
            }else{
                zoneData::$response['success'] = false ;
                zoneData::$response['message'] = $zone->getErrors() ;
            }
            echo json_encode(zoneData::$response);

        }else{
             $zone=new DiscountType();
            $zone->company_id = Yii::app()->user->getState('company_branch_id');
            $zone->discount_type_name  = $data['discount_type_name'];
            if($zone->save()){
                zoneData::$response['success'] = true ;
                zoneData::$response['message'] = $zone->discount_type_id ;

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

        $discount_type_id = $data['discount_type_id'];

        $zone = DiscountType::model()->findByPk($discount_type_id);

        if($zone->delete()){

            zoneData::$response['success'] = true ;

        }else{
            zoneData::$response['success'] = false ;
        }

        echo json_encode(zoneData::$response);
    }


}