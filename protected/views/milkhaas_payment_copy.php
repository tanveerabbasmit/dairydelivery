
<?php



$MerchantID ="MC10734"; //Your Merchant from transaction Credentials

$Password   ="fws295911t"; //Your Password from transaction Credentials
$ReturnURL  ="https://dairydelivery.conformiz.com/Milkhaas_payment/return_page"; //Your Return URL
//$ReturnURL  ="https://dairypayments.conformiz.com/jazz_payment/return_page.php"; //Your Return URL

$HashKey    ="uz82z09tz0";//Your HashKey from transaction Credentials

$PostURL = "https://sandbox.jazzcash.com.pk/CustomerPortal/transactionmanagement/merchantform";



date_default_timezone_set("Asia/karachi");

$Amount        = ($current_amount)*100; //Last two digits will be considered as Decimal
$Amount_actual = ($current_amount); //Last two digits will be considered as Decimal

$BillReference = $client_id;

$Description = "Thank you for using Jazz Cash";

$Language = "EN";

$TxnCurrency = "PKR";

$TxnDateTime = date('YmdHis') ;

$TxnExpiryDateTime = date('YmdHis', strtotime('+8 Days'));

$TxnRefNumber = "T".date('YmdHis');

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



$SortedArray=$HashKey;

for ($i = 0; $i < count($HashArray); $i++) {

    if($HashArray[$i] != 'undefined' AND $HashArray[$i]!= null AND $HashArray[$i]!="" )

    {



        $SortedArray .="&".$HashArray[$i];

    }               }

$Securehash = hash_hmac('sha256', $SortedArray, $HashKey);

?>
<style>
    body, html {

    }

    .bg {
        /* The image used */
        background-image: url("https://dairypayments.conformiz.com/img/background_picture.png");

        /* Full height */
        height: 100%;

        /* Center and scale the image nicely */
        background-position: center;
        background-repeat: repeat-x;
        background-size: cover;
    }
</style>
<html lang="en"><head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>


<body class="bg">

<div class="container" >
    <img  style="width: auto; height: 10vh; margin: 1em 0; float: left" src="https://dairypayments.conformiz.com/milk_khass.jpg" alt="Taza">
    <img  style="width: auto; height: 10vh; margin: 1em 0; float: right " src="https://dairydelivery.conformiz.com/themes/milk/images/usa_payment.jpg" alt="Taza">

</div>

<div class="container" >
    <form id="myForm" method="post" action="<?php echo $PostURL; ?>"/>
        <div style="background-color: #9ab082;" class="row">
            <div class="col-sm-12" style="margin-right:40%">
                <h3>Summary</h3>
            </div>
        </div>


    <input type="hidden" name="pp_Version" value="<?php echo $Version; ?>" />

    <input type="hidden" name="pp_TxnType" value="<?php echo $TxnType; ?>" />

    <input type="hidden" name="pp_Language" value="<?php echo $Language; ?>" />

    <input type="hidden" name="pp_MerchantID" value="<?php echo $MerchantID; ?>" />

    <input type="hidden" name="pp_SubMerchantID" value="<?php echo $SubMerchantID; ?>" />

    <input  type="hidden" name="pp_Password" value="<?php echo $Password; ?>" />

    <input type="hidden" name="pp_TxnRefNo" value="<?php echo $TxnRefNumber; ?>"/>

   <!-- <input type="hidden" name="pp_Amount" value="<?php /*echo $Amount; */?>" />-->

    <input type="hidden" name="pp_TxnCurrency" value="<?php echo $TxnCurrency; ?>"/>

    <input type="hidden" name="pp_TxnDateTime" value="<?php echo $TxnDateTime; ?>" />

    <input type="hidden" name="pp_BillReference" value="<?php echo $BillReference ?>" />

    <input type="hidden" name="pp_Description" value="<?php echo $Description; ?>" />

    <input type="hidden" id="pp_DiscountedAmount" name="pp_DiscountedAmount" value="<?php echo $DiscountedAmount ?>">

    <input type="hidden" id="pp_DiscountBank" name="pp_DiscountBank" value="<?php echo $DiscountedBank ?>">

    <input type="hidden" name="pp_TxnExpiryDateTime" value="<?php echo  $TxnExpiryDateTime; ?>" />


    <input type="hidden" name="pp_ReturnURL" value="<?php echo $ReturnURL; ?>" />


    <input type="hidden" name="pp_SecureHash" value="<?php echo $Securehash; ?>" />


    <input type="hidden" name="ppmpf_1" value="<?php echo $ppmpf_1; ?>" />

    <input type="hidden" name="ppmpf_2" value="<?php echo $ppmpf_2; ?>" />

    <input type="hidden" name="ppmpf_3" value="<?php echo $ppmpf_3; ?>" />

    <input type="hidden" name="ppmpf_4" value="<?php echo $ppmpf_4; ?>" />

    <input type="hidden" name="ppmpf_5" value="<?php echo $ppmpf_5; ?>" />



        <div style="background-color: #9ab082;margin-top:1px;padding-top:10px;padding-bottom:10px;" class="row">
            <div  class="col-xs-6 col-sm-4" style="font-weight: bold;">Full Name:</div>
            <div  class="col-xs-6 col-sm-4"><?php echo $client_object->fullname ?></div>
        </div>

    <div style="background-color: #9ab082;margin-top:1px;padding-top:10px;padding-bottom:10px;" class="row">
        <!-- <div  class="col-xs-12 col-sm-12" style="color: antiquewhite"> Last two digits will be considered as Decimal.</div>-->
        <div  class="col-xs-6 col-sm-4" style="font-weight: bold;">Outstanding Balance:</div>


        <div  class="col-xs-6 col-sm-4" style="">Rs. <?php echo $outstanding_balance ?></div>
    </div>
       <div style="background-color: #9ab082;margin-top:1px;padding-top:10px;padding-bottom:10px;" class="row">
           <!-- <div  class="col-xs-12 col-sm-12" style="color: antiquewhite"> Last two digits will be considered as Decimal.</div>-->
            <div  class="col-xs-6 col-sm-4" style="font-weight: bold;">Paid Amount:</div>

           <div  class="col-xs-6 col-sm-4" style=""> Rs.  <?php echo $Amount_actual; ?></div>
           <input style="width: 20%;background-color: #e1edd3; " class="form-control" type="hidden" name="pp_Amount" value="<?php echo $Amount; ?>" />

            <!--<div  class="col-xs-6 col-sm-4"><?php /*echo $current_amount */?></div>-->
        </div>

        <div style="background-color: #9ab082;margin-top:1px;padding-top:10px;padding-bottom:10px;" class="row">
            <div  class="col-xs-6 col-sm-4" style="font-weight: bold;">Payment Date :</div>
            <div  class="col-xs-6 col-sm-4"><?php echo date("d-M-Y"); ?></div>
        </div>
        <div style="background-color: #9ab082;margin-top:1px;padding-top:10px;padding-bottom:10px;" class="row">
            <p style="font-size: large;text-align: center">This amount shall be paid against your outstanding balance/bill</p>
        </div>
        <div class="col-sm-12" style="padding-left: 30% ; margin-top:20px" >
            <input type='Hidden' name='Style' value='STL:18'/>
            <input  id="myBtn" style="width: 50%;background-color:#4a4a4a;color:white " type="submit" value="Confirm" class="btn btn-lg black btn-block">

        </div>
    </form>
</div>

<style>
    body {font-family: Arial, Helvetica, sans-serif;}

    /* The Modal (background) */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        padding-top: 100px; /* Location of the box */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
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
</head>
<body>


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

</body>
</html>



