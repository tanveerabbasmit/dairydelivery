

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/referredcustomers/referredcustomers_view_grid.js"></script>
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
    <div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo $fiveDayAgo ?> , <?php echo $company_id ?> , <?php echo $riderList; ?>,"<?php echo Yii::app()->createAbsoluteUrl('referredcustomers/referredcustomers_view_list'); ?>"," <?php echo Yii::app()->createAbsoluteUrl('referredcustomers/googleMap');?>" ," <?php echo Yii::app()->createAbsoluteUrl('referredcustomers/saveDeliveryFromPortal');?>")'>


        <div class="tabbable">
            <ul class="nav nav-tabs nav-tabs-lg">
                <li>

                    <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                       Referred Customers list
                    </a>

                </li>
            </ul>
            <div class="tab-content">
                <div class=" active" id="tab_1" style="margin: 10px">

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
                     {{list}}
                    <table id="customers">
                        <thead>
                        <tr>
                            <th><a href="#"> #</a></th>
                            <th><a href="#">Referred Customer Name</a></th>
                            <th><a href="#">Address</a> </th>
                            <th><a href="#">Mob.# </a> </th>
                            <th><a href="#">Zone</a> </th>
                            <th><a href="#">Payment Term</a> </th>
                            <th><a href="#">First Delivered on</a> </th>
                            <th><a href="#">Last Delivered on</a> </th>
                            <th><a href="#">Product Name</a> </th>
                            <th><a href="#">Quantity</a> </th>
                            <th><a href="#">Rate</a> </th>
                            <th><a href="#">Sale Amount</a> </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="list in riderList">
                            <td>{{$index + 1}}</td>
                            <td> {{list.fullname}}</td>
                            <td> {{list.cell_no_1}}</td>
                            <td> {{list.address}}</td>

                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>

            <table width="100%" ng-show="false" id="printTalbe" style="border-collapse: collapse; border: 1px solid black;">
                <tr>
                    <td style=" border: 1px solid black;" colspan="3">Date Rang: </td>
                    <td style=" border: 1px solid black;" colspan="5">{{startDate}} TO {{endDate}} </td>
                </tr>
                <tr>
                <tr>
                    <td style=" border: 1px solid black;" colspan="3">Rider </td>
                    <td style=" border: 1px solid black;" colspan="5">{{selectedRider}}</td>
                </tr>
                <tr>
                    <td style=" border: 1px solid black;">#</td>
                    <td style=" border: 1px solid black;">ID</td>
                    <td style=" border: 1px solid black;">Customer Name</td>
                    <td style=" border: 1px solid black;">Address</td>



                    <td style=" border: 1px solid black;">Opening Balance</td>
                    <td style=" border: 1px solid black;">Delivery</td>

                    <td style=" border: 1px solid black;">Amount Paid</td>
                    <td style=" border: 1px solid black;"> Outstanding Balance</td>
                </tr>
                <tr ng-repeat="regularOrderList in todayDeliveryproductList | orderBy:sortType:!sortReverse  track by $index">
                    <td style=" border: 1px solid black;">{{$index + 1}}</td>
                    <td style=" border: 1px solid black;"><span ng-bind="regularOrderList.client_id"></span></td>
                    <td style="color: {{regularOrderList.color}};border: 1px solid black;" > <span ng-bind="regularOrderList.fullname"></span>
                        <br> {{regularOrderList.cell_no_1}}</td >





                    <td style=" border: 1px solid black;"><span ng-bind="regularOrderList.address"></span></td>
                    <td style="text-align: right;border: 1px solid black;"><span ng-bind="regularOrderList.OpeningBlance | number"></span></td>
                    <td style="text-align: right;border: 1px solid black;"><span ng-bind="regularOrderList.totalMakeDelivery | number"></span></td>
                    <td style="text-align: right;border: 1px solid black;"><span ng-bind="regularOrderList.totalMakePayment | number"></span></td>
                    <td style="text-align: right;border: 1px solid black;"><span ng-bind="regularOrderList.balance | number"></span></td>

                </tr>
                <tr>
                    <td style=" border: 1px solid black;"></td>
                    <th style=" border: 1px solid black;" colspan="3">Total</th>
                    <td style="text-align: right ;border: 1px solid black;">{{total_OpeningBlance | number}}</td>
                    <td style="text-align: right;border: 1px solid black;">{{total_totalMakeDelivery | number}}</td>
                    <td style="text-align: right;border: 1px solid black;">{{amountPaid | number}}</td>
                    <td style="text-align: right;border: 1px solid black;">{{totalOutStandingBalance | number}}</td>
                </tr>


            </table>

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
