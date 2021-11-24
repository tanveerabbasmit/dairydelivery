
<style>
    .angularjs-datetime-picker{
        z-index: 99999 !important;
    }
</style>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/angular.min.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/viewcompanystock/stock_out_detail_grad.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/angularDatePicker/angularjs-datetime-picker.js"></script>
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/js/angularDatePicker/angularjs-datetime-picker.css" rel="stylesheet">

<head>
    <title>Stock Summary View</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/lib/fontawesome/css/font-awesome.css" rel="stylesheet">
</head>

    <?php
       include  Yii::app()->basePath.'/include_files/menu_file.php';
    ?>


<div class="" id="testContainer" style="display: none" ng-app="clintManagemaent"ng-controller="clintManagemaent" ng-init='init( <?php echo json_encode($data) ?> , "<?php echo Yii::app()->createAbsoluteUrl('Mobiledesign/stock_summary_list'); ?>")'>

    <div class="col-lg-12" style="text-align: center;">
         <h3 style="color: darkolivegreen">Stock Out Detail</h3>
    </div>

    <div class="col-lg-12 " style="">


        <div class="form-group col-sm-3">

            <select class="form-control input-sm" ng-model="product_id">
                <option ng-repeat="list in productList" value="{{list.product_id}}" class="ng-binding ng-scope">
                    {{list.name}}
                </option>
            </select>
        </div>

        <div class="col-sm-3 form-group">
            <input  class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="start_date">
        </div>

        <div class="col-sm-3 form-group">
            <input   class="form-control input-sm "    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="end_date" >
        </div>

        <div class="col-sm-3 form-group">
            <a href="" class="btn btn-primary input-sm" style="float: left" ng-click="getCustomerLedgerReportFunction()"><i class="fa fa-search" style=""></i> Search</a>

            <img style="margin: 10px" ng-show="reportLoader"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading.gif" alt="" class="loading">
        </div>

    </div>

   <br>

    <table class="table table-bordered" style="margin-top: 15px">
        <thead>
        <tr>
            <th style=" text-align: center; vertical-align: middle;">Date</th>

            <th style=" text-align: center; vertical-align: middle;">Total Sale</th>
            <th style=" text-align: center; vertical-align: middle;">Category</th>
            <th style=" text-align: center; vertical-align: middle;">Sale</th>


        </tr>
        </thead>
        <tbody>
        <tr ng-repeat="list in main_list">

            <td ng-show="list.show_row" rowspan="{{list.size_of_category}}" style=" text-align: center; vertical-align: middle;"><span ng-bind="list.date"></span> </td>
            <td ng-show="list.show_row" rowspan="{{list.size_of_category}}" style=" text-align: center; vertical-align: middle;"><span ng-bind="list.total_sale"></span> </td>
            <td style=" text-align: center; vertical-align: middle;"><span ng-bind="list.category_name"></span> </td>
            <td style=" text-align: center; vertical-align: middle;"><span ng-bind="list.sale"></span> </td>


        </tr>

        </tbody>
    </table>
</div>