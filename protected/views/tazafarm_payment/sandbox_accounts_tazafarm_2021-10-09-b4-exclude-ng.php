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

    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/js/table/taza_farm_payment_style.css">
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/angular.min.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/pointOfSale/send_box_grid.js"></script>
</head>

<body style="background:transparent">

    <div class=" container" ng-app="productGrid" ng-controller="manageProduct" ng-init='init(<?php echo $data  ?>)'>

        <form ng-submit="proceed_function()">
            <div>
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
                    <div class="col-xs-5" style="font-weight: bold;">Full Name:</div>
                    <div class="col-xs-7"><?php echo $client_object->fullname ?></div>
                </div>

                <div style="margin-top:1px;padding-top:10px;padding-bottom:10px;" class="row">
                    <div class="col-xs-5" style="font-weight: bold;">Outstanding Bal:</div>
                    <div class="col-xs-7" style="">Rs. <?php echo $outstanding_balance ?></div>
                </div>

                <div style="margin-top:1px;padding-top:10px;padding-bottom:10px;" class="row">
                    <div class="col-xs-5" style="font-weight: bold;">Pay Amount:</div>
                    <div class="col-xs-7">
                        <input ng-change="check_valid_amount()" style="background-color: white; " class="form-control" type="text" name="pp_Amount" ng-model="amount" />
                        <span ng-show="valid_amount_show" style="color: red" ng-bind="valid_amount_message"></span>
                    </div>
                </div>

                <div style="margin-top:1px;padding-top:10px;padding-bottom:10px;" class="row">
                    <div class="col-xs-5" style="font-weight: bold;">Pay By:</div>
                    <div class="col-xs-7">
                        <select style="background-color: white; " class="form-control" ng-model="pp_TxnType">
                            <option value="MPAY">Card Payment (Tax 3.5%)</option>
                            <option value="MWALLET">Mobile Wallet Payment (Tax 1.5%)</option>
                            <option value="OTC">Voucher Payment (Tax 2.5%)</option>
                        </select>
                    </div>
                </div>

                <div style="margin-top:1px;padding-top:10px;padding-bottom:10px;" class="row">
                    <div class="col-xs-5" style="font-weight: bold;">Payment Date :</div>
                    <div class="col-xs-7"><?php echo date("d-M-Y"); ?></div>
                </div>

                <div style="margin-top:1px;padding-top:10px;padding-bottom:10px;" class="row">
                    <p style="font-size: large;text-align: center">This amount shall be paid against your outstanding balance/bill</p>
                </div>

                <div class="col-sm-12" style="padding-left: 30% ; margin-top:20px">
                    <input type='Hidden' name='Style' value='STL:18' />

                    <button ng-disabled="valid_amount_show" type="submit" id="main_button" class="btn btn-lg black btn-block" href="<?php echo Yii::app()->baseUrl; ?>/tazafarm_payment/confirm_payment?client_id=<?php echo $client_id ?>&amount={{amount}}&outstanding_balance=<?php echo $outstanding_balance ?>&pp_TxnType={{pp_TxnType}}&ru=<?php echo $ru ?>">Proceed</button>
                    <button type="button" onclick="window.location.reload()" class="btn btn-lg black btn-block">Refresh</button>
                </div>
            </div>
        </form>

    </div>

</body>

</html>