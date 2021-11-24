<?php

class TagColorController extends Controller
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
		$this->render('index');
	}

    public function actionMangeColorTag()
    {

        $this->render('MangeColorTag' , array(
            "zoneList"=>colorTagData::getColorTagList(),
            "companyBranchList"=>companyBranchData::getCompanyBranchList(),
        ));
    }

    public function actionsaveNewTagColor_save_new()
    {
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
         $tag_color_id =  $data['tag_color_id'];

        if($tag_color_id >0){

            $zone = TagColor::model()->findByPk($tag_color_id);
            $zone->tag_color_name = $data['tag_color_name'];
            $zone->tag_color_code = $data['tag_color_code'];
            $zone->company_id = Yii::app()->user->getState('company_branch_id');
            if($zone->save()){
                zoneData::$response['success']=true;
                zoneData::$response['message']=$zone->tag_color_id ;
            }else{
                zoneData::$response['success'] = false ;
                zoneData::$response['message'] = $zone->getErrors() ;
            }
            echo json_encode(zoneData::$response);

        }else{


            $zone=new TagColor();
            $zone->tag_color_name= $data['tag_color_name'];
            $zone->tag_color_code= $data['tag_color_code'];
            $zone->company_id = Yii::app()->user->getState('company_branch_id');

            if($zone->save()){

                zoneData::$response['success'] = true ;
                zoneData::$response['message'] = $zone->tag_color_id ;

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

        $sales_reps_id = $data['sales_reps_id'];

        $zone = DiscountType::model()->findByPk($sales_reps_id);
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
}