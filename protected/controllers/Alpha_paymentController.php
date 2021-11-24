<?php

class Alpha_paymentController extends Controller
{
	public function actionAlpha_payment_return_page()
	{
	    $get =$_GET;

	    $RC =$get['RC'];
	    $order_id =$get['O'];

	    if($RC=='00'){
	        $payment_request =PaymentRequest::model()->findByAttributes([
	            'order_id'=>$order_id
            ]);
            $amount_paid = $payment_request['amount'];
            $payment_master_obeject = PaymentMaster::model()->findByAttributes([
                'pp_RetreivalReferenceNo'=>$order_id
            ]);
            $payment_request->status=1;
            $payment_request->save();

            $data =[
                'pp_RetreivalReferenceNo'=>$order_id,
                'amount_paid'=>$amount_paid,
                'client_id'=>$payment_request['client_id'],
                'payment_mode'=>5,
                'remarks'=>'alpha bank online payment'
            ];
             conformPayment::conformPaymentMethodFromApp(1, $data);

            $color ='red';
            $ru ='google.com';
            $responce_data['color']='green';
            $responce_data['icone']='fa fa-check';
            $responce_data['message']='THANKS YOU FOR USING ALPHA BANK,
                              YOUR TRANSACTION WAS SUCCESSFUL';
        }else{
            $color ='red';
            $ru ='google.com';
            $responce_data['color']='red';
            $responce_data['icone']='fa fa-times';
            $responce_data['message']='Some Thing Wrong';
        }



        $this->layout = false;
		$this->render('alpha_payment_return_page', [
            'data' => $responce_data,
            'color' => $color,
            'ru' => $ru,

        ]);
	}
	public function actionAlpha_payment_form(){
        $get_data = $_GET;

        $client_id = $get_data['client_id'];

        $ru = jazz_cash_payment::ger_var_function('ru');

        $client_object = Client::model()->findByPk($client_id);

        $amount =  APIData::calculateFinalBalance($client_id);

        $this->layout = false;
        $data = [];

        $data['current_amount'] = round($amount, 0);
        $data['outstanding_balance'] = round($amount, 0);
        $data['ru'] = $ru;
        $data['base_url'] = Yii::app()->baseUrl;
        $data['client_id'] = $client_id;

        $this->render('alpha_payment_form', [
            'client_object' => $client_object,
            'data' => json_encode($data),
            'current_amount' => round($amount, 0),
            'client_id' => $client_id,
            'ru' => $ru
        ]);
     }
     public function actionalpha_payment_confirm(){

         $get_data = $_GET;
         $client_id = $get_data['client_id'];
         $pp_TxnType = 0;
         $ru = jazz_cash_payment::ger_var_function('ru');
         $outstaning_blance =  APIData::calculateFinalBalance($client_id);
         $client_object = Client::model()->findByPk($client_id);
         $amount =  $get_data['amount'];
         $bankorderId   = date('YmdHis');
         $this->layout = false;
         $object =new PaymentRequest();
         $object->client_id =$client_id;
         $object->order_id =$bankorderId;
         $object->company_id =1;
         $object->status =0;
         $object->amount =$amount;
         if($object->Save()){

         }else{
             echo json_encode($object->getError());
             die();
         }

         $this->render('alpha_payment_confirm', [
             'client_object' => $client_object,
             'current_amount' => $amount,
             'pp_TxnType' => $pp_TxnType,
             'outstanding_balance' => round($outstaning_blance, 0),
             'client_id' => $client_id,
             'ru' => $ru,
             'bankorderId' => $bankorderId,
         ]);
     }


}