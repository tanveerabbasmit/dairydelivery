

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/vendorpayment/vendor_payment_create_grad.js"></script>

<link href="<?php echo Yii::app()->theme->baseUrl; ?>/js/monthPickerFile/examples.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/js/monthPickerFile/MonthPicker.min.css" rel="stylesheet" type="text/css" />

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>

<?php $allow_delete = crudRole::getCrudrole(1); ?>


<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css2" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>


<div id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">

    <div ng-controller="manageZone" ng-init='init(<?php echo $allow_delete ?>,<?php echo $zoneList ?>,  <?php echo $companyBranchList ?> ,"<?php echo Yii::app()->createAbsoluteUrl('VendorPayment/base_url'); ?>","<?php echo Yii::app()->createAbsoluteUrl('VendorPayment/editZone'); ?>","<?php echo Yii::app()->createAbsoluteUrl('VendorPayment/delete'); ?>","<?php echo Yii::app()->createAbsoluteUrl('VendorPayment/saveRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('VendorPayment/deleteRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('VendorPayment/search'); ?>")'>
        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                  Vendor Payment
                </a>
            </li>

        </ul>
        <div class="" style="margin: 10px">
             <div class="">
            </div>
            <div style="margin-top:0px;" class="table-responsive">


                <form ng-submit="savePaymernt_for_dairy_farm()">

                    <div class="col-lg-12">
                        <div class="col-lg-6" style="background-color: honeydew">
                            <div class="col-lg-4 " style="padding-top: 10px" >
                                <span style="font-weight: bold;">Date:</span>
                            </div>
                            <div class="col-lg-8">
                                <input class="form-control"  datetime-picker="" date-only="" date-format="yyyy-MM-dd" type="text" required="" ng-model="main_object.action_date" size="2">
                            </div>
                        </div>
                        <div class="col-lg-6" style="background-color: honeydew">
                            <div class="col-lg-4" style="padding-top: 10px">
                                <span style="font-weight: bold;">Reference No.</span>
                            </div>
                            <div class="col-lg-8">
                                <input  type="text" ng-model="main_object.reference_no" class="form-control" required>
                            </div
                        </div>
                    </div>
                    <div class="panel-body">
                    </div>
                    <div class="col-lg-6" style="background-color: 	#FFF0F5">
                        <div class="col-lg-4" style="padding-top: 10px">
                            <span style="font-weight: bold;">Amount Paid :</span>
                        </div>
                        <div class="col-lg-8">
                            <input  type="number" ng-model="main_object.amount" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-lg-6" style="background-color: 	#FFF0F5">
                        <div class="col-lg-4" style="padding-top: 10px">
                            <span style="font-weight: bold;">Mode : </span>
                        </div>
                        <div class="col-lg-8">
                            <select  ng-model="main_object.payment_mode" class="form-control" required>
                                <option value="">Select</option>
                                <option value="2">cheque</option>
                                <option value="3">Cash</option>
                                <option value="5">Bank Transaction</option>
                                <option value="6">Card Transaction</option>
                            </select>
                        </div>
                    </div>



                    <div class="panel-body">
                    </div>
                    <div class="col-lg-12" style="background-color: 	honeydew;" >
                        <div class="col-lg-6">
                            <div class="col-lg-4" style="padding-top: 10px">
                                <span  style="font-weight: bold;">Vendor : </span>

                            </div>
                            <div class="col-lg-8">

                                <select   id="target_change_payment" ng-model="main_object.vendor_id" class="form-control select2 input-sm" style="float: left ; width: 100%">
                                    <option value="">Select Vendor</option>
                                    <option ng-repeat="list in farm_list" value="{{list.vendor_id}}">{{list.vendor_name}}</option>
                                </select>

                               <!-- <select ng-change="change_dairy_farm(main_object.farm_id)"  ng-model="main_object.vendor_id" class="form-control" required>
                                    <option value="">Select</option>
                                    <option ng-repeat="list in farm_list" value="{{list.vendor_id}}">{{list.vendor_name}}</option>
                                </select>-->
                            </div>
                        </div>
                        <div class="col-lg-6" style="text-align: right">

                            <img ng-show="save_data" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
                            <button ng-show="main_object.farm_payment_id>0" ng-click="main_object_function()"  type="submit" class="btn btn-sm btn-info next" > <i class="fa fa-edit"></i>Add New Mayment</button>
                            <button  ng-disabled="imageLoader || allow_delete[1]" type="submit" class="btn btn-sm btn-info next" > <i class="fa fa-edit"></i> {{button_text}}</button>

                        </div>
                    </div>
                </form>

                <table style="margin-top:55px;" class="table table-striped nomargin" ng-show="!mainPageSwitch">
                    <thead>
                    <tr>
                        <th>#</th>
                      <!--  <th>Farm </th>-->
                        <th>Received Date </th>
                        <th>Reference No. </th>
                        <th>Received Amount</th>
                        <th>Mode</th>
                        <th></th>

                    </tr>
                    </thead>
                    <tbody>
                     <tr ng-repeat="list in farm_payment">
                         <td>{{$index+1}}</td>
                         <!--<td><span ng-bind="list.farm_name"></span></td>-->
                         <td><span ng-bind="list.action_date"></span></td>
                         <td><span ng-bind="list.reference_no"></span></td>
                         <td style="text-align: center">
                             <span ng-show="!list.update" ng-bind="list.amount"></span>
                             <input style="width: 100px" ng-show="list.update" type="text" class="form-control" ng-model="list.amount">
                         </td>
                         <td>
                             <span ng-show="list.payment_mode=='2'">cheque</span>
                             <span ng-show="list.payment_mode=='3'">Cash</span>
                             <span ng-show="list.payment_mode=='5'">Bank Transaction</span>
                             <span ng-show="list.payment_mode=='6'">Card Transaction</span>
                         </td>
                         <td>
                             <button ng-show="!list.update" title="Edit" type="button" ng-click="edit_payment(list)" class="btn btn-default btn-xs"><i style="margin: 2px" class="fa fa-edit  "></i></button>
                             <button ng-show="list.update" title="Edit" type="button" ng-click="savePaymernt_for_dairy_farm_update(list)" class="btn btn-info btn-xs"><i style="margin: 2px" class="fa fa-edit  "></i></button>
                             <button  title="Edit" type="button" ng-click="savePaymernt_for_dairy_farm_delete(list)" class="btn btn-info btn-xs"><i class="fa fa-trash" style="margin: 2px" aria-hidden="true"></i></button>
                         </td>
                     </tr>
                    </tbody>
                </table>
            </div>
        </div>


    </div>
</div>

<script>
    $('.select2').select2();
</script>

<style>
    .select2-selection--single {
        height: 33px !important;
    }
</style>