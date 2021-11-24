
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/dailyRecoveryReport/purchase_voucher_grid.js"></script>
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
                        <input type="radio" name="optradio" value="1" ng-model="payment_type_id">
                        <span style="margin-top: 5px">Farm purchase</span>
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="optradio" value="2" ng-model="payment_type_id">
                        <span style="margin-top: 5px">Vendor purchase</span>
                    </label>

                </div>


           </div>
            <div class="col-sm-12">
                <div class="tab-pane active" id="tab_1">
                    <input style="width: 25%; float: left;margin-bottom: 10px; margin-right: 10px" class="form-control input-sm"  ng-change="selectRiderOnChange(selectRiderID)"  datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">

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

                    <div ng-show="list_data.length>0" class="col-md-12" style="margin-top: 10px;margin-bottom: 10px">
                        <table style="width: 100%">
                            <tr>
                                <th style="padding: 15px">Total Amount :</th>
                                <td style="padding: 15px">{{grand_total |number}}</td>

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
                            <th ng-repeat="list in lable"><a href="" >{{list}}</a></th>


                        </tr>
                        </thead>
                        <tbody>

                        <tr ng-repeat="List in list_data  track by $index">
                            <td>{{$index + 1}}</td>
                            <td>{{List.farm_name}}</td>
                            <td>{{List.product_name}}</td>
                           <!-- <td style="text-align: center">{{List.purchase_rate}}</td>-->
                            <td style="text-align: center">{{List.net_quantity}}</td>
                            <td style="text-align: center">{{List.net_amount}}</td>

                        </tr>
                        <tr>
                            <th></th>
                            <th colspan="3"> <a href=""> Total </a></th>
                            <th style="text-align: center" ><a href="#">{{grand_total |number}} </a> </th>
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
