<?php





class firebase_cloud_messaging_data
{

    public static function send_sms($message,$value){

        $mobile_no = $value['phone_no'];

        $number = $mobile_no;
        if(substr($mobile_no, 0, 2) == "03"){
            $number = '923' . substr($mobile_no, 2);
        }else if(substr($mobile_no, 0, 1) == "3"){
            $number = '923' . substr($mobile_no, 1);
        }else if(substr($mobile_no, 0, 2) == "+9"){
            $number =  substr($mobile_no, 1);
        }
        $message = urlencode($message);
        $url = 'http://api.bizsms.pk/api-send-branded-sms.aspx?username=conformiz@bizsms.pk&pass=c3nuji8uj99&text='.$message.'&masking=emremr&destinationnum='.$number.'&language=English';
        $url = 'http://api.bizsms.pk/api-send-branded-sms.aspx?username=conformiz@bizsms.pk&pass=c3nuji8uj99&text='.$message.'&masking=Taza%20Farms&destinationnum='.$number.'&language=English&network=0';


        if($_result = file_get_contents($url)) {
            $_result_f = json_decode($_result);
        }else{
            echo "not Send";
        }
    }
    public static function sendGCM($token, $title, $message)
    {
      
       // define('FCM_API_KEY', 'AAAAfm05khg:APA91bH_tzNeTXV7IX_8FCmIPLG1-y7aBtCzwQLw76JvOvyJDDZvFXtGXdzuIM-p3GaJ8YeEt0NiD1vFPuH16f2tFUUa5bWZWRWFo4rs8io6wPOpCUF2T3LAiCHndP4Fd_Fh9dNzwHX1');
        $fcm_key ='AAAADvs7lU0:APA91bFb7bJMcSMVPaD9wGmwIK5X1iyB3-NP4e-0_76XjJezmdrGZb-PAfNUUhmZc6n1UbnoQnyMHfrSKjg_b-Dq7Q4SYm-VbIXgODRrqcFZLnF_PSx3Fsc_tACkavltoqV_JxNCQwbO';
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = [
            'registration_ids' => [$token],
            'notification' => [
                "title" => $title,
                "body" => $message,
            ]
        ];
        $fields = json_encode($fields);
        $headers = [
            'Authorization: key=' .$fcm_key,
            'Content-Type: application/json'
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        $result = curl_exec($ch);
        curl_close($ch);
        try {
            return json_decode($result);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public static function send_email($msg,$email){

        $msg = wordwrap($msg,70);

        $headers = "MIME-Version: 1.0" . "\r\n";

        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        $headers .= 'From: <school@gmail.com>' . "\r\n";

        mail($email,"KPSS - Notification from Parental App",$msg);
    }
}