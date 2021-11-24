<div class=" container">

    <h3 style="margin-bottom:16px">Enter Payment Info</h3>

    <form action="<?= $baseUrl ?>/payments/confirm?clientId=<?= $clientId ?>" method="post">
        <input type="hidden" name="outstanding_balance" value="<?= $balance ?>" />
        <input type="hidden" name="gateway" value="<?= $gateway ?>" />

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
            <div class="col-xs-7">
                <input type="number" class="form-control" autocomplete="off" id="txtAmount" name="amount" value="<?= $balance < 0 ? 0 : $balance ?>" onkeyup="validateAmountInput()" />
            </div>
        </div>

        <div class="row" style="margin-bottom: 1em;">
            <div class="col-xs-5" style="font-weight: bold;">Pay By :</div>
            <div class="col-xs-7">
                <?php $payModeError = false; ?>
                <?php if ('jazzcash' == $gateway) { ?>
                    <?php if ('MWALLET' == $paymentMethod) { ?>
                        <input type="hidden" name="paymentMethod" value="MWALLET">
                        Mobile Wallet
                    <?php } elseif ('MPAY' == $paymentMethod) { ?>
                        <input type="hidden" name="paymentMethod" value="MPAY">
                        Credit Card
                    <?php } else { ?>
                        <select class="form-control" name="paymentMethod">
                            <option value="MWALLET">Mobile Wallet</option>
                            <option value="MPAY">Credit Card</option>
                        </select>
                    <?php } ?>
                <?php } elseif ('alfapay' == $gateway) { ?>

                    <?php if (1 == $paymentMethod) { ?>
                        <input type="hidden" name="paymentMethod" value="1">
                        Alfa Wallet
                    <?php } elseif (2 == $paymentMethod) { ?>
                        <input type="hidden" name="paymentMethod" value="2">
                        Alfa Account
                    <?php } else { ?>
                        <select class="form-control" name="paymentMethod">
                            <option value="1">Alfa Wallet</option>
                            <option value="2">Alfa Account</option>
                        </select>
                    <?php } ?>
                <?php } else {
                    $payModeError = true; ?>
                    <div class="alert alert-danger" role="alert">Payment Gateway not selected correctly!</div>
                <?php } ?>
            </div>
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
                <button type="submit" class="btn" id="btnProceed">Proceed</button>
            </div>
        </div>

        <div style="padding-top: 2em; text-align: center; color: white">
            Powered by: <img src="<?= Yii::app()->theme->baseUrl . '/images/gateway-' . $gateway . '.png' ?>" alt="<?= $gateway ?>" style="width:48px;height:48px;border-radius:50%" />
        </div>

    </form>

    <script>
        function validateAmountInput() {
            var $txtAmount = $('#txtAmount');
            if ($txtAmount.val() < 0) {
                $txtAmount.val(0);
            }

            if ($txtAmount.val() < 1 || <?= $payModeError ? 'true' : 'false' ?>) {
                $('#btnProceed').prop('disabled', true);
            } else {
                $('#btnProceed').prop('disabled', false);
            }
        }

        function submitForm() {

        }

        window.setTimeout(() => {
            $(function() {
                validateAmountInput();
            })
        }, 1000);
    </script>

</div>