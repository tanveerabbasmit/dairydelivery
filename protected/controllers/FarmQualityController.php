<?php

class FarmQualityController extends Controller
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

    public function actionqualityValue(){

        $company_id = Yii::app()->user->getState('company_branch_id');

        $this->render('qualityValue',array(
            "farmList"=>qualityListData::getFarmList(),
            "Quality"=>json_encode(array()),

        ));
    }
    public function actionvalueList(){

        $company_id = Yii::app()->user->getState('company_branch_id');

        $today_date =Date("Y-m-d");

         $data = array();

        $data['date'] = $today_date;
        $data['end_date'] = $today_date;

        $this->render('valueList',array(
            "farmList"=>qualityListData::getFarmList_with_quality_value($data),
            "Quality"=>qualityListData::getqualityList(),

        ));
    }

    public function actiongetFarmQualityValue(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        echo qualityValueData::get_farm_quality($data);
    }
    public function actiongetFarmQualityValue_farmValueList(){
            $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

            echo qualityListData::getFarmList_with_quality_value($data);
     }



    public function actiongetFarmQualityValue_saveValue(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        echo qualityValueData::farm_quality_save($data);
    }

}