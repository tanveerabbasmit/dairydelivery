<link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet'>
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/js/table/taza_farm_payment_style.css">
<?php

$MerchantID = "MC24616";

$Password   = "9h91yscyb0";
$ReturnURL  = "https://dairydelivery.conformiz.com/tazafarm_payment/return_page?ru=$ru"; //Your Return URL
//$ReturnURL  =$ru; //Your Return URL

$HashKey    = "v2cg58xy0w";

$PostURL = "https://sandbox.jazzcash.com.pk/CustomerPortal/transactionmanagement/merchantform";

date_default_timezone_set("Asia/karachi");

/*Mobile Wallet:                 1.5% Fee
Voucher payment:           2.5% Fee
 Debit credit card:             3.5% Fee
 <option value="MPAY OR MIGS">Card Payment</option>
 <option value="MWALLET">Mobile Wallet Payment</option>
 <option value="OTC">Voucher Payment</option>
*/

if ($pp_TxnType == 'MPAY') {
    /* 3.5%*/
    $text_amount =  round(($current_amount * 3.5) / 100, 0);
}

if ($pp_TxnType == 'MWALLET') {
    /* 1.5%*/
    $text_amount =  round(($current_amount * 1.5) / 100, 0);
}

if ($pp_TxnType == 'OTC') {
    /* 2.5%*/
    $text_amount =  round(($current_amount * 2.5) / 100, 0);
}
$text_amount = 0;
$current_amount = $current_amount + $text_amount;

$TxnType = $pp_TxnType;

$Amount        = ($current_amount) * 100; //Last two digits will be considered as Decimal
$Amount_actual = ($current_amount); //Last two digits will be considered as Decimal
$BillReference = $client_id;
$Description = "Thank you for using Jazz Cash";
$Language = "EN";
$TxnCurrency = "PKR";
$TxnDateTime = date('YmdHis');
$TxnExpiryDateTime = date('YmdHis', strtotime('+8 Days'));
//$TxnExpiryDateTime = $TxnDateTime+ 8877000000;
$TxnRefNumber = "T" . date('YmdHis');
$Version = '1.1';
$SubMerchantID = "";
$DiscountedAmount = "";
$DiscountedBank = "";
$ppmpf_1 = "1";
$ppmpf_2 = "2";
$ppmpf_3 = "3";
$ppmpf_4 = "4";
$ppmpf_5 = "5";

$HashArray = [$Amount, $BillReference, $Description, $DiscountedAmount, $DiscountedBank, $Language, $MerchantID, $Password, $ReturnURL, $TxnCurrency, $TxnDateTime, $TxnExpiryDateTime, $TxnRefNumber, $TxnType, $Version, $ppmpf_1, $ppmpf_2, $ppmpf_3, $ppmpf_4, $ppmpf_5];

$SortedArray = $HashKey;

for ($i = 0; $i < count($HashArray); $i++) {
    if ($HashArray[$i] != 'undefined' and $HashArray[$i] != null and $HashArray[$i] != "") {
        $SortedArray .= "&" . $HashArray[$i];
    }
}
//echo $SortedArray;
//echo $HashKey;

$Securehash = hash_hmac('sha256', $SortedArray, $HashKey);
?>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
</head>

<body style="background:transparent">

    <div class="container">
        <form id="myForm" method="post" target="_parent" action="<?php echo $PostURL; ?>" />
        <div style="padding: 10px">
            <div style="" class="row">
                <div class="col-sm-12" style="margin-right:40%">
                    <h3>Summary</h3>
                </div>
            </div>


            <input type="hidden" name="pp_Version" value="<?php echo $Version; ?>" />

            <input type="hidden" name="pp_TxnType" value="<?php echo $TxnType; ?>" />

            <input type="hidden" name="pp_Language" value="<?php echo $Language; ?>" />

            <input type="hidden" name="pp_MerchantID" value="<?php echo $MerchantID; ?>" />

            <input type="hidden" name="pp_SubMerchantID" value="<?php echo $SubMerchantID; ?>" />

            <input type="hidden" name="pp_Password" value="<?php echo $Password; ?>" />

            <input type="hidden" name="pp_TxnRefNo" value="<?php echo $TxnRefNumber; ?>" />

            <!-- <input type="hidden" name="pp_Amount" value="<?php /*echo $Amount; */ ?>" />-->

            <input type="hidden" name="pp_TxnCurrency" value="<?php echo $TxnCurrency; ?>" />

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

                <div class="col-xs-6 col-sm-4" style=""> Rs. <?php echo $Amount_actual; ?></div>
                <input style="width: 20%;background-color: #e1edd3; " class="form-control" type="hidden" name="pp_Amount" value="<?php echo $Amount; ?>" />

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

                <div style="width: 30%;float: left">
                    <input onclick="goBack()" style="color:green;" type="button" value="Back" class="btn btn-lg black btn-block">
                </div>
                <div style="width: 30%;float: right">
                    <input id="myBtn" style="color:green;" type="submit" value="Confirm" class="btn btn-lg black btn-block">
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