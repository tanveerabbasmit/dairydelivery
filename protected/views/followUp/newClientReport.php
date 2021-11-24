<style>
    .angularjs-datetime-picker{
        z-index: 99999 !important;
    }
</style>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/newclientreport/newclientreport-grid.js"></script>
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
    <div ng-controller="clintManagemaent" ng-init='init(<?php echo   $getCategoryList;  ?>, <?php echo $clientList ?> , "<?php echo Yii::app()->createAbsoluteUrl('followUp/getNewCustomerList'); ?>")'>

        <div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <strong>message! </strong>
            {{taskMessage}}
        </div>
        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                    New Customers Report
                </a>
            </li>
        </ul>


        <div class="panel-body row">
            <div class="col-lg-12 row">
                <!--<select ng-model="clientID" class="form-control" style="width: 30%; float: left" >
                    <option value="">Select Customer </option>
                  <option ng-repeat="list in clientList" value="{{list.client_id}}">{{list.fullname}}</option>
                </select>-->

                <input style="float: left ; width: 12% ; margin-left: 0%" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">
                <button class="btn btn-info btn-sm" style="float: left"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
                <input style="width: 12% ; float: left"  class="form-control input-sm "    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">

                <select ng-model="customer_category_id" class="form-control input-sm" style="margin-right: 5px; width: 12% ; float: left" >
                    <option value="0">All</option>
                    <option ng-repeat="list in getCategoryLis" value="{{list.customer_category_id}}">{{list.category_name}}</option>
                </select>

                <button class="btn btn-primary input-sm" style="float: left" ng-click="getCustomerLedgerReportFunction()"><i class="fa fa-search" style=""></i> Search</button>
                <button class="btn btn-info input-sm" style="float: left; margin-left: 5px;" onclick="javascript:xport.toCSV('customers');"> <i class="fa fa-share"></i> Export</button>
                <img style="margin: 10px" ng-show="reportLoader"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading.gif" alt="" class="loading">
            </div>
        </div>
        <div class="col-lg-12" ng-show="address" style="background-color: #FFF8DC">
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
        <table  id="customers" style="margin-top: 6px" >
            <thead>
            <tr style="background-color: #F0F8FF">
                <th colspan="12" style="text-align: center;background-color: #96cb7f;">
                    <a href="#" style="color: black"> New Customers Report </a>
                </th>
            </tr>
            <tr style="background-color: #F0F8FF">
                <th> <a href="#">#</a></th>
                <th><a href="#">Customer Name</a> </th>
                <th><a href="#">Address </a> </th>
                <th><a href="#">Contact No.</a> </th>
                <th style="width: 150px"><a href="#">Zone</a> </th>
                <th><a href="#">Category</a> </th>
                <th style="width: 150px"><a href="#" >First Delivered on</a> </th>
                <th style="width: 150px"><a href="#">Last Delivered on</a> </th>
                <th><a href="#">Total Quantity</a> </th>
                <th><a href="#">Avg</a> </th>
                <th  ><a href="#">Price</a> </th>
                <th  ><a href="#">Creted By</a> </th>
                <!--<th><a href="#">Converted</a> </th>
                <th><a href="#">Drop Reason</a> </th>-->
            </tr>
            </thead>
            <tbody>
            <tr ng-repeat="list in new_Customer  |  orderBy : '+created_date'">
                <td ng-show="list.fullname !=''">{{$index + 1}}</td>
                <td ng-show="list.fullname==''"></td>
                <td >
                    <a target="_blank" href="<?php echo Yii::app()->createUrl('client/manageClient')?>?client_id={{list.client_id}}&edit=1">{{list.fullname}}</a>
                </td>
                <td>{{list.address}}</td>
                <td> {{list.cell_no_1}}</td>
                <td>{{list.zone_name}}</td>
                <td>{{list.category_name}}</td>
                <td><span ng-show="list.show_data"> {{list.created_date}}</span></td>
                <td>{{list.last_delivery}}</td>
                <td style="text-align: right">{{list.total_quantity | number :2 }}</td>
                <td style="text-align: right">{{list.quantity | number :2 }}</td>
                <td  style="text-align: right">{{list.deliveryQuantity_unit_price | number :2}}</td>
                <td>{{list.creted_by}}</td>
            </tr>
            </tbody>

            <tr ng-show="load_data_show" style="background-color: #F0F8FF ;border: 2px solid #5F9EA0;border-bottom:0pt solid black">
                <th colspan="7">
                    <a href="#" style="color: {{text_box_color_name}}">Total customers =</a>
                    <a href="#" style="margin-left: 30px;color: {{text_box_color_name}}">{{new_customer_box_data.count_of_client | number :2}}</a>
                </th>
                <!--<th colspan="3" style="text-align: right">
                    <a href="#" style="color: {{text_box_color_name}}" >{{new_customer_box_data.count_of_client | number :2}}</a>
                </th>-->
                <th colspan="7">
                    <a href="#" style="color: {{text_box_color_name}}">Total Sale = </a>
                    <a href="#" style="margin-left: 30px; color: {{text_box_color_name}}">{{new_customer_box_data.end_total_delivery_sum | number :2}}</a>
                </th>
                <!--<th colspan="4" style="text-align: right">
                    <a href="#" style="color: {{text_box_color_name}}">{{new_customer_box_data.end_total_delivery_sum | number :2}}</a>
                </th>-->
            </tr>

            <tr ng-show="load_data_show" style="background-color: #F0F8FF ;border: 2px solid #5F9EA0;border-top:0pt solid black">
                <th colspan="7">
                    <a href="#" style="color: {{text_box_color_name}}">Avg. sales per customer =</a>
                    <a href="#" style="margin-left: 30px;color: {{text_box_color_name}}">{{new_customer_box_data.avg_sale_per_customer | number :2}} </a>
                </th>
               <!-- <th colspan="3" style="text-align: right">
                    <a href="#" style="color: {{text_box_color_name}}" >{{new_customer_box_data.avg_sale_per_customer | number :2}}</a>
                </th>-->
                <th colspan="7">
                    <a href="#" style="color: {{text_box_color_name}}">Avg. sale per day = </a>
                    <a href="#" style="margin-left: 30px;color: {{text_box_color_name}}">{{new_customer_box_data.avg_sale_per_day | number :2}}</a>
                </th>
                <!--<th colspan="4" style="text-align: right">
                    <a href="#" style="color: {{text_box_color_name}}">{{new_customer_box_data.avg_sale_per_day | number :2}}</a>
                </th>-->
            </tr>

        </table>
        <table  id="customers" style="margin-top: 6px" >

            <tr ng-show="load_data_show" style="background-color: #F0F8FF">
                <th colspan="14" style="text-align: center;background-color: #96cb7f;">
                    <a href="#" style="color: black"> Dropped-off Customers Report</a>
                </th>
            </tr>
            <tr ng-show="load_data_show" style="background-color: #F0F8FF">
                <th> <a href="#">#</a></th>
                <th ><a style="margin-right: 80px" href="#">Customer Name</a> </th>
                <th><a href="#" style="margin-right: 80px">Address</a> </th>
                <th><a href="#">Contact No.</a> </th>
                <th><a href="#">Zone</a> </th>
                <th><a href="#">Category</a> </th>
                <th style="width: 150px"><a href="#">First Delivered on</a> </th>
                <th style="width: 150px"><a href="#">Last Delivered on</a> </th>
                <th><a href="#">Total Quantity</a> </th>
                <th><a href="#">Avg</a> </th>
                <th><a href="#">Price</a> </th>
               <!-- <th><a href="#"></a> </th>-->
                <th><a href="#">Drop Reason</a> </th>
                <th  ><a href="#">Creted By</a> </th>
            </tr>

            <tr ng-repeat="list in drop_Customer_list |  orderBy : '+created_date'" ">
                <td ng-show="list.fullname !=''">{{$index + 1}}</td>
                <td ng-show="list.fullname==''"></td>
                <td >
                    <a target="_blank" href="<?php echo Yii::app()->createUrl('client/manageClient')?>?client_id={{list.client_id}}&edit=1">{{list.fullname}}</a>
                </td>
                <td>{{list.address}}</td>
                <td> {{list.cell_no_1}}</td>
                <td>{{list.zone_name}}</td>
                <td>{{list.category_name}}</td>
                <td><span ng-show="list.show_data"> {{list.created_date}}</span></td>
                <td>{{list.last_delivery}}</td>
                <td style="text-align: right">{{list.total_quantity | number :2 }}</td>
                <td style="text-align: right">{{list.quantity | number :2}}</td>
                <td style="text-align: right">{{list.deliveryQuantity_unit_price | number :2}}</td>
                <!--<td>{{list.convert}}</td>-->
                <td >{{list.drop_reason}}</td>
                <td>{{list.creted_by}}</td>
            </tr>

             <!--BOX DATA Start-->
            <tr ng-show="load_data_show" style="background-color: #F0F8FF ;border: 2px solid #5F9EA0;border-bottom:0pt solid black">
                <th colspan="7">
                    <a href="#" style="color: {{text_box_color_name}}">Total customers =</a>
                    <a href="#" style="margin-left: 30px;color: {{text_box_color_name}}">{{drop_Customer_list_box_data.count_of_client | number :2}}</a>
                </th>
                <!--<th colspan="3" style="text-align: right">
                    <a href="#" style="color: {{text_box_color_name}}" >{{drop_Customer_list_box_data.count_of_client | number :2}}</a>
                </th>-->
                <th colspan="7">
                    <a href="#" style="color: {{text_box_color_name}}">Total Sale = </a>
                    <a href="#" style="margin-left: 30px;color: {{text_box_color_name}}">{{drop_Customer_list_box_data.end_total_delivery_sum | number :2}}</a>
                </th>
                <!--<th colspan="4" style="text-align: right">
                    <a href="#" style="color: {{text_box_color_name}}">{{drop_Customer_list_box_data.end_total_delivery_sum | number :2}}</a>
                </th>-->
            </tr>

            <tr ng-show="load_data_show" style="background-color: #F0F8FF ;border: 2px solid #5F9EA0;border-top:0pt solid black">
                <th colspan="7">
                    <a href="#" style="color: {{text_box_color_name}}">Avg. sales per customer =</a>
                    <a href="#" style="margin-left: 30px;color: {{text_box_color_name}}">{{drop_Customer_list_box_data.avg_sale_per_customer | number :2}}</a>
                </th>
                <!--<th colspan="3" style="text-align: right">
                    <a href="#" style="color: {{text_box_color_name}}" >{{drop_Customer_list_box_data.avg_sale_per_customer | number :2}}</a>
                </th>-->
                <th colspan="7">
                    <a href="#" style="color: {{text_box_color_name}}">Avg. sale per day =</a>
                    <a href="#" style="margin-left: 30px;color: {{text_box_color_name}}"> {{drop_Customer_list_box_data.avg_sale_per_day | number :2}}</a>
                </th>
            </tr>
        </table>
        <table  id="customers" style="margin-top: 6px" >
            <!--BOX DATA End-->

            <tr ng-show="load_data_show" style="background-color: #F0F8FF">
                <th colspan="14" style="text-align: center;background-color: #96cb7f;">
                    <a href="#" style="color: black">Sample Customers Report</a>
                </th>
            </tr>

            <tr ng-show="load_data_show" style="background-color: #F0F8FF">
                <th> <a href="#">#</a></th>
                <th><a href="#">Customer Name</a> </th>
                <th style="width: 150px"><a href="#">Address</a> </th>
                <th><a href="#">Contact No.</a> </th>
                <th><a href="#">Zone</a> </th>
                <th><a href="#">Category</a> </th>
                <th style="width: 150px"><a href="#">First Delivered on</a> </th>
                <th style="width: 150px"><a href="#">Last Delivered on</a> </th>
                <th><a href="#">Total Quantity</a> </th>
                <th><a href="#">Avg</a> </th>
                <th><a href="#">Price</a> </th>
                <th ><a href="#">Converted</a> </th>
                <th><a href="#">Drop Reason</a> </th>
                <th  ><a href="#">Creted By</a> </th>
            </tr>
            <tr ng-repeat="list in sample_Customer |  orderBy : '+created_date'" ">
                <td ng-show="list.fullname !=''">{{$index + 1}}</td>
                <td ng-show="list.fullname==''"></td>
                <td>
                    <a target="_blank" href="<?php echo Yii::app()->createUrl('client/manageClient')?>?client_id={{list.client_id}}&edit=1">{{list.fullname}}</a>
                </td>
                <td>{{list.address}}</td>
                <td> {{list.cell_no_1}}</td>
                <td>{{list.zone_name}}</td>
                <td>{{list.category_name}}</td>
                <td><span ng-show="list.show_data"> {{list.created_date}}</span></td>
                <td>{{list.last_delivery}}</td>
                <td style="text-align: right">{{list.total_quantity | number :2 }}</td>
                <td style="text-align: right">{{list.deliveryQuantity_sum | number :2}}</td>
                <td style="text-align: right">{{list.deliveryQuantity_unit_price | number :2}}</td>
                <td>{{list.convert}}</td>
                <td>{{list.drop_reason}}</td>
                <td>{{list.creted_by}}</td>
            </tr>

            <!--BOX DATA Start-->
            <tr ng-show="load_data_show" style="background-color: #F0F8FF ;border: 2px solid #5F9EA0;border-bottom:0pt solid black">
                <th colspan="7">
                    <a href="#" style="color: {{text_box_color_name}}">Total customers = </a>
                    <a href="#" style="margin-left: 30px;color: {{text_box_color_name}}">{{sample_Customer_box_data.count_of_client | number :2}}</a>
                </th>
                <!--<th colspan="3" style="text-align: right">
                    <a href="#" style="color: {{text_box_color_name}}" >{{sample_Customer_box_data.count_of_client | number :2}}</a>
                </th>-->
                <th colspan="7">
                    <a href="#" style="color: {{text_box_color_name}}">Total Sale = </a>
                    <a href="#" style="margin-left: 30px;color: {{text_box_color_name}}">{{sample_Customer_box_data.end_total_delivery_sum | number :2}}</a>
                </th>
               <!-- <th colspan="4" style="text-align: right">
                    <a href="#" style="color: {{text_box_color_name}}">{{sample_Customer_box_data.end_total_delivery_sum | number :2}}</a>
                </th>-->
            </tr>
            <tr ng-show="load_data_show" style="background-color: #F0F8FF ;border: 2px solid #5F9EA0;border-top:0pt solid black">
                <th colspan="7">
                    <a href="#" style="color: {{text_box_color_name}}">Avg. sales per customer =1 </a>
                    <a href="#" style="margin-left: 30px;color: {{text_box_color_name}}">{{sample_Customer_box_data.avg_sale_per_customer | number :2}} </a>
                </th>
                <!--<th colspan="3" style="text-align: right">
                    <a href="#" style="color: {{text_box_color_name}}" >{{sample_Customer_box_data.avg_sale_per_customer | number :2}}</a>
                </th>-->
                <th colspan="7">
                    <a href="#" style="color: {{text_box_color_name}}">Avg. sale per day = </a>
                    <a href="#" style="margin-left: 30px;color: {{text_box_color_name}}">{{sample_Customer_box_data.avg_sale_per_day | number :2}}</a>
                </th>
               <!-- <th colspan="4" style="text-align: right">
                    <a href="#" style="color: {{text_box_color_name}}">{{sample_Customer_box_data.avg_sale_per_day | number :2}}</a>
                </th>-->
            </tr>

            <!--BOX DATA End-->

            </tbody>
        </table>
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

