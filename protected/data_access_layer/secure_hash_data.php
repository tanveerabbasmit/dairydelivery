<?php


class secure_hash_data
{
     public static function get_secure_hash_on_return_page(
         $Amount,
         $BillReference,
         $TxnDateTime,
         $TxnRefNumber
     ){



         $MerchantID ="MC10734"; //Your Merchant from transaction Credentials

         $Password   ="fws295911t"; //Your Password from transaction Credentials
         $ReturnURL  ="https://dairydelivery.conformiz.com/Milkhaas_payment/return_page"; //Your Return URL
//$ReturnURL  ="https://dairypayments.conformiz.com/jazz_payment/return_page.php"; //Your Return URL

         $HashKey    ="uz82z09tz0";//Your HashKey from transaction Credentials

         $PostURL = "https://sandbox.jazzcash.com.pk/CustomerPortal/transactionmanagement/merchantform";



         date_default_timezone_set("Asia/karachi");


        // $BillReference = $client_id;

         $Description = "Thank you for using Jazz Cash";

         $Language = "EN";

         $TxnCurrency = "PKR";

        // $TxnDateTime = date('YmdHis') ;

         $TxnExpiryDateTime = date($TxnDateTime, strtotime('+8 Days'));
          $TxnExpiryDateTime = $TxnDateTime+ 8877000000;


        // $TxnRefNumber = "T".date('YmdHis');

         $TxnType = "";

         $Version = '1.1';

         $SubMerchantID = "";

         $DiscountedAmount = "";

         $DiscountedBank = "";

         $ppmpf_1="";

         $ppmpf_2="";

         $ppmpf_3="";

         $ppmpf_4="";

         $ppmpf_5="";



         $HashArray=[$Amount,$BillReference,$Description,$DiscountedAmount,$DiscountedBank,$Language,$MerchantID,$Password,$ReturnURL,$TxnCurrency,$TxnDateTime,$TxnExpiryDateTime,$TxnRefNumber,$TxnType,$Version,$ppmpf_1,$ppmpf_2,$ppmpf_3,$ppmpf_4,$ppmpf_5];

         echo "<pre>";
         print_r($HashArray);




         $SortedArray=$HashKey;

         for ($i = 0; $i < count($HashArray); $i++) {

             if($HashArray[$i] != 'undefined' AND $HashArray[$i]!= null AND $HashArray[$i]!="" )

             {



                 $SortedArray .="&".$HashArray[$i];

             }
         }

         // echo $SortedArray;
         //echo $HashKey;
         $Securehash = hash_hmac('sha256', $SortedArray, $HashKey);

         echo  $Securehash;
         echo "<br>return page ";

         return $Securehash;
     }
    public static function get_secure_hash_on_return_page_testing_for_taza($post){
        //$HashKey    ="uz82z09tz0";
        $HashKey    ="v2cg58xy0w";
        $final_array =[];
        foreach($post as $key=>$value){
            if($key!='pp_SecureHash'){
                $final_array[] = $post[$key];
            }

           // array_push($final_array,$value);
        }
        $HashArray=$final_array;


        $SortedArray=$HashKey;
        for ($i = 0; $i < count($HashArray); $i++) {

            if($HashArray[$i] != 'undefined' AND $HashArray[$i]!= null AND $HashArray[$i]!="" )

            {

                $SortedArray .="&".$HashArray[$i];


            }
        }


        $Securehash = hash_hmac('sha256', $SortedArray, $HashKey);
        return $Securehash;

    }
    public static function get_secure_hash_on_return_page_testing($post){
        //$HashKey    ="uz82z09tz0";
        $HashKey    ="yt5s4d1wyt";
        $final_array =[];
        foreach($post as $key=>$value){
            if($key!='pp_SecureHash'){
                $final_array[] = $post[$key];
            }

           // array_push($final_array,$value);
        }
        $HashArray=$final_array;


        $SortedArray=$HashKey;
        for ($i = 0; $i < count($HashArray); $i++) {

            if($HashArray[$i] != 'undefined' AND $HashArray[$i]!= null AND $HashArray[$i]!="" )

            {

                $SortedArray .="&".$HashArray[$i];


            }
        }


        $Securehash = hash_hmac('sha256', $SortedArray, $HashKey);
        return $Securehash;

    }
    public static function get_responce_code($pp_ResponseCode){

            $responce_array =[
                "000"=>"Thank you for Using JazzCash, your transaction was successful.	Completed",
                "001"=>"Limit exceeded",
                "002"=>"Account not found",
                "003"=>"Account inactive",
                "004"=>"Low balance",
                "014"=>"Warm card",
                "015"=>"Hot card",
                "016"=>"Invalid card status",
                "024"=>"Bad PIN",
                "055"=>"Host link down",
                "058"=>"Transaction timed out",
                "059"=>"Transaction rejected by host",
                "060"=>"PIN retries exhausted",
                "062"=>"Host offline",
                "063"=>"Destination not found",
                "066"=>"No transactions allowed",
                "067"=>"Invalid account status",
                "095"=>"Transaction rejected",
                "101"=>"Invalid merchant credentials.	Failed",
                "102"=>"Card blocked",
                "103"=>"Customer blocked",
                "104"=>"BIN not allowed for use on merchant",
                "105"=>"Transaction exceeds merchant per transaction limit.	Failed",
                "106"=>"Transaction exceeds per transaction limit for card",
                "107"=>"Transaction exceeds cycle limit for card",
                "108"=>"Authorization of customer registration required",
                "109"=>"Transaction does not exist",
                "110"=>"Invalid value for .	Failed",
                "111"=>"Transaction not allowed on Merchant/Bank.	Failed",
                "112"=>"Transaction Cancelled by User.	Failed",
                "113"=>"Transaction settlement period lapsed",
                "15"=>"Invalid hash received.	Failed",
                "116"=>"Transaction Expired",
                "117"=>"Transaction not allowed on Sub Merchant",
                "118"=>"Transaction not allowed due to maintenance.	Failed",
                "119"=>"Transaction is awaiting Reversal",
                "120"=>"Delivery status cannot be updated",
                "121"=>"Transaction has been marked confirmed by Merchant.	Completed",
                "122"=>"Reversed",
                "124"=>"Order is placed and waiting for financials to be received over the counter.	Pending",
                "125"=>"Order has been delivered",
                "126"=>"Transaction is disputed",
                "127"=>"Sorry! Transaction is not allowed due to maintenance",
                "128"=>"Awaiting action by scheme on Dispute",
                "129"=>"Transaction is dropped.	Dropped",
                "131"=>"Transaction is Refunded",
                "132"=>"Sorry! The selected transaction cannot be refunded",
                "134"=>"Transaction has timed out",
                "135"=>"Invalid BIN was entered for discount",
                "157"=>"Transaction is pending.(for Mwallet and MIgs)",
                "199"=>"System error",
                "200"=>"Transaction approved – Post authorization",
                "210"=>"Authorization pending",
                "401"=>"Sorry! Your transaction could not be processed at this time, please try again after few minutes.",
                "402"=>"Your transaction was declined by your bank, please contact your bank",
                "403"=>"Your transaction has timed out, please try again",
                "404"=>"Your transaction was declined because your card is expired, please use a valid card",
                "405"=>"Your transaction was declined because of insufficient balance in your card",
                "406"=>"Sorry! Your transaction could not be processed at this time due to system error, please try again after few minutes",
                "407"=>"Sorry! Your transaction could not be processed at this time due to internal system error, please try again after few minutes",
                "408"=>"Your bank does not support internet transactions, please contact your bank",
                "409"=>"Transaction declined - do not contact issuer",
                "410"=>"You have aborted the transaction, Our team will contact you shortly to assist you or you can share your feedback with us via emailing us at ",
                "411"=>"Sorry! Your transaction is blocked due to risk, please use any other card and try again",
                "412"=>"You have aborted the transaction, Our team will contact you shortly to assist you or you can share your feedback with us via emailing us at :",
                "414"=>"Sorry! Your transaction was declined, please contact your bank",
                "415"=>"Your 3D Secure ID verification was failed, please use correct ID and try again.",
                "416"=>"Your CVV verification was failed, please use correct CVV and try again, click the help button In CVV tab to find out the details of CVV",
                "417"=>"Order locked - another transaction is in progress for this order",
                "419"=>"Your Card is not enrolled in 3D secure, please contact your bank and active your 3D secure ID",
                "421"=>"Your retry limit is exhausted, please contact your bank",
                "422"=>"Your transaction was declined due to duplication, please try again",
                "423"=>"Your transaction was declined due to address verification failed, please try again",
                "424"=>"Your transaction was declined due to wrong CVV, please try again with correct CVV, click the help button  In CVV tab to find out the details of CVV",
                "425"=>"Transaction declined due to address verification and card security code",
                "426"=>"Transaction declined due to payment plan, please contact your issuer bank",
                "429"=>"Your transaction has not been processed this time, please try again or contact your issuer bank.",
                "430"=>"Request_rejected",
                "431"=>"Server_failed",
                "432"=>"Server_busy",
                "433"=>"Not_enrolled_no_error_details",
                "434"=>"Not_enrolled_error_details_provided",
                "435"=>"Card_enrolled",
                "436"=>"Enrollment_status_undetermined_no_error_details",
                "437"=>"Enrollment_status_undetermined_error_details_provided",
                "438"=>"Invalid_directory_server_credentials",
                "439"=>"Error_parsing_check_enrollment_response",
                "440"=>"Error_communicating_with_directory_server",
                "441"=>"Mpi_processing_error",
                "442"=>"Error_parsing_authentication_response",
                "443"=>"Invalid_signature_on_authentication_response",
                "444"=>"Acs_session_timeout",
                "446"=>"Authentication_failed",
                "448"=>"Card_does_not_support_3ds",
                "449"=>"Authentication_not_available_no_error_details",
                "450"=>"Authentication_not_available_error_details_provided",
                "999"=>"Transaction failed. This response code will be sent when the transaction fails due to some technical issue at PG or Bank’s end",
            ];
            if(isset($responce_array[$pp_ResponseCode])){
                $message = $responce_array[$pp_ResponseCode];
            }else{
                $message ="Somethings Went wrong" ;
            }

           /* if($pp_ResponseCode=='001'){
            $message ="Limit exceeded" ;
            } elseif($pp_ResponseCode=='02'){
            $message ="Account not found" ;
            }elseif($pp_ResponseCode=='02'){
            $message ="Account inactive" ;
            }else{
            $message ="Somethings Went wrong" ;
            }*/
            return $message;
    }
}