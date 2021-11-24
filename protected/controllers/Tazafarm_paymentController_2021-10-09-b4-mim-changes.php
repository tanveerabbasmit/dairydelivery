<?php

class Tazafarm_paymentController extends Controller
{
    public function actionsandbox_accounts()
    {

        $get_data = $_GET;

        $client_id = $get_data['client_id'];

        $ru =jazz_cash_payment::ger_var_function('ru');


        $client_object = Client::model()->findByPk($client_id);

        $amount =  APIData::calculateFinalBalance($client_id);


        $this->layout = false;
        $data=[];

        $data['current_amount']=round($amount,0);
        $data['outstanding_balance']=round($amount,0);
        $data['ru']=$ru;
        $data['base_url']= Yii::app()->baseUrl;
        $data['client_id']= $client_id;

        $this->render('sandbox_accounts_tazafarm',[
            'client_object'=>$client_object,
            'data'=>json_encode($data),
            'current_amount'=>round($amount,0),
            'client_id'=>$client_id,
            'ru'=>$ru
        ]);
    }

    public function actionconfirm_payment()
    {

        $get_data = $_GET;
        $client_id = $get_data['client_id'];
        $pp_TxnType = $get_data['pp_TxnType'];
        $ru =jazz_cash_payment::ger_var_function('ru');
        $outstaning_blance =  APIData::calculateFinalBalance($client_id);
        $client_object = Client::model()->findByPk($client_id);
        $amount =  $get_data['amount'];

        $this->layout = false;
        $this->render('confirm_payment',[
            'client_object'=>$client_object,
            'current_amount'=>$amount,
            'pp_TxnType'=>$pp_TxnType,
            'outstanding_balance'=>round($outstaning_blance,0),
            'client_id'=>$client_id,
            'ru'=>$ru
        ]);
    }
    public function actionreturn_page(){


        $post = $_POST;
        $data = [];
        $this->layout = false;

        $responce_data = [];
        $color ='red';

        $responce_data['icone']='fa fa-close';
        $responce_data['color']='red';
        $responce_data['client_id']=0;

        $client_id = 0;
        $ru =jazz_cash_payment::ger_var_function('ru');

        $responce_data['retun_utton_message']='Try Again';
        $responce_data['url'] = 'http://dairydelivery.conformiz.com/tazafarm_payment/sandbox_accounts?client_id='.$client_id;
        if($post){

            $responce_data['message'] = $post['pp_ResponseMessage'];
            $client_id = $post['pp_BillReference'];
            $responce_data['client_id']=$client_id;
            $pp_Amount = $post['pp_Amount'];
            $pp_TxnType = $post['pp_TxnType'];
            $pp_SecureHash='';
            if(isset($post['pp_SecureHash'])){

                $pp_SecureHash = $post['pp_SecureHash'];

            }
            $new_genrated_hash= secure_hash_data::get_secure_hash_on_return_page_testing_for_taza($post);

            $data['amount_paid'] = $pp_Amount/100;
            $data['client_id'] = $client_id;
            $data['payment_mode'] = 5;
            $data['remarks'] = 'Jazz Cash';
            $company_id =1;
            $responce_data['url'] = 'http://dairydelivery.conformiz.com/tazafarm_payment/sandbox_accounts?client_id='.$client_id;
            if(strtolower($pp_SecureHash)==strtolower($new_genrated_hash)){
                if($post['pp_ResponseCode']=='000'){

                    $data['amount_paid'] = milkkhasData::calculate_tex_amount($pp_TxnType,$data);
                    $responce_data['retun_utton_message']='Back';
                    $responce_data['icone']='fa fa-check';
                    $responce_data['color']='green';
                    $responce_data['message']=$post['pp_ResponseMessage'];
                    conformPayment::conformPaymentMethodFromApp($company_id , $data);
                    jazz_cash_payment::save_jazz_payment_reponce('seccessfull',$post);

                }elseif($post['pp_ResponseCode']=='124'){

                    $data['amount_paid'] = milkkhasData::calculate_tex_amount($pp_TxnType,$data);
                    $responce_data['retun_utton_message']='Back';
                    $responce_data['icone']='fa fa-clock-o';
                    $responce_data['color']='green';
                    $responce_data['message']=$post['pp_ResponseMessage'];
                    conformPayment::conformPaymentMethodFromApp($company_id , $data);
                    jazz_cash_payment::save_jazz_payment_reponce('seccessfull',$post);

                }else{


                    $pp_ResponseCode =  $post['pp_ResponseCode'];
                    $responce_data['message'] = secure_hash_data::get_responce_code($pp_ResponseCode)." - Error Code ". $pp_ResponseCode;

                    $error_code = ' Error Code-'.$pp_ResponseCode;

                    jazz_cash_payment::save_jazz_payment_reponce('Responce Code Error',$post);
                }
            }else{
                $responce_data['message'] ='SecureHash is not correct';
                jazz_cash_payment::save_jazz_payment_reponce('SecureHash Error',$post);
            }


        }else{

            if(isset($post['pp_ResponseMessage'])){
                $responce_data['message'] = $post['pp_ResponseMessage'];
            }else{


                $responce_data['message'] ="Some things wrong" ;
            }
            jazz_cash_payment::save_jazz_payment_reponce('Some things wrong',$post);
        }


        $this->layout = false;
        $this->render('return_result',[
            'data'=>$responce_data,
            'color'=>$color,
            'ru'=>$ru,

        ]);
    }


