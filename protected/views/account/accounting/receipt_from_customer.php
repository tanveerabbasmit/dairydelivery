
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/dailyRecoveryReport/receipt_from_customer_grid.js"></script>
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
    <div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo $company_id ?> , <?php echo $riderList; ?>,"<?php echo Yii::app()->createAbsoluteUrl('account/accounting/get_today_recovery_report_data'); ?>"," <?php echo Yii::app()->createAbsoluteUrl('rider/googleMap');?>" ," <?php echo Yii::app()->createAbsoluteUrl('rider/saveDeliveryFromPortal');?>")'>
        <div class="tabbable">
            <ul class="nav nav-tabs nav-tabs-lg">
                <li>
                    <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                        Daily Recovery Report
                    </a>
                </li>
            </ul>

            <div class="col-md-12">
                <div class="col-md-2">
                    <h4>Select Type</h4>
                </div>
                <div class="col-md-8" style="padding: 10px">
                    <label class="radio-inline" >
                        <input ng-click="change_select_function()" type="radio" name="optradio" value="1" ng-model="payment_type_id">
                        <span style="margin-top: 5px">Customer</span>
                    </label>
                    <label class="radio-inline">
                        <input ng-click="change_select_function()" type="radio" name="optradio" value="2" ng-model="payment_type_id">
                        <span style="margin-top: 5px">Vendor Payment</span>
                    </label>
                    <label class="radio-inline">
                        <input ng-click="change_select_function()" type="radio" name="optradio" value="3" ng-model="payment_type_id">
                        <span style="margin-top: 5px">Farm Payment</span>
                    </label>
                </div>


           </div>
            <div class="col-sm-12">
                <div class="tab-pane active" id="tab_1">
                    <input style="float: left ; width: 15% ;" class="form-control input-sm"  ng-change="selectRiderOnChange(selectRiderID)"  datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">




                    <select style="width: 15%; float: left;margin-bottom: 10px;margin-left: 10px ; margin-right: 10px" class="form-control input-sm" ng-model="payment_mode" >
                        <option value="0">All Mode</option>
                        <option value="2">cheque</option>
                        <option value="3">Cash</option>
                        <option value="5">Bank Transaction</option>
                        <option value="6">Card Transaction</option>
                    </select>



                    <button type="button"  ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-primary btn-sm"> <i style="margin: 4px" class="fa fa-search"></i> Search</button>

                    <a ng-show="false" class="btn btn-primary btn-sm " href="<?php echo Yii::app()->createUrl('riderDailyStock/exportDialyDeliveryCustomer')?>?date={{todate}}&riderID={{selectRiderID}}"><i style="margin: 4px" class="fa fa-share"></i> Export 20 </a>

                    <button ng-show="false" class="btn btn-info btn-sm" style="float: left; margin-left: 5px" onclick="javascript:xport.toCSV('customers');"> <i class="fa fa-share"></i> Export 2</button>

                    <img ng-show="imageLoading" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">

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

                    <style>

                        [data-tooltip] {
                            position: relative;
                            z-index: 2;
                            cursor: pointer;
                        }

                        /* Hide the tooltip content by default */
                        [data-tooltip]:before,
                        [data-tooltip]:after {
                            visibility: hidden;
                            -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
                            filter: progid: DXImageTransform.Microsoft.Alpha(Opacity=0);
                            opacity: 0;
                            pointer-events: none;
                        }

                        /* Position tooltip above the element */
                        [data-tooltip]:before {
                            position: absolute;
                            bottom: 150%;
                            left: 50%;
                            margin-bottom: 5px;
                            margin-left: -80px;
                            padding: 7px;
                            width: 160px;
                            -webkit-border-radius: 3px;
                            -moz-border-radius: 3px;
                            border-radius: 3px;
                            background-color: #000;
                            background-color: hsla(0, 0%, 20%, 0.9);
                            color: #fff;
                            content: attr(data-tooltip);
                            text-align: center;
                            font-size: 14px;
                            line-height: 1.2;
                        }

                        /* Triangle hack to make tooltip look like a speech bubble */
                        [data-tooltip]:after {
                            position: absolute;
                            bottom: 150%;
                            left: 50%;
                            margin-left: -5px;
                            width: 0;
                            border-top: 5px solid #000;
                            border-top: 5px solid hsla(0, 0%, 20%, 0.9);
                            border-right: 5px solid transparent;
                            border-left: 5px solid transparent;
                            content: " ";
                            font-size: 0;
                            line-height: 0;
                        }

                        /* Show tooltip content on hover */
                        [data-tooltip]:hover:before,
                        [data-tooltip]:hover:after {
                            visibility: visible;
                            -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
                            filter: progid: DXImageTransform.Microsoft.Alpha(Opacity=100);
                            opacity: 1;
                        }

                    </style>

                    <div ng-show="customerList.length>0" class="col-md-12" style="margin-top: 10px;margin-bottom: 10px">
                        <table style="width: 100%">
                            <tr>
                                <th style="padding: 15px">Total Amount :</th>
                                <td style="padding: 15px">{{count |number}}</td>

                                <th style="padding: 15px">Account Name :</th>
                                <td style="padding: 15px"><span style="margin-left: 15px">{{product_receivable_account_name}}</span></td>
                            </tr>

                            <tr>
                                <td colspan="4" style="padding: 10px">

                                    <button ng-disabled="save_vocher" style="float: right;margin-left: 10px;" type="button"  ng-click="save_customer_payment_function()" class="btn btn-primary btn-sm "> Save Vocher</button>
                                    <img  ng-show="save_vocher" style="float: right;margin-left: 10px;margin-top: 5px"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
                                </td>
                            </tr>
                        </table>
                    </div>




                    <table id="customers">
                        <thead>
                        <tr>
                            <th><a href="">#</a></th>
                            <th><a href="">ID</a></th>

                            <th><a href="">Customer Name</a></th>
                            <th><a href=""> Address</a></th>
                            <th><a href=""> Mode of payment</a></th>
                            <th><a href="">Refrence No.</a></th>
                            <th><a href="">Amount</a></th>


                        </tr>
                        </thead>
                        <tbody>

                        <tr ng-repeat="List in customerList  track by $index">
                            <td>{{$index + 1}}</td>
                            <td>{{List.client_id}}</td>
                            <td ><span ng-bind="List.fullname"></span></td >
                            <td><span ng-bind="List.address"></span></td>
                          <!--  <td>
                                <span ng-bind="List.payment_user_name"></span>
                                <span ng-bind="List.payment_rider_name"></span>
                            </td>-->
                           <!-- <td style="width: 120px"><span ng-bind="List.date"></span></td>-->
                            <td><span ng-bind="List.payment_mode"></span></td>
                            <td><span ng-bind="List.reference_number"></span></td>
                            <td style="text-align: center"> <span ng-bind="List.amountpaid | number "></span></td>
                            <!--<td style="text-align: center"> <span ng-bind="List.discount_amount "></span></td>
                            <td style="text-align: center"> <span ng-bind="List.net_amount | number"></span></td>
                            <td ></td>-->
                        </tr>
                        <tr>
                            <th></th>
                            <th colspan="5"> <a href=""> Total </a></th>
                            <th style="text-align: center" ><a href="#">{{count |number}} </a> </th>

                        </tr>
                        </tbody>


                    </table>
                </div>
            </div>

            <!--Company Limit Model-->
            <modal title="Set Company Limit" visible="limitModelShow">
                <div class="row">
                    <?php
                    $form = $this->beginWidget(
                        'CActiveForm',
                        array(
                            'id' => 'agreement-form',
                            'enableAjaxValidation' => false,
                        )
                    );
                    ?>
                    <div class="col-sm-12">
                        <div class="col-sm-12">
                            <label for="email" style="font-weight: bold;float: left;margin: 10px">Company Limit :</label>
                            <input style="float: left; width: 40%" type="number" class="form-control" name="companyLimit" placeholder="" ng-model="companyLimit" required />
                        </div>
                        <div class="col-sm-12">
                            <div style="margin: 12px">
                                <button  type="submit" class="btn-success  btn-sm">Save</button>
                                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                        <?php $this->endWidget(); ?>
                    </div>
            </modal>


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
