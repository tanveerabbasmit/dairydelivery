<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/categoryWiseCustomerDateRangeReport/categoryWiseCustomerDateRangeReport-grid.js"></script>
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
    <div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo $payment_term; ?> ,<?php echo $customerCategory; ?> , <?php echo $year ?> , <?php echo $monthNum ?> , <?php echo json_encode($fiveDayAgo) ?> , <?php echo $company_id ?> , <?php echo $riderList; ?>,"<?php echo Yii::app()->createAbsoluteUrl('index.php/PaymentMaster/getCustomerLedger_dateRangeCustomerReport'); ?>"," <?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/googleMap');?>" ," <?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/saveDeliveryFromPortal');?>")'>
        <div class="tabbable">
            <ul class="nav nav-tabs nav-tabs-lg">
                <li>
                    <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                        Category Wise Customer Date Range Report
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class=" active" id="tab_1" style="margin: 10px">
                    <!--<select ng-model="clientID" class="form-control" style="width: 30%; float: left" >
                           <option value="">Select Customer </option>
                         <option ng-repeat="list in clientList" value="{{list.client_id}}">{{list.fullname}}</option>
                       </select>-->

                    <input style="float: left ; width: 18% ;margin-bottom: 10px" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">
                    <button  style="float: left" type="button"   class="btn btn-info btn-sm"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
                    <input ng-disabled="false" style="float: left ; width: 18% ;" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">


                    <select style="width: 18%; float: left;margin-bottom: 10px;margin-left: 10px ; margin-right: 10px" class="form-control input-sm"  ng-model="selectRiderID">
                        <option value="0">All Rider</option>
                        <option ng-repeat="list in riderList" value="{{list.rider_id}}">{{list.fullname}}
                        </option>
                    </select>

                    <select style="width: 18%; float: left;margin-bottom: 10px;margin-left: 10px ; margin-right: 10px" class="form-control input-sm" ng-model="customer_category_id" ng-change="changeSelectRider(selectRiderID)">
                        <option value="0">All</option>
                        <option ng-repeat="list in customerCategory" value="{{list.customer_category_id}}">{{list.category_name}}
                        </option>
                    </select>

                    <button type="button"  ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-primary btn-sm"> <i class="fa fa-search" style="margin: 5px"></i> Search</button>
                    <button type="button"  ng-click="printFunction()" class="btn btn-primary btn-sm"> <i class="fa fa-print" style="margin: 5px"></i> Print</button>

                    <button class="btn btn-info btn-sm" style="margin-left: 5px;" onclick="javascript:xport.toCSV('customers');"> <i class="fa fa-share"></i> Export</button>

                    <img ng-show="imageLoading" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
                    <!-- {{todayDeliveryproductList}}-->

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
                    <table id="customers">
                        <thead>
                        <tr>
                            <th><a href="#"> #</a></th>
                            <th><a href="#"> ID</a></th>
                            <th><a href="#"> Customer Name</a></th>
                            <th><a href="#" >Address</a></th>
                            <th><a href="#" >Rate</a></th>
                            <th><a href="#" >Quantity</a></th>
                            <th><a href="#" >Opening Balance</a></th>
                            <th><a href="#" >Current Month sale</a></th>
                            <th><a href="#">Total Receiveable</a></th>
                            <th><a href="#">Total Received</a></th>
                            <th><a href="#">Outstanding balance</a></th>
                            <th><a href="#">Advance</a></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="regularOrderList in todayDeliveryproductList | orderBy:sortType:!sortReverse  track by $index">
                            <td>{{$index + 1}}</td>
                            <td><span ng-bind="regularOrderList.client_id"></span></td>
                            <td style="color: {{regularOrderList.color}} ; font-weight: bold;" > <span ng-bind="regularOrderList.fullname"></span>
                                <br> {{regularOrderList.cell_no_1}}</td >
                            <td style="width: 150px"><span ng-bind="regularOrderList.address"></span></td>
                            <td style="width: 150px;text-align: right"><span ng-bind="regularOrderList.product_list_rate |number:0"></span></td>
                            <td style="width: 150px;text-align: right"><span ng-bind="regularOrderList.product_quantity"></span></td>
                            <td style="text-align: right"><span ng-bind="regularOrderList.final_total_amount_opening | number"></span></td>
                            <td style="text-align: right"><span ng-bind="regularOrderList.totaldeliverySum_current | number"></span></td>

                            <td style="text-align: right"><span ng-bind="regularOrderList.totalMakePayment | number"></span></td>
                            <td style="text-align: right"><span ng-bind="regularOrderList.endDateBalance | number"></span></td>
                            <td style="text-align: right"><span ng-bind="regularOrderList.balance | number"></span></td>
                            <td style="text-align: right"><span ng-bind="regularOrderList.difference | number"></span></td>
                        </tr>
                        <tr>
                            <td></td>
                            <th><a href="#"></a></th>
                            <th ><a href="#"></a></th>


                            <th><a href="#" >Total</a></th>
                            <th style="text-align: right"><a href="#">{{vag_rate }}</a></th>
                            <th style="text-align: right"><a href="#"  >{{product_quantity_total | number}}_0</a></th>

                             <th style="text-align: right"><a href="#" >{{final_total_amount_opening_sum | number}} _1</a></th>
                              <th style="text-align: right"><a href="#" >{{totaldeliverySum_current_sum | number}} _2</a></th>
                              <th style="text-align: right"><a href="#" >{{amountPaid | number}}_3</a></th>
                              <th style="text-align: right"><a href="#" >{{endDateBalance | number}}_4</a></th>
                              <th style="text-align: right"><a href="#" >{{totalOutStandingBalance | number}}_5</a></th>
                              <th style="text-align: right"><a href="#" >{{difference | number}}</a></th>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <table width="100%" ng-show="false" id="printTalbe" style="border-collapse: collapse; border: 1px solid black;">
                <tr>
                    <td style=" border: 1px solid black;" colspan="3">Date Rang: </td>
                    <td style=" border: 1px solid black;" colspan="4"> {{selectedMonthName}} {{year}} </td>

                </tr>

                <tr>
                    <td style=" border: 1px solid black;" colspan="3">Rider </td>
                    <td style=" border: 1px solid black;" colspan="4">{{selectedRider}}</td>

                </tr>
                <tr>
                    <td style=" border: 1px solid black;">#</td>
                    <td style=" border: 1px solid black;">ID</td>
                    <td style=" border: 1px solid black;">Customer Name</td>
                    <td style=" border: 1px solid black;">Address</td>
                    <td style=" border: 1px solid black;">Quantity</td>
                    <td style=" border: 1px solid black;">Amount Paid</td>
                    <td style=" border: 1px solid black;">Outstanig Balance</td>
                </tr>

                <tr ng-repeat="regularOrderList in todayDeliveryproductList | orderBy:sortType:!sortReverse  track by $index">
                    <td style=" border: 1px solid black;">{{$index + 1}}</td>
                    <td style=" border: 1px solid black;"><span ng-bind="regularOrderList.client_id"></span></td>
                    <td style="color: {{regularOrderList.color}};border: 1px solid black;" > <span ng-bind="regularOrderList.fullname"></span>
                        <br> {{regularOrderList.cell_no_1}}</td >

                    <td style=" border: 1px solid black;"><span ng-bind="regularOrderList.address"></span></td>
                    <td style=" border: 1px solid black;"><span ng-bind="regularOrderList.product_quantity"></span></td>
                    <td style="text-align: right;border: 1px solid black;"><span ng-bind="regularOrderList.totalMakePayment | number"></span></td>
                    <td style="text-align: right;border: 1px solid black;"><span ng-bind="regularOrderList.balance | number"></span></td>

                </tr>
                <tr>
                    <td style=" border: 1px solid black;"></td>
                    <td style=" border: 1px solid black;"></td>
                    <td style=" border: 1px solid black;"></td>
                    <td style=" border: 1px solid black;"></td>
                    <th style=" border: 1px solid black;" >Total</th>
                    <td style="text-align: right ;border: 1px solid black;">{{amountPaid | number}}</td>
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
