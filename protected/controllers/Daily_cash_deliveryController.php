<?php

class Daily_cash_deliveryController extends Controller
{
	public function actionDaily_cash_delivery_form()
	{
        $company_id = Yii::app()->user->getState('company_branch_id');


        $project_list =productData::product_list(1);

        $company_id = Yii::app()->user->getState('company_branch_id');
        $companyObject = Company::model()->findByPk(intval($company_id));


        $data = [];
        $data['today_data'] = date("Y-m-d");
        $data['project_list'] = $project_list;
        $data['company_title'] =  $companyObject['company_title'];

        $this->render('daily_cash_delivery_form',array(

            'data'=>json_encode($data),

        ));

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