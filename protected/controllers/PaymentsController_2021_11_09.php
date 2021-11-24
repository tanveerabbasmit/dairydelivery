<?php

// define('PAYMENT_RETURN_URL', 'https://dairydelivery.conformiz.com/payments/returnPage');
// define('PAYMENT_RETURN_URL', 'https://dairydelivery.conformiz.com/tazafarm_payment/return_page');

define('JAZCASH_RETURN_URL', 'https://dairydelivery.conformiz.com/payments/returnPageJazzCash');
define('JAZZCASH_MERCHANT_ID', 'MC24616');
define('JAZZCASH_PASSWORD', '9h91yscyb0');
define('JAZZCASH_HASH_KEY', 'v2cg58xy0w');
define('JAZZCASH_SANDBOX', true);
define(
    'JAZZCASH_PAY_URL',
    JAZZCASH_SANDBOX ?
        'https://sandbox.jazzcash.com.pk/CustomerPortal/transactionmanagement/merchantform' :
        'https://payments.jazzcash.com.pk/CustomerPortal/transactionmanagement/merchantform'
);

define('ALPHAPAY_RETURN_URL', 'https://dairydelivery.conformiz.com/payments/returnPageAlphaPay');
define('ALPHAPAY_MERCHANT_KEY1', 'FDXw6wympD852njT');
define('ALPHAPAY_MERCHANT_KEY2', '2113793511884894');
define('ALPHAPAY_MERCHANT_ID', '7443');
define('ALPHAPAY_CHANNEL_ID', '1001');
define('ALPHAPAY_STORE_ID', '015707');
define('ALPHAPAY_MERCHANT_HASH', 'OUU362MB1uqAD7xz9tWIksz4VGxclcxsYvfI+z5z0CJexu4cSowc5xINhoFvPZ3dsaqmG3FdxZ/3R0aRXsdodCwi1OYDOULW');
define('ALPHAPAY_MERCHANT_USER', 'ihufyr');
define('ALPHAPAY_MERCHANT_PASS', 'EtWWjqnUMa5vFzk4yqF7CA==');
define('ALPHAPAY_SANDBOX', true);
define(
    'ALPHAPAY_POST_URL',
    ALPHAPAY_SANDBOX ?
        'https://sandbox.bankalfalah.com/' :
        'https://payments.bankalfalah.com/'
);

class PaymentsController extends Controller
{
    public function actionPaymentInitForm()
    {
        $client = $this->getClient();

        $this->view('1-payment-form', [
            'clientId' => $client->client_id,
            'client' => $client,
            'gateway' => $this->getVar('gateway'),
            'paymentMethod' => $this->getVar('paymentMethod'),
            'balance' => round(APIData::calculateFinalBalance($client->client_id)),
        ]);
    }

    public function actionConfirm()
    {
        $client = $this->getClient();
        $amount = (float) $this->postVar('amount');
        $gateway = $this->postVar('gateway');
        $paymentMethod = $this->postVar('paymentMethod');
        $balance = round(APIData::calculateFinalBalance($client->client_id));

        if ('jazzcash' == $gateway) {
            $this->payByJazzCash($client, $paymentMethod, $amount, $balance);
        } elseif ('alphapay' == $gateway) {

            $this->payByAlphaPay($client, $paymentMethod, $amount, $balance);
        } else {
            $this->dieWithError('Payment gateway is not defined!');
        }
    }

    private function payByJazzCash($client, $paymentMethod, $amount, $balance)
    {
        $formData = [
            'pp_Amount' => $amount * 100,
            'pp_BillReference' => $client->client_id,
            'pp_Description' => 'Payment for outstanding balance.',
            'pp_Language' => 'EN',
            'pp_MerchantID' => JAZZCASH_MERCHANT_ID,
            'pp_Password' => JAZZCASH_PASSWORD,
            'pp_ReturnURL' => JAZCASH_RETURN_URL,
            'pp_TxnCurrency' => 'PKR',
            'pp_TxnDateTime' => date('YmdHis'),
            'pp_TxnExpiryDateTime' =>  date('YmdHis', strtotime('+8 Days')),
            'pp_TxnRefNo' => 'T' . date('YmdHis'),
            'pp_TxnType' => $paymentMethod,
            'pp_Version' => '1.1',
        ];
        ksort($formData);
        $Securehash = hash_hmac('sha256', JAZZCASH_HASH_KEY . '&' . implode('&', $formData), JAZZCASH_HASH_KEY);
        $formData['pp_SecureHash'] = $Securehash;

        // die('<pre>' . print_r($formData, 1) . '</pre>');

        $this->view('2-payment-confirm-jazzcash', [
            'clientId' => $client->client_id,
            'client' => $client,
            'paymentMethod' => $paymentMethod,
            'amount' => $amount,
            'balance' => $balance,
            'paymentUrl' => JAZZCASH_PAY_URL,
            'formData' => $formData,
        ]);
    }

