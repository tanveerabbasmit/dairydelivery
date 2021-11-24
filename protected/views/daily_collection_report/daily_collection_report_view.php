
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/PosDateRang/daily_collection_report_view_grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>


<div  id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">
    <div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo str_replace("'","&#39;",$data ); ?>," <?php echo Yii::app()->createAbsoluteUrl('daily_collection_report/base');?>")'>

        <div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <strong>message! </strong>
            {{taskMessage}}
        </div>


        <div class="tabbable">
            <ul class="nav nav-tabs nav-tabs-lg">
                <li>
                    <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                        DAILY COLLECTION REPORT
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class=" active" id="tab_1" style="margin-bottom: 10px; margin: 10px">

                    <input  style="float: left ; width: 20% ;" class="form-control input-sm"  ng-change="selectRiderOnChange(selectRiderID)"  datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="today_data" size="2">


                  <!--  <select ng-model="product_id" style="float: left ; width: 20% ;margin-left: 5px" class="form-control input-sm">
                        <option value="0">Select</option>
                        <option ng-repeat="list in project_list" value="{{list.product_id}}">{{list.name}}</option>
                    </select>-->

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
                            <th><a href="#">Customer Name</a></th>
                            <th><a href="#">Amount</a></th>
                            <th><a href="#">Cash</a></th>
                            <th><a href="#">Qty</a></th>

                        </tr>
                        </thead>

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
