



<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/vendorpayment/bill_from_vendor_create_grad.js"></script>

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

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>


<?php $allow_delete = crudRole::getCrudrole(1); ?>

<div id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">

    <div ng-controller="manageZone" ng-init='init(<?php echo $allow_delete ?>,<?php echo $zoneList ?>,  <?php echo $companyBranchList ?> ,"<?php echo Yii::app()->createAbsoluteUrl('BillFromVendor/base_url'); ?>")'>
        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                    Bill From Vendor Create
                </a>
            </li>

        </ul>
        <div class="" style="margin: 10px">

            <div style="margin-top:0px;" class="table-responsive">


                <form ng-submit="savePaymernt_for_dairy_farm()">


                    <div class="col-lg-12" style="background-color: 	honeydew;" >
                        <div class="col-lg-6" style="background-color: honeydew">
                            <div class="col-lg-4" style="padding-top: 10px">
                                <span style="font-weight: bold;">Date : </span>
                            </div>
                            <div class="col-lg-8">
                                <input class="form-control"  datetime-picker="" date-only="" date-format="yyyy-MM-dd" type="text"  ng-model="main_object.action_date" required>
                            </div>
                        </div>

                        <div class="col-lg-6" style="background-color: honeydew">
                            <div class="col-lg-4" style="padding-top: 10px">
                                <span style="font-weight: bold;">Item : </span>
                            </div>
                            <div class="col-lg-8">
                               <select ng-change=""  ng-model="main_object.item_id" class="form-control" required>
                                    <option value="">Select</option>
                                    <option ng-repeat="list in item_list" value="{{list.item_id}}">{{list.item_name}}</option>
                                </select>

                               <!-- <select id="change_party_id"  id="vendor_id_value" ng-model="main_object.item_id" class="form-control select2 input-sm" style="width: 100%;">
                                    <option value="">Select</option>
                                    <option ng-repeat="list in item_list" value="{{list.item_id}}">{{list.item_name}}</option>
                               </select>-->
                            </div>
                        </div>

                    </div>

                    <div class="panel-body">
                    </div>

                    <div class="col-lg-12" style="background-color: 	honeydew;" >
                        <div class="col-lg-6" style="background-color: honeydew">
                            <div class="col-lg-4" style="padding-top: 10px">
                                <span style="font-weight: bold;">Vendor : </span>
                            </div>
                            <div class="col-lg-8">
                                <!--<select  ng-model="main_object.vendor_id" class="form-control" required>
                                    <option value="">Select</option>
                                    <option ng-repeat="list in farm_list" value="{{list.vendor_id}}">{{list.vendor_name}}</option>
                                </select>-->

                                <select  id="vendor_id_value" ng-model="main_object.vendor_id" class="form-control select2 input-sm" style="width: 100%;">
                                    <option value="">Select</option>
                                    <option ng-repeat="list in farm_list" value="{{list.vendor_id}}">{{list.vendor_name}}</option>
                                </select>

                            </div>
                        </div>

                        <div class="col-lg-6" style="background-color: honeydew">
                            <div class="col-lg-4" style="padding-top: 10px">
                                <span style="font-weight: bold;">price : </span>
                            </div>
                            <div class="col-lg-8">
                                <input ng-change="calculation_function()"  ng-model="main_object.price" type="text" class="form-control" required>
                            </div>
                        </div>

                    </div>

                    <div class="panel-body">
                    </div>

                    <div class="col-lg-12" style="background-color: 	honeydew;" >
                        <div class="col-lg-6" style="background-color: honeydew">
                            <div class="col-lg-4" style="padding-top: 10px">
                                <span style="font-weight: bold;">Quantity : </span>
                            </div>
                            <div class="col-lg-8">
                                <input ng-change="calculation_function()"  ng-model="main_object.quantity" type="text" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-lg-6" style="background-color: honeydew">
                            <div class="col-lg-4" style="padding-top: 10px">
                                <span style="font-weight: bold;">Gross Amount : </span>
                            </div>
                            <div class="col-lg-8">
                                <input ng-disabled="true" ng-change="calculation_function()" ng-model="main_object.gross_amount" type="text" class="form-control" required>
                            </div>
                        </div>

                    </div>

                    <div class="panel-body">
                    </div>

                    <div class="col-lg-12" style="background-color: 	honeydew;" >
                        <div class="col-lg-6" style="background-color: honeydew">
                            <div class="col-lg-4" style="padding-top: 10px">
                                <span style="font-weight: bold;">Tax Amount : </span>
                            </div>
                            <div class="col-lg-8">
                                <input ng-change="calculation_function()" ng-model="main_object.tax_amount" type="text" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-lg-6" style="background-color: honeydew">
                            <div class="col-lg-4" style="padding-top: 10px">
                                <span style="font-weight: bold;">Discount Amount : </span>
                            </div>
                            <div class="col-lg-8">
                                <input ng-change="calculation_function()"  ng-model="main_object.discount_amount" type="text" class="form-control" required>
                            </div>
                        </div>

                    </div>

                    <div class="panel-body">
                    </div>

                    <div class="col-lg-12" style="background-color: 	honeydew;" >
                        <div class="col-lg-6" style="background-color: honeydew">
                            <div class="col-lg-4" style="padding-top: 10px">
                                <span style="font-weight: bold;">Net Amount : </span>
                            </div>
                            <div class="col-lg-8">
                                <input ng-disabled="true" ng-model="main_object.net_amount" type="text" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-lg-6" style="background-color: honeydew">
                            <div class="col-lg-4" style="padding-top: 10px">
                                <span style="font-weight: bold;">Reference : </span>
                            </div>
                            <div class="col-lg-8">
                                <input ng-model="main_object.remarks" type="text" class="form-control" required>
                            </div>
                        </div>

                    </div>

                    <div class="panel-body">
                    </div>

                    <div class="col-lg-12" style="background-color: 	honeydew;" >
                        <div class="col-lg-12">

                           <button ng-disabled="save_loading" class="btn btn-primary input-sm" style="float: right" ng-click="getAllPaymentList(searchPayment)"><i class="fa fa-save" style=""></i> {{button_text}}</button>

                           <img ng-show="save_loading" style="padding: 10px;float: right" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">

                        </div>

                    </div>


                </form>

            </div>
        </div>


        <style>
            #customers {
                border-collapse: collapse;
                width: 100%;
            }

            #customers td, #customers th {
                border: 1px solid #ddd;
                padding: 8px;
                color: black;
            }

            #customers tr:nth-child(even){background-color: #F8F8FF;}
            #customers tr:hover {background-color: #FAFAD2;}
            #customers th {
                padding-top: 12px;
                padding-bottom: 12px;
                text-align: left;
                color: white;
            }
        </style>

        <div class="" ng-show="list.length>0">
            <table id="customers">
                <thead>
                <tr>
                    <th><a href="#"> #</a></th>
                    <th><a href="#">Date</a></th>
                    <th><a href="#">Vendor</a></th>
                    <th><a href="#">item_name</a> </th>
                    <th><a href="#">Amount </a></th>
                    <th><a href="#"></a></th>

                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="list in list track by $index">
                    <td>{{$index + 1}}</td>
                    <td>{{list.action_date}}</td>
                    <td>{{list.vendor_name}}</td>
                    <td>{{list.item_name}}</td>
                    <td>{{list.net_amount}}</td>
                    <td width="150px">

                        <button title="Delete "  type="button" ng-click="delete_function(list)" class="btn btn-primary btn-xs"><i style="margin: 2px" class="fa fa-trash  "></i></button>
                        <a  href="<?php echo Yii::app()->baseUrl; ?>/BillFromVendor/bill_from_vendor_create?bill_from_vendor_id={{list.bill_from_vendor_id}}"  type="button"   class="btn btn-default btn-xs"> <i class="fa fa-edit"></i> </a>

                    </td>

                </tr>

                </tbody>
            </table>
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