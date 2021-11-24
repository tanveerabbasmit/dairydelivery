<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/js/table/taza_farm_payment_style.css"
      xmlns="http://www.w3.org/1999/html">

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/angular.min.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/pointOfSale/send_box_grid.js"></script>
<?php


$outstanding_balance = $current_amount;

if($current_amount<0){
    $current_amount =0;
}
$MerchantID ="MC10734"; //Your Merchant from transaction Credentials

$Password   ="fws295911t"; //Your Password from transaction Credentials

$ReturnURL  =$ru; //Your Return URL

$HashKey    ="uz82z09tz0";//Your HashKey from transaction Credentials

$PostURL = "https://sandbox.jazzcash.com.pk/CustomerPortal/transactionmanagement/merchantform";



date_default_timezone_set("Asia/karachi");

$Amount = ($current_amount); //Last two digits will be considered as Decimal

$BillReference = "OrderID";

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

<html lang="en"><head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>

<head>
  <link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet'>

    <meta name="viewport" content="width=device-width, initial-scale=1" />
</head>

<body >

<div class="container" style="text-align: center" >
    <img  style="width: auto; height: 10vh; margin: 1em 0;" src="https://dairydelivery.conformiz.com/themes/milk/company_logo/taza_logo_new.png" alt="Taza">
   <!-- <img  style="width: auto; height: 10vh; margin: 1em 0; float: right " src="https://dairydelivery.conformiz.com/themes/milk/images/usa_payment.jpg" alt="Taza">-->

</div>




<div  class="bg container" ng-app="productGrid" ng-controller="manageProduct" ng-init='init(<?php echo $Amount  ?>)'>

    <form id="myForm" method="post" action=' <?php echo Yii::app()->baseUrl; ?>/tazafarm_payment/confirm_payment/tazafarm_payment/confirm_payment?client_id=<?php echo $client_id ?>&amount={{amount}}'&outstanding_balance=<?php echo $outstanding_balance ?>&pp_TxnType={{pp_TxnType}}&ru=<?php echo  $ru ?> >

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

             {{amount}}

           <div style="margin-top:1px;padding-top:10px;padding-bottom:10px;" class="row">
               <div  class="col-xs-6 col-sm-4" style="font-weight: bold;">Full Name:</div>
               <div  class="col-xs-6 col-sm-4"><?php echo $client_object->fullname ?></div>
           </div>

           <div style="margin-top:1px;padding-top:10px;padding-bottom:10px;" class="row">
               <!-- <div  class="col-xs-12 col-sm-12" style="color: antiquewhite"> Last two digits will be considered as Decimal.</div>-->
               <div  class="col-xs-6 col-sm-4" style="font-weight: bold;">Outstanding Balance:</div>


               <div  class="col-xs-6 col-sm-4" style="">Rs. <?php echo $outstanding_balance ?></div>
           </div>

           <div style="margin-top:1px;padding-top:10px;padding-bottom:10px;" class="row">
               <div  class="col-xs-6 col-sm-4" style="font-weight: bold;">Pay Amount:</div>
               <div  class="col-xs-6 col-sm-4" style="">
                   <input style="background-color: white; " class="form-control" type="text" name="pp_Amount" ng-change="check_valid_amount()" ng-model="amount" required />
                   <span ng-show="valid_amount_show" style="color: red" ng-bind="valid_amount_message"></span>
               </div>
           </div>


           <div style="margin-top:1px;padding-top:10px;padding-bottom:10px;" class="row">

               <div  class="col-xs-6 col-sm-4" style="font-weight: bold;">Pay By:</div>

               <div  class="col-xs-6 col-sm-4" >

                   <select style="background-color: white; " class="form-control" ng-model="pp_TxnType">
                       <option value="MPAY">Card Payment (Tax 3.5%)</option>
                       <option value="MWALLET">Mobile Wallet Payment (Tax 1.5%)</option>
                       <option value="OTC">Voucher Payment(Tax 2.5%)</option>
                   </select>
                   <!--<input style="background-color: #e1edd3; width: 200px" class="form-control" type="text" name="pp_Amount" ng-model="amount" />-->
               </div>
               <div  class="col-xs-12 col-sm-12" >
                   <div  class="col-xs-6 col-sm-4" style="font-weight: bold;"></div>
                   <div  class="col-xs-6 col-sm-8" style="font-weight: bold;">

                   </div>
               </div>



           </div>

           <div style="margin-top:1px;padding-top:10px;padding-bottom:10px;" class="row">
               <div  class="col-xs-6 col-sm-4" style="font-weight: bold;">Payment Date :</div>
               <div  class="col-xs-6 col-sm-4"><?php echo date("d-M-Y"); ?></div>
           </div>
           <div style="margin-top:1px;padding-top:10px;padding-bottom:10px;" class="row">
               <p style="font-size: large;text-align: center">This amount shall be paid against your outstanding balance/bill</p>
           </div>
           <div class="col-sm-12" style="padding-left: 30% ; margin-top:20px" >
               <input type='Hidden' name='Style' value='STL:18'/>
               <!--<input  style="width: 50%;background-color:#4a4a4a;color:white " type="submit" value="Confirm" class="btn btn-lg black btn-block">-->

               <button ng-disabled="valid_amount_show" id="main_button" type="submit" class="btn btn-lg black btn-block" >Proceed</button>

           </div>
       </div>
    <!--href="<?php /*echo Yii::app()->baseUrl; */?>/tazafarm_payment/confirm_payment?client_id=<?php /*echo $client_id */?>&amount={{amount}}&outstanding_balance=<?php /*echo $outstanding_balance */?>&pp_TxnType={{pp_TxnType}}&ru=--><?php /*echo $ru*/?>"
    </form>
</div>



</body>
</html>



