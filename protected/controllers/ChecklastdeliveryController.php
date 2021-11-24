<?php

class ChecklastdeliveryController extends Controller
{
	public function actionCheck_last_date_delivery()
	{

		$this->render('check_last_date_delivery');
	}

	public function actionlast_delivery_customer_list(){

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

         $endDate= $data['endDate'];
    }


}