    private function payByAlphaPay($client, $paymentMethod, $amount, $balance)
    {
        $transRefNo = date('YmdHis') . $client->client_id;


        $handshake = $this->alphapayHandshake($transRefNo, ALPHAPAY_RETURN_URL);

        if (is_string($handshake)) {
            $this->dieWithError($handshake);
        }

        $formData = [
            'AuthToken' => $handshake->authToken,
            'RequestHash' => $handshake->requestHash,
            'ChannelId' => ALPHAPAY_CHANNEL_ID,
            'Currency' => 'PKR',
            'IsBIN' => 0,
            'ReturnURL' => $handshake->returnUrl,
            'MerchantId' => ALPHAPAY_MERCHANT_ID,
            'StoreId' => ALPHAPAY_STORE_ID,
            'MerchantHash' => ALPHAPAY_MERCHANT_HASH,
            'MerchantUsername' => ALPHAPAY_MERCHANT_USER,
            'MerchantPassword' => ALPHAPAY_MERCHANT_PASS,
            'TransactionTypeId' => $paymentMethod,
            'TransactionReferenceNumber' => $transRefNo,
            'TransactionAmount' => $amount,
        ];

        $mapString = '';
        foreach ($formData as $key => $val) {
            $mapString .= '&' . $key . '=' . $val;
        }
        $mapString = substr($mapString, 1);

        $formData['RequestHash'] = base64_encode(openssl_encrypt($mapString, 'aes-128-cbc', ALPHAPAY_MERCHANT_KEY1, OPENSSL_RAW_DATA, ALPHAPAY_MERCHANT_KEY2));

        $object = new PaymentRequest();
        $object->client_id = $client->client_id;
        $object->order_id = $transRefNo;
        $object->company_id = 1;
        $object->status = 0;
        $object->amount = $amount;
        if (!$object->Save()) {
            $this->dieWithError($object->getError());
        }

        // die('<pre>' . print_r($formData, 1) . '</pre>');

        $this->view('2-payment-confirm-alphapay', [
            'clientId' => $client->client_id,
            'client' => $client,
            'paymentMethod' => $paymentMethod,
            'amount' => $amount,
            'balance' => $balance,
            'paymentUrl' => ALPHAPAY_POST_URL . 'SSO/SSO/SSO',
            'formData' => $formData,
        ]);
    }

    private function alphapayHandshake($transRefNo, $returnUrl)
    {
        $data = [
            "HS_RequestHash" => '',
            "HS_IsRedirectionRequest" => 0,
            "HS_ChannelId" => ALPHAPAY_CHANNEL_ID,
            "HS_ReturnURL" => $returnUrl,
            "HS_MerchantId" => ALPHAPAY_MERCHANT_ID,
            "HS_StoreId" => ALPHAPAY_STORE_ID,
            "HS_MerchantHash" => ALPHAPAY_MERCHANT_HASH,
            "HS_MerchantUsername" => ALPHAPAY_MERCHANT_USER,
            "HS_MerchantPassword" => ALPHAPAY_MERCHANT_PASS,
            "HS_TransactionReferenceNumber" => $transRefNo,
        ];

        $mapString = '';
        foreach ($data as $key => $val) {
            $mapString .= '&' . $key . '=' . $val;
        }
        $mapString = substr($mapString, 1);

        $data['HS_RequestHash'] = base64_encode(openssl_encrypt($mapString, 'aes-128-cbc', ALPHAPAY_MERCHANT_KEY1,   OPENSSL_RAW_DATA, ALPHAPAY_MERCHANT_KEY2));

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, ALPHAPAY_POST_URL . 'HS/HS/HS');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $res = curl_exec($ch);

        try {
            $handshake =  json_decode($res);
        } catch (Exception $e) {
            return $e->getMessage();
        }

        if (!isset($handshake->AuthToken) || !isset($handshake->ReturnURL)) {
            return 'Handshake failed with Alpha server!'; // . '<pre>' . print_r([$data, $handshake], 1) . '</pre>';
        }

