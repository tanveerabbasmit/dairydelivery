<?php

/**
 * Created by PhpStorm.
 * User: Muhammad.Imran
 * Date: 7/12/2016
 * Time: 11:12 AM
 */

   class sendEmail{
       public  static function forgetPassword(){

           /*$username = "root";
           $password = "";
           $servername = "localhost";
           $dbname = "contract_db";
           $conn = new mysqli($servername, $username, $password, $dbname);
           if ($conn->connect_error) {
               die("Connection failed: " . $conn->connect_error);
           }
           $sql = "SELECT * FROM meta";
           $result = $conn->query($sql);
           if ($result->num_rows > 0) {
               $data=array();
               while($row = $result->fetch_assoc()) {
                   $data[]=$row;
               }
           } else {
               echo "0 results";
           }
           $cc=$data[0]['meta_value'];
           $days=$data[1]['meta_value'];
           $sql2 = "SELECT d.department_email FROM dealer_agreement da
                LEFT JOIN department d USING(department_id)
                WHERE da.agreement_expire_date >= CURDATE() + INTERVAL $days DAY";
           $result2 = $conn->query($sql2);
           $getEmail=$result2->fetch_assoc();*/

           $from = "oooo@hotmail.com"; // sender
           $subject = " My cron is working";
           $message = "My first Cron  :)";

           // message lines should not exceed 70 characters (PHP rule), so wrap it

           $message = wordwrap($message, 70);

           // send mail

           /*ini_set("SMTP","localhost");
           ini_set("smtp_port","25");
           ini_set("sendmail_from","00000@gmail.com");
           ini_set("sendmail_path", "C:\wamp\bin\sendmail.exe -t");

           mail("tanveerabbas412@gmail.com",$subject,$message,"From: $from\n");

           echo "Thank you for sending us feedback";*/



           $to = "tanveerabbas412@gmail.com";
           $subject = "My subject";
           $txt = "Hello world!";


            $test = mail($to,$subject,$txt);
             var_dump($test);

           /*$conn->close();*/
       }
   }
