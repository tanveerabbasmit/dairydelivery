<?php

class Category_wise_customer_bill_receivedController extends Controller
{
	public function actionCustomer_bill_received_category_wise()
	{


        $company_id = Yii::app()->user->getState('company_branch_id');

        $clientQuery = "select count(*) as total from client r
               where r.company_branch_id ='$company_id' ";

        $clientResult =  Yii::app()->db->createCommand($clientQuery)->queryAll();
        $clientResult =  $clientResult[0]['total'];

        $getCategoryList = categoryData::getCategoryList();



        $this->render('customer_bill_received_category_wise',array(
            'riderList_list'=>$getCategoryList,
            'riderList'=>$clientResult,
            'company_id'=>$company_id,
        ));
	}

	public static function actioncategory_wsie_customer_bill_report(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);


      
        $query="SELECT 
            c.client_id,
            c.fullname,
            c.cell_no_1,
            c.address
            from client AS c
            WHERE c.customer_category_id = '$data'  ";

        $clientResult = Yii::app()->db->createCommand($query)->queryAll();

        $result= [];

        foreach ($clientResult as $value){
          $client_id = $value['client_id'];

        $queery_payment = " SELECT 
            p.amount_paid,
            p.date,
            p.client_id
            FROM payment_master AS p
            WHERE p.client_id ='$client_id'
            ORDER BY p.date DESC
            LIMIT 1 ";

            $clientResult = Yii::app()->db->createCommand($queery_payment)->queryRow();

            $value['action_date'] = $clientResult['date'];
            $value['amount_paid'] = round($clientResult['amount_paid'],0);


            $result[] = $value;
        }

        $final_data = [];
        $final_data['list'] =$result;

        echo json_encode($final_data);
    }


}