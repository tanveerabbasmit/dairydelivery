<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/js/table/table_style.css">

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/manageZone/manageZone-grid.js"></script>

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

    <div ng-controller="manageZone" ng-init='init(<?php echo $allow_delete ?>,<?php echo $zoneList ?>,  <?php echo $companyBranchList ?> ,"<?php echo Yii::app()->createAbsoluteUrl('zone/saveNewZone'); ?>","<?php echo Yii::app()->createAbsoluteUrl('zone/editZone'); ?>","<?php echo Yii::app()->createAbsoluteUrl('zone/delete'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/saveRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/deleteRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/search'); ?>")'>
      <!--  <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                    Manage Zone
                </a>
            </li>

        </ul>-->
        <div class="" style="margin: 10px">
             <div class="">

                  <!--{{zoneObject}}-->
                <div class="col-lg-4">
                    <div >
                        <button ng-disabled="allow_delete[1]" type="button"  ng-click="addnewZone()" class="btn btn-primary btn-sm"> <i class="fa fa-plus"></i> Add New Zone</button>

                    </div>
                </div>

                <div class="col-lg-5">
                </div>

                 <style>

                 </style>
                <div class="form-group input-group col-lg-3">
                    <input style="" type="text" class="form-control " ng-model="search" ng-change="changeSearchBar(search)" ng-disabled="isLoading" id="search-dealer" placeholder="Search Zone">
                    <span class="input-group-btn">

                                    <button   style="box-shadow: inset 0 0 0 5px #DCDCDC;" class="btn btn-default" ng-disabled="isLoading" ng-click="searchClientFuction(search)" type="button"><i class="fa fa-search"></i>
                                    </button>
                            </span>
                </div>

            </div>
            <div style="margin-top:0px;" class="table-responsive">


                <table id="customers" style="" class="table table-fixed">
                    <thead>
                    <tr id="head_tr" style="">
                        <th  class="col-xs-1"><a href="#">#</a></th>
                        <th  class="col-xs-1">
                            <a ng-click="change_sort_function('zone_id')" href="#">ID
                                <i ng-show="sort_object.name !='zone_id' || sort_object.name==''" class="fa fa-sort" aria-hidden="true"></i>
                                <i ng-show="sort_object.name =='zone_id'" class="fa fa-sort-{{sort_object.value.sort_icone}}"></i>
                            </a>
                        </th>
                        <th  class="col-xs-4" >
                            <a ng-click="change_sort_function('name')" href="#">
                                Name
                                <i ng-show="sort_object.name !='name' || sort_object.name==''" class="fa fa-sort" aria-hidden="true"></i>
                                <i ng-show="sort_object.name =='name'" class="fa fa-sort-{{sort_object.value.sort_icone}}"></i>
                            </a>
                        </th>
                        <th  class="col-xs-2" >
                            <a ng-click="change_sort_function('commission')" href="#">
                                Commission
                                <i ng-show="sort_object.name !='commission' || sort_object.name==''" class="fa fa-sort" aria-hidden="true"></i>
                                <i ng-show="sort_object.name =='commission'" class="fa fa-sort-{{sort_object.value.sort_icone}}"></i>
                            </a>
                        </th>
                        <th  class="col-xs-2"><a href="#">Status</a></th>
                        <th  class="col-xs-2" style="text-align: center"><a href="#">Action</a></th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr ng-repeat="zone in zoneList | filter:search:stric track by $index ">


                        <td class="col-xs-1">{{$index + 1}}</td>
                        <td class="col-xs-1">{{zone.zone_id}}</td>
                        <td class="col-xs-4">{{zone.name}}</td>
                        <td class="col-xs-2">{{zone.commission}}</td>
                        <td class="col-xs-2">
                            <span ng-show="zone.is_active == '1'" class="label label-default">Active</span>
                            <span ng-show="zone.is_active == '0'" class="label label-primary">Deactive</span>
                        </td>
                        <td class="col-xs-2">
                            <button ng-disabled=" allow_delete[3]" ng-click="editZone(zone)"  class="btn btn-sm btn-default next btn-xs" > <i class="fa fa-edit "></i> </button>
                            <button ng-disabled=" allow_delete[2]" ng-click="zoneDelete(zone)"  class="btn btn-sm btn-info next btn-xs" > <i class="fa fa-trash "></i></button>
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

                <div class="col-lg-12 form-group" style="background-color: 	#FFF0F5">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Show In app :</span>
                    </div>
                    <div class="col-lg-8" style="padding: 10px">
                        <label class="radio-inline">
                            <input ng-model="zoneObject.show_in_app"   type="radio" name="show_in_app" value="1" required>
                            <span  class="label label-default">Active</span>
                        </label>
                        <label class="radio-inline">
                            <input ng-model="zoneObject.show_in_app" type="radio" name="show_in_app" value="0" required>
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

