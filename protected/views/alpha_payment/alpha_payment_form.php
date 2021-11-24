<?php
date_default_timezone_set("Asia/karachi");

$outstanding_balance = $current_amount;

if ($current_amount < 0) {
    $current_amount = 0;
}
$MerchantID = "MC10734"; //Your Merchant from transaction Credentials
$Password   = "fws295911t"; //Your Password from transaction Credentials
$ReturnURL  = $ru; //Your Return URL
$HashKey    = "uz82z09tz0"; //Your HashKey from transaction Credentials
$PostURL = "https://sandbox.jazzcash.com.pk/CustomerPortal/transactionmanagement/merchantform";
$Amount = ($current_amount); //Last two digits will be considered as Decimal
$BillReference = "OrderID";
$Description = "Thank you for using Jazz Cash";
$Language = "EN";
$TxnCurrency = "PKR";
$TxnDateTime = date('YmdHis');
$TxnExpiryDateTime = date('YmdHis', strtotime('+8 Days'));
$TxnRefNumber = "T" . date('YmdHis');
$TxnType = "";
$Version = '1.1';
$SubMerchantID = "";
$DiscountedAmount = "";
$DiscountedBank = "";
$ppmpf_1 = "";
$ppmpf_2 = "";
$ppmpf_3 = "";
$ppmpf_4 = "";
$ppmpf_5 = "";

$HashArray = [$Amount, $BillReference, $Description, $DiscountedAmount, $DiscountedBank, $Language, $MerchantID, $Password, $ReturnURL, $TxnCurrency, $TxnDateTime, $TxnExpiryDateTime, $TxnRefNumber, $TxnType, $Version, $ppmpf_1, $ppmpf_2, $ppmpf_3, $ppmpf_4, $ppmpf_5];

$SortedArray = $HashKey;

for ($i = 0; $i < count($HashArray); $i++) {
    if ($HashArray[$i] != 'undefined' and $HashArray[$i] != null and $HashArray[$i] != "") {
        $SortedArray .= "&" . $HashArray[$i];
    }
}

$Securehash = hash_hmac('sha256', $SortedArray, $HashKey);
?>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet'>

    <link rel="stylesheet" href="<?= Yii::app()->theme->baseUrl; ?>/js/table/taza_farm_payment_style.css">
</head>

<body style="background:transparent" onload="checkValidAmount()">

<div class=" container">

    <form action="<?= Yii::app()->baseUrl ?>/alpha_payment/alpha_payment_confirm" method="get">
        <input type="hidden" name="client_id" value="<?= $client_id ?>" />
        <input type="hidden" name="outstanding_balance" value="<?= $outstanding_balance ?>" />

        <div>
            <div style="" class="row">
                <div class="col-sm-12" style="margin-right:40%">
                    <h3>Summary</h3>
                </div>
            </div>

            <div style="margin-top:1px;padding-top:10px;padding-bottom:10px;" class="row">
                <div class="col-xs-5" style="font-weight: bold;">Full Name:</div>
                <div class="col-xs-7"><?= $client_object->fullname ?></div>
            </div>

            <div style="margin-top:1px;padding-top:10px;padding-bottom:10px;" class="row">
                <div class="col-xs-5" style="font-weight: bold;">Outstanding Bal:</div>
                <div class="col-xs-7" style="">Rs. <?= $outstanding_balance ?></div>
            </div>

            <div style="margin-top:1px;padding-top:10px;padding-bottom:10px;" class="row">
                <div class="col-xs-5" style="font-weight: bold;">Pay Amount:</div>
                <div class="col-xs-7">
                    <input onkeyup="checkValidAmount(this.value)" style="background-color: white; " class="form-control" type="text" name="amount" value="<?= $Amount ?>" />
                    <span style="color:red;display:none" id="idAmtErrMsg">please enter a valid amount</span>
                </div>
            </div>



            <div style="margin-top:1px;padding-top:10px;padding-bottom:10px;" class="row">
                <div class="col-xs-5" style="font-weight: bold;">Payment Date :</div>
                <div class="col-xs-7"><?= date("d-M-Y"); ?></div>
            </div>

            <div style="margin-top:1px;padding-top:10px;padding-bottom:10px;" class="row">
                <p style="font-size: large;text-align: center">This amount shall be paid against your outstanding balance/bill</p>
            </div>

            <div style="margin-top:20px" class="row">
                <div class="col-xs-3"></div>
                <div class="col-xs-6">
                    <button id="idBtnSubmit" type="submit" class="btn btn-lg btn-block" style="color:black">Proceed</button>
                </div>
            </div>
        </div>
    </form>

</div>

<script>
    function checkValidAmount(val) {
        var elAmtErrMsg = document.getElementById('idAmtErrMsg');
        var elBtnSubmit = document.getElementById('idBtnSubmit');

        if (+val <= 0) {
            elAmtErrMsg.style.display = 'block';
            elBtnSubmit.disabled = true;
        } else {
            elAmtErrMsg.style.display = 'none';
            elBtnSubmit.disabled = false;
        }
    }
</script>

</body>

</html>