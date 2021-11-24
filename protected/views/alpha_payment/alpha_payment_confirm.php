<link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet'>
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/js/table/taza_farm_payment_style.css">
<?php


$url = "https://sandbox.bankalfalah.com/HS/HS/HS";





$Key1 = "ChRuw26CtjfNwWtm";
$Key2 = "3928362524320288";
$HS_ChannelId = "1001";
$HS_MerchantId = "7443";
$HS_StoreId = "015707";
$HS_IsRedirectionRequest  = 0;
$HS_ReturnURL = "https://dairydelivery.conformiz.com/alpha_payment/alpha_payment_return_page";
$HS_MerchantHash = "OUU362MB1uqAD7xz9tWIksz4VGxclcxsYvfI+z5z0CKA9IADlctrT+BhEwRszpEgStveemNieF+g+EWMK8AecGmlFdqCsCrDoh7Ga1FG6wcgJXv58RZTww==";
$HS_MerchantUsername = "wagose";
$HS_MerchantPassword = "IPumJi7a8PpvFzk4yqF7CA==";
$HS_TransactionReferenceNumber = $bankorderId;
$transactionTypeId = "3";
$TransactionAmount = $current_amount;

$cipher = "aes-128-cbc";

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

$cipher_text = openssl_encrypt($mapString, $cipher, $Key1,   OPENSSL_RAW_DATA, $Key2);
$hashRequest = base64_encode($cipher_text);

//The data you want to send via POST
$fields = [
    "HS_ChannelId" => $HS_ChannelId,
    "HS_IsRedirectionRequest" => $HS_IsRedirectionRequest,
    "HS_MerchantId" => $HS_MerchantId,
    "HS_StoreId" => $HS_StoreId,
    "HS_ReturnURL" => $HS_ReturnURL,
    "HS_MerchantHash" => $HS_MerchantHash,
    "HS_MerchantUsername" => $HS_MerchantUsername,
    "HS_MerchantPassword" => $HS_MerchantPassword,
    "HS_TransactionReferenceNumber" => $HS_TransactionReferenceNumber,
    "HS_RequestHash" => $hashRequest
];

$fields_string = http_build_query($fields);

//open connection
$ch = curl_init();
//set the url, number of POST vars, POST data
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
//So that curl_exec returns the contents of the cURL; rather than echoing it
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//execute post
$result = curl_exec($ch);

$handshake =  json_decode($result);



$AuthToken = $handshake->AuthToken;
$ReturnURL = $handshake->ReturnURL;

$Currency = "PKR";
$IsBIN = 0;

$mapStringSSo =
    "AuthToken=$AuthToken"
    . "&RequestHash=$hashRequest"
    . "&ChannelId=$HS_ChannelId"
    . "&Currency=$Currency"
    . "&IsBIN=$IsBIN"
    . "&ReturnURL=$ReturnURL"
    . "&MerchantId=$HS_MerchantId"
    . "&StoreId=$HS_StoreId"
    . "&MerchantHash=$HS_MerchantHash"
    . "&MerchantUsername=$HS_MerchantUsername"
    . "&MerchantPassword=$HS_MerchantPassword"
    . "&TransactionTypeId=3"
    . "&TransactionReferenceNumber=$HS_TransactionReferenceNumber"
    . "&TransactionAmount=$TransactionAmount";


$cipher_text = openssl_encrypt($mapStringSSo, $cipher, $Key1,   OPENSSL_RAW_DATA, $Key2);
$hashRequest1 = base64_encode($cipher_text);

$PostURL = 'https://sandbox.bankalfalah.com/SSO/SSO/SSO';
?>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
</head>

<body style="background:transparent">

