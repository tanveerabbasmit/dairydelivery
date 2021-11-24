<?php

class Payment_in_outController extends Controller
{
	public function actionPayment_in_out_report_view()
	{
        date_default_timezone_set("Asia/Karachi");
        $today_date = date("Y-m-d");
        $data= array();
        $page = '';
        $this->render('payment_in_out_report_view',array(
            'productList'=>json_encode($data),
            'productCount'=>productData::getproductCount(),
            'today_date'=>json_encode($today_date),
        ));
	}

	public function actionPayment_in_out_report_view_report_view_list(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $start_date = $data['start_date'];
        $start_date = $data['end_date'];


        $delivery_list =  payment_in_out_data::total_delivery_between_date_range($data);

        $result = [];
        $result['delivery_list'] =$delivery_list;
        $result['make_payment'] =payment_in_out_data::make_total_payment($data);
        $result['pos'] =payment_in_out_data::get_pos_sale_data($data);
        $result['expence_type'] =payment_in_out_data::get_expence_type($data);
        $result['vendor_bill_amount'] =payment_in_out_data::vendor_bill_list($data);
        $result['vendor_payment'] =payment_in_out_data::vendor_payment($data);
        $result['farm_purchase'] =payment_in_out_data::farm_purchase($data);
        $result['final_balance'] =payment_in_out_data::calculate_final_balance($result);



        echo json_encode($result);

    }

    public function actionpayment_in_out_report_view_report_view_list_payment_list(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $start_date = $data['start_date'];
        $start_date = $data['end_date'];


    }



}