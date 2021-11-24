<style>
    .angularjs-datetime-picker{
        z-index: 99999 !important;
    }
</style>



<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/monthlyReport/jquery.js"></script>

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/monthlyReport/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/FileSaver/FileSaver.js"></script>

<script type="text/javascript">
    $(function() {
        $('.date-picker').datepicker( {
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'MM yy',
            onClose: function(dateText, inst) {
                var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                $(this).datepicker('setDate', new Date(year, month, 1));
            },
            beforeShow : function(input, inst) {
                var datestr;
                if ((datestr = $(this).val()).length > 0) {
                    year = datestr.substring(datestr.length-4, datestr.length);
                    month = jQuery.inArray(datestr.substring(0, datestr.length-5), $(this).datepicker('option', 'monthNamesShort'));
                    $(this).datepicker('option', 'defaultDate', new Date(year, month, 1));
                    $(this).datepicker('setDate', new Date(year, month, 1));
                }
            }
        });
    });
</script>
<style>
    .ui-datepicker-calendar {
        display: none;
    }
</style>

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/monthlyReport/monthlyReport-grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>

<div class="row" id="testContainer" style="display: none ; background-color: white ;width: 3300px" ng-app="clintManagemaent" >
    <div ng-controller="clintManagemaent" ng-init='init(<?php  echo $todayMonth  ?> ,<?php  echo $year  ?> , <?php echo $reportData  ?> , <?php echo $riderList ?> , "<?php echo Yii::app()->createAbsoluteUrl('zone/getOneMonthlyReport'); ?>")'>
        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a  href="#tab_1" data-toggle="tab" aria-expanded="false">
                    Monthly Report
                </a>
            </li>

            <li>
                <a  href="#tab_1" data-toggle="tab" aria-expanded="false">
                   Total Record <span> {{totalRecord}}</span>
                </a>
            </li>
        </ul>
       <br>


        <div style="margin:10px;"  class="col-lg-12">

                <!--<select ng-model="clientID" class="form-control" style="width: 30%; float: left" >
                    <option value="">Select Customer </option>
                  <option ng-repeat="list in clientList" value="{{list.client_id}}">{{list.fullname}}</option>
                </select>-->

                <div style="float: left;">
                    <select class="form-control" ng-model="client_type">
                        <option value="0">All </option>
                        <option value="1">Regular</option>
                        <option value="2">Sample</option>

                    </select>
                </div>
                <div style="float: left;">
                  <select class="form-control" ng-model="riderId">
                      <option value="0">All</option>
                      <option ng-repeat="list in riderList" value="{{list.rider_id}}">{{list.fullname}}</option>
                  </select>
                </div>

                <!--<input style="float: left ; width: 150px ;" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">
                <button class="btn btn-info btn-sm" style="float: left"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
                <input style="width: 150px; float: left"  class="form-control input-sm "    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">-->

                <div style="float: left;">
                    <select class="form-control" ng-model = "selectMonth">
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
                </div>

                <div style="float: left;">
                    <select class="form-control" ng-model="selectYear">
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
                </div>
                <button class="btn btn-primary" style="float: left" ng-click="getCustomerFunctionONClick(1)"> <i class="fa fa-search"></i> Search</button>


                <button class="btn btn-info" style="float: left; margin-left: 5px" onclick="javascript:xport.toCSV('monthly_report');"> <i class="fa fa-share"></i> Export</button>



        </div>

        <div class="col-lg-12" style="width: 800px ; margin-left: 10px" >
            <div class="progress  ">
                <div class="progress-bar progress-bar-success" role="progressbar"  aria-valuemin="0"  style="width:{{totalPercentage}}%">
                    {{totalPercentage | number:0}}% Complete (Load Data)
                </div>
            </div>
        </div>

        <div style="margin:10px;">

            <style>

                #monthly_report {

                    border-collapse: collapse;
                    width: 100%;
                }

                #monthly_report td, #monthly_report th {
                    border: 1px solid #ddd;
                    padding: 5px;
                    color: black;
                }

                #monthly_report tr:nth-child(even){background-color: #F8F8FF;}

                #monthly_report tr:hover {background-color: #FAFAD2;}

                #monthly_report th {
                    padding-top: 6px;
                    padding-bottom: 6px;
                    text-align: left;

                    color: white;
                }
            </style>

            <table id="monthly_report">
                <thead>
                <tr ng-show="false">
                    <td> </td>
                    <td>Monthly Report</td>
                    <td>2017 </td>
                </tr>
                <tr style="background-color: #F0F8FF">
                    <th width="210px"><a href="#">#</a></th>
                    <th width="210px"><a href="#"> ID</a></th>
                    <th width="210px"><a href="#"> Name</a></th>
                    <th width="210px"><a href="#"> Address</a></th>
                    <th width="210px"><a href="#"> Phone No.</a></th>
                    <th width="210px"><a href="#"> Product</a></th>
                    <th width="100px"><a href="#">Zone</a> </th>
                    <th   ng-repeat="list in lableList"><a href="#"><span style="margin-right:60px">{{list.day_name}}<span><br>{{list.day_Date}}</a></th>
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="list in data track by $index">

                    <td >{{$index+1}}</td>
                    <td >{{list.client_id}}</td>
                    <td ><a  target="_blank" href="<?php echo Yii::app()->createUrl('client/manageClient')?>?client_id={{list.client_id}}&edit=1">{{list.fullname}} </a> </td>
                    <td >{{list.address}}</td>
                    <td >{{list.cell_no_1}}</td>
                    <td >{{list.product_name}}</td>
                    <td>{{list.zone_name}}</td>
                    <td style="text-align: right ;color: {{deliver.color}} " ng-repeat="deliver in list.row_data track by $index">{{deliver.delivery}}</td>
                </tr>
                <tr style="background-color: #DEB887">
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="text-align: right">Total</th>
                    <th style="text-align: right"  ng-repeat="list in totalCountObject">{{list.total | number}}</th>
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

