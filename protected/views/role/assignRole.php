
<style xmlns="http://www.w3.org/1999/html">
    .angularjs-datetime-picker{
        z-index: 99999 !important;
    }
</style>

<style>
    .modal{
    //  display: block !important; /* I added this to see the modal, you don't need this */
    }

    /* Important part */
    .modal-dialog{
        overflow-y: initial !important
    }
    .modal-body{
        max-height: 600px;
        overflow-y: auto;
    }
</style>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/manageRole/manageRole-grid.js"></script>
<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>

<?php $allow_delete = Yii::app()->user->getState('allow_delete'); ?>
<div  id="testContainer" style="display: none" class="panel row" ng-app="riderDailyStockGridModule">
    <div ng-controller="manageZone" ng-init='init(<?php echo $allow_delete ?> ,<?php echo $menuList ?> ,  <?php echo $assignRole  ?> ,"<?php echo Yii::app()->createAbsoluteUrl('role/saveNewRole'); ?>","<?php echo Yii::app()->createAbsoluteUrl('role/editRole'); ?>","<?php echo Yii::app()->createAbsoluteUrl('role/delete'); ?>","<?php echo Yii::app()->createAbsoluteUrl('role/getAssignRoleManu'); ?>","<?php echo Yii::app()->createAbsoluteUrl('role/changeRole'); ?>")'>
        <div class="panel-heading">
            <h4 class="panel-title">Assign Role</h4>
        </div>
        <div class="panel-body">
            <div class="row">

                <div class="col-lg-4">
                    <div class="btn-demo">
                        <button ng-disabled=" allow_delete =='0'"  class="btn btn-primary btn-sm" ng-click="addnewZone()" ><i class="fa fa-plus"></i> Add New Role</button>
                    </div>
                </div>

                <div class="col-lg-4">
                </div>
                <div class="col-lg-4">
                    <div class="input-group">
                        <input  class="form-control" placeholder="Search Role" ng-change="searchBarOnzero(searchBar)"  type="text" required ng-model="searchBar" size="2">
                        <span class="input-group-addon" ng-click="searchZone(searchBar)"><i class="glyphicon glyphicon-search"></i></span>
                    </div>
                </div>


            </div>
            <div style="margin-top:5px;" class="table-responsive">

                <table class="table table-striped nomargin">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Role Name</th>
                        <th>Role Key</th>

                        <th style="text-align: center">Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr ng-repeat="zone in assignRole | filter:search:stric track by $index">
                        <td>{{$index + 1 }}</td>
                        <td>{{zone.role_name}}</td>
                        <td>{{zone.role_key}}</td>
                        <td style="text-align: center">
                           <!-- ng-disabled=" allow_delete =='0'"-->
                            <button  ng-click="editZone(zone)"  class="btn btn-sm btn-default next btn-xs" > <i class="fa fa-edit "></i> </button>
                            <button  ng-click="zoneDelete(zone)"   class="btn btn-sm btn-info next btn-xs" > <i class="fa fa-trash "></i></button>
                            <button  ng-click="assignRoleFunction(zone)"   class="btn btn-sm btn-default next btn-xs" > <i class="fa fa-tasks "></i></button>
                            
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div><!-- table-responsive -->
        </div>
        <!-- start: add new Zone -->
        <modal title="Add New Role" visible="showAddNewZone">
            <form role="form" class="form-group" ng-submit="saveZone(zoneObject)">
                <div class="col-lg-12 form-group" style="background-color: honeydew">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Name :</span>
                    </div>Assign Menu

                    <div class="col-lg-8">
                        <input type="text" ng-model="zoneObject.role_name" class="form-control"   required/>
                    </div>
                </div>
                <div class="col-lg-12 form-group" style="background-color: 	#FFF0F5">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Key :</span>Assign Menu
                    </div>
                    <div class="col-lg-8">
                        <input type="text" ng-model="zoneObject.role_key" class="form-control"   required/>
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
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Name :</span>
                    </div>

                    <div class="col-lg-8">
                        <input type="text" ng-model="zoneObject.role_name" class="form-control"   required/>
                    </div>

                </div>
                <div class="col-lg-12 form-group" style="background-color: 	#FFF0F5">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Key :</span>
                    </div>

                    <div class="col-lg-8">
                        <input type="text" ng-model="zoneObject.role_key" class="form-control"   required/>
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

        <!-- start: add new Zone -->


        <modal title="Assign Menu" visible="popUPassignRole">

            <form role="form" class="form-group" ng-submit="saveAssignMenuFunction(menuList)">

                <table class="table table-striped nomargin">
                    <thead>
                    <tr>
                        <th>Menu</th>

                        <th>Assign</th>
                    </tr>
                    </thead>
                    <tbody ng-repeat="menu in menuList">
                    <tr >

                        <th><h4>{{$index +1}}:  &nbsp{{menu.menu.menu_name}}</h4></th>
                        <td>
                             <h4 style="color: #DEB887">
                                <label class="checkbox-inline">
                                     <input style="margin-top: 0px !important;" type="checkbox" ng-model="menu.menu.assignTo"> Change
                                </label>
                             </h4>
                        </td>

                    </tr>
                    <tr>
                        <td colspan="2">
                            <label style="width: 30%" ng-repeat="crudlist in menu.crud" class="checkbox-inline">
                                <input style="margin-top: 0px !important;" type="checkbox" ng-model="crudlist.selected">{{crudlist.name}}
                            </label>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <div class=" form-group" style="margin-top: 20px">
                    <button type="submit" class="btn-success  btn-sm">Save</button>

                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                    <img  ng-show="saveCrudList" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
                </div>
            </form>
        </modal>

        <!-- end: add new Zone -->
    </div>
</div>

