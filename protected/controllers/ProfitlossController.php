<?php

class ProfitlossController extends Controller
{
	public function actionProfitloss_view()
	{
        $data =[];
        $data['start_date'] = date('Y-m-').'01';

        $data['end_date'] = date('Y-m-d');
        $data['fram_list'] = qualityListData::getFarmList_for_drop_down();

        $this->render('profitloss_view',[
            'data'=>json_encode($data)
        ]);
	}

	public function actionBase_url_Profitloss_view_list_data(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $total_sale =  profitloss_view_data::total_sold_stock($data);
        $purchased =  profitloss_view_data::total_purchased_stock_profitloss($data);

        $vendor_bill =  profitloss_view_data::Vendor_bills_function($data);
        $expence=  profitloss_view_data::expenses_function($data);

        $total_expence = $expence + $vendor_bill;

        $rate=  profitloss_view_data::expense_per_ltr($total_expence,$purchased);

        $grosss_income =$total_sale - $total_expence;

        $data = [];
        $data['total_sale'] = $total_sale;

        $data['purchased'] = $purchased;

        $data['vendor_bill'] = $vendor_bill;

        $data['expence'] = $expence;


        $data['rate_per_liter'] = $rate;

        $data['grosss_income'] = $grosss_income;

        echo json_encode($data);

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