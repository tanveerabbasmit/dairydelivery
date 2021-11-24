

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/dashboard/dashboard-grid.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/chart_api/loader.js"></script>
<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>


<div id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">

    <?php  $data ?>

    <body onload='codeAddress(<?php echo json_encode($data) ?>)'>

    </body>



    <div ng-controller="manageZone" ng-init='init("<?php echo Yii::app()->createAbsoluteUrl(''); ?>","<?php echo Yii::app()->createAbsoluteUrl('zone/editZone'); ?>","<?php echo Yii::app()->createAbsoluteUrl('zone/delete'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/saveRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/deleteRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/search'); ?>")'>
        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                  dashboard
                </a>
            </li>
        </ul>
        <div>


            <div class="col-lg-4" >
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table" >
                            <thead>
                            <tr>
                                <th colspan="4">New and Open Complaints</th>
                            </tr>
                            </thead>
                            <tbod">
                            <tr>
                                <td colspan="4">
                                    <div id="donutchart1"></div>
                                </td>
                            </tr>


                            </tbody>
                        </table>
                    </div>

                </div>

            </div>

            <div class="col-lg-4" >
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table" >
                            <thead>
                            <tr>
                                <th colspan="4">Total Customer : <?php echo ($data['totalCustomer']) ; ?></th>
                            </tr>
                            </thead>
                            <tbod">
                            <tr>
                                <td colspan="4">
                                    <div id="donutchart2" ></div>
                                </td>
                            </tr>


                            </tbody>
                        </table>
                    </div>

                </div>

            </div>

            <div class="col-lg-4" >
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table" >
                            <thead>
                            <tr>
                                <th colspan="4">Total Customer : <?php echo ($data['totalCustomer']) ; ?></th>
                            </tr>
                            </thead>
                            <tbod">
                            <tr>
                                <td colspan="4">
                                    <div id="donutchart3" ></div>
                                </td>
                            </tr>


                            </tbody>
                        </table>
                    </div>

                </div>

            </div>


            <div class="col-sm-6">
               
                <div class="col-sm-6">

                    <script type="text/javascript">
                        function codeAddress(data) {
                           var unResolved = data['unResolved'];
                           var total = data['total'];
                           var totalCustomer = data['totalCustomer'];
                           var totalActiveCustomer = data['totalActiveCustomer'];
                           var totalOnineCustomer = data['totalOnineCustomer'];
                              var resolved = Number(total) - Number(unResolved);
                            google.charts.load("current", {packages:["corechart"]});
                            google.charts.setOnLoadCallback(drawChart);
                            function drawChart(){
                                var data1 = google.visualization.arrayToDataTable([
                                    ['Task', 'Hours per Day'],
                                    ['Resoved',     Number(resolved)],
                                    ['Unresolved',   Number(unResolved)]
                                ]);
                                var options1 = {
                                    title: "Total:"+total+", Unresolved:"+unResolved ,
                                    pieHole: 0.7,
                                };
                                var chart = new google.visualization.PieChart(document.getElementById('donutchart1'));
                                chart.draw(data1, options1);
                            }
                            chart2(totalCustomer, totalActiveCustomer , totalOnineCustomer);
                       }
                    </script>
                </div>
            </div>
            <div class="col-sm-6">
              <!--  <h4 style="text-align: center">Total Customer : <?php /*echo ($data['totalCustomer']) ; */?></h4>-->
                <div class="col-sm-6">

                    <script type="text/javascript">
                         function chart2(totalCustomer , totalActiveCustomer , totalOnineCustomer) {
                              var left_active_customer = Number(totalCustomer) - Number(totalActiveCustomer) ;
                             google.charts.load("current", {packages: ["corechart"]});
                             google.charts.setOnLoadCallback(drawChart);
                             function drawChart() {
                                 var data2 = google.visualization.arrayToDataTable([
                                     ['Task', 'Hours per Day'],
                                     ['Inactive', Number(left_active_customer)],
                                     ['Active', Number(totalActiveCustomer)],
                                 ]);
                                 var options2 = {
                                     title: "Active Customers : "+totalActiveCustomer,
                                     pieHole: 0.7,
                                 };
                                 var chart = new google.visualization.PieChart(document.getElementById('donutchart2'));
                                 chart.draw(data2, options2);
                             }
                             chart3(totalCustomer , totalOnineCustomer)
                         }
                    </script>
                </div>

                <div class="col-sm-6">
                    <div id="donutchart3" ></div>

                    <script type="text/javascript">
                       function chart3(totalCustomer , totalOnineCustomer){
                             var offLine = Number(totalCustomer) - Number(totalOnineCustomer)
                           google.charts.load("current", {packages:["corechart"]});
                           google.charts.setOnLoadCallback(drawChart);
                           function drawChart() {
                               var data3 = google.visualization.arrayToDataTable([
                                   ['Task', 'Hours per Day'],
                                   ['Offline', Number(offLine)],
                                   ['Online',    Number(totalOnineCustomer)]
                               ]);
                               var options3 = {
                                   title: 'Online Customers : '+totalOnineCustomer,
                                   pieHole: 0.7,
                               };
                               var chart = new google.visualization.PieChart(document.getElementById('donutchart3'));
                               chart.draw(data3, options3);
                           }

                           document.getElementById("testContainer").style.display = "block";
                           document.getElementById("loaderImage").style.display = "none";
                       }

                    </script>
                </div>
            </div>
        </div>

        <style>
            button.accordion {

                background-color: #eee;
                color: #444;
                cursor: pointer;
                padding: 18px;
                width: 100%;
                border: none;
                text-align: left;
                outline: none;
                font-size: 15px;
                transition: 0.4s;
            }
            button.accordion.active, button.accordion:hover {
                background-color: #ddd;
            }
            div.panel {
                padding: 0 18px;
                display: none;
                background-color: white;
            }
        </style>
        <div class="col-sm-12 row">
            <div class="col-sm-6">
                <div class="col-sm-12 " >
                    <button ng-click="getNewCustomer()" class="accordion well well-sm dropdown-toggle"><strong><?php echo $data['result_count_days_ago']; ?> New Customers</strong> (Last 10 days)
                        <i style="margin-left: 5px" class="glyphicon glyphicon-chevron-down"></i>   <img ng-show="getNewCustomerLoader" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading"></button>
                    <div class="panel row">
                       <table width="100%">
                          <tr ng-repeat="list in newCustomer">
                              <td>{{list.date}}</td>
                              <th>{{list.fullname}}</th>
                              <td>{{list.name}}</td>
                              <td>{{list.cell_no_1}}</td>
                          </tr>
                       </table>
                    </div>
                </div>

                <div class="col-sm-12">
                    <button  ng-click="()" class="accordion well well-sm dropdown-toggle"><strong>Month wise new Customers</strong>
                        <i style="margin-left: 5px" class="glyphicon glyphicon-chevron-down"></i>   <img ng-show="monthWiseNewCustomerLoader" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading"></button>
                    <div class="panel row">



                    </div>
                    <script type="text/javascript">

                    </script>
               </div>
                <div class="col-sm-12">
                    <button ng-click="paymentReciveToday()" class="accordion well well-sm dropdown-toggle"><strong>Payment Received Today</strong>
                        <i style="margin-left: 5px" class="glyphicon glyphicon-chevron-down"></i>   <img ng-show="paymentReciveTodayLoader" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading"></button>
                    <div class="panel">
                        <span>Cash/Cheque</span> <span style=" font-weight: bold; font-family: Times New Roman, Times, serif;font-size: 250%;color: #7FFFD4 "> {{total_cshPayment}}</span>
                        <span>online Rs.</span> <span style=" font-weight: bold; font-family: Times New Roman, Times, serif; font-size: 250%; color: #1a70be"> {{total_OnlinePayment}}</span>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="col-sm-12">
                    <button ng-click="newScheduleFunction()" class="accordion well well-sm"><strong>Customers with new Schedule</strong>
                        <i style="margin-left: 5px" class="glyphicon glyphicon-chevron-down"></i>   <img ng-show="newScheduleLoader" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading"></button>
                    <div class="panel">
                        <table width="100%">
                            <tr ng-repeat="list in newSchedule">
                                <td>{{list.date}}</td>
                                <th>{{list.fullname}}</th>
                                <td>{{list.name}}</td>
                                <td>{{list.cell_no_1}}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="col-sm-12">
                    <button ng-click="deliveryStatus()" class="accordion well well-sm dropdown-toggle"><strong>Delivery Status</strong>
                        <i style="margin-left: 5px" class="glyphicon glyphicon-chevron-down"></i>   <img ng-show="deliveryStatusLoader" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading"></button>
                    <div class="panel">
                        <table width="100%">
                             <tr>
                                 <th>Rider</th>
                                 <th>Product</th>
                                 <th style="text-align: right">Customers</th>
                                 <th style="text-align: right">Today</th>
                                 <th style="text-align: right">Current Month</th>
                             </tr>
                            <tr ng-repeat="list in DeliveryStatus">
                                <td>{{list.rider_name}}</td>
                                <td>{{list.product_name}}</td>
                                <td style="text-align: right">{{list.totalClient}}</td>
                                <td style="text-align: right">{{list.totalQuantity}}</td>
                                <td style="text-align: right">{{list.dileryMonthResult}}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="col-sm-12">
                    <button ng-click="outStandingBalnce()" class="accordion well well-sm dropdown-toggle"><strong>Customers with Outstanding Balance</strong>
                        <i style="margin-left: 5px" class="glyphicon glyphicon-chevron-down"></i>   <img ng-show="outStandingBalnceLoader" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading"></button>
                    <div class="panel">
                        <h3 style="color: red; line-height: 30%;">Rs. {{outstanding_balance | number}}</h3>
                        <table width="100%">
                            <tr ng-repeat="list in result_balnce_clientLIst">
                                <td>{{list.fullname}}</td>
                                <td>{{list.balanace}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

        </div>



        <div class="col-lg-6" >
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table" >
                            <thead>
                            <tr>
                                <th colspan="4">5 New Customers </th>
                            </tr>

                            </thead>
                            <tbody ng-repeat="list in newCustomer">

                            <tr ng-show="$index == 0" class="">
                                <td>{{list.date}}</td>
                                <th>{{list.fullname}}</th>
                                <td>{{list.name}}</td>
                                <td>{{list.cell_no_1}}</td>
                            </tr>

                            <tr ng-show="$index == '1'" class="danger">
                                <td>{{list.date}}</td>
                                <th>{{list.fullname}}</th>
                                <td>{{list.name}}</td>
                                <td>{{list.cell_no_1}}</td>
                            </tr>

                            <tr ng-show="$index == '2'" class="info">
                                <td>{{list.date}}</td>
                                <th>{{list.fullname}}</th>
                                <td>{{list.name}}</td>
                                <td>{{list.cell_no_1}}</td>
                            </tr>

                            <tr ng-show="$index == '3'" class="warning">
                                <td>{{list.date}}</td>
                                <th>{{list.fullname}}</th>
                                <td>{{list.name}}</td>
                                <td>{{list.cell_no_1}}</td>
                            </tr>
                            <tr ng-show="$index == '4'" class="success">
                                <td>{{list.date}}</td>
                                <th>{{list.fullname}}</th>
                                <td>{{list.name}}</td>
                                <td>{{list.cell_no_1}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

        </div>


        <div class="col-lg-6" >
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table" >
                        <thead>
                        <tr>
                            <th colspan="4">5 Customers with new Schedule </th>

                        </tr>
                        </thead>
                        <tbody ng-repeat="list in newSchedule">

                        <tr ng-show="$index == 0" class="">


                            <td>{{list.date}}</td>
                            <th>{{list.fullname}}</th>
                            <td>{{list.name}}</td>
                            <td>{{list.cell_no_1}}</td>
                        </tr>

                        <tr ng-show="$index == '1'" class="danger">


                            <td>{{list.date}}</td>
                            <th>{{list.fullname}}</th>
                            <td>{{list.name}}</td>
                            <td>{{list.cell_no_1}}</td>
                        </tr>

                        <tr ng-show="$index == '2'" class="info">


                            <td>{{list.date}}</td>
                            <th>{{list.fullname}}</th>
                            <td>{{list.name}}</td>
                            <td>{{list.cell_no_1}}</td>
                        </tr>

                        <tr ng-show="$index == '3'" class="warning">


                            <td>{{list.date}}</td>
                            <th>{{list.fullname}}</th>
                            <td>{{list.name}}</td>
                            <td>{{list.cell_no_1}}</td>
                        </tr>

                        <tr ng-show="$index == '4'" class="success">

                            <td>{{list.date}}</td>
                            <th>{{list.fullname}}</th>
                            <td>{{list.name}}</td>
                            <td>{{list.cell_no_1}}</td>
                        </tr>


                        </tbody>
                    </table>
                </div>

            </div>

        </div>

        <div class="col-lg-12" ></div>

        <div class="col-lg-6" >
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table" >
                        <thead>
                        <tr>
                            <th colspan="5">Delivery Status </th>

                        </tr>

                        <tr>
                            <td>Rider</td>
                            <td>Product</td>
                            <td>Customer</td>
                            <td>Today</td>
                            <td>Month</td>
                        </tr>

                        </thead>
                        <tbody ng-repeat="list in DeliveryStatus">

                        <tr ng-show="$index == 0" class="success">


                            <td>{{list.rider_name}}</td>
                            <td>{{list.product_name}}</td>
                            <td>{{list.totalClient}}</td>
                            <td>{{list.totalQuantity}}</td>
                            <td>{{list.dileryMonthResult}}</td>
                        </tr>

                        <tr ng-show="$index == '1'" class="danger">


                            <td>{{list.rider_name}}</td>
                            <td>{{list.product_name}}</td>
                            <td>{{list.totalClient}}</td>
                            <td>{{list.totalQuantity}}</td>
                            <td>{{list.dileryMonthResult}}</td>
                        </tr>

                        <tr ng-show="$index == '2'" class="info">


                            <td>{{list.rider_name}}</td>
                            <td>{{list.product_name}}</td>
                            <td>{{list.totalClient}}</td>
                            <td>{{list.totalQuantity}}</td>
                            <td>{{list.dileryMonthResult}}</td>
                        </tr>

                        <tr ng-show="$index == '3'" class="warning">


                            <td>{{list.rider_name}}</td>
                            <td>{{list.product_name}}</td>
                            <td>{{list.totalClient}}</td>
                            <td>{{list.totalQuantity}}</td>
                            <td>{{list.dileryMonthResult}}</td>
                        </tr>

                        <tr ng-show="$index == '4'" class="">

                            <td>{{list.rider_name}}</td>
                            <td>{{list.product_name}}</td>
                            <td>{{list.totalClient}}</td>
                            <td>{{list.totalQuantity}}</td>
                            <td>{{list.dileryMonthResult}}</td>
                        </tr>


                        </tbody>
                    </table>
                </div>

            </div>

        </div>

        <div class="col-lg-6" >
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table" >
                        <thead>
                        <tr>
                            <th colspan="4">Customer With Outstanding Balance   <span style=" font-weight: bold; color: red; line-height: 30%;">Rs. {{outstanding_balance | number}}</span></th>
                        </tr>
                        </thead>
                        <tbody ng-repeat="list in result_balnce_clientLIst">

                        <tr ng-show="$index == 0" class="">

                            <td>{{list.fullname}}</td>
                            <td>{{list.balanace | number}}</td>

                        </tr>

                        <tr ng-show="$index == '1'" class="danger">

                            <td>{{list.fullname}}</td>
                            <td>{{list.balanace | number }}</td>

                        </tr>

                        <tr ng-show="$index == '2'" class="info">

                            <td>{{list.fullname}}</td>
                            <td>{{list.balanace | number }}</td>

                        </tr>

                        <tr ng-show="$index == '3'" class="warning">

                            <td>{{list.fullname}}</td>
                            <td>{{list.balanace | number }}</td>

                        </tr>
                        <tr ng-show="$index == '4'" class="success">

                            <td>{{list.fullname}}</td>
                            <td>{{list.balanace | number }}</td>

                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <div class="col-lg-12" ></div>
        <div class="col-lg-6" >
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table" >
                        <thead>
                        <tr>
                            <th colspan="4">Month wise new Customer </th>
                        </tr>
                        </thead>
                        <tbod">
                        <tr>
                            <td colspan="4">
                                <div id="curve_chart" style=""></div>
                            </td>
                       </tr>


                        </tbody>
                    </table>
                </div>

            </div>

        </div>


        <div class="col-lg-6" >
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table" >
                        <thead>
                        <tr>
                            <th colspan="4">Payment Recived Today </th>
                        </tr>
                        </thead>
                        <tbod">
                        <tr class="success">
                            <td>
                               Cash/Check
                            </td>
                            <th>
                                  <span style=" font-weight: bold; font-family: Times New Roman, Times, serif;color: black "> {{total_cshPayment | number}}</span>
                            </th>
                       </tr>
                        <tr class="info">
                            <td>
                               Online
                            </td>
                            <th>
                                <span style=" font-weight: bold; font-family: Times New Roman, Times, serif;  color: black"> {{total_OnlinePayment | number}}</span>
                            </th>
                       </tr>
                        </tbody>
                    </table>
                </div>

            </div>

        </div>



        <script>
            var acc = document.getElementsByClassName("accordion");
            var i;

            for (i = 0; i < acc.length; i++) {
                acc[i].onclick = function(){
                    this.classList.toggle("active");
                    var panel = this.nextElementSibling;
                    if (panel.style.display === "block") {
                        panel.style.display = "none";
                    } else {
                        panel.style.display = "block";
                    }
                }
            }
        </script>

    </div>

