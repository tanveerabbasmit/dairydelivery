<?php

/**
 * Created by PhpStorm.
 * User: Tanveer.Abbas
 * Date: 11/15/2017
 * Time: 2:21 PM
 */
class smsLog
{
    public static function saveSms($client_id ,$company_id ,$phoneNo ,$fullname ,$message){
        date_default_timezone_set("Asia/Karachi");
        $time = date("H:i");
        $todayDate = date("Y-m-d");

        $servername = "localhost";
        $username = "dbusersms112";
        $password = "Provided@112";
        $dbname = "sms_log";


        /* $servername = "localhost";
         $username = "smslog";
         $password = "smslog12345";
         $dbname = "sms_log";*/

        $conn = new mysqli($servername, $username, $password, $dbname);

        $sql = "INSERT INTO `sms_record` (`sms_record_id`, `client_id`, `company_id`, `client_name`, `phone_number`, `text_message`, `date`, `setTime`)
             VALUES (NULL, $client_id, $company_id, '$fullname', '$phoneNo', '$message', '$todayDate', '$time')";

        $conn->query($sql);

    }

}