
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/dateWiseRiderSampleDelivery/riderwisecustomer_list_grid.js"></script>

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/FileSaver/FileSaver.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>



<div  id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">
    <div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo $riderList; ?>,"<?php echo Yii::app()->createAbsoluteUrl('riderwisecustomer/base_url'); ?>")'>
        <div class="tabbable">
            <ul class="nav nav-tabs nav-tabs-lg">
                <li>
                    <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                       Rider Wise Customer Route
                    </a>
                </li>
            </ul>
            <!--  {{productList}}
              {{todayData}}-->

            <div class="tab-content">
                <div class=" active" id="tab_1" style="margin-bottom: 10px;  margin: 10px">

                    <select ng-show="true" style="width: 20%; float: left;margin-bottom: 10px;margin-left: 10px ; margin-right: 10px" class="form-control input-sm" ng-model="selectRiderID" >
                        <option value="0">All Rider</option>
                        <option ng-repeat="list in riderList" value="{{list.rider_id}}">{{list.fullname}}
                        </option>
                    </select>
                    <button style="margin-left: 5px;" type="button"  ng-click="selectRiderOnChange()" class="btn btn-primary  btn-sm"> <i class="fa fa-search" style="margin: 4px"></i> Search</button>
                    <a  onclick="javascript:xport.toCSV('customers');" class="btn btn-primary  btn-sm " href="<?php echo   Yii::app()->createUrl('Riderwisecustomer/export_list')  ?>/{{selectRiderID}}"><i class="fa fa-download"></i> Export</a>

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
                    <table id="customers" style="margin-top: 6px">
                        <thead>
                        <tr style="background-color: #F0F8FF">
                            <th><a href="#">#</a></th>
                            <th><a href="#"> ID</a></th>
                            <th><a href="#"> Customer Name</a></th>
                            <th><a href="#"> Address</a></th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="list in customer_list">
                            <td>{{$index+1}}</td>
                            <td> {{list.client_id}}</td>
                            <td>{{list.fullname}}</td>
                            <td>{{list.address}}</td>

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
