

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/manageCategory/manageCategory-grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>


<div id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">

    <div ng-controller="manageZone" ng-init='init(<?php echo $zoneList ?>,  <?php echo $companyBranchList ?> ,"<?php echo Yii::app()->createAbsoluteUrl('CustomerCategory/saveNewCategory'); ?>","<?php echo Yii::app()->createAbsoluteUrl('CustomerCategory/editZone'); ?>","<?php echo Yii::app()->createAbsoluteUrl('CustomerCategory/delete'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/saveRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/deleteRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/search'); ?>")'>
        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                    Manage Customer Category
                </a>
            </li>

        </ul>
        <div class="" style="margin: 10px">
             <div class="">

                  <!--{{zoneObject}}-->
                <div class="col-lg-4">
                    <div >
                        <button type="button"  ng-click="addnewZone()" class="btn btn-primary btn-sm"> <i class="fa fa-plus"></i> Add New Category</button>
                    </div>
                </div>

                <div class="col-lg-5">
                </div>
              <!--  <div class="col-lg-4">
                    <div class="input-group">
                        <input  class="form-control" placeholder="Search Zone" ng-change="searchBarOnzero(searchBar)"  type="text" required ng-model="searchBar" size="2">
                        <span class="input-group-addon" ng-click="searchZone(searchBar)"><i class="glyphicon glyphicon-search"></i></span>
                    </div>
                </div>-->
                 <style>

                 </style>
                <div class="form-group input-group col-lg-3">
                    <input style="" type="text" class="form-control " ng-model="search" ng-change="changeSearchBar(search)" ng-disabled="isLoading" id="search-dealer" placeholder="Search Category">
                    <span class="input-group-btn">

                                    <button  style="box-shadow: inset 0 0 0 5px #DCDCDC;" class="btn btn-default" ng-disabled="isLoading" ng-click="searchClientFuction(search)" type="button"><i class="fa fa-search"></i>
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

                        <th width="40%"><a href="#">Category Name</a></th>
                        <th width="40%"><a href="#">Color</a></th>
                          <th style="text-align: center;"><a href="#">Action</a></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="zone in zoneList | filter:search:stric track by $index ">
                        <td>{{$index + 1}}</td>

                        <td>{{zone.category_name}}</td>
                        <td style="background-color: {{zone.color_name}}">{{zone.color_name}}</td>


                        <td >
                            <ul class="table-options">
                                <li><a href=""ng-click="editZone(zone)"  title="Edit"><i class="fa fa-edit btn btn-default btn-md"></i></a></li>
                                <li><a href="" ng-click="zoneDelete(zone)" data-toggle="modal" data-target="#dailyStockDetailModal" title="Delete"><i class="fa fa-trash btn btn-info btn-md"></i></a></li>
                            </ul>
                        </td>
                    </tr>
                    </tbody>
                </table>



            </div><!-- table-responsive -->
        </div>

        <!-- start: add new Zone -->

        <modal title="Add New Category" visible="showAddNewZone">
            <form role="form" class="form-group" ng-submit="saveZone(zoneObject)">
                <div class="col-lg-12 form-group" style="background-color: honeydew">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Name :</span>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" ng-model="zoneObject.category_name" class="form-control"   required/>
                    </div>
                </div>

                <div class="col-lg-12 form-group" style="background-color: honeydew">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Color :</span>
                    </div>
                    <div class="col-lg-8">

                        <input type="color" ng-model="zoneObject.color_name" class="form-control"   required/>
                    </div>
                </div>

                <div ng-show="false" class="col-lg-12 form-group" style="background-color: 	#FFF0F5">
                    <div class="col-lg-4">
                          <span style="font-weight: bold;font-size: 13px; padding: 8px">Company Branch :</span>
                    </div>

                    <div class="col-lg-8" style="padding: 10px">
                        <!--<input type="text"   ng-model="zoneObject.companyBranch"  class="form-control" required/>-->
                        <select ng-model="zoneObject.companyBranch" class="form-control">
                             <option value="">Select</option>
                            <option ng-repeat="company in companyBranchList" value="{{company.company_branch_id}}">
                                {{company.name}}
                            </option>

                        </select>
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


        <modal title="Update CAtegory" visible="showEditZone">
            <form role="form" class="form-group" ng-submit="editZoneFunction(zoneObject)">
                <div class="col-lg-12 form-group" style="background-color: honeydew">
                    <div class="col-lg-4">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Name :</span>
                    </div>

                    <div class="col-lg-8">
                        <input type="text" ng-model="zoneObject.category_name" class="form-control"   required/>
                    </div>

                </div>

                <div class="col-lg-12 form-group" style="background-color: honeydew">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Color :</span>
                    </div>
                    <div class="col-lg-8">

                        <input type="color" ng-model="zoneObject.color_name" class="form-control"   required/>
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

