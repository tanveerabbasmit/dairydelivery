<?php

class SalespurchasesummaryController extends Controller
{
	public function actionSalespurchasesummary_view()
	{

          $base_url =  Yii::app()->createAbsoluteUrl('Salespurchasesummary/baseurl');
          $data = [];
          $data['base_url'] =$base_url;

          $data['start_date'] =date("Y-m-")."01";
          $data['end_date'] =date("Y-m-d");
          $data['product_list'] =productData::product_list();

		$this->render('salespurchasesummary_view',[
		    'data'=>json_encode($data)
        ]);
	}

	public static function actionbaseurl_get_salespurchasesummary_view(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $start_date = $data['start_date'];
        $end_date = $data['end_date'];
        $product_id = $data['product_id'];

        $x= strtotime($start_date);
        $y= strtotime($end_date);

        $final_result = [];
        $total_sale_lts = 0;
        $total_sale_amount = 0;
        $total_sale_price_ltr = 0;
        $total_purchase_amount = 0;
        $total_purchase_ltrs = 0;
        $total_price_per_ltr = 0;
        $total_sale_purchase = 0;
        $total_expenses = 0;
        while($x < ($y+8640)) {
            $one_object = array();


            $selectDate = date("Y-m-d", $x);
            $one_object['date'] =$selectDate;
            $sale_object = sales_purchase_summary_data::get_total_sale_between_date_range($data,$selectDate);
            $one_object['sale_object'] =$sale_object;
            $purchase_object = sales_purchase_summary_data::get_purchase_list_between_date_range($data,$selectDate);
            $get_total_expence = sales_purchase_summary_data::get_total_expence($data,$selectDate);
            $one_object['purchase_object'] =$purchase_object;
            $one_object['sale_Purchase'] =$sale_object['amount'] - $purchase_object['total_price'];
            $one_object['get_total_expence'] =$get_total_expence;

            $final_result[] =$one_object;


            $total_sale_lts = $total_sale_lts + $sale_object['quantity'];
            $total_sale_amount = $total_sale_amount +$sale_object['amount'];
            $total_sale_price_ltr =$total_sale_price_ltr +$sale_object['rate'];
            $total_purchase_amount = $total_purchase_amount +$purchase_object['total_price'];
            $total_purchase_ltrs = $total_purchase_ltrs +$purchase_object['total_quantity'];

            $total_sale_purchase = $total_sale_purchase +$one_object['sale_Purchase'];
            $total_expenses =$total_expenses +$get_total_expence;

            $x += 86400;
        }

        $total_object = [];
        $total_object['total_sale_lts'] = $total_sale_lts;
        $total_object['total_sale_amount'] = $total_sale_amount;
        $total_object['total_sale_price_ltr'] = $total_sale_price_ltr;
        $total_object['total_purchase_amount'] = $total_purchase_amount;
        $total_object['total_purchase_ltrs'] = $total_purchase_ltrs;
        $total_object['total_sale_purchase'] = $total_sale_purchase;
        $total_object['total_expenses'] = $total_expenses;

        $result = [];
        $result['data_list'] = $final_result;
        $result['total_object'] = $total_object;

        echo json_encode($result);

    }
}