    public function actionQuality_report_view()
    {
        $get_data = $_GET;

        if(isset($get_data['today'])){
            $today_date = $get_data['today'];
        }else{
            $today_date = date("Y-m-d");
        }



        $date_object  = [];
        $date_object[]  = date('Y-m-d', strtotime('-1 day', strtotime($today_date)));
        $date_object[]  =  $today_date;
        $date_object[]  = date('Y-m-d', strtotime('+1 day', strtotime($today_date)));



        $query="SELECT p.*  from   product as p 
                where p.company_branch_id =4 and bottle = 0";

        $product_list =  Yii::app()->db->createCommand($query)->queryAll();

        $final_data = [];

        foreach ($product_list as $value){

            $product_id =  $value['product_id'];
            $name=  $value['name'];
            $qualityreport = array();

            $qualityreport['product_name'] =$name;
            $qualityreportObject = MilkDailyQuallityreport::model()->findByAttributes(array('company_branch_id'=>4 ,'date'=>$today_date ,'animal_type'=>$product_id));

            if($qualityreportObject){

                $qualityreport['protein'] =$qualityreportObject['protein'];
                $qualityreport['lactose'] =$qualityreportObject['lactose'];
                $qualityreport['fat'] = $qualityreportObject['fat'];
                $qualityreport['salt'] =$qualityreportObject['salt'];
                $qualityreport['adulterants'] =$qualityreportObject['adulterants'];
                $qualityreport['antiboitics'] =$qualityreportObject['antiboitics'];
                $qualityreport['company_id'] = 4 ;
                $qualityreport['date'] = $today_date ;
            }else{

                $qualityreport['protein'] ='';
                $qualityreport['lactose'] ='';
                $qualityreport['fat'] ='';
                $qualityreport['salt'] ='';
                $qualityreport['adulterants'] ='';
                $qualityreport['antiboitics'] ='';
                $qualityreport['company_id'] = 4 ;
                $qualityreport['date'] = $today_date ;
            }
            $final_data[] = $qualityreport;
        }






        $this->layout = false;
        $this->render('quality_report_view',[
            'final_data'=>$final_data,
            'date_object'=>$date_object

        ]);
    }

    public function actionFinal_message(){
        $this->layout = false;
        $data = [];
        $data['icone']='fa fa-check';
        $data['color']='green';
        $data['message']='updated successfuly';
        $data['message']='updated successfuly';
        $this->render('final_message',[
            'data'=>$data
        ]);
    }

}