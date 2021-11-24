<?php

class UblPaymentController extends Controller
{
    public function filters()
    {
        $user_id = Yii::app()->user->getState('user_id');
        if(!$user_id){
            $this->redirect(Yii::app()->user->returnUrl);
        }

        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }


    
	public $layout='//layouts/column2';

    public function actionregisterTransactionID(){

        try {

            $cert = "C:/xampp/htdocs/milk_Company/themes/milk/ipg/Demo Merchant 2020.pem";

            $pass = "Comtrust";

            $opts = array(
                'ssl' => array(

                    'verify_peer'       => true,

                    'verify_peer_name'  => true,

                    "cafile" => "C:/xampp/htdocs/milk_Company/themes/milk/ipg/ca-bundle.crt",

                    // 'ciphers'=>'RC4-SHA'

                )



            );

            $options = array(

                'trace' => 1,

                'keep_alive' => true,

                'exceptions' => 0,

                'soap_version' => SOAP_1_1,

                'local_cert' => $cert,

                'passphrase' => $pass,

                'stream_context' => stream_context_create($opts),

                'cache_wsdl' => WSDL_CACHE_NONE



            );

            $client = new SoapClient("C:/xampp/htdocs/milk_Company/themes/milk/ipg/MerchantAPI.xml", $options);
            //   $client = new SoapClient("https://demo-ipg.comtrust.ae:2443/MerchantAPI.svc?singleWsdl", $options);




            $params = array(

                'Register' => '',

                'request' => array(

                    'Customer' => 'Demo Merchant',

                    'Language' => 'en',

                    'version' => 2,

                    'Amount' => 10,

                    'Currency' => 'PKR',

                    'OrderID' => 1234563434,

                    'OrderInfo' => 141850,

                    'OrderName' => 141850,

                    'ReturnPath' => 'http://localhost/ipg/returntFile.php',

                    'TransactionHint' => 'VCC:Y'

                )

            );

            $result = $client->Register($params);


            echo json_encode($result);


            // var_dump($result);





        }

//catch exception
        catch(Exception $e) {
            echo $e;
        }

    }
    public function actionreturnurl(){
        echo 'testing';
    }
}
