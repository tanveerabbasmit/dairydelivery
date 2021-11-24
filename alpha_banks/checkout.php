<?php 
/* 
| Developed by: Tauseef Ahmad
| Last Upate: 16-12-2020 01:18 PM
| Facebook: www.facebook.com/ahmadlogs
| Twitter: www.twitter.com/ahmadlogs
| YouTube: https://www.youtube.com/channel/UCOXYfOHgu-C-UfGyDcu5sYw/
| Blog: https://ahmadlogs.wordpress.com/
 */ 
 


date_default_timezone_set('Asia/Karachi');
//60 seconds = 1 minutes


$amount = 10;

//makinging order id, usually it comes from database
$DateTime 	 = new DateTime();
$orderRefNum = $DateTime->format('YmdHis');
//NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN


//NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
//3.
//to make expiry date and time add one hour to current date and time
//format: YYYYMMDD HHMMSS
$ExpiryDateTime = $DateTime;
$ExpiryDateTime->modify('+' . 1 . ' hours');
$expiryDate = $ExpiryDateTime->format('Ymd His');
//NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN



//--------------------------------------------------------------------------------
//$post_data14157
$STORE_ID ='141570';// from account
//$STORE_ID ='14763';// from account
$POST_BACK_URL1 ='https://dairydelivery.conformiz.com/payments/ReturnPageEasypaisa';
$HASH_KEY ='1832KR924OW2Q309';
//live
//$TRANSACTION_POST_URL1 = 'https://easypay.easypaisa.com.pk/easypay/Index.jsf';



/*sendbox url*/
$TRANSACTION_POST_URL1 ='https://easypaystg.easypaisa.com.pk/easypay/Index.jsf';

$post_data =  array(
	"storeId" 			=> $STORE_ID,
	"amount" 			=> $amount,
	"postBackURL" 		=> $POST_BACK_URL1,
	"orderRefNum" 		=> $orderRefNum,
	"expiryDate" 		=> $expiryDate, 	  	//Optional
	"merchantHashedReq" => "",				  	//Optional
	"autoRedirect" 		=> "1",				  	//Optional
	//"paymentMethod" 	=> "MA_PAYMENT_METHOD",	//Optional
 	 "paymentMethod" 	=> "",	//Optional
);
//OTC_PAYMENT_METHOD
//MA_PAYMENT_METHOD
//CC_PAYMENT_METHOD

//--------------------------------------------------------------------------------

$_SESSION['post_data'] = $post_data;

//NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
//4.
//$sorted_string
//make an alphabetically ordered string using $post_data array above
//and skip the blank fields in $post_data array
$sortedArray = $post_data;
ksort($sortedArray);
$sorted_string = '';
$i = 1;

foreach($sortedArray as $key => $value){
	if(!empty($value))
	{
		if($i == 1)
		{
			$sorted_string = $key. '=' .$value;
		}
		else
		{
			$sorted_string = $sorted_string . '&' . $key. '=' .$value;
		}
	}
	$i++;
}	
// AES/ECB/PKCS5Padding algorithm
$cipher = "aes-128-ecb";
$crypttext = openssl_encrypt($sorted_string, $cipher, $HASH_KEY, OPENSSL_RAW_DATA);
$HashedRequest = Base64_encode($crypttext);



//NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN

$post_data['merchantHashedReq'] =  $HashedRequest;


?>



<!-- container --> 
  <section class="showcase">
    <div class="container">
      <div class="pb-2 mt-4 mb-2 border-bottom">
        <h2> Checkout</h2>
      </div>      
      <span id="success-msg" class="payment-errors"></span>   
      
    <div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6 pb-5">
    <div class="row"></div>
        <!--Form with header-->
            <div class="card border-gray rounded-0">
                <div class="card-header p-0">
                    <div class="bg-gray text-left py-2">

                        <h6 class="pl-2"><strong>Amount: </strong> <?php echo $amount;?> PKR</h6>
                    </div>
                </div>

<!-- MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM -->
<!-- Telenor EasyPay payment form -->
<!-- MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM -->

<form action="<?php echo $TRANSACTION_POST_URL1;?>" method="POST" id="myCCForm">
<input type="hidden" name="amount" value="<?php echo $post_data['amount'];?>">
<input type="hidden" name="storeId" value="<?php echo $post_data['storeId'];?>">
<input type="hidden" name="postBackURL" value="<?php echo $post_data['postBackURL'];?>">
<input type="hidden" name="orderRefNum" value="<?php echo $post_data['orderRefNum'];?>">
<input type="hidden" name="expiryDate" value="<?php echo $post_data['expiryDate'];?>">
<input type="hidden" name="autoRedirect" value="<?php echo $post_data['autoRedirect'];?>">
<input type="hidden" name="merchantHashedReq" value="<?php echo $post_data['merchantHashedReq'];?>">
<!--<input type="hidden" name="paymentMethod" value="<?php /*echo $post_data['paymentMethod'];*/?>">-->
<input type="hidden" name="paymentMethod" value="<?php echo $post_data['paymentMethod'];?>">

<!-- MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM -->

<div class="card-body p-3">   


	<br>
	<div class="text-right">

		<button type="buttom" id="payBtn" class="btn btn-info py-2">Proceed to Checkhout</button>
	</div>
	
</div>
</form>



                
            </div> 
              <div>                
                </div>
          </div>
        </div>
    </div>
  </section>