        return (object)[
            'requestHash' => $data['HS_RequestHash'],
            'authToken' => $handshake->AuthToken,
            'returnUrl' => $handshake->ReturnURL,
        ];
    }

    public function actionReturnPageJazzCash()
    {
        // die('<pre>' . print_r([$_GET, $_POST, $_SERVER], 1) . '</pre>');

        $respCode = $this->postVar('pp_ResponseCode');
        $respMsg = $this->postVar('pp_ResponseMessage');


        $success = '000' == $respCode;

        // TODO: Mark order complete here if required

        $data = [
            'heading' => 'Error in Payment',
            'icon' => 'fa fa-exclamation-triangle',
            'color' => 'red',
            'message' => $respMsg,
        ];

        if ($success) {
            $message_data = [
                'heading' => 'Payment Successful',
                'icon' => 'fa fa-check-circle',
                'color' => 'green',
                'message' => 'Your payment was successfully made!<br /><br />Thank you for your order.',
            ];


            $clientId = $this->postVar('pp_BillReference');
            $transRefNo = $this->postVar('pp_TxnRefNo');
            $pp_Amount = $this->postVar('pp_Amount');

            $pp_TxnType = $this->postVar('pp_TxnType');

            $pp_RetreivalReferenceNo = $this->postVar('pp_RetreivalReferenceNo');
            $data =[];
            $data['amount_paid'] = $pp_Amount / 100;
            $data['client_id'] = $clientId;
            $data['payment_mode'] = 5;
            $data['remarks'] = 'Jazz Cash';
            if($respCode=='000'){
                $payment_master_obeject = PaymentMaster::model()->findByAttributes([
                    'pp_RetreivalReferenceNo'=>$pp_RetreivalReferenceNo
                ]);
                $data['amount_paid'] = milkkhasData::calculate_tex_amount($pp_TxnType, $data);
                $data['pp_RetreivalReferenceNo'] =$pp_RetreivalReferenceNo;
                $company_id =1;
                if(!$payment_master_obeject){
                    conformPayment::conformPaymentMethodFromApp($company_id, $data);
                    jazz_cash_payment::save_jazz_payment_reponce('seccessfull', $_POST);
                }
            }
        }

        $this->view('3-return-page', [
            'data' =>$message_data,
        ]);
    }

    public function actionReturnPageAlphaPay()
    {
        die('<pre>' . print_r([$_GET, $_POST, $_SERVER], 1) . '</pre>');

        $success = isset($_GET['RC']) && '00' == $_GET['RC'];
        $transRefNo = isset($_GET['O']) ? $_GET['O'] : '';

        // TODO: Mark order complete here if required

        $data = [
            'heading' => 'Error in Payment',
            'icon' => 'fa fa-exclamation-triangle',
            'color' => 'red',
            'message' => 'Your payment was not successful!<br /><br />Please contact support for assistance.',
        ];

        if ($success) {

            $data = [
                'heading' => 'Payment Successful',
                'icon' => 'fa fa-check-circle',
                'color' => 'green',
                'message' => 'Your payment was successfully made!<br /><br />Thank you for your order.',
            ];


            $payment_request =PaymentRequest::model()->findByAttributes([
                'order_id'=>$transRefNo
            ]);
            $amount_paid = $payment_request['amount'];
            $payment_master_obeject = PaymentMaster::model()->findByAttributes([
                'pp_RetreivalReferenceNo'=>$transRefNo
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


        }

        $this->view('3-return-page', [
            'data' => $data,
        ]);
    }

    private function getClient()
    {
        $clientId = (int) $this->getVar('clientId');
        if (0 == $clientId) {
            $this->dieWithError('Client ID is missing!');
        }
        $client = Client::model()->findByPk($clientId);
        if (!is_object($client)) {
            $this->dieWithError('Client not found by ID: ' . $clientId);
        }

        return $client;
    }

    private function dieWithError($message)
    {
        $this->view('x-error', ['message' => $message]);
    }

    private function getVar($key, $defaultValue = '')
    {
        return isset($_GET[$key]) ? $_GET[$key] : $defaultValue;
    }

    private function postVar($key, $defaultValue = '')
    {
        return isset($_POST[$key]) ? $_POST[$key] : $defaultValue;
    }

    private function view($name, $params)
    {
        $this->layout = false;
        $this->render('_wrapper', [
            'body' => $this->render($name, array_merge([
                'baseUrl' => Yii::app()->baseUrl,
            ], $params)),
        ]);
        die; // this is must, before removing this for any reason, see all the uses of view() function
    }
}
