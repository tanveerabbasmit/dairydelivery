<div class=" container">

    <h3 style="margin-bottom:16px">Confirm Payment Info</h3>

    <form action="<?= $paymentUrl ?>" method="post" target="_parent" onsubmit="return submitForm(this)">
        <?php foreach ($formData as $key => $val) { ?>
            <input type="hidden" name="<?= $key ?>" value="<?= $val ?>" />
        <?php } ?>

        <div class="row" style="margin-bottom: 1em;">
            <div class="col-xs-5" style="font-weight: bold;">Full Name :</div>
            <div class="col-xs-7"><?= $client->fullname ?></div>
        </div>

        <div class="row" style="margin-bottom: 1em;">
            <div class="col-xs-5" style="font-weight: bold;">Outstanding Bal :</div>
            <div class="col-xs-7">Rs <?= $balance ?></div>
        </div>

        <div class="row" style="margin-bottom: 1em;">
            <div class="col-xs-5" style="font-weight: bold;">Pay Amount :</div>
            <div class="col-xs-7">Rs <?= $amount ?></div>
        </div>

        <div class="row" style="margin-bottom: 1em;">
            <div class="col-xs-5" style="font-weight: bold;">Pay By :</div>
            <div class="col-xs-7"><?= 'MWALLET' == $paymentMethod ? 'Mobile Wallet' : 'Credit Card' ?></div>
        </div>

        <div class="row" style="margin-bottom: 1em;">
            <div class="col-xs-5" style="font-weight: bold;">Payment Date :</div>
            <div class="col-xs-7"><?= date('d-M-Y') ?></div>
        </div>

        <div style="margin-top:1px;padding-top:10px;padding-bottom:10px;" class="row">
            <p style="font-size:16px;text-align:center">This amount shall be paid against your outstanding balance/bill</p>
        </div>

        <div class="row" style="color: #333333 !important;">
            <div class="col-xs-6" style="text-align:left">
                <button type="button" class="btn" onclick="history.back()">Go Back</button>
            </div>
            <div class="col-xs-6" style="text-align:right">
                <button type="submit" class="btn" id="btnProceed">Pay Now</button>
            </div>
        </div>

        <div style="padding-top: 2em; text-align: center; color: white">
            Powered by: <img src="<?= Yii::app()->theme->baseUrl . '/images/gateway-jazzcash.png' ?>" alt="jazzcash" style="width:48px;height:48px;border-radius:50%" />
        </div>

    </form>

    <script>
        function submitForm(frm) {
            $btn = $('#btnProceed');

            $btn.html('submitting...');
            $btn.prop('disabled', true);

            return true;
        }
    </script>

</div>