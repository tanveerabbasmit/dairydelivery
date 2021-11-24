<?php

class ReferredcustomersController extends Controller
{
	public function actionreferredcustomers_view()
	{

         $data = [];

        $company_id = Yii::app()->user->getState('company_branch_id');
        $data['todayMonth'] = Date('m');
        $data['todayYear'] = Date('Y');
        $data['company_id'] = $company_id;
        $data['product_id'] = '0';
        $data['product_list'] = productData::product_list('1');
        $data['base'] = Yii::app()->createAbsoluteUrl('referredcustomers/referredcustomers_view_list');

        $this->render('referredcustomers_view',array(
            'data'=>json_encode($data)
        ));
	}

	public function actionreferredcustomers_view_list(){

        $post = file_get_contents("php://input");

        $data = CJSON::decode($post, TRUE);


        $todayYear = $data['todayYear'];
        $todayMonth = $data['todayMonth'];
        $product_id = $data['product_id'];

        $start_date =$todayYear.'-'.$todayMonth.'-01';
        $end_date = $todayYear.'-'.$todayMonth.'-31';

        $company_id = Yii::app()->user->getState('company_branch_id');

        $query = "SELECT 
            c.client_id,
            c.fullname,
            c.customer_source_id,
            c.customer_source_name as customer_source,
            cs.customer_source_name
            FROM  client AS c
            LEFT JOIN customer_source AS cs
            ON c.customer_source_id =cs.customer_source_id
            WHERE c.new_create_date 
            BETWEEN '$start_date' AND '$end_date'
            AND c.customer_source_name !=''
            AND c.company_branch_id = '$company_id' ";


        /*AND (c.customer_source_id >0
            || c.customer_source_name !='')*/

        $vendor_list =  Yii::app()->db->createCommand($query)->queryAll();



        $final_data = [];
        foreach ($vendor_list as $value){
           $customer_source = $value['customer_source'];
           $final_data[$customer_source][] = $value;
        }

        $final_result= [];
        foreach ($final_data as $value){
            $value[0]['no_of_customers'] =sizeof($value);
            $value[0]['refered_quantity'] =referredcustomers_data::get_total_of_refered_customer_quantity($start_date,$end_date,$value,$product_id);
            $final_result[]= $value[0];


        }


         $result =[];
         $result['list'] =$final_result;
        echo json_encode($result);

    }

    public function actionreferredcustomers_list(){
	    $get_data = $_GET;




        $data = [];

        $company_id = Yii::app()->user->getState('company_branch_id');
        $data['customer_source'] = $get_data['customer_source'];

        $data['product_id'] = $get_data['product_id'];

        $data['todayMonth'] = Date('m');
        $data['todayYear'] = Date('Y');
        $data['company_id'] = $company_id;
        $data['product_list'] = productData::product_list('1');
        $data['base'] = Yii::app()->createAbsoluteUrl('referredcustomers/referredcustomers_sale_quantity');

        $this->render('referredcustomers_list',array(
            'data'=>json_encode($data)
        ));


    }

    public function actionreferredcustomers_sale_quantity(){

        $post = file_get_contents("php://input");

        $data = CJSON::decode($post, TRUE);


        $todayYear = $data['todayYear'];
        $todayMonth = $data['todayMonth'];
        $product_id = $data['product_id'];
        $customer_source = $data['customer_source'];

        $start_date =$todayYear.'-'.$todayMonth.'-01';
        $end_date = $todayYear.'-'.$todayMonth.'-31';

        $company_id = Yii::app()->user->getState('company_branch_id');

        $object = Product::model()->findByPk($product_id);

        $selected_product = $object['name'];

        $query = "SELECT 
            c.client_id,
            c.fullname,
            c.address,
            c.cell_no_1,
            c.customer_source_id,
            c.customer_source_name as customer_source,
            cs.customer_source_name,
             z.name AS zone_name,
            pt.payment_term_name
            FROM  client AS c
            LEFT JOIN customer_source AS cs
              ON c.customer_source_id =cs.customer_source_id
                
             LEFT JOIN zone AS z ON z.zone_id = c.zone_id
            LEFT JOIN payment_term AS pt ON pt.payment_term_id = c.payment_term
            WHERE c.customer_source_name ='$customer_source'
            AND c.company_branch_id = '$company_id' ";



        $customer_list =  Yii::app()->db->createCommand($query)->queryAll();

        $final_data = [];

         foreach ($customer_list as $value){
             $one_object = [];
             $one_object['client_id'] =$value['client_id'];
             $one_object['fullname'] =$value['fullname'];
             $one_object['address'] =$value['address'];
             $one_object['cell_no_1'] =$value['cell_no_1'];
             $one_object['customer_source_id'] =$value['customer_source_id'];
             $one_object['customer_source'] =$value['customer_source'];
             $one_object['customer_source_name'] =$value['customer_source_name'];
             $one_object['zone_name'] =$value['zone_name'];
             $one_object['payment_term_name'] =$value['payment_term_name'];
             $one_object['first_delivery_on'] =referredcustomers_data::get_first_delivery_on($value['client_id'],$data);
             $one_object['last_delivery_on'] =referredcustomers_data::get_last_delivery_on($value['client_id'],$data);
             $one_object['selected_product'] =$selected_product;
             $quantiy_object  =referredcustomers_data::get_product_quantity($value['client_id'],$data);

             $one_object['quantity'] =$quantiy_object['quantity'];
             $one_object['amount'] =$quantiy_object['amount'];
             $one_object['rate'] =$quantiy_object['rate'];


             $final_data[] = $one_object;
         }

        $data = [];
        $data['final_data'] =$final_data;


        echo json_encode($data);


    }


}