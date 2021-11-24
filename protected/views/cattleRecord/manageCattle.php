
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/js/table/table_style.css">

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/addCattle/addCattle-grid.js"></script>

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
    <div ng-controller="manageZone" ng-init='init(<?php echo json_encode(date("Y-m-d")); ?>,<?php echo $allow_delete ?>,<?php echo $CattleList ?>,  <?php echo $companyBranchList ?> ,"<?php echo Yii::app()->createAbsoluteUrl('zone/saveNewZone'); ?>","<?php echo Yii::app()->createAbsoluteUrl('zone/editZone'); ?>","<?php echo Yii::app()->createAbsoluteUrl('zone/delete'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/saveRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/deleteRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/search'); ?>")'>
        <ul class="nav nav-tabs nav-tabs-lg">
            <li>

                    Manage Cattle

            </li>

        </ul>
        <div class="" style="margin: 10px">
            <div class="">

                <!--{{zoneObject}}-->
                <div class="col-lg-2">
                    <div >
                        <a  href="<?php echo Yii::app()->baseUrl; ?>/cattleRecord/addCattle/0"  type="button"   class="btn btn-primary btn-sm"> <i class="fa fa-plus"></i> Add New</a>
                    </div>



                </div>

                <div class="col-lg-7">
                    <a  href="<?php echo Yii::app()->baseUrl; ?>/cattleProduction/Production"  type="button"   class="btn btn-primary btn-sm">  Miilk daily production </a>
                    <a  href="<?php echo Yii::app()->baseUrl; ?>/cattleProduction/ProductionComparative"  type="button"   class="btn btn-primary btn-sm">  Comparative Production </a>
                </div>

                <style>

                </style>
                <div class="form-group input-group col-lg-3">
                    <input style="" type="text" class="form-control " ng-model="search" ng-change="changeSearchBar(search)" ng-disabled="isLoading" id="search-dealer" placeholder="Search Catttle">
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


                <table  id="customers"  class="table table-fixed">
                    <thead>
                    <tr style="background-color: #F0F8FF">

                        <th class="col-xs-1" ><a href="#">#</a></th>
                        <th class="col-xs-1" ><a href="#">Number</a></th>
                        <th class="col-xs-2"><a href="#">Type</a></th>
                        <th class="col-xs-2"><a href="#">milking</a></th>
                        <th class="col-xs-2"><a href="#">Milking Time</a></th>
                        <th class="col-xs-2"><a href="#">picture</a></th>
                        <th class="col-xs-2" ><a href="#"><span style="margin-right: 80px">Action</span></a></th>

                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="zone in zoneList | filter:search:stric track by $index ">
                        <td class="col-xs-1">{{$index + 1}}</td>
                        <td class="col-xs-1">{{zone.number}}</td>
                        <td class="col-xs-2">
                          {{zone.type}}
                        </td>
                        <td class="col-xs-2">
                            <span ng-show="zone.milking==1">Yes</span>
                            <span ng-show="zone.milking==2">No</span>
                        </td>
                        <td class="col-xs-2">
                            <span ng-show="zone.milking_time_morning==1">Morning ,</span>
                            <span ng-show="zone.milking_time_afternoun==1">Afternoon ,</span>
                            <span ng-show="zone.milking_time_evening==1">Evening</span>
                        </td>
                        <td class="col-xs-2">
                            <span  > <img  ng-click="mouseoveronimg(zone.picture)" style="height: 50px ;width: 50px" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/cattle/{{zone.picture}}" alt="" class="loading"></span>
                        </td>
                        <td class="col-xs-2">
                            <a href="<?php echo Yii::app()->baseUrl; ?>/cattleRecord/addCattle/{{zone.cattle_record_id}}"    class="btn btn-sm btn-default next " > <i class="fa fa-edit "></i> </a>
                            <a onclick="deleteConform()" href="<?php echo Yii::app()->baseUrl; ?>/cattleRecord/deleteCattle/{{zone.cattle_record_id}}"    class="btn btn-sm btn-default next " > <i class="fa fa-trash "></i> </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
                   <script>
                       function deleteConform() {
                          return false
                       }
                   </script>
            </div><!-- table-responsive -->
        </div>
        <!-- start: add new Zone -->
        <modal title="Add New Zone" visible="showAddNewZone">
            <img  ng-click="closeImg()"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/cattle/{{selectImg}}" alt="" class="loading">
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

