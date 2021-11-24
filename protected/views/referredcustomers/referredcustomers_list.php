

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/referredcustomers/referredcustomers_list_grid.js"></script>
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
                        <tr ng-repeat="list in final_data track by $index">
                            <td>{{$index + 1}}</td>
                            <td>
                                <a href="<?php echo Yii::app()->baseUrl; ?>/referredcustomers/referredcustomers_list?customer_source={{list.customer_source}}&product_id={{product_id}}&todayYear={{todayYear}}&todayMonth={{todayMonth}}">
                                    {{list.fullname}}
                                </a>
                            </td>
                            <td style="text-align: center">{{list.address}}</td>
                            <td style="text-align: center">{{list.cell_no_1}}</td>
                            <td style="text-align: center">{{list.zone_name}}</td>
                            <td style="text-align: center">{{list.payment_term_name}}</td>
                            <td style="text-align: center">{{list.first_delivery_on}}</td>
                            <td style="text-align: center">{{list.last_delivery_on}}</td>
                            <td style="text-align: center">{{list.selected_product}}</td>
                            <td style="text-align: center">{{list.quantity}}</td>
                            <td style="text-align: center">{{list.rate}}</td>
                            <td style="text-align: center">{{list.amount}}</td>
                        </tr>

                        </tbody>
                    </table>
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
