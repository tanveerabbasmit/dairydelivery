
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/PosDateRang/dailymilksummary_view_report_grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>


<div  id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">
    <div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo str_replace("'","&#39;",$data ); ?>," <?php echo Yii::app()->createAbsoluteUrl('dailymilksummary/dailymilksummary_view_report_data');?>")'>

        <div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <strong>message! </strong>
            {{taskMessage}}
        </div>


        <div class="tabbable">
            <ul class="nav nav-tabs nav-tabs-lg">
                <li>
                    <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                        Daily Milk Summary
                    </a>
                </li>
            </ul>
            <!--  {{productList}}
              {{todayData}}-->


            <div class="tab-content">
                <div class=" active" id="tab_1" style="margin-bottom: 10px; margin: 10px">




                    <input  style="float: left ; width: 20% ;" class="form-control input-sm"  ng-change="selectRiderOnChange(selectRiderID)"  datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="today_data" size="2">


                     <select ng-model="product_id" style="float: left ; width: 20% ;margin-left: 5px" class="form-control input-sm">
                         <option value="0">Select</option>
                         <option ng-repeat="list in project_list" value="{{list.product_id}}">{{list.name}}</option>
                     </select>

                    <button style="margin-left: 10px;" type="button"  ng-click="get_data_function()" class="btn btn-primary btn-sm "> <i class="fa fa-search" style="margin: 4px"></i> Search</button>

                  <!--  <a ng-disabled="true" class="btn btn-primary btn-sm" href="<?php /*echo Yii::app()->createUrl('#')*/?>"><i class="fa fa-share" style="margin: 4px"></i> Export </a>-->
                    <img ng-show="loading" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">

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


                   <div style="text-align: center">
                       <h3>{{company_title}}</h3>
                       <h4>Daily Milk Summary (System Generated)</h4>
                       <h5>{{today_data}}</h5>
                   </div>

                    <table id="customers" style="margin-top: 6px">
                        <thead>
                        <tr style="background-color: #F0F8FF">
                            <th><a href="#">Sr No.</a></th>
                            <th><a href="#">Parameter</a></th>
                            <th><a href="#">Description</a></th>
                            <th><a href="#">Qty</a></th>
                        </tr>
                        </thead>
                        <tbody style="background-color: white">
                        <tr style="background-color: white">
                            <td></td>
                            <td colspan="2">Carry forward from previous day</td>
                            <td style="text-align: center">{{opening_stock |number:2}}</td>
                        </tr>
                        <tr style="background-color: white">
                            <td rowspan="3">1</td>
                            <td rowspan="3">own Production</td>
                            <td>Morning</td>
                            <td style="text-align: center">{{production_stock.morning |number:2}}</td>
                        </tr>
                        <tr style="background-color: white">
                            <td>Noon</td>
                            <td style="text-align: center">{{production_stock.afternoun|number:2}}</td>
                        </tr>
                        <tr style="background-color: white">
                            <td>Evening</td>
                            <td style="text-align: center">{{production_stock.evenining|number:2}}</td>
                        </tr>
                        <tr style="background-color: white">
                            <td></td>
                            <td colspan="2" style=" font-weight: bold;">Total Production</td>
                            <td style="text-align: center;font-weight: bold;">{{total_production |number:2}}</td>

                        </tr>
                        <tr style="background-color: white" ng-repeat="list in purchase_list.final_rresult">
                            <td ng-show="$index==0" rowspan="{{purchase_list_size}}">2</td>
                            <td ng-show="$index==0" rowspan="{{purchase_list_size}}" >Total purchase</td>
                            <td style="text-align: center">{{list.farm_name}}</td>
                            <td style="text-align: center">{{list.net_quantity}}</td>

                        </tr>
                        <tr style="background-color: white">
                            <td></td>
                            <td colspan="2" style="font-weight: bold;">TOTAL MILK PURCHASED</td>
                            <td style="text-align: center;font-weight: bold;">{{purchase_list.grand_total |number:2}}</td>
                        </tr>
                        <tr style="background-color: white">
                            <td rowspan="{{rider_return_size+1}}">2</td>
                            <td rowspan="{{rider_return_size+1}}">Milk Return</td>


                        </tr>
                        <tr style="background-color: white" ng-repeat="list in rider_return">
                            <td>{{list.fullname}}</td>
                            <td style="text-align: center">0</td>
                        </tr>

                        <tr style="background-color: white">
                            <td></td>
                            <td colspan="2" style="font-weight: bold;">Total milk Return</td>
                            <td style="text-align:center;font-weight: bold; ">0</td>
                        </tr>

                        <tr style="background-color: white">
                            <td></td>
                            <td colspan="2" style="font-weight: bold;">Grand Total</td>
                            <td style="text-align:center ;font-weight: bold;">{{grand_total_stock_in |number:2}}</td>
                        </tr>

                        <tr style="background-color: white">
                            <td style="text-align: center;font-weight: bold;" colspan="4">
                                Sale
                            </td>
                        </tr>

                        <tr style="background-color: white">
                           <td>Sr No.</td>
                           <td>Parameters</td>
                           <td>Description</td>
                           <td>Qty</td>
                        </tr>
                        <tr style="background-color: white" ng-repeat="list in one_day_credit_sale_list.list">
                                <td>{{$index+1}}</td>
                                <td>{{list.name}}</td>
                                <td></td>
                                <td style="text-align:center ">{{list.amount |number:0}}</td>
                        </tr>
                        <tr>
                            
                        </tr>

                        <tr style="background-color: white">
                          <td colspan="4"></td>
                        </tr>

                        <tr style="background-color: white">
                            <td style="text-align: center;font-weight: bold;" colspan="4">In House Usage</td>
                        </tr>
                        <tr style="background-color: white" ng-repeat="list in in_house_usage.list">
                             <td>{{$index + 1}} </td>
                            <td colspan="2">{{list.name}}</td>
                            <td style="text-align: center">{{list.amount |number:0}}</td>
                        </tr>
                        <tr style="background-color: white">
                            <td></td>
                            <td colspan="2">Sub Total</td>
                            <td style="text-align: center">{{in_house_usage.total_in_home_uses |number:0}}</td>
                        </tr>

                        <tr style="background-color: white">
                            <td></td>
                            <td colspan="2" style="font-weight: bold;">Total sold and used</td>
                            <td style="text-align: center;font-weight: bold;">{{grand_total_sale_and_use |number:0}}</td>
                        </tr>

                        <tr style="background-color: white">
                            <td></td>
                            <td colspan="2" style="font-weight: bold;">Carry Forword Milk to next day</td>
                            <td style="text-align: center;font-weight: bold;">{{next_day_carry |number:2}}</td>
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
