<style>
    .angularjs-datetime-picker{
        z-index: 99999 !important;
    }
</style>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/crudoptionhistory/crudoptionhistory_grid.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/FileSaver/FileSaver.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>


<div class="panel row" id="testContainer" style="display: none" ng-app="clintManagemaent">
    <div ng-controller="clintManagemaent" ng-init='init(<?php echo $riderList;  ?> , <?php echo $clientList ?> , "<?php echo Yii::app()->createAbsoluteUrl('index.php/Crudoptionhistory/get_list'); ?>")'>

        <div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <strong>message! </strong>
            {{taskMessage}}
        </div>
        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                    Log History Report
                </a>
            </li>
        </ul>
        <!--  {{riderList}}-->
        <div class="row" style="margin: 10px">
            <div class="col-lg-12">


                <select style=" float: left ; width: 18% ;" ng-model="payment_mode" class="form-control input-sm ng-pristine ng-valid ng-not-empty ng-valid-required ng-touched" required="">
                    <option value="0">All</option>
                    <option value="edit_delivery">Delivery Edit</option>
                    <option value="delete_delivery">Delivery Delete</option>
                    <option value="edit_payment">Payment Edit</option>
                    <option value="delete_payment">Payment Delete</option>
                    <option value="farm_payment">Farm Payment</option>

                </select>

                <input style="float: left ; width: 18% ;" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">
                <button class="btn btn-info btn-sm" style="float: left"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
                <input style="width: 18% ; float: left"  class="form-control input-sm "    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">
                <button class="btn btn-primary input-sm" style="float: left" ng-click="getCustomerLedgerReportFunction()"><i class="fa fa-search" style=""></i> Search</button>
                <!--<a nh-show="false" ng-disabled="false" style="margin-left: 5px" class="btn btn-primary btn-sm" href="<?php /*echo Yii::app()->createUrl('client/customerLedgerExport')*/?>?clientID={{clientID}}&startDate={{startDate}}&endDate={{endDate}}"><i class="fa fa-share " style="margin: 5px"></i> Export </a>-->
                <button class="btn btn-primary btn-sm " style="margin-left: 5px" onclick="javascript:xport.toCSV('payment_report');"> <i class="fa fa-share" style="margin: 5px"></i> Export</button>
                <img style="margin: 10px" ng-show="reportLoader"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading.gif" alt="" class="loading">
            </div>
        </div>
        <div class="col-lg-12" style="text-align: center">
             <h2> Edit or Delete History Report</h2>
        </div>

        <div class="col-lg-12" ng-show="address" style="background-color: #FFF8DC ;">
            <div style="float: left">
                <span style="font-weight: bold;">Address :  </span> {{address}}
            </div>

            <div style="float: left ; margin-left: 20px">
                <span style="font-weight: bold;">Contact Number :  </span> {{cell_no_1}}
            </div>
            <div style="float: left ; margin-left: 20px">
                <span style="font-weight: bold;">Zone :  </span> {{zone_name}}
            </div>
        </div>
        <style>
            #payment_report {
                border-collapse: collapse;
                width: 100%;
            }
            #payment_report td, #payment_report th {
                border: 1px solid #ddd;
                padding: 8px;
                color: black;
            }
            #payment_report tr:nth-child(even){background-color: #F8F8FF;}
            #payment_report tr:hover {background-color: #FAFAD2;}
            #payment_report th {
                padding-top: 12px;
                padding-bottom: 12px;
                text-align: left;
                color: white;
            }
        </style>

        <div class="col-md-12">



            <table  id="payment_report" style="" >
                <thead>
                <tr style="background-color: #F0F8FF">
                    <th rowspan="2" style="text-align: center ;"><a style=" color: black" href="#" >SR #</a></th>
                    <th rowspan="2" style="text-align: center ;"><a style=" color: black" href="#" >ID</a></th>
                    <th rowspan="2" style="text-align: center ;"><a style=" color: black" href="#" >Customer</a></th>
                    <th rowspan="2" style="text-align: center ;"><a style=" color: black" href="#" >Value Type</a></th>
                    <!--<th rowspan="2" width="130px" style="width: 130px ;text-align: center ;"><a style=" color: black" href="#" >Change Date</a></th>
                    <th rowspan="2" style="text-align: center ;"><a style=" color: black" href="#" >Change Time</a></th>-->
                    <th colspan="3" style="text-align: center"> <a style=" color: black" href="#">Original Value Detail</a></th>
                    <th colspan="3" style="text-align: center"><a style=" color: black" href="#">Change Value Detail</a></th>
                    <th rowspan="2" style="text-align: center"><a style=" color: black" href="#">Comments/Reson</a></th>

                </tr>
                <tr style="background-color: #F0F8FF">

                    <th width="150px"><a style=" color: black" href="#" ro>Date Time</a></th>
                    <th><a style=" color: black" href="#" ro>Original Value</a></th>
                    <th><a style=" color: black" href="#" ro>User</a></th>




                    <th width="150px"><a style=" color: black" href="#" ro>Date Time</a></th>
                    <th><a style=" color: black" href="#" ro>Change Value</a></th>
                    <th><a style=" color: black" href="#" ro>User</a></th>


                </tr>
                </thead>
                <tbody>

                <tr ng-repeat="list in responce  track by $index ">
                    <td>{{$index+1}}</td>
                    <td>{{list.client_id}}</td>

                    <td>{{list.cleint_name}}({{list.address}})</td>
                    <td>{{list.action_name}}</td>

                    <!--<td><div style="width: 110px">{{list.action_date}}</div></td>
                    <td>{{list.action_time}}</td>-->

                    <td >{{list.befour_date}}</td>
                    <td style="text-align: right">{{list.orginal_value}}</td>
                    <td>{{list.befour_user_name}}</td>

                    <td >{{list.action_date}} {{list.action_time}}</td>
                    <td style="text-align: right">{{list.new_value}}</td>

                    <td >{{list.user_name}}</td>


                    <td >{{list.rearks}}</td>






                </tr>


                </tbody>
            </table>
        </div>



        <style>
            .dropdown.dropdown-scroll .dropdown-menu {
                max-height: 200px;
                width: 60px;
                overflow: auto;
            }

        </style>

    </div>


</div>
</div>

