

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
    <div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo $data ?>)'>


        <div class="tabbable">
            <ul class="nav nav-tabs nav-tabs-lg">
                <li>

                    <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                        Referred Customers View
                    </a>

                </li>
            </ul>
            <div class="tab-content">
                <div class=" active" id="tab_1" style="margin: 10px">

                    <div style="margin-bottom: 5px">
                        <select style="float: left ; width: 18% ;" class="form-control input-sm" ng-model="todayMonth">
                            <option value="01">January</option>
                            <option value="02">February</option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="0">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>

                        <select  style="float: left ; width: 18% ;" class="form-control  input-sm" ng-model="todayYear">
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

                        <select ng-model="product_id" style="float: left ; width: 18% ;margin-left: 5px"  class=" input-sm form-control">
                            <option value="0">Select Product</option>
                            <option value="{{list.product_id}}" ng-repeat="list in product_list">{{list.name}}</option>
                        </select>
                    </div>



                   <!-- <input style="float: left ; width: 18% ;margin-bottom: 10px" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">
                    <button  style="float: left" type="button"  ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-info btn-sm"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
                    <input ng-disabled="false" style="float: left ; width: 18% ;" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">-->
                    <!--<select style="margin-left :10px;margin-right:10px ;float: left ; width: 18% ;margin-bottom: 10px" class="form-control input-sm" ng-model="vendor_id">
                        <option value="0">All Rider</option>
                        <option value="{{list.vendor_id}}" ng-repeat="list in riderList">{{list.vendor_name}}</option>
                    </select>-->


                    <button type="button"  ng-click="getDataFunction()" class="btn btn-primary btn-sm"> <i class="fa fa-search" style="margin: 5px"></i> Search</button>

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

                    <table id="customers" style="margin-top: 10px">
                        <thead>
                        <tr>
                            <th><a href="#"> #</a></th>
                            <th>
                                <a href="#">
                                    Refered By
                                </a>
                            </th>

                            <th><a href="#">No. of Customers Referred</a> </th>
                            <th><a href="#">Referred Sale Quantity</a> </th>
                          <!--  <th><a href="#">Referred Sale Amount</a> </th>-->


                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="list in list track by $index">
                            <td>{{$index + 1}}</td>
                            <td>
                                <a href="<?php echo Yii::app()->baseUrl; ?>/referredcustomers/referredcustomers_list?customer_source={{list.customer_source}}&product_id={{product_id}}&todayYear={{todayYear}}&todayMonth={{todayMonth}}">
                                  {{list.customer_source}}
                                </a>
                            </td>
                            <td style="text-align: center">{{list.no_of_customers}}</td>
                            <td style="text-align: center">{{list.refered_quantity}}</td>
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
