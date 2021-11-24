
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/DateRangeRiderledger/riderwisesale_view_grid.js"></script>
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
    <div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo $main_deaing; ?>,<?php echo json_encode($fiveDayAgo) ?> , <?php echo $company_id ?> , <?php echo $riderList; ?>,"<?php echo Yii::app()->createAbsoluteUrl('riderwisesale/date_range_rider_sale'); ?>"," <?php echo Yii::app()->createAbsoluteUrl('riderwisesale/googleMap');?>" ," <?php echo Yii::app()->createAbsoluteUrl('riderwisesale/saveDeliveryFromPortal');?>")'>
        <div class="tabbable">
            <ul class="nav nav-tabs nav-tabs-lg">
                <li>
                    <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                        Rider Wise Sale View
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class=" active" id="tab_1" style="margin: 10px">
                    <input style="float: left ; width: 22% ;margin-bottom: 10px" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">
                    <button  style="float: left" type="button"  ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-info btn-sm"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
                    <input ng-disabled="false" style="float: left ; width: 22% ;" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">

                    <button type="button"  ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-primary btn-sm"> <i class="fa fa-search" style="margin: 5px"></i> Search</button>

                    <button type="button"  ng-click="printFunction()" class="btn btn-primary btn-sm"> <i class="fa fa-print" style="margin: 5px"></i> Print</button>
                    <!--  <button class="btn btn-info btn-sm" style="margin-left: 5px" onclick="javascript:xport.toCSV('customers');"> <i class="fa fa-share" style="margin: 5px"></i> Export</button>-->
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
                            <th rowspan="2" style="width: 250px"><a href="#">Date</a></th>
                            <th ng-repeat="list in riderList" colspan="2" >
                                <a  href="#"> <span ng-bind="list.fullname"></span></a>
                             </th>
                        </tr>

                        <tr>
                            <td ng-repeat="list in main_heading track by $index"><a href=""> <span ng-bind="list"></span></a></td>
                        </tr>


                        </thead>
                        <tbody>

                        <tr ng-repeat="main_list_1 in quantity_amount_sale_list track by $index">
                            <td ng-show="$index==0" style="text-align: right" ng-repeat="list in main_list_1 track by $index"><span ng-bind="list"></span></td>
                            <td ng-show="$index!=0" style="text-align: right" ng-repeat="list in main_list_1 track by $index"><span ng-bind="list | number :0"></span></td>
                        </tr>
                        <tr>
                            <th style="width: 250px"><a href="#">Total</a></th>
                            <td style="text-align: right" ng-repeat="list in grand_total track by $index"><span ng-bind="list | number :0"></span> </td>
                        </tr>

                        <tr>
                            <th colspan="2">
                                <a href=""> Total Quantity</a>
                            </th>
                            <td>
                                {{grand_total_quantity.total_quantity | number:2}}
                            </td>

                            <th colspan="2">
                                <a href=""> Total Amount</a>
                            </th>

                            <td>
                                {{grand_total_quantity.total_amount | number:2}}
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>


            <div  ng-show="false" id="printTalbe">
                <table width="100%" style="border-collapse: collapse; border: 1px solid black;">
                    <thead>
                    <tr>
                        <th rowspan="2" style=" border: 1px solid black;">Date</th>
                        <th style=" border: 1px solid black;" ng-repeat="list in riderList" colspan="2" >
                             <span ng-bind="list.fullname"></span>
                        </th>
                    </tr>

                    <tr>
                        <td style=" border: 1px solid black;" ng-repeat="list in main_heading track by $index"> <span ng-bind="list"></span></td>
                    </tr>


                    </thead>
                    <tbody>

                    <tr ng-repeat="main_list_1 in quantity_amount_sale_list track by $index">

                        <td style="text-align: right; border: 1px solid black;" ng-repeat="list in main_list_1 track by $index"><span ng-bind="list"></span></td>
                    </tr>
                    <tr>
                        <th style=" border: 1px solid black;">Total</th>
                        <td style="text-align: right; border: 1px solid black;" ng-repeat="list in grand_total track by $index"><span ng-bind="list | number :0"></span> </td>
                    </tr>

                    </tbody>
                </table>
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