<div class="container">
    <form  action="<?php echo $PostURL; ?>" id="PageRedirectionForm" method="post" novalidate="novalidate">
    <div style="padding: 10px">
        <div style="" class="row">
            <div class="col-sm-12" style="margin-right:40%">
                <h3>Summary</h3>
            </div>
        </div>

        <input id="AuthToken" name="AuthToken" type="hidden" value="<?php echo $AuthToken; ?>">
        <input id="RequestHash" name="RequestHash" type="hidden" value="<?php echo $hashRequest1; ?>">
        <input id="ChannelId" name="ChannelId" type="hidden" value="<?php echo $HS_ChannelId; ?>">
        <input id="Currency" name="Currency" type="hidden" value="PKR">
        <input id="IsBIN" name="IsBIN" type="hidden" value="0">
        <input id="ReturnURL" name="ReturnURL" type="hidden" value="<?= $ReturnURL ?>">
        <input id="MerchantId" name="MerchantId" type="hidden" value="<?php echo $HS_MerchantId; ?>">
        <input id="StoreId" name="StoreId" type="hidden" value="<?php echo $HS_StoreId; ?>">
        <input id="MerchantHash" name="MerchantHash" type="hidden" value="<?php echo $HS_MerchantHash; ?>">
        <input id="MerchantUsername" name="MerchantUsername" type="hidden" value="<?php echo $HS_MerchantUsername; ?>">
        <input id="MerchantPassword" name="MerchantPassword" type="hidden" value="<?php echo $HS_MerchantPassword; ?>">
        <input id="TransactionTypeId" name="TransactionTypeId" type="hidden" value="3">


        <input autocomplete="off" id="TransactionReferenceNumber" name="TransactionReferenceNumber" placeholder="Order ID" type="hidden" value="<?php echo $HS_TransactionReferenceNumber; ?>">
        <input autocomplete="off" id="TransactionAmount" name="TransactionAmount" placeholder="Transaction Amount" type="hidden" value="<?php echo $TransactionAmount; ?>">





        <div style="margin-top:1px;padding-top:10px;padding-bottom:10px;" class="row">
            <div class="col-xs-6 col-sm-4" style="font-weight: bold;">Full Name:</div>
            <div class="col-xs-6 col-sm-4"><?php echo $client_object->fullname ?></div>
        </div>

        <div style="margin-top:1px;padding-top:10px;padding-bottom:10px;" class="row">
            <!-- <div  class="col-xs-12 col-sm-12" style="color: antiquewhite"> Last two digits will be considered as Decimal.</div>-->
            <div class="col-xs-6 col-sm-4" style="font-weight: bold;">Outstanding Balance:</div>


            <div class="col-xs-6 col-sm-4" style="">Rs. <?php echo $outstanding_balance ?></div>
        </div>
        <div style="margin-top:1px;padding-top:10px;padding-bottom:10px;" class="row">
            <!-- <div  class="col-xs-12 col-sm-12" style="color: antiquewhite"> Last two digits will be considered as Decimal.</div>-->
            <div class="col-xs-6 col-sm-4" style="font-weight: bold;">Paid Amount:</div>

            <div class="col-xs-6 col-sm-4" style=""> Rs. <?php echo $current_amount; ?></div>
           

            <!--<div  class="col-xs-6 col-sm-4"><?php /*echo $current_amount */ ?></div>-->
        </div>

        <div style="margin-top:1px;padding-top:10px;padding-bottom:10px;" class="row">
            <div class="col-xs-6 col-sm-4" style="font-weight: bold;">Payment Date :</div>
            <div class="col-xs-6 col-sm-4"><?php echo date("d-M-Y"); ?></div>
        </div>
        <div style="margin-top:1px;padding-top:10px;padding-bottom:10px;" class="row">
            <p style="font-size: large;text-align: center">This amount shall be paid against your outstanding balance/bill</p>
        </div>

        <div class="col-sm-12" style="">
            <input type='Hidden' name='Style' value='STL:18' />

            <style>
                .btn {
                    display: inline-block;
                    padding: 6px 1px !important;
                }
            </style>
            <div style="width: 30%;float: left">
                <input onclick="goBack()" style="color:black;" type="button" value="Back" class="btn  black btn-block">
            </div>
            <div style="width: 30%;float: right">
                
                <button style="color:black;" type="submit" class="btn  black btn-block" id="run">PAY NOW</button>
            </div>




            <script>
                function goBack() {
                    window.history.back();
                }
            </script>
        </div>
    </div>
    </form>
</div>

<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
    }

    /* The Modal (background) */
    .modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        /* Stay in place */
        z-index: 1;
        /* Sit on top */
        padding-top: 100px;
        /* Location of the box */
        left: 0;
        top: 0;
        width: 100%;
        /* Full width */
        height: 100%;
        /* Full height */
        overflow: auto;
        /* Enable scroll if needed */
        background-color: rgb(0, 0, 0);
        /* Fallback color */
        background-color: rgba(0, 0, 0, 0.4);
        /* Black w/ opacity */
    }

    /* Modal Content */
    .modal-content {
        background-color: #fefefe;
        margin: auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
    }

    /* The Close Button */
    .close {
        color: #aaaaaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }
</style>

<div id="myModal" class="modal">

    <!-- Modal content -->
    <div class="modal-content">

        <p style="text-align: center;color: green">Please wait while your payment is being processed</p>
    </div>

</div>

<script>
    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the button that opens the modal
    var btn = document.getElementById("myBtn");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal
    btn.onclick = function() {
        document.getElementById("myBtn").disabled = true;
        modal.style.display = "block";

        document.getElementById("myForm").submit();
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {

        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

</html>