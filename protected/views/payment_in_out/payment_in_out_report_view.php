

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/ProductSummary/payment_in_out_report_view_grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>



<div id="testContainer" style="display: none" class="panel row" ng-app="productGrid">
    <div ng-controller="manageProduct" ng-init='init(<?php echo $today_date; ?>, <?php echo $productList ?> ,"<?php echo Yii::app()->createAbsoluteUrl('Payment_in_out/payment_in_out_report_view_report_view_list'); ?>")'>
        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                    Payment In Out Report
                </a>
            </li>

        </ul>
        <div  style="margin: 10px">

            <div style="margin-top:5px;" class="table-responsive">

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

                <input  style="float: left ; width: 20% ;" class="form-control input-sm"  ng-change="selectRiderOnChange(selectRiderID)"  datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="today_date" size="2">

                <button style="float: left" type="button" ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-info btn-sm"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>

                <input  style="float: left ; width: 20% ;" class="form-control input-sm"  ng-change="selectRiderOnChange(selectRiderID)"  datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="end_date" size="2">
                <button style="margin-left: 10px;" type="button"  ng-click="selectDateWiseData()" class="btn btn-primary btn-sm "> <i class="fa fa-search" style="margin: 4px"></i> Search</button>
                <img ng-show="imageLoading" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">


                <table id="customers" style="margin-top: 10px">

                    <tr  style="background-color: #F0F8FF">
                        <th colspan="3"><a target="_blank" href="<?php echo Yii::app()->baseUrl; ?>/riderDailyStock/dateWisedeliveryReport?start_date={{today_date}}&end_date={{end_date}}">Make Delivery</a></th>
                    </tr>

                    <tr style="background-color: white;" ng-repeat="list in delivery_list.product_list">
                        <td style="background-color: white"><span ng-bind="list.name"></span> </td>
                        <td style="background-color: white;text-align: right"><span ng-bind="list.quantity"></span></td>
                        <td style=" background-color: white; text-align: right"><span ng-bind="list.amount | number :0"></span></td>
                    </tr>

                    <tr>
                        <th style="background-color: LightGrey;"><a href="" style="color: MidnightBlue">Total Delivery</a></th>

                        <td style="background-color: LightGrey;text-align: right"><span ng-bind="delivery_list.total_quantity"></span> </td>

                        <td style="background-color: LightGrey;text-align: right"><span ng-bind="delivery_list.total_amount | number :0"></span> </td>
                    </tr>


                    <tr  style="background-color: #F0F8FF">
                        <th colspan="3"><a target="_blank" href="<?php echo Yii::app()->baseUrl; ?>/ExpenceReport/addExpence?start_date={{today_date}}&end_date={{end_date}}">Expence</a></th>
                    </tr>
                    <tr  style="background-color: white;" ng-repeat="list in expence_type.result">
                        <td colspan="2"><span ng-bind="list.type"></span> </td>
                        <td style="text-align: right"><span ng-bind="list.amount | number :0"></span></td>
                    </tr>
                    <tr>
                        <th style="background-color: LightGrey"><a href="" style="color: MidnightBlue"> Total Expence</a></th>
                        <td style="background-color: LightGrey"></td>
                        <td style="background-color: LightGrey;text-align: right"><span ng-bind="expence_type.total_amount"></span> </td>
                    </tr>
                    <tr  style="background-color: #F0F8FF">
                        <th colspan="3"><a href="#">Vendor Bill Amount</a></th>
                    </tr>
                    <tr  style="background-color: white;" ng-repeat="list in vendor_bill_amount.list">
                        <td colspan="2"><span ng-bind="list.vendor_name"></span> </td>
                        <td style="text-align: right"><span ng-bind="list.gross_amount "></span></td>
                    </tr>
                    <tr>
                        <th style="background-color: LightGrey"><a href="" style="color: MidnightBlue">Total Vendor Bill Amount </a></th>
                        <td style="background-color: LightGrey"></td>
                        <td style="background-color: LightGrey;text-align: right"><span ng-bind="vendor_bill_amount.total_amount"></span> </td>
                    </tr>


                    <tr  style="background-color: #F0F8FF">
                        <th colspan="3"><a target="_blank" href="<?php echo Yii::app()->baseUrl; ?>/riderDailyStock/dateWisedeliveryReport?start_date={{today_date}}&end_date={{end_date}}">POS</a></th>
                    </tr>
                    <tr style="background-color: white;" ng-repeat="list in pos.product_list">
                        <td><span ng-bind="list.name"></span> </td>
                        <td style="text-align: right"><span ng-bind="list.quantity | number :0"></span></td>
                        <td style="text-align: right"><span ng-bind="list.total_price | number :0"></span></td>
                    </tr>

                    <tr  style="background-color: #F0F8FF">
                        <th colspan="3"><a href="<?php echo Yii::app()->baseUrl; ?>/company/viewcompanystock?start_date={{today_date}}&end_date={{end_date}}"">Farm Purchase</a></th>
                    </tr>
                    <tr style="background-color: white;" ng-repeat="list in farm_purchase.list">
                        <td><span ng-bind="list.farm_name"></span> </td>
                        <td style="text-align: right"><span ng-bind="list.net_quantity | number :0"></span></td>
                        <td style="text-align: right"><span ng-bind="list.net_amount | number :0"></span></td>
                    </tr>

                    <tr>
                        <th style="background-color: LightGrey"><a href="" style="color: MidnightBlue">Total Purchase</a></th>
                        <td style="background-color: LightGrey;text-align: right"><span ng-bind="farm_purchase.total_result.total_net_quantity | number:0"></span> </td>
                        <td style="background-color: LightGrey;text-align: right"><span ng-bind="farm_purchase.total_result.total_net_amount | number:0"></span> </td>

                    </tr>
                    <tr>
                        <th colspan="2" style="background-color: Khaki"><a href="" style="color: MidnightBlue">Grand Total</a></th>

                        <td style="background-color: Khaki;text-align: right"><span ng-bind="final_balance | number"></span> </td>

                    </tr>

                   <!-- <tr>
                        <th><a href="">Vendor Payment</a></th>
                        <td></td>
                        <td style="text-align: right"><span ng-bind="vendor_payment"></span> </td>
                    </tr>
                    <tr>
                        <th><a href="">Make Payment</a></th>
                        <td></td>
                        <td style="text-align: right"><span ng-bind="make_payment"></span> </td>
                    </tr>-->

                </table>



            </div><!-- table-responsive -->
        </div>






    </div>
</div>

