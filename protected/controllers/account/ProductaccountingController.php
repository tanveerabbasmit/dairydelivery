<?php

class ProductaccountingController extends Controller
{

    public function actionbase_get_account_list(){
        $token = accounting_data::auth_token();

        $token_data = json_decode($token ,true);
        $token =  $token_data['data']['token'];
        $account_list = accounting_data::account_list($token);

        $account_list = json_decode($account_list,true);

        if(!$account_list['success']){
            // echo $account_list['message'];
             //die();
        }
        $account =$account_list['data'];


        $account_list = [];

        foreach ($account as $value){
            if($value['is_leaf']==1){
                $account_list[] =$value;
            }
            foreach($value['children'] as $value_2){
                if($value_2['is_leaf']==1){
                    $account_list[] =$value_2;
                }
                foreach($value_2['children'] as $value_3){
                    if($value_3['is_leaf']==1){
                        $account_list[] =$value_3;
                    }
                    foreach($value_3['children'] as $value_4){
                        if($value_4['is_leaf']==1){
                            $account_list[] =$value_4;
                        }
                    }
                }
            }
        }



       /*  $one_object = [];
         $one_object['id'] =1;
         $one_object['name'] ='assets';
         $account_list[] =$one_object;

         $one_object = [];
         $one_object['id'] =2;
         $one_object['name'] ='income';
         $account_list[] =$one_object;*/

         echo json_encode($account_list);
    }
	public function actionadd_product_account()
	{




        $this->render('add_product_account',array(
            'productList'=>productData::getproductList($page =false),
            'productCount'=>productData::getproductCount(),
            'account_list'=>json_encode([]),

        ));


	}

	public function actionbase_save_account_function(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);

        $product_id = $data['product_id'];
        $object = Product::model()->findByPk($product_id);

        $object->product_sale_account_id = $data['product_sale_account_id'];
        $object->product_receivable_account_id = $data['product_receivable_account_id'];
        $object->product_sale_account_name = $data['product_sale_account_name'];
        $object->product_receivable_account_name = $data['product_receivable_account_name'];
        $object->is_active =1;

        if($object->save()){

        }else{
             echo "<pre>";
             print_r($object->getErrors());
             die();
        }



    }


}