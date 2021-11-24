<?php

class Daily_collection_reportController extends Controller
{
	public function actionDaily_collection_report_view()
	{
        $company_id = Yii::app()->user->getState('company_branch_id');


        $project_list =productData::product_list(1);

        $company_id = Yii::app()->user->getState('company_branch_id');
        $companyObject = Company::model()->findByPk(intval($company_id));


        $data = [];
        $data['today_data'] = date("Y-m-d");
        $data['project_list'] = $project_list;
        $data['company_title'] =  $companyObject['company_title'];;
        $this->render('daily_collection_report_view',array(
            'data'=>json_encode($data),


        ));

	}

	public static function actionbasedaily_collection_report_view_data(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $today_data = $data['today_data'];


       $query = "SELECT pm.client_id, pm.date , pm.payment_master_id, pm.user_id,
            pm.rider_id,pm.reference_number,
            pm.payment_mode ,IFNULL((pm.amount_paid) ,0)  as amount_paid FROM payment_master as pm
            where   pm.company_branch_id = '1'
            and pm.client_id in (-1,11829,2881,13390,14114,4598,4061,4341,3975,9437,9439,9511,3962,2392,81,57,2813,423,13842,3094,12283,3920,3172,12580,3890,12097,4984,15380,12624,4305,4599,15542,10772,3475,3528,11605,5241,9401,133,11189,3445,3479,138,9502,161,3352,3513,125,5417,3722,3143,3551,4520,15550,3279,131,5419,3312,3919,2351,3474,2705,143,64,4516,5028,153,136,12376,146,3690,3650,3128,4080,5183,5019,3049,2850,3310,3473,10097,160,3311,3756,11243,5418,67,129,4614,906,4216,4610,3490,5290,13701,4772,163,3518,75,12553,13753,9551,23,11649,141,142,11549,13162,123,60,12722,12402,11352,58,5067,710,150,10712,11831,302,13544,13791,297,13563,12061,753,399,305,71,304,295,3723,66,404,54,301,10725,456,13318,12046,405,12734,3452,9412,745,3297,5394,56,9668,13163,303,5291,386,4208,3938,3901,3145,5572,5524,2704,11421,4329,4959,4990,3894,4180,12519,3623,3622,10703,2814,13304,10660,14107,11870,12654,9596,13264,4914,4402,12777,11018,10231,74,841,4028,12487,12125,3765,10711,334,372,13114,4268,2817,12095,5350,5349,12716,12647) and pm.date
            between '2021-06-11' and '2021-06-11' ";


    }


}