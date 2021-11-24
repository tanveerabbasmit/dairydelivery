

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/futureScheduled/futureScheduled-grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>

<?php $allow_delete = crudRole::getCrudrole(1); ?>

<div id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">
    <div ng-controller="manageZone" ng-init='init(<?php echo $allow_delete ?>,<?php echo $data;  ?>,<?php echo $zoneList ?>,  <?php echo $companyBranchList ?> ,"<?php echo Yii::app()->createAbsoluteUrl('EffectiveDateSchedule/saveNewZone'); ?>","<?php echo Yii::app()->createAbsoluteUrl('EffectiveDateSchedule/editZone'); ?>","<?php echo Yii::app()->createAbsoluteUrl('EffectiveDateSchedule/delete'); ?>","<?php echo Yii::app()->createAbsoluteUrl('EffectiveDateSchedule/saveRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('EffectiveDateSchedule/deleteRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('EffectiveDateSchedule/search'); ?>")'>
        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                    Future Schedule
                </a>
            </li>
        </ul>
        <div class="" style="margin: 10px">
             <div class="">
                  <!--{{zoneObject}}-->

                <div class="col-lg-4">
                    <div >
                        <button ng-show="false" ng-disabled="allow_delete[1]" type="button"  ng-click="addnewZone()" class="btn btn-primary btn-sm"> <i class="fa fa-plus"></i> Add New Zone</button>
                    </div>
                </div>
                <div class="col-lg-5">
                </div>
                 <style>
                 </style>
                <div class="form-group input-group col-lg-3">
                    <input style="" type="text" class="form-control " ng-model="search" ng-change="changeSearchBar(search)" ng-disabled="isLoading" id="search-dealer" placeholder="Search">
                    <span class="input-group-btn">

                                    <button   style="box-shadow: inset 0 0 0 5px #DCDCDC;" class="btn btn-default" ng-disabled="isLoading" ng-click="searchClientFuction(search)" type="button"><i class="fa fa-search"></i>
                                    </button>
                            </span>
                </div>
            </div>
            <div style="margin-top:0px;" class="table-responsive">
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


                <table id="customers" ng-show="data.interval_size >0">
                    <thead>
                    <tr style="background-color: #F0F8FF">
                        <th><a href="#">#</a></th>
                        <th><a href="#">ID</a></th>
                        <th><a href="#">Name</a></th>
                        <th><a href="#">product</a></th>
                        <th><a href="#">Start Order</a></th>
                        <th><a href="#">Interval</a></th>
                        <th><a href="#">Quantiy</a></th>
                        <th style="text-align: center"><a href="#">Action</a></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="zone in zoneList | filter:search:stric track by $index ">
                        <td>{{$index + 1}}</td>
                        <td>{{zone.client_id}}</td>
                        <td>{{zone.fullname}}</td>
                        <td>{{zone.product_name}}</td>
                        <td>{{zone.start_interval_scheduler}}</td>
                        <td>{{zone.interval_days}}</td>
                        <td>{{zone.product_quantity}}</td>
                        <td >
                            <button ng-disabled="true" ng-click="editZone(zone)"  class="btn btn-sm btn-default next btn-xs" > <i class="fa fa-edit "></i> </button>
                            <button ng-disabled=" allow_delete[2]" ng-click="zoneDelete(zone)"  class="btn btn-sm btn-info next btn-xs" > <i class="fa fa-trash "></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <table id="customers" ng-show="data.weekly_size >0">
                    <thead>
                    <tr style="background-color: #F0F8FF">
                        <th colspan="5"> <a href="#">Weekly Scheduale </a></th>

                    </tr>
                    </thead>
                </table>

                <table id="customers" ng-show="data.weekly_size >0">
                    <thead>
                    <tr style="background-color: #F0F8FF">
                        <th><a href="#">#</a></th>
                        <th><a href="#">ID</a></th>
                        <th><a href="#">Name</a></th>
                        <th><a href="#">product</a></th>
                        <th><a href="#">Start Order</a></th>
                        <th><a href="#">Days</a></th>
                        <th><a href="#">Quantiy</a></th>
                        <th style="text-align: center"><a href="#">Action</a></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="zone in data.weekly_result | filter:search:stric track by $index ">
                        <td>{{$index + 1}}</td>
                        <td>{{zone.client_id}}</td>
                        <td>{{zone.fullname}}</td>
                        <td>{{zone.product_name}}</td>
                        <td>{{zone.date}}</td>
                        <td>{{zone.day_name}}</td>
                        <td>{{zone.quantity}}</td>
                        <td >
                            <button ng-disabled="true" ng-click="editZone(zone)"  class="btn btn-sm btn-default next btn-xs" > <i class="fa fa-edit "></i> </button>
                           <!-- <button ng-disabled=" allow_delete[2]" ng-click="zoneDelete(zone)"  class="btn btn-sm btn-info next btn-xs" > <i class="fa fa-trash "></i></button>-->

                            <a href="<?php echo Yii::app()->baseUrl; ?>/EffectiveDateSchedule/DeleteEffectiveSchedule/?client_id={{zone.client_id}}&product_id={{zone.product_id}}"    class="btn btn-info next btn-xs " > <i class="fa fa-trash"></i> </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div><!-- table-responsive -->
        </div>


    </div>
</div>

