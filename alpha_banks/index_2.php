<?php


  
    $url = "https://sandbox.bankalfalah.com/HS/HS/HS";
	 
	// $url = "https://sandbox.bankalfalah.com/HS/api/HSAPI/HSAPI";

          // $bankorderId   = $this->session->userdata('bankorderId');
         $bankorderId   = rand(0,1786612);


      $Key1 = "ChRuw26CtjfNwWtm";
       $Key2 = "3928362524320288";
        $HS_ChannelId="1002";
        $HS_MerchantId="7443";
        $HS_StoreId="015707";
        $HS_IsRedirectionRequest  = 0;
        $HS_ReturnURL="http://dairydelivery.conformiz.com/alpha_payment/alpha_payment_return_page";
        $HS_MerchantHash="OUU362MB1uqAD7xz9tWIksz4VGxclcxsYvfI+z5z0CKA9IADlctrT+BhEwRszpEgStveemNieF+g+EWMK8AecGmlFdqCsCrDoh7Ga1FG6wcgJXv58RZTww==";
        $HS_MerchantUsername="wagose" ;
        $HS_MerchantPassword="IPumJi7a8PpvFzk4yqF7CA==";
        $HS_TransactionReferenceNumber= $bankorderId;
         $transactionTypeId = "3";
	    $TransactionAmount = "3";   
		
		   $cipher="aes-128-cbc";
	    
	    
        $mapString = 
          "HS_ChannelId=$HS_ChannelId" 
        . "&HS_IsRedirectionRequest=$HS_IsRedirectionRequest" 
        . "&HS_MerchantId=$HS_MerchantId" 
        . "&HS_StoreId=$HS_StoreId" 
        . "&HS_ReturnURL=$HS_ReturnURL"
        . "&HS_MerchantHash=$HS_MerchantHash"
        . "&HS_MerchantUsername=$HS_MerchantUsername"
        . "&HS_MerchantPassword=$HS_MerchantPassword"
        . "&HS_TransactionReferenceNumber=$HS_TransactionReferenceNumber";
        
     
        $cipher_text = openssl_encrypt($mapString, $cipher, $Key1,   OPENSSL_RAW_DATA , $Key2);
        $hashRequest = base64_encode($cipher_text);

        
        //The data you want to send via POST
        $fields = [
            "HS_ChannelId"=>$HS_ChannelId,
            "HS_IsRedirectionRequest"=>$HS_IsRedirectionRequest,
            "HS_MerchantId"=> $HS_MerchantId,
            "HS_StoreId"=> $HS_StoreId,
            "HS_ReturnURL"=> $HS_ReturnURL,
            "HS_MerchantHash"=> $HS_MerchantHash,
            "HS_MerchantUsername"=> $HS_MerchantUsername,
            "HS_MerchantPassword"=> $HS_MerchantPassword,
            "HS_TransactionReferenceNumber"=> $HS_TransactionReferenceNumber,
            "HS_RequestHash"=> $hashRequest
        ];



        $fields_string = http_build_query($fields);
        
        //open connection
        $ch = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
        //execute post
        $result = curl_exec($ch);
         var_dump($result);
           die();
       $handshake =  json_decode($result);
       
        $AuthToken = $handshake->AuthToken;

        echo $AuthToken;
         die();

	   // echo $result ."<br>";
	   // echo $AuthToken ."<br>";
	  
	  
	  /* ==============SSO CALL ================*/
	  
	  // you need Auth Token & Amount Here before Hashing
	  
	  
	  
	
$RequestHash1 = NULL;
$Currency = "PKR";
$IsBIN =0;

$mapStringSSo = 
  "AuthToken=$AuthToken" 
. "&RequestHash=$RequestHash1" 
. "&ChannelId=$HS_ChannelId"
. "&Currency=$Currency"
. "&IsBIN=$IsBIN"
. "&ReturnURL=$HS_ReturnURL"
. "&MerchantId=$HS_MerchantId"
. "&StoreId=$HS_StoreId" 
. "&MerchantHash=$HS_MerchantHash"
. "&MerchantUsername=$HS_MerchantUsername"
. "&MerchantPassword=$HS_MerchantPassword"
. "&TransactionTypeId=3"
. "&TransactionReferenceNumber=$HS_TransactionReferenceNumber"
. "&TransactionAmount=$TransactionAmount";



		echo $mapStringSSo."<br>";
		
		 
$cipher_text = openssl_encrypt($mapStringSSo, $cipher, $Key1,   OPENSSL_RAW_DATA , $Key2);
$hashRequest1 = base64_encode($cipher_text);

echo $hashRequest1;



?>

                                                                                                                                                               
  

		
		
        <form action="https://sandbox.bankalfalah.com/SSO/SSO/SSO" id="PageRedirectionForm" method="post" novalidate="novalidate">

             <p>AuthToken</p>  <input id="AuthToken" name="AuthToken" type="text" value="<?php echo $AuthToken; ?>"><br>
              <p>RequestHash</p>  <input id="RequestHash" name="RequestHash" type="text" value="<?php echo $hashRequest1; ?>"><br>
              <p>ChannelId</p>  <input id="ChannelId" name="ChannelId" type="text" value="<?php echo $HS_ChannelId; ?>"><br>
            <p>Currency</p> <input id="Currency" name="Currency" type="text" value="PKR"><br>
            <p>IsBIN</p> <input id="IsBIN" name="IsBIN" type="text" value="0"><br>
            <p>ReturnURL</p>  <input id="ReturnURL" name="ReturnURL" type="text" value="<?php echo $HS_ReturnURL ?>"><br>
            <p>MerchantId</p>  <input id="MerchantId" name="MerchantId" type="text" value="<?php echo $HS_MerchantId;?>"><br>
            <p>StoreId</p>  <input id="StoreId" name="StoreId" type="text" value="<?php echo $HS_StoreId;?>"><br>
            <p>MerchantHash</p>   <input id="MerchantHash" name="MerchantHash" type="text" value="<?php echo $HS_MerchantHash;?>"><br>
            <p>MerchantUsername</p>  <input id="MerchantUsername" name="MerchantUsername" type="text" value="<?php echo $HS_MerchantUsername;?>"><br>
            <p>MerchantPassword</p>   <input id="MerchantPassword" name="MerchantPassword" type="text" value="<?php echo $HS_MerchantPassword;?>"><br>
            <p>TransactionTypeId</p>   <input id="TransactionTypeId" name="TransactionTypeId" type="text" value="3"><br>

                <input autocomplete="off" id="TransactionReferenceNumber" name="TransactionReferenceNumber" placeholder="Order ID" type="text" value="<?php echo $HS_TransactionReferenceNumber;?>">
                <input autocomplete="off"  id="TransactionAmount" name="TransactionAmount" placeholder="Transaction Amount" type="text" value="<?php echo $TransactionAmount; ?>">

     
				 <br>
     <center>	<button type="submit" class="btn btn-custon-four btn-danger" id="run">PAY ONLINE</button>        </center>                                                                                                    
     </form> 
				
		
		
		

		
		