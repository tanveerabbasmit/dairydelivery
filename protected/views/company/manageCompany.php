

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/manageCompany/manageCompany-grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>


<div id="testContainer" style="display: none" class="panel row" ng-app="riderDailyStockGridModule">
    <div ng-controller="manageZone" ng-init='init(<?php echo $companyList ?>,  <?php echo "companyBranchList" ?> ,"<?php echo Yii::app()->createAbsoluteUrl('company/saveNewCompany'); ?>","<?php echo Yii::app()->createAbsoluteUrl('company/editCompany'); ?>","<?php echo Yii::app()->createAbsoluteUrl('company/delete'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/saveRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/deleteRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/search'); ?>")'>
        <div class="panel-heading">
            <h4 class="panel-title">Company</h4>
        </div>
        <div class="panel-body">
            <div class="row">
                  <!--{{zoneObject}}-->
                <div class="col-lg-4">
                    <div class="btn-demo">
                        <button class="btn btn-primary btn-ms" ng-click="addnewZone()" ><i class="fa fa-plus"></i> Add New Company</button>
                    </div>
                </div>

                <div class="col-lg-4">
                </div>
                <div class="col-lg-4">
                    <div class="input-group">
                        <input  class="form-control" placeholder="Search Company" ng-change="searchBarOnzero(searchBar)"  type="text" required ng-model="searchBar" size="2">
                        <span class="input-group-addon" ng-click="searchZone(searchBar)"><i class="glyphicon glyphicon-search"></i></span>
                    </div>
                </div>
            </div>
            <div style="margin-top:5px;" class="table-responsive">
                <table class="table table-striped nomargin">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Subdomain</th>
                        <th>Address</th>
                        <th>Contact Person</th>
                        <th>Phone Number</th>
                        <th>Status</th>
                        <th>Delete</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="company in companyList | filter:search:stric">
                        <td>{{company.company_name}}</td>
                        <td>{{company.subdomain}}</td>
                        <td>{{company.address}}</td>
                        <td>{{company.phone_numer}}</td>
                        <td>{{company.contact_person}}</td>

                        <td>
                            <span ng-show="company.is_active == '1'" class="label label-default">Active</span>
                            <span ng-show="company.is_active == '0'" class="label label-primary">Deactive</span>
                        </td>
                        <td>
                            <span ng-show="company.is_deleted == '0'" class="label label-primary">No</span>
                            <span ng-show="company.is_deleted == '1'" class="label label-default">Deleted</span>
                        </td>
                        <td>
                            <ul class="table-options">
                                <li><a href="" ng-click="editZone(company)" data-toggle="modal" data-target="#dailyStockDetailModal" title="Edit"><i class="fa fa-edit"></i></a></li>
                                <li><a href="" ng-click="zoneDelete(company)" data-toggle="modal" data-target="#dailyStockDetailModal" title="Delete"><i class="fa fa-trash"></i></a></li>
                                <!-- <li><a href="" ng-click="deleteStock(dailyStock)"><i class="fa fa-trash"></i></a></li> -->
                            </ul>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div><!-- table-responsive -->
        </div>


        <!-- start: add new Zone -->


        <modal title="Add New Company" visible="showAddNewZone">
            <form role="form" class="form-group" ng-submit="saveZone(zoneObject)">
                <div class="col-lg-12 form-group">
                    <div class="col-lg-4">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Name :</span>
                    </div>

                    <div class="col-lg-8">
                        <input type="text" ng-model="zoneObject.company_name" class="form-control"   required/>
                    </div>

                </div>

                <div  class="col-lg-12 form-group">
                    <div class="col-lg-4">
                          <span style="font-weight: bold;font-size: 13px; padding: 8px">Subdomain :</span>
                    </div>

                    <div class="col-lg-8">
                        <!--<input type="text"   ng-model="zoneObject.companyBranch"  class="form-control" required/>-->
                        <input type="text" ng-model="zoneObject.subdomain" class="form-control"   required/>
                    </div>

                </div>

                <div  class="col-lg-12 form-group">
                    <div class="col-lg-4">
                          <span style="font-weight: bold;font-size: 13px; padding: 8px">Address :</span>
                    </div>

                    <div class="col-lg-8">
                        <!--<input type="text"   ng-model="zoneObject.companyBranch"  class="form-control" required/>-->
                        <input type="text" ng-model="zoneObject.address" class="form-control"   required/>
                    </div>

                </div>


                <div  class="col-lg-12 form-group">
                    <div class="col-lg-4">
                          <span style="font-weight: bold;font-size: 13px; padding: 8px">Phone Number  :</span>
                    </div>

                    <div class="col-lg-8">
                        <!--<input type="text"   ng-model="zoneObject.companyBranch"  class="form-control" required/>-->
                        <input type="text" ng-model="zoneObject.phone_number" class="form-control"   required/>
                    </div>

                </div>

                <div  class="col-lg-12 form-group">
                    <div class="col-lg-4">
                          <span style="font-weight: bold;font-size: 13px; padding: 8px">Contact Person :</span>
                    </div>

                    <div class="col-lg-8">
                        <!--<input type="text"   ng-model="zoneObject.companyBranch"  class="form-control" required/>-->
                        <input type="text" ng-model="zoneObject.contact_person" class="form-control"   required/>
                    </div>

                </div>


                <div  class="col-lg-12 form-group">
                    <div class="col-lg-4">
                          <span style="font-weight: bold;font-size: 13px; padding: 8px">Logo :</span>
                    </div>

                    <div class="col-lg-8">
                        <!--<input type="text"   ng-model="zoneObject.companyBranch"  class="form-control" required/>-->
                        <input type="text" ng-model="zoneObject.logo" class="form-control"   required/>
                    </div>

                </div>

                <div class="col-lg-12 form-group">
                    <div class="col-lg-4">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Status :</span>
                    </div>

                    <div class="col-lg-8">

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
                <form role="form" class="form-group" ng-submit="saveZone(zoneObject)">
                    <div class="col-lg-12 form-group">
                        <div class="col-lg-4">
                            <span style="font-weight: bold;font-size: 13px; padding: 8px">Name :</span>
                        </div>

                        <div class="col-lg-8">
                            <input type="text" ng-model="zoneObject.company_name" class="form-control"   required/>
                        </div>

                    </div>

                    <div  class="col-lg-12 form-group">
                        <div class="col-lg-4">
                            <span style="font-weight: bold;font-size: 13px; padding: 8px">Subdomain :</span>
                        </div>

                        <div class="col-lg-8">
                            <!--<input type="text"   ng-model="zoneObject.companyBranch"  class="form-control" required/>-->
                            <input type="text" ng-model="zoneObject.subdomain" class="form-control"   required/>
                        </div>

                    </div>

                    <div  class="col-lg-12 form-group">
                        <div class="col-lg-4">
                            <span style="font-weight: bold;font-size: 13px; padding: 8px">Address :</span>
                        </div>

                        <div class="col-lg-8">
                            <!--<input type="text"   ng-model="zoneObject.companyBranch"  class="form-control" required/>-->
                            <input type="text" ng-model="zoneObject.address" class="form-control"   required/>
                        </div>

                    </div>


                    <div  class="col-lg-12 form-group">
                        <div class="col-lg-4">
                            <span style="font-weight: bold;font-size: 13px; padding: 8px">Phone Number  :</span>
                        </div>

                        <div class="col-lg-8">
                            <!--<input type="text"   ng-model="zoneObject.companyBranch"  class="form-control" required/>-->
                            <input type="text" ng-model="zoneObject.phone_number" class="form-control"   required/>
                        </div>

                    </div>

                    <div  class="col-lg-12 form-group">
                        <div class="col-lg-4">
                            <span style="font-weight: bold;font-size: 13px; padding: 8px">Contact Person :</span>
                        </div>

                        <div class="col-lg-8">
                            <!--<input type="text"   ng-model="zoneObject.companyBranch"  class="form-control" required/>-->
                            <input type="text" ng-model="zoneObject.contact_person" class="form-control"   required/>
                        </div>

                    </div>


                    <div  class="col-lg-12 form-group">
                        <div class="col-lg-4">
                            <span style="font-weight: bold;font-size: 13px; padding: 8px">Logo :</span>
                        </div>

                        <div class="col-lg-8">
                            <!--<input type="text"   ng-model="zoneObject.companyBranch"  class="form-control" required/>-->
                            <input type="text" ng-model="zoneObject.logo" class="form-control"   required/>
                        </div>

                    </div>

                    <div class="col-lg-12 form-group">
                        <div class="col-lg-4">
                            <span style="font-weight: bold;font-size: 13px; padding: 8px">Status :</span>
                        </div>

                        <div class="col-lg-8">

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
    </div>
</div>

