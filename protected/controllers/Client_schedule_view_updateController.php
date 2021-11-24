<?php

class Client_schedule_view_updateController extends Controller
{
	public function actionClient_schedule_view()
	{


	    $data = [];
	    $data['product_list'] = productData::product_list();

        $this->render('client_schedule_view',array(
            'data' => json_encode($data)
        ));
	}

	public function actionbase_customer_lis(){
        echo  clientData::getActiveClientList_forLedger();
    }

    public function actionbase_schedule_list(){
        $post = file_get_contents("php://input");
        $data_get = json_decode($post,true);

        $data = [];
        $data['clientID'] = $data_get['client_id'] ;

        $data['productID'] = $data_get['product_id'] ;


        $order =  clientData::getOrderAgainstClint_product_wise($data);

        $order_type =0;
        $weekly_result =[];
        $order_type_name ='No selected';

        if($order){
            $order_type =$order['order_type'];

            if($order_type==1){

                $order_type_name = 'Weekly';

            }else{

                $order_type_name = 'Interval';

            }

        }


        if($order_type==2){
            $weekly_result =  clientData::selectFrequencyForOrderFunction_interval_for_export($data);


        }
        if($order_type==1){
            $weekly_list =  clientData::selectFrequencyForOrderFunction_for_report($data);

            $weekly_list_frequency_wise = [];

            foreach ($weekly_list as $weekly_value){

                $frequency_id = $weekly_value['frequency_id'];
                $weekly_list_frequency_wise[$frequency_id] = $weekly_value['quantity'];
            }

            $weekly_result = [];
            $frequency_list = Frequency::model()->findAll();
            foreach($frequency_list as $value_w){
                $value_w_one_object = [];
                $value_w_one_object['day_name'] =$value_w['day_name'];
                $value_w_one_object['quantity'] ='0';
               $frequency_id = $value_w['frequency_id'];
                //$value_w['quantity'] = '0';
                if(isset($weekly_list_frequency_wise[$frequency_id])){
                    $value_w_one_object['quantity'] = $weekly_list_frequency_wise[$frequency_id];
                }
                $weekly_result[] = $value_w_one_object;
            }

        }

        $final_end_result = [];

        $final_end_result['order_type'] = $order_type;
        $final_end_result['order_type_name'] = $order_type_name;
        $final_end_result['weekly_data'] = $weekly_result;

        echo  json_encode($final_end_result);
    }
}