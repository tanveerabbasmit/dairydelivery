

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/zoneWiseCommission/zoneWiseCommission-grid.js"></script>

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

    <div ng-controller="manageZone" ng-init='init(<?php echo $productList ?>,<?php echo $allow_delete ?>,<?php echo $zoneList ?>,  <?php echo $companyBranchList ?> ,"<?php echo Yii::app()->createAbsoluteUrl('zone/saveNewZone'); ?>","<?php echo Yii::app()->createAbsoluteUrl('zone/editZone'); ?>","<?php echo Yii::app()->createAbsoluteUrl('zone/delete'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/saveRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/deleteRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/search'); ?>")'>
        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                    Manage Zone
                </a>
            </li>

        </ul>
        <div class="" style="margin: 10px">

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

                <table id="customers">
                    <thead>
                    <tr style="background-color: #F0F8FF">
                        <th><a href="#">#</a></th>
                        <th width="40%"><a href="#">Zone</a></th>
                        <th width="20%" ng-repeat="list in productList"><a href="#">{{list.name}}</a></th>


                        <th style="text-align: center"><a href="#">Action</a></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="zone in zoneList | filter:search:stric track by $index ">
                        <td>{{$index + 1}}</td>

                        <td>{{zone.zone_name}}</td>
                             <td ng-repeat="commission in zone.productList">
                                 <input ng-show="zone.updateMode" type="type"  ng-model="commission.amount" class="form-control btn-xs">
                                 <span ng-show="!zone.updateMode">{{commission.amount}}</span>
                             </td>
                          <td >
                            <button title="Edit"  ng-show="!zone.updateMode" ng-click="editZone(zone)"  class="btn btn-sm btn-default next" > <i class="fa fa-edit "></i> </button>
                            <button title="Save" ng-show="zone.updateMode" ng-click="saveZone(zone)"  class="btn btn-sm btn-primary next" > <i class="fa fa-save "></i> </button>

                        </td>
                    </tr>
                    </tbody>
                </table>



            </div><!-- table-responsive -->
        </div>


        <!-- end: add new Zone -->
    </div>
</div>

