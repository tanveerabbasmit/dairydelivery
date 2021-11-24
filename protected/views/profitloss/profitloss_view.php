


<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>



<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/profitloss/profitloss_view_grad.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/FileSaver/FileSaver.js"></script>

<div  id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">
    <div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo $data;  ?>, "<?php echo Yii::app()->createAbsoluteUrl('profitloss/base_url'); ?>")'>

        <div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <strong>message! </strong>
            {{taskMessage}}
        </div>

        <div class="tabbable">
            <ul class="nav nav-tabs nav-tabs-lg">
                <li>
                    <a href="#tab_1" data-toggle="tab" aria-expanded="false">

                        Profit Loss View

                    </a>
                </li>
            </ul>

            <div class="tab-content">

                <div class="tab-pane active" id="tab_1">

                    <input  style="float: left ; width: 20% ;margin-bottom: 10px" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="start_date" size="2">
                    <button style="float: left" type="button" ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-info btn-sm"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
                    <input  style="float: left ; width: 20% ;margin-bottom: 10px" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="end_date" size="2">

                   <!--<select style="width: 20%; float: left;margin-bottom: 10px;margin-left: 10px ; margin-right: 10px" class="form-control input-sm" ng-model="farm_id" >
                        <option value="0">All Farm</option>
                        <option ng-repeat="list in fram_list" value="{{list.farm_id}}">{{list.farm_name}}
                        </option>
                    </select>-->


                    <button ng-disabled="imageLoading" type="button"  ng-click="product_purchase()" class="btn btn-primary btn-sm"> <i style="margin: 4px" class="fa fa-search"></i> Search</button>
                    <img ng-show="imageLoading" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">



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

                    <table id="customers">

                            <tr>
                                <th><a style="color: #0c0c0c" href="">Sales</a></th>
                                <td style="text-align: center"><span ng-bind="list_result.total_sale | number :0 "></span></td>

                            </tr>
                            <tr>
                                <th><a style="color: #0c0c0c" href="">Purchase</a></th>
                                <td style="text-align: center"><span ng-bind="list_result.purchased | number :0"></span></td>

                            </tr>

                            <tr>
                                <th><a style="color: #0c0c0c" href="">Vendor Bills</a></th>
                                <td style="text-align: center"><span ng-bind="list_result.vendor_bill | number :0"></span></td>

                            </tr>
                            <tr>
                                <th><a style="color: #0c0c0c" href="">Expenses</a></th>
                                <td style="text-align: center"><span ng-bind="list_result.expence | number :0"></span></td>

                            </tr>
                            <tr>
                                <th><a style="color: #0c0c0c" href="">Expense per Ltr </a></th>
                                <td style="text-align: center"><span ng-bind="list_result.rate_per_liter | number :0"></span></td>

                            </tr>

                            <tr>
                                <th><a style="color: #0c0c0c" href="">Grosss Income </a></th>
                                <td style="text-align: center"><span ng-bind="list_result.grosss_income | number :0"></span></td>

                            </tr>
                            <tr>
                                <th><a style="color: #0c0c0c" href="">Profit/Loss </a></th>
                                <td></td>

                            </tr>

                        <tbody>


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
