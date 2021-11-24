<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/CutomerDiscountReport/CutomerDiscountReport-grid.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/FileSaver/FileSaver.js"></script>


<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>

<?php /*echo $riderList; */?>



<div  id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">
    <div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo  $discount_type ?> , <?php echo $year ?> , <?php echo $monthNum ?> , <?php echo json_encode($fiveDayAgo) ?> , <?php echo $company_id ?> , <?php echo $riderList; ?>,"<?php echo Yii::app()->createAbsoluteUrl('DiscountList/discountReport'); ?>"," <?php echo Yii::app()->createAbsoluteUrl('DiscountList/googleMap');?>" ," <?php echo Yii::app()->createAbsoluteUrl('DiscountList/saveDeliveryFromPortal');?>")'>

        <!-- {{discountList}}-->

        <div class="tabbable">
            <ul class="nav nav-tabs nav-tabs-lg">
                <li>
                    <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                        Rider Wise Discount Report
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class=" active" id="tab_1" style="margin: 10px">

                    <select style="float: left ; width: 15% ;" class="form-control input-sm" ng-model="monthNum">
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>



                    <select style="float: left ; width: 15% ;" class="form-control input-sm"  ng-model="year">
                        <option value="2016">2016</option>
                        <option value="2017">2017</option>
                        <option value="2018">2018</option>
                        <option value="2019">2019</option>
                        <option value="2020">2020</option>
                        <option value="2021">2021</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                        <option value="2027">2027</option>
                        <option value="2028">2028</option>
                        <option value="2029">2029</option>
                    </select>

                    <select style="width: 23%; float: left;margin-bottom: 10px;margin-left: 10px ; margin-right: 10px" class="form-control input-sm" ng-model="selectRiderID" ng-change="changeSelectRider(selectRiderID)">
                        <option value="0">All Rider</option>
                        <option ng-repeat="list in riderList" value="{{list.rider_id}}">{{list.fullname}}
                        </option>
                    </select>
                    <button type="button"  ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-primary btn-sm"> <i class="fa fa-search" style="margin: 5px"></i> Search</button>
                    <button ng-disabled="true" type="button"  ng-click="printFunction()" class="btn btn-primary btn-sm"> <i class="fa fa-print" style="margin: 5px"></i> Print</button>

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
                            <th><a href="#" >Received Amount</a></th>
                           <th ng-repeat="list in discount_type"><a href="#" >{{list.discount_type_name}}</a></th>
                           <th ><a href="#" >Total discount</a></th>
                        </tr>
                        </thead>
                        <tbody ng-repeat="list in discountList ">
                        <tr ng-show="selectAllRider">
                            <th></th>
                            <th></th>
                            <th colspan="1" style="text-align: right"> <a href="#">{{list.fullname}}</a>  </th>
                            <th></th>
                            <th ng-repeat="list in discount_type"></th>
                            <th></th>

                        </tr>
                        <tr ng-show="list.loading">
                            <td colspan="{{totalcolumn}}"> <img  style="width: 43px ; height: 23px ;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/Ellipsis-1s-200px.gif" alt="" class="loading"></td>

                        </tr>

                        <tr ng-show="list.nodataFound">
                            <td colspan="{{totalcolumn}}">No Discount Found</td>
                        </tr>

                        <tr ng-repeat="regularOrderList in list.discountList">
                            <td>{{$index + 1}}</td>
                            <td><span ng-bind="regularOrderList.client_id"></span></td>
                            <td style="color: {{regularOrderList.color}} ; " > <span ng-bind="regularOrderList.fullname"></span>
                                <br> {{regularOrderList.cell_no_1}}</td >
                            <td style="width: 150px;text-align: right"><span ng-bind="regularOrderList.amount_paid | number"></span></td>
                            <td style="width: 150px;text-align: right" ng-repeat="list2 in regularOrderList.discount track by $index"><span ng-bind="list2 | number"></span></td>
                            <td style="text-align: right"><span ng-bind="regularOrderList.total_discount | number "></span></td>
                        </tr>

                        <tr ng-show="!list.nodataFound">
                            <th></th>
                            <th></th>

                            <th colspan="1"> <a href="#">Total</a>  </th>
                            <th style="text-align: right" > <a href="#">{{list.total_paid | number}}</a></th>
                            <th style="text-align: right" ng-repeat="discount in list.one_rider_discount_wise_sum track by $index"><a href="#">{{discount}}</a> </th>
                            <th style="text-align: right" > <a href="#">{{list.total_discount | number}}</a></th>
                        </tr>
                        </tbody>
                        <tr ng-show="selectAllRider">
                            <th></th>
                            <th></th>
                            <th style="color: #008B8B;" colspan="1">Grand Total </th>
                            <th  style="text-align: right;color: #008B8B;">{{grand_total_paid | number}} </th>
                            <th style="text-align: right;color: #008B8B;" ng-repeat="list in grandDiscount track by $index"><a href="#" >{{list |number}}</a></th>
                            <th style="text-align: right;color: #008B8B;">{{grand_total_discount | number}} </th>
                        </tr>

                    </table>
                </div>
            </div>

            <table width="100%" ng-show="false" id="printTalbe" style="border-collapse: collapse; border: 1px solid black;">
                <tr>
                    <td style=" border: 1px solid black;" colspan="3">Date Rang: </td>
                    <th ng-repeat="list in discount_type"><a href="#" >{{list.discount_type_name}}</a></th>
                    <td style=" border: 1px solid black;" colspan="3">{{startDate}} TO {{endDate}} </td>

                </tr>

                <tr>
                    <td style=" border: 1px solid black;" colspan="3">Rider </td>
                    <td style=" border: 1px solid black;" colspan="3">{{selectedRider}}</td>

                </tr>
                <tr>
                    <td style=" border: 1px solid black;">#</td>
                    <td style=" border: 1px solid black;">ID</td>
                    <td style=" border: 1px solid black;">Customer Name</td>
                    <td style=" border: 1px solid black;">Address</td>
                    <td style=" border: 1px solid black;">Amount Paid</td>
                    <td style=" border: 1px solid black;">Outstanig Balance</td>
                </tr>

                <tr ng-repeat="regularOrderList in todayDeliveryproductList | orderBy:sortType:!sortReverse  track by $index">
                    <td style=" border: 1px solid black;">{{$index + 1}}</td>
                    <td style=" border: 1px solid black;"><span ng-bind="regularOrderList.client_id"></span></td>
                    <td style="color: {{regularOrderList.color}};border: 1px solid black;" > <span ng-bind="regularOrderList.fullname"></span>
                        <br> {{regularOrderList.cell_no_1}}</td >

                    <td style=" border: 1px solid black;"><span ng-bind="regularOrderList.address"></span></td>
                    <td style="text-align: right;border: 1px solid black;"><span ng-bind="regularOrderList.totalMakePayment | number"></span></td>
                    <td style="text-align: right;border: 1px solid black;"><span ng-bind="regularOrderList.balance | number"></span></td>

                </tr>
                <tr>
                    <td style=" border: 1px solid black;"></td>
                    <td style=" border: 1px solid black;"></td>
                    <td style=" border: 1px solid black;"></td>
                    <th style=" border: 1px solid black;" >Total</th>
                    <td style="text-align: right ;border: 1px solid black;">{{amountPaid | number :2}}</td>
                    <td style="text-align: right;border: 1px solid black;">{{totalOutStandingBalance | number :2}}</td>
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
