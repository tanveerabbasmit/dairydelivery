
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/js/table/table_style.css">


<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>



<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/product_purchase_summary/product_purchase_summary_report_view_grad.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/FileSaver/FileSaver.js"></script>

<div  id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">
    <div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo $data;  ?>, "<?php echo Yii::app()->createAbsoluteUrl('product_purchase_summary/base_url'); ?>")'>

        <div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <strong>message! </strong>
            {{taskMessage}}
        </div>

        <div class="tabbable">
            <ul class="nav nav-tabs nav-tabs-lg">
                <li>
                    Product Purchase Summary Report View
               </li>
            </ul>

            <div class="tab-content">

                <div class="tab-pane active" id="tab_1">



                    <input  style="float: left ; width: 20% ;margin-bottom: 10px" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="start_date" size="2">
                    <button style="float: left" type="button" ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-info btn-sm"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
                    <input  style="float: left ; width: 20% ;margin-bottom: 10px" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="end_date" size="2">

                    <select style="width: 20%; float: left;margin-bottom: 10px;margin-left: 10px ; margin-right: 10px" class="form-control input-sm" ng-model="farm_id" >
                        <option value="0">All Farm</option>
                        <option ng-repeat="list in fram_list" value="{{list.farm_id}}">{{list.farm_name}}
                        </option>
                    </select>
                    <button ng-disabled="imageLoading" type="button"  ng-click="product_purchase()" class="btn btn-primary btn-sm"> <i style="margin: 4px" class="fa fa-search"></i> Search</button>
                    <img ng-show="imageLoading" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">

                    <div class="dropdown" style="f ;margin-left: 5px">
                        <button class="btn btn-info dropdown-toggle btn-sm" type="button" data-toggle="dropdown">
                            <span class="glyphicon glyphicon-download-alt"></span>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                           <!-- <li ng-click="printFunction()"><a href="#">Print</a></li>-->
                            <li><a href="<?php echo Yii::app()->baseUrl; ?>/product_purchase_summary/product_purchase_summary_report_view_export?start_date={{start_date}}&end_date={{end_date}}&farm_id={{farm_id}}">Export CSV</a></li>
                        </ul>
                    </div>



                    <style>

                        [data-tooltip] {
                            position: relative;
                            z-index: 2;
                            cursor: pointer;
                        }

                        /* Hide the tooltip content by default */
                        [data-tooltip]:before,
                        [data-tooltip]:after {
                            visibility: hidden;
                            -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
                            filter: progid: DXImageTransform.Microsoft.Alpha(Opacity=0);
                            opacity: 0;
                            pointer-events: none;
                        }

                        /* Position tooltip above the element */
                        [data-tooltip]:before {
                            position: absolute;
                            bottom: 150%;
                            left: 50%;
                            margin-bottom: 5px;
                            margin-left: -80px;
                            padding: 7px;
                            width: 160px;
                            -webkit-border-radius: 3px;
                            -moz-border-radius: 3px;
                            border-radius: 3px;
                            background-color: #000;
                            background-color: hsla(0, 0%, 20%, 0.9);
                            color: #fff;
                            content: attr(data-tooltip);
                            text-align: center;
                            font-size: 14px;
                            line-height: 1.2;
                        }

                        /* Triangle hack to make tooltip look like a speech bubble */
                        [data-tooltip]:after {
                            position: absolute;
                            bottom: 150%;
                            left: 50%;
                            margin-left: -5px;
                            width: 0;
                            border-top: 5px solid #000;
                            border-top: 5px solid hsla(0, 0%, 20%, 0.9);
                            border-right: 5px solid transparent;
                            border-left: 5px solid transparent;
                            content: " ";
                            font-size: 0;
                            line-height: 0;
                        }

                        /* Show tooltip content on hover */
                        [data-tooltip]:hover:before,
                        [data-tooltip]:hover:after {
                            visibility: visible;
                            -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
                            filter: progid: DXImageTransform.Microsoft.Alpha(Opacity=100);
                            opacity: 1;
                        }

                    </style>
                    <!-- {{productList}}-->
                    <table id="customers"  class="table table-fixed">
                        <thead>
                        <tr>
                            <th class="col-xs-1"><a href="">Sr.#</a></th>
                            <th class="col-xs-2"><a href="">Date</a></th>
                            <th class="col-xs-3"><a href="">Product</a></th>
                            <th class="col-xs-2"><a href="">Farm</a></th>
                            <th class="col-xs-2"><a href="">Recived Quantity</a></th>
                            <th class="col-xs-1"><a href="">Rate</a></th>
                            <th class="col-xs-1"><a href="">Amount</a></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="List in list_data">
                            <td class="col-xs-1">{{$index +1}}</td>
                            <td class="col-xs-2">{{List.date}}</td>
                            <td class="col-xs-3">{{List.product_name}}</td>
                            <td class="col-xs-2">
                               <span ng-show="farm_id!=0">{{List.farm_name}}</span>
                               <span ng-show="farm_id==0">All Farm</span>
                            </td>
                            <td class="col-xs-2" style="text-align: center">{{List.net_quantity}}</td>
                            <td class="col-xs-1" style="text-align: center">{{List.purchase_rate}}</td>
                            <td class="col-xs-1" style="text-align: center">{{List.net_amount}}</td>
                        </tr>
                        <tr>
                            <td class="col-xs-8" colspan="4">Total</td>
                            <td class="col-xs-2" style="text-align: center" >{{total_result.total_net_quantity |number:2}}</td>
                            <td class="col-xs-1"></td>
                            <td class="col-xs-1" style="text-align: center">{{total_result.total_net_amount}}</td>

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
