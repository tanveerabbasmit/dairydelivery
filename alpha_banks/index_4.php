<?php

        $url = "https://sandbox.bankalfalah.com/HS/HS/HS";
			// no need to change /*changed        $url = "https://sandbox.bankalfalah.com/HS/api/HSAPI/HSAPI";*/
	    //$url = "https://payments.bankalfalah.com/HS/HS/HS";

          // $bankorderId   = $this->session->userdata('bankorderId');
         $bankorderId   = rand(0,1786612);
        
                $Key1= "";
        $Key2= "";
        $HS_ChannelId="1002";
        $HS_MerchantId="7443" ;
        $HS_StoreId="015707" ;
        $HS_IsRedirectionRequest  = 0;
        $HS_ReturnURL="https://dairydelivery.conformiz.com/alpha_payment/alpha_payment_return_page";
        $HS_MerchantHash="Yz3GaQjMzXs=";
        $HS_MerchantUsername="otahef" ;
        $HS_MerchantPassword="clawMyPB2pVvFzk4yqF7CA==";
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
        //$hashRequest = '0/XHJHBkEUH0YWu0ITcfhzZqjAnXb1xzyhz1P5XXV5E+hLPBWMVb7TRylZ7z+Mb9KmwrMktG/3i0XqslLhvRjdKmkOcIXk26XJq7Cu3ElEPVNNlPjnepeZRZstY3C7vhn6bHcjbduyc+wOGABDfatAY88BmOS2aIbiW6xRcQA9/tzUNmAsp4GTLKh3T1XRDTwdpS/0eoW9ZwZsh5gCadiJBzCuPY1FJL3ZTA37OMMlObXJ7Gw+twbuicNO/ZoND9EIPMhvTBJZAEN72MppVC2Q==';

		
        
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
        
        
	    echo $result ."<br>";
	    echo $AuthToken ."<br>";
	  
	  
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
     	<input id="AuthToken" name="AuthToken" type="hidden" value="<?php echo $AuthToken; ?>">                                                                                                                                
     	<input id="RequestHash" name="RequestHash" type="hidden" value="<?php echo $hashRequest1; ?>">                                                                                                                            
     	<input id="ChannelId" name="ChannelId" type="hidden" value="<?php echo $HS_ChannelId; ?>">                                                                                                                            
     	<input id="Currency" name="Currency" type="hidden" value="PKR">                                                                                                                               
         <input id="IsBIN" name="IsBIN" type="hidden" value="0">                                                                                     
     	<input id="ReturnURL" name="ReturnURL" type="hidden" value="https://google.com">                                                                            
         <input id="MerchantId" name="MerchantId" type="hidden" value="<?php echo $HS_MerchantId;?>">                                                                                                                           
         <input id="StoreId" name="StoreId" type="hidden" value="<?php echo $HS_StoreId;?>">                                                                                                                     
     	<input id="MerchantHash" name="MerchantHash" type="hidden" value="<?php echo $HS_MerchantHash;?>">                                  
     	<input id="MerchantUsername" name="MerchantUsername" type="hidden" value="<?php echo $HS_MerchantUsername;?>">                                                                                                            
     	<input id="MerchantPassword" name="MerchantPassword" type="hidden" value="<?php echo $HS_MerchantPassword;?>">  
    <input id="TransactionTypeId" name="TransactionTypeId" type="hidden" value="3"> 
      
                                                                                                                                                                              
     	<input autocomplete="off" id="TransactionReferenceNumber" name="TransactionReferenceNumber" placeholder="Order ID" type="hidden" value="<?php echo $HS_TransactionReferenceNumber;?>">                                  
    	<input autocomplete="off"  id="TransactionAmount" name="TransactionAmount" placeholder="Transaction Amount" type="hidden" value="<?php echo $TransactionAmount; ?>">  
          
     
				 <br>
     <center>	<button type="submit" class="btn btn-custon-four btn-danger" id="run">PAY ONLINE</button>        </center>                                                                                                    
     </form> 
				
		
		
		

		
		