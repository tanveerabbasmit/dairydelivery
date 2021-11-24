<?php

class RiderwisesaleController extends Controller
{
	public function actionRiderwisesale_view()
	{
        $today_date = date("Y-m-d");
        $fiveDayAgo = date('Y-m-d', strtotime('-5 day', strtotime($today_date)));
        $company_id = Yii::app()->user->getState('company_branch_id');

        $rider_list =  riderDailyStockData::getRiderList();

        $main_deaing = [];

        foreach ($rider_list as $value){
            $main_deaing[] ='Quantity';
            $main_deaing[] ='Amount';
        }



        $this->render('riderwisesale_view',array(
            'main_deaing'=>json_encode($main_deaing),
            'riderList'=>json_encode(riderDailyStockData::getRiderList()),
            'company_id'=>$company_id,
            'fiveDayAgo'=>$fiveDayAgo,
        ));
	}

	public function actiondate_range_rider_sale(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $start_date = $data['startDate'];
        $end_date = $data['endDate'];
        $x= strtotime($start_date);
        $y= strtotime($end_date);
        $main_object  = [];
        while($x < ($y+8640)) {
            $one_object = [];
            $selectDate = date("Y-m-d", $x);
            $one_object['date'] = $selectDate;
            $one_object['sale_list'] = riderwisesale_view_data::get_sale_list_of_rider($selectDate);
            $main_object[] = $one_object;
            $x += 86400;
        }


        $final_result = [];

       // $final_result['grand_total']= riderwisesale_view_data::total_count($main_object);

        $final_result['list']=riderwisesale_view_data::sale_and_quantity_find_function($main_object);

        $final_result['grand_total']= riderwisesale_view_data::total_count_quantity_amount( $final_result['list']);



        $final_result['grand_total_quantity']= riderwisesale_view_data::total_count_quantity_amount_quantity($final_result['grand_total']);
        echo json_encode($final_result);
    }


}