<?php

class Sample_scheduleController extends Controller
{
	public function actionSample_schedule_form()
	{
        $company_id = Yii::app()->user->getState('company_branch_id');
        $data = [];
        $data['action_date'] = date("Y-m-d");
        $this->render("sample_schedule_form",array(
            'data'=>json_encode($data)
        ));

	}

	public function actionbase_url_save_sample_schedule_function(){

    }


}