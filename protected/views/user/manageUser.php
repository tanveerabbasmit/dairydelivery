
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/manageUser/manageUser-grid.js"></script>

    <div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
        <div class="modal-content">
            <div class="modal-body">
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
                <span>&nbsp;&nbsp;Loading... </span>
            </div>
        </div>
    </div>

    <?php $allow_delete = crudRole::getCrudrole(12); ?>
<div id="testContainer" style="display: none" class="panel row" ng-app="riderDailyStockGridModule">
    <div ng-controller="manageZone" ng-init='init(<?php echo  $posShopList ?> , <?php echo $allow_delete   ?> ,<?php echo $rolList   ?> , <?php echo $UserList ?>,  <?php echo "companyBranchList" ?> ,"<?php echo Yii::app()->createAbsoluteUrl('user/saveNewUser'); ?>","<?php echo Yii::app()->createAbsoluteUrl('user/editUser'); ?>","<?php echo Yii::app()->createAbsoluteUrl('user/delete'); ?>","<?php echo Yii::app()->createAbsoluteUrl('user/checkAlredyExist'); ?>","<?php echo Yii::app()->createAbsoluteUrl('user/viewRole'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/search'); ?>")'>
        <div class="panel-heading">
            <h4 class="panel-title">Manage User</h4>
        </div>


        <div class="panel-body">
            <div class="row">

                <div class="col-lg-4">
                    <div class="btn-demo">
                        <button ng-disabled="allow_delete[1]" class="btn btn-primary btn-sm" ng-click="addnewZone()" ><i class="fa fa-plus"></i> Add New User</button>
                    </div>
                </div>
                <div class="col-lg-4">
                </div>
                <div class="col-lg-4">
                    <div class="input-group">
                        <input  class="form-control" placeholder="Search User" ng-change="searchBarOnzero(searchBar)"  type="text" required ng-model="searchBar" size="2">
                        <span class="input-group-addon" ng-click="searchZone(searchBar)"><i class="glyphicon glyphicon-search"></i></span>
                    </div>
                </div>
            </div>
            <div style="margin-top:5px;" class="table-responsive">

                <table class="table table-striped nomargin">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Full name</th>
                        <th>User name</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Phone Number</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="zone in userList | filter:search:stric track by $index">
                        <td>{{$index + 1}}</td>
                        <td>{{zone.full_name}}</td>
                        <td>{{zone.user_name}}</td>
                        <td>{{zone.role_key}}</td>
                        <td>{{zone.email}}</td>
                        <td>{{zone.password}}</td>
                        <td>{{zone.phone_number}}</td>
                        <td>
                            <span ng-show="zone.is_active == '1'" class="label label-default">Active</span>
                            <span ng-show="zone.is_active == '0'" class="label label-primary">Deactive</span>
                        </td>
                        <td style="width: 150px">
                          <!--  "
                            ng-disabled="allow_delete[2]"-->
                            <button ng-click="editZone(zone)" class="btn btn-sm btn-default next btn-xs" > <i class="fa fa-edit "></i> </button>
                            <button  ng-click="zoneDelete(zone)"  class="btn btn-sm btn-info next btn-xs" > <i class="fa fa-trash "></i></button>
                            <button  ng-click="add_rider(zone)"  class="btn btn-sm btn-success next btn-xs" > Rider</button>
                         <!-- <ul class="table-options">

                                <li><a href="" ng-click="editZone(zone)" data-toggle="modal" data-target="#dailyStockDetailModal" title="Edit"><i class="fa fa-edit btn btn-default btn-md"></i></a></li>
                                <li><a href="" ng-click="zoneDelete(zone)" data-toggle="modal" data-target="#dailyStockDetailModal" title="Delete"><i class="fa fa-trash btn btn-info btn-md"></i></a></li>
                            </ul>-->
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div><!-- table-responsive -->
        </div>
        <!-- start: add new Zone -->


        <modal title="Add New User" visible="showAddNewZone">

            <form role="form" class="form-group" ng-submit="saveZone(zoneObject)">

                <div class="col-lg-12 form-group" style="background-color: honeydew">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Full Name:</span>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" ng-model="zoneObject.full_name" class="form-control"   required/>
                    </div>
                </div>

                <div class="col-lg-12 form-group">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">User Name:</span>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" ng-change="checkAlreadyExistFunction(zoneObject.user_name)" ng-model="zoneObject.user_name" class="form-control"   required/>
                        <span ng-show="checkUserNameAlredy" style="color: green">This user name is already exist</span>
                    </div>
                </div>
                <div class="col-lg-12 form-group">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Phone Number:</span>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" ng-model="zoneObject.phone_number" class="form-control"   required/>
                    </div>
                </div>
                <div class="col-lg-12 form-group" style="background-color: honeydew">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Role:</span>
                    </div>
                    <div class="col-lg-6">
                        <select class="form-control" ng-model="zoneObject.user_role_id" required>
                            <option value="" >Select User Role</option>
                            <option ng-repeat="role in rolList" value="{{role.role_id}}">{{role.role_name}}</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <button type="button" style="margin-top: 12px" class="btn btn-primary btn-xs" ng-click="viewMenuName(zoneObject.user_role_id)"   ><i class="fa fa-eye" ></i> View Menu</button>
                    </div>
                </div>

                <div class="col-lg-12 form-group">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Email:</span>
                    </div>
                    <div class="col-lg-8">
                        <input type="email" ng-model="zoneObject.email" class="form-control"   required/>
                    </div>
                </div>
                <div class="col-lg-12 form-group">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Password:</span>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" ng-model="zoneObject.Password" class="form-control"   required/>
                    </div>
                </div>
                <div class="col-lg-12 form-group" style="background-color: honeydew">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">POS</span>
                    </div>
                    <div class="col-lg-8">
                        <select class="form-control" ng-model="zoneObject.pos_shop_id">
                            <option value="0">Select Shop</option>
                            <option value="{{list.pos_shop_id}}" ng-repeat="list in  posShopList">{{list.shop_name}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-12 form-group" style="background-color: honeydew">
                    <div class="col-lg-4" style="padding-top: 10px">
                        <span style="font-weight: bold;font-size: 13px; margin-top: 8px">Allow Delete :</span>
                    </div>
                    <div class="col-lg-8" style="padding: 10px">
                        <label class="radio-inline">
                            <input ng-model="zoneObject.allow_delete"   type="radio" name="allow_delete" value="1" required>
                            <span  class="label label-default">Yes</span>
                        </label>
                        <label class="radio-inline">
                            <input ng-model="zoneObject.allow_delete" type="radio" name="allow_delete" value="0" required>
                            <span  class="label label-primary">No</span>
                        </label>
                    </div>
                </div>

                <div class="col-lg-12 form-group" style="background-color: honeydew">
                    <div class="col-lg-4" style="padding-top: 10px">
                        <span style="font-weight: bold;font-size: 13px; ">Status :</span>
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

                    <button ng-disabled="checkUserNameAlredy" type="submit" class="btn-success  btn-sm">Save</button>
                    <button type="button" ng-click="setReset()" class="btn-success  btn-sm">Reset</button>
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                </div>
            </form>
        </modal>

        <!-- end: add new Zone -->

        <!-- start: add new Zone -->


        <modal title="Update User" visible="showEditZone">


            <form role="form" class="form-group" ng-submit="editZoneFunction(zoneObject)">

                <div class="col-lg-12 form-group" style="background-color: honeydew">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Full Name:</span>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" ng-model="zoneObject.full_name" class="form-control"   required/>
                    </div>

                </div>

                <div class="col-lg-12 form-group">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">User Name:</span>
                    </div>

                    <div class="col-lg-8">
                        <input type="text" ng-change="checkAlreadyExistFunction(zoneObject.user_name)" ng-model="zoneObject.user_name" class="form-control"   required/>
                        <span ng-show="checkUserNameAlredy" style="color: green">This user name is already exist</span>
                    </div>

                </div>

                <div class="col-lg-12 form-group">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Phone Number:</span>
                    </div>

                    <div class="col-lg-8">
                        <input type="text" ng-model="zoneObject.phone_number" class="form-control"   required/>
                    </div>

                </div>

                <div class="col-lg-12 form-group" style="background-color: honeydew">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Role:</span>
                    </div>
                    <div class="col-lg-8">
                        <select class="form-control" ng-model="zoneObject.user_role_id">
                            <option value="" >Select User Role</option>
                            <option ng-repeat="role in rolList" value="{{role.role_id}}">{{role.role_name}}</option>
                        </select>
                    </div>

                </div>

                <div class="col-lg-12 form-group">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Email:</span>
                    </div>

                    <div class="col-lg-8">
                        <input type="email" ng-model="zoneObject.email" class="form-control"   required/>
                    </div>

                </div>
                <div class="col-lg-12 form-group">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Password:</span>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" ng-model="zoneObject.password" class="form-control"   required/>
                    </div>
                </div>

                <div class="col-lg-12 form-group" style="background-color: honeydew">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">POS</span>
                    </div>
                    <div class="col-lg-8">
                        <select class="form-control" ng-model="zoneObject.pos_shop_id">
                            <option value="0">Select Shop</option>
                            <option value="{{list.pos_shop_id}}" ng-repeat="list in  posShopList">{{list.shop_name}}</option>
                        </select>
                    </div>
                </div>

                <div class="col-lg-12 form-group" style="background-color: honeydew">
                    <div class="col-lg-4" style="padding-top: 10px">
                        <span style="font-weight: bold;font-size: 13px; margin-top: 8px">Allow Delete :</span>
                    </div>
                    <div class="col-lg-8" style="padding: 10px">
                        <label class="radio-inline">
                            <input ng-model="zoneObject.allow_delete"   type="radio" name="allow_delete" value="1" required>
                            <span  class="label label-default">Yes</span>
                        </label>
                        <label class="radio-inline">
                            <input ng-model="zoneObject.allow_delete" type="radio" name="allow_delete" value="0" required>
                            <span  class="label label-primary">No</span>
                        </label>
                    </div>
                </div>
                <div class="col-lg-12 form-group" style="background-color: honeydew">
                    <div class="col-lg-4" style="padding: 10px">
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
                    <button ng-disabled="checkUserNameAlredy" type="submit" class="btn-success  btn-sm">Save</button>
                    <button type="button" ng-click="setReset()" class="btn-success  btn-sm">Reset</button>
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                </div>

            </form>
        </modal>

        <!-- end: add new Zone -->

        <!-- start: Show Menu -->


        <modal title="Update User" visible="showMenuList">
            <form role="form" class="form-group" ng-submit="editZoneFunction(zoneObject)">

                <table class="table table-striped nomargin">
                    <thead>
                    <tr>
                        <th>Sr.</th>
                        <th>Munu Mane</th>

                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="zone in menuList ">

                        <td></td>
                        <td>{{zone.menu_name}}</td>

                    </tr>
                    </tbody>
                </table>
                <div class=" form-group ">


                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                </div>

            </form>
        </modal>

        <!-- end: add Show Menu-->


        <modal title="User whose delivery add and edit" visible="show_rider_right_model">
            <form role="form" class="form-group" ng-submit="editZoneFunction(zoneObject)">
                <div class="col-lg-12 form-group" >

                    <div class="col-lg-4" ng-repeat="list in riderlist" style="padding: 10px">
                        <label class="checkbox-inline">
                            <input type="checkbox" ng-model="list.selected">{{list.fullname}}
                        </label>
                    </div>




                </div>



                <div class=" form-group ">
                    <button ng-click="save_rider_right()" type="button" class="btn-success  btn-sm">Save</button>
                    <button type="button" ng-click="setReset()" class="btn-success  btn-sm">Reset</button>
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                </div>

            </form>
        </modal>

    </div>
</div>

<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>