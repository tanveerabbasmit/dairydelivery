

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/manageZone/stock_transfer_to_production_grid.js"></script>

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

    <div ng-controller="manageZone" ng-init='init(<?php echo $data ?>,<?php echo $allow_delete ?>,<?php echo $zoneList ?>,  <?php echo $companyBranchList ?> ,"<?php echo Yii::app()->createAbsoluteUrl('zone/saveNewZone'); ?>","<?php echo Yii::app()->createAbsoluteUrl('zone/editZone'); ?>","<?php echo Yii::app()->createAbsoluteUrl('zone/delete'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/saveRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/deleteRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/search'); ?>")'>
        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                    Manage Zone
                </a>
            </li>

        </ul>
        <div class="" style="margin: 10px">
             <div class="">

                  <!--{{zoneObject}}-->

                <div class="form-group input-group col-lg-3">
                    <input datetime-picker="" date-only="" date-format="yyyy-MM-dd" style="" type="text" class="form-control " ng-model="today_date"  >
                    <span class="input-group-btn">

                                    <button   style="box-shadow: inset 0 0 0 5px #DCDCDC;" class="btn btn-default" ng-disabled="isLoading" ng-click="searchClientFuction(search)" type="button"><i class="fa fa-calendar"></i>
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

                <table id="customers">
                    <thead>
                    <tr style="background-color: #F0F8FF">
                        <th><a href="#">#</a></th>
                        <th><a href="#">ID</a></th>
                        <th width="40%"><a href="#">Name</a></th>
                        <th width="20%"><a href="#">Stock Transfer</a></th>
                        <th width="20%"><a href="#">Used in production</a></th>

                        <th style="text-align: center"><a href="#">Action</a></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="zone in zoneList | filter:search:stric track by $index ">
                        <td>{{$index + 1}}</td>
                        <td>{{zone.zone_id}}</td>
                        <td>{{zone.name}}</td>
                        <td>{{zone.commission}}</td>
                        <td>
                           <span ng-show="zone.is_active == '1'" class="label label-default">Active</span>
                            <span ng-show="zone.is_active == '0'" class="label label-primary">Deactive</span>
                        </td>

                        <td >
                            <button ng-disabled=" allow_delete[3]" ng-click="editZone(zone)"  class="btn btn-sm btn-default next btn-xs" > <i class="fa fa-edit "></i> </button>
                          <!--  <button ng-disabled=" allow_delete[2]" ng-click="zoneDelete(zone)"  class="btn btn-sm btn-info next btn-xs" > <i class="fa fa-trash "></i></button>-->
                           
                        </td>
                    </tr>
                    </tbody>
                </table>



            </div><!-- table-responsive -->
        </div>

        <!-- start: add new Zone -->

        <modal title="Add New Zone" visible="showAddNewZone">
            <form role="form" class="form-group" ng-submit="saveZone(zoneObject)">
                <div class="col-lg-12 form-group" style="background-color: honeydew">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Name :</span>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" ng-model="zoneObject.name" class="form-control"   required/>
                    </div>
                </div>
                <div class="col-lg-12 form-group" style="background-color: honeydew">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">	Commission :</span>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" ng-model="zoneObject.commission" class="form-control"   required/>
                    </div>
                </div>



                <div class="col-lg-12 form-group" style="background-color: 	#FFF0F5">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Status :</span>
                    </div>

                    <div class="col-lg-8" style="padding: 10px">

                        <label class="radio-inline">
                            <input ng-model="zoneObject.is_active"   type="radio" name="status" value="1" required>
                            <span  class="label label-default">Active</span>
                        </label>
                        <label class="radio-inline">
                            <input ng-model="zoneObject.is_active" type="radio" name="status" value="0" required>
                            <span  class="label label-primary">Inactive</span>
                        </label>

                    </div>

                </div>

                <div class=" form-group ">

                    <button type="submit" class="btn-success  btn-sm">Save</button>
                    <button type="button" ng-click="setReset()" class="btn-success  btn-sm">Reset</button>
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                </div>
            </form>
        </modal>

        <!-- end: add new Zone -->

        <!-- start: add new Zone -->


        <modal title="Update Zone" visible="showEditZone">
            <form role="form" class="form-group" ng-submit="editZoneFunction(zoneObject)">
                <div class="col-lg-12 form-group" style="background-color: honeydew">
                    <div class="col-lg-4">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Name :</span>
                    </div>

                    <div class="col-lg-8">
                        <input type="text" ng-model="zoneObject.name" class="form-control"   required/>
                    </div>

                </div>

                <div class="col-lg-12 form-group" style="background-color: honeydew">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">	Commission :</span>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" ng-model="zoneObject.commission" class="form-control"   required/>
                    </div>
                </div>

                <div ng-show="false" class="col-lg-12 form-group">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Company Branch :</span>
                    </div>

                    <div class="col-lg-8">
                        <!--<input type="text"   ng-model="zoneObject.companyBranch"  class="form-control" required/>-->
                        <select ng-model="zoneObject.companyBranch" class="form-control">
                            <option value="">Select</option>
                            <option ng-repeat="company in companyBranchList" value="{{company.company_branch_id}}">
                                {{company.name}}
                            </option>

                        </select>
                    </div>

                </div>

                <div class="col-lg-12 form-group" style="background-color: 	#FFF0F5">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Status :</span>
                    </div>

                    <div class="col-lg-8" style="padding: 10px">

                        <label class="radio-inline">
                            <input ng-model="zoneObject.is_active"   type="radio" name="status" value="1" required>
                            <span  class="label label-default">Active</span>
                        </label>
                        <label class="radio-inline">
                            <input ng-model="zoneObject.is_active" type="radio" name="status" value="0" required>
                            <span  class="label label-primary">Inactive</span>
                        </label>

                    </div>

                </div>





                <div class=" form-group ">

                    <button type="submit" class="btn-success  btn-sm">Update</button>
                    <button type="button" ng-click="setReset()" class="btn-success  btn-sm">Reset</button>
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                </div>
            </form>
        </modal>

        <!-- end: add new Zone -->
    </div>
</div>

