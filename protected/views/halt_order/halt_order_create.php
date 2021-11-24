
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/reconcileStock/halt_order_create.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>


<div  id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">
    <div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo $data; ?>,"<?php echo Yii::app()->createAbsoluteUrl('halt_order/base');?>")'>

        <div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <strong>message! </strong>
            {{taskMessage}}
        </div>

        <div class="tabbable">
            <ul class="nav nav-tabs nav-tabs-lg">
                <li>
                    <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                       Halt Order
                    </a>
                </li>
            </ul>
        </div>
        <!--  {{productList}}
          {{todayData}}-->

       
        <div class="col-sm-12">
            <span style="font-weight: bold;">Customer :</span>
            <span style="color: green"> {{data.client_name}}</span>

            <span style="font-weight: bold;margin-left: 10px">Product :</span>
            <span style="color: green"> {{data.product_name}}</span>
        </div>
        <div class="col-sm-12">
            <h5 style="color: green">Halt delivery between date rang</h5>
        </div>
        <div class=" active" id="tab_1" style="margin-bottom: 10px; margin: 10px ; margin-top: 10px">
            <input  style="float: left ; width: 18% ;margin-bottom: 10px" class="form-control input-sm"   datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="data.start_date" size="2">
            <button class="btn btn-info btn-sm" style="float: left"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
            <input  style="float: left ; width: 18% ;margin-bottom: 10px" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="data.end_date" size="2">
            <button ng-disabled="showProgressBar" style="margin-left: 10px;" type="button"  ng-click="cancel_order()" class="btn btn-primary btn-sm "> <i class="fa fa-save" style="margin: 4px"></i> Save</button>
            <img ng-show="showProgressBar" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
        </div>

        <div class="col-sm-12" ng-show="halt_result.length>0">

            <h3>Already halt his schedule  from  <span style="color: green"> {{halt_result[0].start_date}}</span> from to <span style="color: green">{{halt_result[0].end_date}}</span>


                <a  href="<?php echo Yii::app()->baseUrl; ?>/halt_order/halt_order_create_delete?client_id={{data.client_id}}&product_id={{data.product_id}}"  type="button"   class="btn btn-primary btn-sm">
                    <span class="glyphicon glyphicon-remove"></span>
                </a>

            </h3>




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
