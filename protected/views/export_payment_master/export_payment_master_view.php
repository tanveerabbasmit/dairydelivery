
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/vendor/vendor_payasble_summary_grd.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/FileSaver/FileSaver.js"></script>
<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>


<div  id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">
    <div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo $data ?> ,"<?php echo Yii::app()->createAbsoluteUrl('vendor/base'); ?>")'>
        <div class="tabbable">
            <ul class="nav nav-tabs nav-tabs-lg">
                <li>
                    <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                        Vendor Payasble Summary
                    </a>
                </li>
            </ul>
            <div class="tab-content">

                <div class=" active" id="tab_1" style="margin: 10px">
                    <input style="float: left ;  width: 17% ;margin-bottom: 10px" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">
                    <button  style="float: left" type="button"  ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-info btn-sm"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
                    <input ng-disabled="false"   style="float: left ; width: 17% ;" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">

                    <a   type="button"   class="btn btn-primary btn-sm" href="<?php echo Yii::app()->createUrl('export_payment_master/export_payment_master_view_download')?>?start_date={{startDate}}&end_date={{endDate}}"> <i class="fa fa-download" style="margin: 5px"></i> Download</a>



                </div>
            </div>






        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $('#searchDate').datepicker({
            format: "yyyy-mm-dd"
        });
        $('#stockDate').datepicker({
            format: "yyyy-mm-dd"
        });
    });
</script>

<style type="text/css">
    .angularjs-datetime-picker {
        z-index: 99999 !important;
    }
</style>
