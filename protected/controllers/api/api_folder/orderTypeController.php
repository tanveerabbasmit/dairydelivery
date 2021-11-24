<?php

/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 10/2/2017
 * Time: 6:25 PM
 */
class OrdertypeController extends Controller
{


    public function sendResponse($data)
    {
        echo  json_encode($data);
    }

    public function actiongetCustomerRegularOrderType()
    {

        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
            $client_id = $data['client_id'];
           $product_id = $data['product_id'];

         $clientSchedulerObject  = ClientSchedulerType::model()->findByAttributes(array('client_id'=>$client_id , 'product_id'=>$product_id));
          if($clientSchedulerObject){
            $type  =  $clientSchedulerObject['client_scheduler_type'];
          }else{
              $type = 0 ;
          }
        $response = array(
            'code' => 401,
            'company_branch_id' => 0,
            'success' => true,
            'message' => '',
            'data' => $type
        );




        $this->sendResponse($response);


    }
}