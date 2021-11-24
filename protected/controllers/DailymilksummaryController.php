<?php

class DailymilksummaryController extends Controller
{
	public function actionDailymilksummary_view_report()
	{
        $company_id = Yii::app()->user->getState('company_branch_id');


        $project_list =productData::product_list(1);

        $company_id = Yii::app()->user->getState('company_branch_id');
        $companyObject = Company::model()->findByPk(intval($company_id));


        $data = [];
        $data['today_data'] = date("Y-m-d");
        $data['project_list'] = $project_list;
        $data['company_title'] =  $companyObject['company_title'];;
        $this->render('dailymilksummary_view_report',array(
            'data'=>json_encode($data),


        ));

	}

	public function actiondailymilksummary_view_report_data(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);



        $company_id = Yii::app()->user->getState('company_branch_id');
        $today_data = $data['today_data'];
        $product_id =$data['product_id'];
        $grand_total_purchase = 0;
        $opening_stock =  dailymilksummary_view_report_data::carry_forward_data($product_id,$company_id,$today_data);



        $production_stock =  dailymilksummary_view_report_data::today_production($product_id,$company_id,$today_data);



        $total_purchase = dailymilksummary_view_report_data::get_total_purchase_farm($product_id,$company_id,$today_data);



        $total_production = $production_stock['morning'] + $production_stock['afternoun']+ $production_stock['evenining'];

        $grand_total_purchase = $opening_stock +$total_production +$total_purchase['grand_total'];

        $rider_return =  dailymilksummary_view_report_data::rider_return_sale($product_id,$company_id,$today_data);

        $one_day_credit_sale_list =  dailymilksummary_view_report_data::one_day_credit_function($product_id,$company_id,$today_data);

        $total_sale =  $one_day_credit_sale_list['total'];

        $in_house_usage =  dailymilksummary_view_report_data::in_house_usage($product_id,$company_id,$today_data);

        $total_in_home_uses =$in_house_usage['total_in_home_uses'];

        $grand_total_sale_and_use = $total_sale + $total_in_home_uses;

        $next_day_carry =$grand_total_purchase - $grand_total_sale_and_use;
        $result = [];
        $result['opening_stock'] = $opening_stock;
        $result['production_stock'] = $production_stock;
        $result['total_production'] = $total_production;
        $result['rider_return'] = $rider_return;
        $result['rider_return_size'] = sizeof($rider_return);
        $result['one_day_credit_sale_list'] = $one_day_credit_sale_list;
        $result['in_house_usage'] = $in_house_usage;
        $result['purchase_list'] = $total_purchase;
        $result['purchase_list_size'] = sizeof($total_purchase['final_rresult']);

        $result['grand_total_stock_in'] = $grand_total_purchase;

        $result['grand_total_sale_and_use'] = $grand_total_sale_and_use;
        $result['next_day_carry'] = $next_day_carry;

        echo json_encode($result);


    }



}