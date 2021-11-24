<?php

class FutureratelistController extends Controller
{
	public function actionFutureratelist_create()
	{
        $data=[];
        $data['start_date']=date('Y-m-d');
        $data['end_date']=date('Y-m-d');
        $data['product_list']=productData::getproductList_arrayForm();
        $data['base_url']=Yii::app()->createAbsoluteUrl('Futureratelist/base');;
        $this->render('futureratelist_create',array(
          'data'=>json_encode($data)
        ));


	}
    public function actionbase_all_customer_list(){
        echo  clientData::getActiveClientList_forLedger();
    }
    public function actionBase_save_future_rate(){
        $post = file_get_contents("php://input");
        $data = CJSON::decode($post, TRUE);
        $start_date =$data['start_date'];
        $end_date = $data['end_date'];
        $rate = $data['rate'];
        $client_id = $data['client_id'];

        $product_id = $data['product_id'];

         $object =new FutureRateList();

         $object->company_id =  Yii::app()->user->getState('company_branch_id');
         $object->client_id =$client_id;
         $object->start_date =$start_date;
         $object->end_date =$end_date;
         $object->rate =$rate;
         $object->product_id =$product_id;
         if($object->save()){
             $success =true;
             $message ='';
         }else{
             $success =true;
             $message =$object->getErrors();
         }
         $responce=[
             'success'=>$success,
             'message'=>$message
         ];
         echo json_encode($responce);
    }
    public function actionbase_rate_list_function(){
        $post = file_get_contents("php://input");
        $array = FutureRateList::model()->findAllByAttributes([
            'client_id'=>$post
        ]);
        $list =[];
        foreach ($array as $value){
            $list[] =$value->attributes;
        }

        echo  json_encode($list);

    }


}