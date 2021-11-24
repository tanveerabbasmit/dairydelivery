

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

<script type = "text/javascript">
    google.charts.load('current', {packages: ['corechart']});
</script>

<div id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">

    <body onload='codeAddress(<?php echo json_encode($data) ?>)'>
    </body>
    <div ng-controller="manageZone" ng-init='init("<?php echo Yii::app()->createAbsoluteUrl(''); ?>","<?php echo Yii::app()->createAbsoluteUrl('zone/editZone'); ?>","<?php echo Yii::app()->createAbsoluteUrl('zone/delete'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/saveRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/deleteRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/search'); ?>")'>
        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                    <?php
                    $id = Yii::app()->user->getState('company_branch_id');
                    $company = Company::model()->findByPk(intval($id));
                    echo $company['company_name']
                    ?>

                </a>
            </li>
            <li>
                <a href="" ng-click="view_manage_wiget_function()" style="background-color: SlateGrey;"> Manage Widget</a>
            </li>
        </ul>
        <?php
        date_default_timezone_set("Asia/Karachi");
        $company_id = Yii::app()->user->getState('company_branch_id');
        $checkCompany = NotificationCompany::model()->findByAttributes([
            'company_id'=>$company_id,
        ]);
        if($checkCompany){

            $data_message_alert =CompanyNotification::model()->findByPk(1);

            if($data_message_alert['end_date'] >=date("Y-m-d")) {

                ?>

                <div style="margin-top: 15px" class="alert alert-<?php echo $data_message_alert['message_type']; ?> alert-dismissible fade in">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong> <?php echo $data_message_alert['heading']; ?>
                        !</strong><?php echo $data_message_alert['message']; ?></div>

                <?php

            }
        }


        ?>



        <?php if(isset($data['allow_widget'][1])){ ?>

        <div class="col-lg-4 " >
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive row">
                    <table class="table" >
                        <thead>
                        <tr>

                            <th colspan="4">New and Open Complaints</th>
                        </tr>
                        </thead>
                        <tbod">
                        <tr>
                            <td colspan="4">
                                <div id="donutchart1" style="width: 97%"></div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php } ?>
        <?php if(isset($data['allow_widget'][2])){ ?>
           <div class="col-lg-4 " >
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table" >
                        <thead>
                        <tr>
                            <th colspan="4">Total Customers : <?php echo ($data['totalCustomer']) ; ?></th>
                        </tr>
                        </thead>
                        <tbod">
                        <tr>
                            <td colspan="4">
                                <div id="donutchart2" style="width: 97%"></div>
                            </td>
                        </tr>


                        </tbody>
                    </table>
                </div>

            </div>

        </div>
        <?php } ?>
        <?php if(isset($data['allow_widget'][3])){ ?>
           <div class="col-lg-4 ">
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table" >
                        <thead>
                        <tr>
                            <th colspan="4">Total Customers : <?php echo ($data['totalCustomer']) ; ?></th>
                        </tr>
                        </thead>
                        <tbod">
                        <tr>
                            <td colspan="4">
                                <div id="donutchart3" style="width: 97%"></div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
        <?php } ?>



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
                        ['Closed',     Number(resolved)],
                        ['Open',   Number(unResolved)]
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



        <?php if(isset($data['allow_widget'][4])){ ?>
         <div class="col-lg-12" >
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table" style="width: 100%">
                        <thead>
                        <tr>
                            <th colspan="4">
                                New Customers<span style="margin-left: 5px ; font-size: 80%;">(latest 5)</span>
                                <img style="width: 10px ;height: 15px"   src="<?php echo Yii::app()->theme->baseUrl; ?>/images/Mobile_Icon.png" alt="" class="loading">
                                <span style="font-size: 80%;color: blue">{{total_mobilesum}}</span>
                                <img style="margin-left: 5px;width: 10px ;height: 10px"   src="<?php echo Yii::app()->theme->baseUrl; ?>/images/Laptop-2-icon.png" alt="" class="loading">
                                <span style="font-size: 80%;color: blue">{{total_laptopsum}}</span>
                            </th>
                            <th  style="text-align: right">
                                <a href="<?php echo Yii::app()->createUrl('client/manageClient') ?>?new_customer=1" class=""> <p class="fa fa-eye"> All</p></a>
                            </th>
                        </tr>
                        </thead>
                        <tbody ng-repeat="list in newCustomer">
                        <tr ng-show="$index == 0" class="">
                            <td>
                                <div style="width: 50px">
                                    <img style="width: 10px;height: 10px" ng-click="viewButton(list)" ng-show="list.view_by_admin == 1"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/redflag.png" alt="" class="loading">
                                    <img style="width: 10px ;height: 10px" ng-show="list.login_form ==1"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/Laptop-2-icon.png" alt="" class="loading">
                                    <img style="width: 10px ;height: 15px" ng-show="list.login_form ==2"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/Mobile_Icon.png" alt="" class="loading">
                                </div>
                            </td>
                            <td>{{list.date}}</td>
                            <th><a href="<?php echo Yii::app()->createUrl('client/manageClient')?>?client_id={{list.client_id}}&edit=1">{{list.fullname}} </a> </th>
                            <td>{{list.name}}</td>
                            <td>{{list.cell_no_1}}</td>
                        </tr>
                        <tr ng-show="$index == '1'" class="danger">
                            <td>
                                <div style="width: 50px">
                                    <img ng-click="viewButton(list)" style="width: 10px;height: 10px" ng-show="list.view_by_admin == 1"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/redflag.png" alt="" class="loading">
                                    <img style="width: 10px ;height: 10px" ng-show="list.login_form ==1"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/Laptop-2-icon.png" alt="" class="loading">
                                    <img style="width: 10px ;height: 15px" ng-show="list.login_form ==2"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/Mobile_Icon.png" alt="" class="loading">
                                </div>
                            </td>

                            <td>{{list.date}}</td>

                            <th><a href="<?php echo Yii::app()->createUrl('client/manageClient')?>?client_id={{list.client_id}}&edit=1">{{list.fullname}} </a> </th>
                            <td>{{list.name}}</td>
                            <td>{{list.cell_no_1}}</td>
                        </tr>

                        <tr ng-show="$index == '2'" class="info">
                            <td>
                                <div style="width: 50px">
                                    <img style="width: 10px;height: 10px" ng-click="viewButton(list)" ng-show="list.view_by_admin == 1"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/redflag.png" alt="" class="loading">
                                    <img style="width: 10px ;height: 10px" ng-show="list.login_form ==1"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/Laptop-2-icon.png" alt="" class="loading">
                                    <img style="width: 10px ;height: 15px" ng-show="list.login_form ==2"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/Mobile_Icon.png" alt="" class="loading">
                                </div>
                            </td>
                            <td>{{list.date}}</td>
                            <th><a href="<?php echo Yii::app()->createUrl('client/manageClient')?>?client_id={{list.client_id}}&edit=1">{{list.fullname}} </a> </th>
                            <td>{{list.name}}</td>
                            <td>{{list.cell_no_1}}</td>
                        </tr>
                        <tr ng-show="$index == '3'" class="warning">
                            <td>
                                <div style="width: 50px">
                                    <img style="width: 10px;height: 10px" ng-click="viewButton(list)" ng-show="list.view_by_admin == 1"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/redflag.png" alt="" class="loading">
                                    <img style="width: 10px ;height: 10px" ng-show="list.login_form ==1"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/Laptop-2-icon.png" alt="" class="loading">
                                    <img style="width: 10px ;height: 15px" ng-show="list.login_form ==2"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/Mobile_Icon.png" alt="" class="loading">
                                </div>
                            </td>
                            <td>{{list.date}}</td>
                            <th><a href="<?php echo Yii::app()->createUrl('client/manageClient')?>?client_id={{list.client_id}}&edit=1">{{list.fullname}} </a> </th>
                            <td>{{list.name}}</td>
                            <td>{{list.cell_no_1}}</td>
                        </tr>
                        <tr ng-show="$index == '4'" class="success">
                            <td>
                                <div style="width: 50px">
                                    <img style="width: 10px;height: 10px" ng-click="viewButton(list)" ng-show="list.view_by_admin == 1"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/redflag.png" alt="" class="loading">
                                    <img style="width: 10px ;height: 10px" ng-show="list.login_form ==1"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/Laptop-2-icon.png" alt="" class="loading">
                                    <img style="width: 10px ;height: 15px" ng-show="list.login_form ==2"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/Mobile_Icon.png" alt="" class="loading">
                                </div>
                            </td>
                            <td>{{list.date}}</td>
                            <th><a href="<?php echo Yii::app()->createUrl('client/manageClient')?>?client_id={{list.client_id}}&edit=1">{{list.fullname}} </a> </th>
                            <td>{{list.name}}</td>
                            <td>{{list.cell_no_1}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <?php } ?>
        <?php if(isset($data['allow_widget'][5])){ ?>
          <div class="col-lg-12" >
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table" style="width: 100%">
                        <thead>
                        <tr>
                            <th colspan="4">Customers with new Schedule<span style="margin-left: 5px ; font-size: 80%;">(latest 5)</span>

                                <img style="width: 10px ;height: 15px"   src="<?php echo Yii::app()->theme->baseUrl; ?>/images/Mobile_Icon.png" alt="" class="loading">
                                <span style="font-size: 80%;color: blue">{{total_mobilesum_sc}}</span>
                                <img style="margin-left: 5px;width: 10px ;height: 10px"   src="<?php echo Yii::app()->theme->baseUrl; ?>/images/Laptop-2-icon.png" alt="" class="loading">
                                <span style="font-size: 80%;color: blue">{{total_laptopsum_sc}}</span>

                            </th>
                            <th colspan="1" style="text-align: right">
                                <a href="<?php echo Yii::app()->createUrl('client/manageClient') ?>" class=""> <p class="fa fa-eye">  All</p></a>
                            </th
                        </tr>
                        </thead>
                        <tbody ng-repeat="list in newSchedule">

                        <tr ng-show="$index == 0" class="">

                            <td>
                                <div style="width: 50px">
                                    <img style="width: 10px;height: 10px" ng-click="viewButton2(list)" ng-show="list.admin_view == 1"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/redflag.png" alt="" class="loading">
                                    <img style="width: 10px ;height: 10px" ng-show="list.change_form ==1"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/Laptop-2-icon.png" alt="" class="loading">
                                    <img style="width: 10px ;height: 15px" ng-show="list.change_form ==2"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/Mobile_Icon.png" alt="" class="loading">
                                </div>
                            </td>
                            <td>{{list.date}}</td>
                            <th><a href="<?php echo Yii::app()->createUrl('client/manageClient')?>?client_id={{list.client_id}}">{{list.fullname}} </a> </th>
                            <td>{{list.name}}</td>
                            <td>{{list.cell_no_1}}</td>
                        </tr>

                        <tr ng-show="$index == '1'" class="danger">
                            <td>
                                <div style="width: 50px">
                                    <img style="width: 10px;height: 10px" ng-click="viewButton2(list)" ng-show="list.admin_view == 1"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/redflag.png" alt="" class="loading">
                                    <img style="width: 10px ;height: 10px" ng-show="list.change_form ==1"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/Laptop-2-icon.png" alt="" class="loading">
                                    <img style="width: 10px ;height: 15px" ng-show="list.change_form ==2"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/Mobile_Icon.png" alt="" class="loading">
                                </div>
                            </td>

                            <td>{{list.date}}</td>
                            <th><a href="<?php echo Yii::app()->createUrl('client/manageClient')?>?client_id={{list.client_id}}">{{list.fullname}} </a> </th>
                            <td>{{list.name}}</td>
                            <td>{{list.cell_no_1}}</td>
                        </tr>

                        <tr ng-show="$index == '2'" class="info">
                            <td>
                                <div style="width: 50px">
                                    <img style="width: 10px;height: 10px" ng-click="viewButton2(list)" ng-show="list.admin_view == 1"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/redflag.png" alt="" class="loading">
                                    <img style="width: 10px ;height: 10px" ng-show="list.change_form ==1"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/Laptop-2-icon.png" alt="" class="loading">
                                    <img style="width: 10px ;height: 15px" ng-show="list.change_form ==2"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/Mobile_Icon.png" alt="" class="loading">
                                </div>
                            </td>

                            <td>{{list.date}}</td>
                            <th><a href="<?php echo Yii::app()->createUrl('client/manageClient')?>?client_id={{list.client_id}}">{{list.fullname}} </a> </th>
                            <td>{{list.name}}</td>
                            <td>{{list.cell_no_1}}</td>
                        </tr>

                        <tr ng-show="$index == '3'" class="warning">

                            <td>
                                <div style="width: 50px">
                                    <img style="width: 10px;height: 10px" ng-click="viewButton2(list)" ng-show="list.admin_view == 1"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/redflag.png" alt="" class="loading">
                                    <img style="width: 10px ;height: 10px" ng-show="list.change_form ==1"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/Laptop-2-icon.png" alt="" class="loading">
                                    <img style="width: 10px ;height: 15px" ng-show="list.change_form ==2"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/Mobile_Icon.png" alt="" class="loading">
                                </div>
                            </td>
                            <td>{{list.date}}</td>
                            <th><a href="<?php echo Yii::app()->createUrl('client/manageClient')?>?client_id={{list.client_id}}">{{list.fullname}} </a> </th>
                            <td>{{list.name}}</td>
                            <td>{{list.cell_no_1}}</td>
                        </tr>

                        <tr ng-show="$index == '4'" class="success">
                            <td>
                                <div style="width: 50px">
                                    <img style="width: 10px;height: 10px" ng-click="viewButton2(list)" ng-show="list.admin_view == 1"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/redflag.png" alt="" class="loading">
                                    <img style="width: 10px ;height: 10px" ng-show="list.change_form ==1"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/Laptop-2-icon.png" alt="" class="loading">
                                    <img style="width: 10px ;height: 15px" ng-show="list.change_form ==2"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/Mobile_Icon.png" alt="" class="loading">
                                </div>
                            </td>
                            <td>{{list.date}}</td>
                            <th><a href="<?php echo Yii::app()->createUrl('client/manageClient')?>?client_id={{list.client_id}}">{{list.fullname}} </a> </th>
                            <td>{{list.name}}</td>
                            <td>{{list.cell_no_1}}</td>
                        </tr>


                        </tbody>
                    </table>
                </div>

            </div>

        </div>
        <?php } ?>
        <?php if(isset($data['allow_widget'][6])){ ?>
          <div class="col-lg-12" >
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table" style="width: 100%">
                        <thead>
                        <tr>
                            <th colspan="2">
                                <span>POint of Sale</span>
                            </th>
                            <th colspan="1" style="text-align: right">
                                <a href="<?php echo Yii::app()->createUrl('pos/PosDateRang') ?>" class=""> <p class="fa fa-eye">  All</p></a>
                            </th>
                        </tr>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Amount</th>
                        </tr>
                        </thead>
                        <tbody ng-repeat="list in posData">

                        <tr ng-show="$index == 0" class="">
                            <td>{{list.product_name}}</td>
                            <td>{{list.quantity | number}}</td>
                            <td>{{list.total_price | number}}</td>
                        </tr>

                        <tr ng-show="$index == '1'" class="danger">
                            <td>{{list.product_name}}</td>
                            <td>{{list.quantity | number}}</td>
                            <td>{{list.total_price | number}}</td>
                        </tr>

                        <tr ng-show="$index == '2'" class="info">
                            <td>{{list.product_name}}</td>
                            <td>{{list.quantity | number}}</td>
                            <td>{{list.total_price | number}}</td>
                        </tr>

                        <tr ng-show="$index == '3'" class="warning">
                            <td>{{list.product_name}}</td>
                            <td>{{list.quantity | number}}</td>
                            <td>{{list.total_price | number}}</td>
                        </tr>

                        <tr ng-show="$index == '4'" class="success">
                            <td>{{list.product_name}}</td>
                            <td>{{list.quantity | number}}</td>
                            <td>{{list.total_price | number}}</td>
                        </tr>


                        </tbody>
                    </table>
                </div>

            </div>

        </div>
        <?php } ?>
        <div class="col-lg-12" ></div>
        <?php if(isset($data['allow_widget'][7])){ ?>
          <div class="col-lg-6" >
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table" >
                        <thead>
                        <tr>
                            <th colspan="3">Delivery Status <span style="margin-left: 5px ; font-size: 80%;">(latest 5)</span></th>

                            <th colspan="2"  style="text-align: right">
                                <a href="<?php echo Yii::app()->createUrl('delivery/deliveryStatusForAllCustomer') ?>" class=""> <p class="fa fa-eye"> All</p></a>
                            </th>

                        </tr>

                        <tr>
                            <td><span style="font-weight: bold;"> Rider </span> </td>
                            <td><span style="font-weight: bold;">Product</span></td>
                            <td><span style="font-weight: bold;"> Customers</span></td>
                            <td><span style="font-weight: bold;">Today</span></td>
                            <td><span style="font-weight: bold;">Month</span></td>
                        </tr>

                        </thead>
                        <tbody ng-repeat="list in DeliveryStatus">

                        <tr ng-show="$index == 0" class="success">


                            <td>{{list.rider_name}}</td>
                            <td>{{list.product_name}}</td>
                            <td style="text-align: center ">{{list.totalClient | number }}</td>
                            <td style="text-align: center">{{list.totalQuantity | number}}</td>
                            <td style="text-align: center">{{list.dileryMonthResult | number}}</td>
                        </tr>

                        <tr ng-show="$index == '1'" class="danger">


                            <td>{{list.rider_name}}</td>
                            <td>{{list.product_name}}</td>
                            <td style="text-align: center">{{list.totalClient | number}}</td>
                            <td style="text-align: center">{{list.totalQuantity | number}}</td>
                            <td style="text-align: center">{{list.dileryMonthResult | number}}</td>
                        </tr>

                        <tr ng-show="$index == '2'" class="info">


                            <td>{{list.rider_name}}</td>
                            <td>{{list.product_name}}</td>
                            <td style="text-align: center">{{list.totalClient | number}}</td>
                            <td style="text-align: center">{{list.totalQuantity | number}}</td>
                            <td style="text-align: center">{{list.dileryMonthResult | number}}</td>
                        </tr>

                        <tr ng-show="$index == '3'" class="warning">


                            <td>{{list.rider_name}}</td>
                            <td>{{list.product_name}}</td>
                            <td style="text-align: center">{{list.totalClient | number}}</td>
                            <td style="text-align: center">{{list.totalQuantity | number}}</td>
                            <td style="text-align: center">{{list.dileryMonthResult | number}}</td>
                        </tr>

                        <tr ng-show="$index == '4'" class="">

                            <td>{{list.rider_name}}</td>
                            <td>{{list.product_name}}</td>
                            <td style="text-align: center">{{list.totalClient | number}}</td>
                            <td style="text-align: center">{{list.totalQuantity | number}}</td>
                            <td style="text-align: center">{{list.dileryMonthResult | number}}</td>
                        </tr>


                        </tbody>
                    </table>
                </div>

            </div>

        </div>
        <?php } ?>

        <?php if(isset($data['allow_widget'][8])){ ?>
        <div class="col-lg-6" >
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table" >
                        <thead>
                        <tr>
                            <th colspan="">Customers With Outstanding Balance <span style="margin-left: 5px ; font-size: 80%;">(latest 5)</span> <br>  <span style=" font-weight: bold; color: red; line-height: 30%;"><?php   /*Yii::app()->user->getState('currency');*/ ?> {{outstanding_balance | number}}</span></th>
                            <th  style="text-align: right">
                                <a href="<?php echo Yii::app()->createUrl('companyLimit/customerList') ?>?all=1" class=""> <p class="fa fa-eye"> All</p></a>
                            </th>
                        </tr>
                        </thead>
                        <tbody ng-repeat="list in result_balnce_clientLIst">

                        <tr ng-show="$index == 0" class="">
                            <td>{{list.fullname}}</td>
                            <td style="text-align: right">{{list.balanace | number}}</td>
                        </tr>

                        <tr ng-show="$index == '1'" class="danger">

                            <td>{{list.fullname}}</td>
                            <td style="text-align: right">{{list.balanace | number }}</td>

                        </tr>

                        <tr ng-show="$index == '2'" class="info">

                            <td>{{list.fullname}}</td>
                            <td style="text-align: right">{{list.balanace | number }}</td>

                        </tr>

                        <tr ng-show="$index == '3'" class="warning">

                            <td>{{list.fullname}}</td>
                            <td style="text-align: right">{{list.balanace | number }}</td>

                        </tr>
                        <tr ng-show="$index == '4'" class="success">

                            <td>{{list.fullname}}</td>
                            <td style="text-align: right">{{list.balanace | number }}</td>

                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <?php } ?>
        <div class="col-lg-12" ></div>

        <?php if(isset($data['allow_widget'][9])){ ?>
          <div class="col-lg-6" >
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table" >
                        <thead>
                        <tr>
                            <th colspan="3">Month wise new Customers </th>
                            <th colspan="1" style="text-align: right">
                                <a href="<?php echo Yii::app()->createUrl('client/manageClient') ?>" class=""> <p class="fa fa-eye"> All</p></a>
                            </th>
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
        <?php } ?>
        <?php if(isset($data['allow_widget'][10])){ ?>
        <div class="col-lg-6" >
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table" >
                        <thead>
                        <tr>
                            <th colspan="3">Payment Received Today </th>
                            <th  style="text-align: right">
                                <a href="<?php echo Yii::app()->createUrl('expenceType/paymentType') ?>" class=""> <p class="fa fa-eye"> All</p></a>
                            </th>
                        </tr>
                        </thead>
                        <tbod">
                        <tr>
                            <th colspan="4">
                                Payment From App
                            </th>
                        </tr>
                        <tr class="success">
                            <td>
                                Cash
                            </td>
                            <th>
                                <span style=" font-weight: bold; font-family: Times New Roman, Times, serif;color: black "> {{totalCash_from_app | number}}</span>
                            </th>

                            <td>
                                Cheque
                            </td>
                            <th>
                                <span style=" font-weight: bold; font-family: Times New Roman, Times, serif;color: black "> {{totalcheque_from_app | number}}</span>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="4">
                                Payment From Portal
                            </th>
                        </tr>

                        <tr class="danger">
                            <td>
                                Cash
                            </td>
                            <th>
                                <span style=" font-weight: bold; font-family: Times New Roman, Times, serif;color: black "> {{total_cshPayment_form_portal | number}}</span>
                            </th>

                            <td>
                                Cheque
                            </td>
                            <th>
                                <span style=" font-weight: bold; font-family: Times New Roman, Times, serif;color: black "> {{total_chequePayment_from_portal | number}}</span>
                            </th>
                        </tr>

                        <tr class="info">
                            <td colspan="3">
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
        <?php } ?>
        <div class="col-lg-12" ></div>
        <?php if(isset($data['allow_widget'][11])){ ?>
          <div class="col-lg-12" >
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table" >
                        <thead>
                        <tr>
                            <th colspan="3">
                                Daily Sales History (Quantity)

                                <img ng-show="daily_sale" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
                            </th>
                            <th colspan="1" style="text-align: right">
                                <a target="_blank" href="<?php echo Yii::app()->createUrl('index.php/ChartGraph/dailySaleQuantityGraph') ?>" class=""> <p class="fa fa-eye"> All</p></a>
                            </th>
                        </tr>
                        </thead>
                        <tbod">
                        <tr>
                            <td colspan="5">
                                <div style="padding: 0px">
                                    <div id="columnchart_material" style=" height: 300px;margin: 0px"></div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php } ?>
        <div class="col-lg-12" ></div>

        <?php if(isset($data['allow_widget'][16])){ ?>

            <div class="col-lg-12" >
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table" >
                            <thead>
                            <tr>
                                <th colspan="3">Month wise Total Customers </th>
                                <th colspan="1" style="text-align: right">
                                    <a href="<?php echo Yii::app()->createUrl('client/manageClient') ?>" class=""> <p class="fa fa-eye"> All</p></a>
                                </th>
                            </tr>
                            </thead>
                            <tbod">
                            <tr>
                                <td colspan="4">
                                    <div id="curve_chart_total_customer" style=""></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        <?php } ?>


        <div class="col-lg-12" ></div>
        <?php if(isset($data['allow_widget'][12])){ ?>
           <div class="col-lg-12" >
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table" >
                        <thead>
                        <tr>
                            <th colspan="3">
                                Daily Sales History
                                <img ng-show="daily_sale" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
                            </th>
                            <th colspan="1" style="text-align: right">
                                <a target="_blank" href="<?php echo Yii::app()->createUrl('index.php/ChartGraph/dailySaleAmountGraph') ?>" class=""> <p class="fa fa-eye"> All</p></a>
                            </th>
                        </tr>
                        </thead>
                        <tbod">
                        <tr>
                            <td colspan="5">
                                <div style="padding: 0px">
                                    <div id="daily_sale_amount" style=" height: 300px;margin: 0px"></div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php } ?>

        <div class="col-lg-12" ></div>

        <?php if(isset($data['allow_widget'][13])){ ?>
          <div class="col-lg-12" >
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table" >
                        <thead>
                        <tr>
                            <th colspan="3">
                                 Daily new Customer added/dropped
                                <img ng-show="daily_sale" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
                            </th>
                            <th colspan="1" style="text-align: right">
                                <a href="<?php echo Yii::app()->createUrl('index.php/ChartGraph/newCustomerGraph') ?>" class=""> <p class="fa fa-eye"> All</p></a>
                            </th>
                        </tr>
                        </thead>
                        <tbod">
                        <tr>
                            <td colspan="5">
                                <div style="padding: 0px">
                                    <div id="new_customer" style=" height: 300px;margin: 0px"></div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php } ?>

        <div class="col-lg-12" ></div>

        <?php if(isset($data['allow_widget'][14])){?>
          <div class="col-lg-12"  >
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table" >
                        <thead>
                        <tr>
                            <th colspan="3">
                                Monthly Sales History (Quantity)
                                <img ng-show="daily_sale" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
                            </th>
                            <th colspan="1" style="text-align: right">
                               <!--<a href="<?php /*echo Yii::app()->createUrl('client/manageClient') */?>" class=""> <p class="fa fa-eye"> All</p></a>-->
                            </th>
                        </tr>
                        </thead>
                        <tbod">
                        <tr>
                            <td colspan="5">
                                <div style="padding: 0px">
                                    <div id="year_graph" style=" height: 300px;margin: 0px"></div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php } ?>

        <div class="col-lg-12" ></div>
        <?php if(isset($data['allow_widget'][15])){ ?>
          <div class="col-lg-12" ng-show="true" >
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table" >
                        <thead>
                        <tr>
                            <th colspan="3">
                                Monthly Sales History

                                <img ng-show="daily_sale" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
                            </th>
                            <th colspan="1" style="text-align: right">
                                <!--<a href="<?php /*echo Yii::app()->createUrl('client/manageClient') */?>" class=""> <p class="fa fa-eye"> All</p></a>-->
                            </th>
                        </tr>
                        </thead>
                        <tbod">
                        <tr>
                            <td colspan="5">
                                <div style="padding: 0px">
                                    <div id="year_graph_amount" style=" height: 300px;margin: 0px"></div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php } ?>


        <modal title="Manage Widget" visible="view_manage_wiget_model" >
            <table class="table table-striped table-bordered">
                <thead>
                <tr style="background-color: #FFF8DC">
                    <th>Widget Name</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="list in manage_wiget_list">
                    <td>
                        {{list.dashboard_widget_list_name}}
                    </td>
                    <td style="text-align: right">
                        <label class="switch">
                            <input type="checkbox" ng-model="list.selected">
                            <span class="slider round"></span>
                        </label>
                    </td>
                </tr>

                </tbody>
            </table>
            <div class=" form-group ">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="button" ng-click="save_widget_function()" class="btn btn-info btn-sm">Save</button>
            </div>
        </modal>
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

    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 24px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }.switch {
             position: relative;
             display: inline-block;
             width: 60px;
             height: 24px;
         }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }.switch {
             position: relative;
             display: inline-block;
             width: 60px;
             height: 24px;
         }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }r-radius: 50%;
        }
    </style>