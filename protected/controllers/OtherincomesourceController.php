<?php

class OtherincomesourceController extends Controller
{
	public function actionOther_income_source_list()
	{

        $this->render('other_income_source_list' , array(
            "zoneList"=>vendor_list_data::get_other_source_list(),
            "companyBranchList"=>companyBranchData::getCompanyBranchList(),
        ));
	}
	public function actionsave_income_source_save_new(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $other_income_source_id = $data['other_income_source_id'];
        $company_id = Yii::app()->user->getState('company_branch_id');
         if($other_income_source_id>0){

             $object = OtherIncomeSource::model()->findByPk(1);


         }else{
             $object = New OtherIncomeSource();
         }


        $object->other_income_source_name = $data['other_income_source_name'];
        $object->status = $data['status'];


        $object->company_id = $company_id;

        $responce =[];
        if($object->save()){
            $responce['success'] =true;
        }else{
            $responce['success'] =false;
            $responce['message'] =$object->getErrors();
        }
        echo json_encode($responce);

    }
    public function actionsave_income_source_get_vendor_list(){
         $list =  vendor_list_data::get_other_source_list();
         echo $list;
    }

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}