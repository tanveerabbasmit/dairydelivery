

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/manageRider/manageRider-grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>

<?php $allow_delete = crudRole::getCrudrole(2) ?>

<div  id="testContainer" style="display: none" class="panel row" ng-app="riderDailyStockGridModule">
    <div ng-controller="manageZone" ng-init='init(<?php echo $posShopList; ?> ,<?php echo $allow_delete ?> ,<?php echo str_replace("'", "&#039;", $ZoneList) ;  ?> , <?php  echo str_replace("'", "&#039;", $riderList) ; ?> , "<?php echo Yii::app()->createAbsoluteUrl('rider/saveNewRider'); ?>","<?php echo Yii::app()->createAbsoluteUrl('rider/editRider'); ?>","<?php echo Yii::app()->createAbsoluteUrl('rider/delete'); ?>"
    ,"<?php echo Yii::app()->createAbsoluteUrl('rider/getZoneAgainstRider'); ?>","<?php echo Yii::app()->createAbsoluteUrl('rider/checkDuplicateRiderUseName'); ?>")'>
        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                    Manage Rider
                </a>
            </li>

        </ul>
        <div  style="margin: 10px">
               <div >
                <!--{{zoneObject}}-->
                <div class="col-lg-4 row">
                       <button type="button" ng-disabled="allow_delete[1]" ng-click="addnewRider()" class="btn btn-primary btn-sm"> <i class="fa fa-plus"></i> Add New Rider</button>
                 </div>
                <div class="col-lg-5">
                </div>
                <div class="form-group input-group col-lg-3 ">
                    <input style="" type="text" class="form-control " ng-model="searchBar"  ng-change="changebarSearch(searchBar)" placeholder="Search Rider" ng-disabled="isLoading" id="search-dealer" >
                    <span class="input-group-btn">
                                    <button  style="box-shadow: inset 0 0 0 5px #DCDCDC;" class="btn btn-default" ng-disabled="isLoading" ng-click="searchBarFunction(searchBar)" type="button"><i class="fa fa-search"></i>
                                    </button>
                            </span>
                </div>
               </div>

            <div style="margin-top:5px;" class="table-responsive">

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
                        <th> <a href="#">#</a> </th>
                        <th><a href="#">Name</a></th>
                        <th><a href="#">Father Name</a> </th>
                        <th><a href="#">CNIC </a> </th>
                        <th width="30%"><a href="#">address</a></th>
                        <th><a href="#">Status </a></th>
                       <!-- <th>Delete</th>-->
                      <!--  <th>Zone </th>-->
                        <th style="text-align: center"><a href="#">Action</a></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="rider in riderList | filter:search:stric track by $index">
                        <td>{{$index + 1}}</td>
                        <td>{{rider.fullname}}</td>
                        <td>{{rider.father_name}}</td>
                        <td>{{rider.cnic}}</td>
                        <td>{{rider.address}}</td>

                        <td>
                            <span ng-show="rider.is_active == '1'" class="label label-default">Active</span>
                            <span ng-show="rider.is_active == '0'" class="label label-primary">Inactive</span>

                        </td>


                        <td>

                            <button  href="" ng-click="editRider(rider)"  class="btn btn-sm btn-default next btn-xs" title="Edit"><i class="fa fa-edit "></i></button>
                            <button ng-disabled="allow_delete[2]" href="" ng-click="delete(rider)" data-toggle="modal" class="btn btn-sm btn-info next btn-xs" title="Delete"><i class="fa fa-trash "></i></button>
                            <a href="<?php echo Yii::app()->baseUrl; ?>/rider/deduction?riderId={{rider.rider_id}}" type="submit" class="btn btn-sm btn-info next btn-xs" title="Delete"><i class="fa fa-scissors "></i></a>

                        </td>



                    </tr>
                    </tbody>
                </table>
            </div><!-- table-responsive -->
        </div>


        <!-- start: add new Zone -->

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
        <modal title="Add New Rider" visible="showAddNewRider">

                <form role="form" class="form-group" ng-submit="saveRider(riderObject)">

                    <div class="col-lg-12 form-group" style="background-color: honeydew">
                        <div class="col-lg-4" style="padding: 10px">
                            <span style="font-weight: bold;font-size: 13px; padding: 8px">Name :</span>
                        </div>
                        <div class="col-lg-8">
                            <input type="text" ng-model="riderObject.fullname" class="form-control"   required/>
                        </div>

                    </div>
                    <div class="col-lg-12 form-group" style="background-color: 	#FFF0F5">
                        <div class="col-lg-4" style="padding: 10px">
                            <span style="font-weight: bold;font-size: 13px; padding: 8px">Father Name :</span>
                        </div>

                        <div class="col-lg-8">
                            <input type="text" ng-model="riderObject.father_name" class="form-control"   required/>
                        </div>

                    </div>

                    <div class="col-lg-12 form-group">
                        <div class="col-lg-4" style="padding: 10px">
                            <span style="font-weight: bold;font-size: 13px; padding: 8px">Usename :</span>
                        </div>

                        <div class="col-lg-8">
                            <input type="text" ng-change="changeRiderUserName(riderObject.userName , '0' )" ng-model="riderObject.userName" class="form-control"   required/>
                            <span ng-show="riderUserNameCheck" style="color: green">This user name is Allready Exist</span>
                        </div>

                    </div>

                    <div class="col-lg-12 form-group" style="background-color: honeydew">
                        <div class="col-lg-4" style="padding: 10px">
                            <span style="font-weight: bold;font-size: 13px; padding: 8px">Password:</span>
                        </div>

                        <div class="col-lg-8">
                            <input type="text" ng-model="riderObject.password" class="form-control"   required/>
                        </div>

                    </div>

                    <div class="col-lg-12 form-group" style="background-color: 	#FFF0F5">
                        <div class="col-lg-4" style="padding: 10px">
                            <span style="font-weight: bold;font-size: 13px; padding: 8px">CNIC No. :</span>
                        </div>

                        <div class="col-lg-8">
                            <input type="text" ng-model="riderObject.cnic" class="form-control"   required/>
                        </div>

                    </div>
                    <div class="col-lg-12 form-group">
                        <div class="col-lg-4" style="padding: 10px">
                            <span style="font-weight: bold;font-size: 13px; padding: 8px">Address :</span>
                        </div>
                        <div class="col-lg-8">
                            <input type="text" ng-model="riderObject.address" class="form-control"   required/>
                        </div>

                    </div>
                    <div class="col-lg-12 form-group" >
                        <div class="col-lg-4" style="padding: 10px">
                            <span style="font-weight: bold;font-size: 13px; padding: 8px">Zone :</span>
                        </div>

                        <div class="col-lg-8">

                            <div class="col-lg-4" ng-repeat="zone in riderObject.zone">

                                <label class="checkbox-inline">
                                    <input type="checkbox" ng-model="zone.isselected">{{zone.name}}
                                </label>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-12 form-group" style="background-color: honeydew">
                        <div class="col-lg-4" style="padding: 10px">
                            <span style="font-weight: bold;font-size: 13px; padding: 8px">Cell No 1 :</span>
                        </div>

                        <div class="col-lg-8">
                            <input type="text" ng-model="riderObject.cell_no_1" class="form-control"   required/>
                        </div>

                    </div>
                    <div class="col-lg-12 form-group" style="background-color: 	#FFF0F5">
                        <div class="col-lg-4" style="padding: 10px">
                            <span style="font-weight: bold;font-size: 13px; padding: 8px">Cell No 2 :</span>
                        </div>
                        <div class="col-lg-8">
                            <input type="text" ng-model="riderObject.cell_no_2" class="form-control"   />
                        </div>
                    </div>
                    <div class="col-lg-12 form-group" >
                        <div class="col-lg-4" style="padding: 10px">
                            <span style="font-weight: bold;font-size: 13px; padding: 8px">Residence Phone No</span>
                        </div>
                        <div class="col-lg-8">
                            <input type="text" ng-model="riderObject.residence_phone_no" class="form-control"   />
                        </div>
                    </div>
                    <div class="col-lg-12 form-group" style="background-color: honeydew">
                        <div class="col-lg-4" style="padding: 10px">
                            <span style="font-weight: bold;font-size: 13px; padding: 8px">Email</span>
                        </div>
                        <div class="col-lg-8">
                            <input type="text" ng-model="riderObject.email" class="form-control"   required/>
                        </div>
                    </div>

                    <div class="col-lg-12 form-group" style="background-color: honeydew">
                        <div class="col-lg-4" style="padding: 10px">
                            <span style="font-weight: bold;font-size: 13px; padding: 8px">POS :</span>
                        </div>
                        <div class="col-lg-8">
                           <select class="form-control" ng-model="riderObject.pos_shop_id">
                               <option value="0">Select Shop</option>
                               <option value="{{list.pos_shop_id}}" ng-repeat="list in  posShopList">{{list.shop_name}}</option>
                           </select>
                        </div>
                    </div>

                    <div class="col-lg-12 form-group" style="background-color: honeydew">
                        <div class="col-lg-4" style="padding: 10px">
                            <span style="font-weight: bold;font-size: 13px; padding: 8px">Show Customer in App</span>
                        </div>

                        <div class="col-lg-8">
                           <select class="form-control" ng-model="riderObject.show_customers_in_app">
                               <option value="1">All Customer</option>
                               <option value="0">Delivered Customer</option>
                           </select>


                        </div>
                    </div>



                    <div class="col-lg-12 form-group">
                        <div class="col-lg-8" style="padding: 10px">
                            <span style="font-weight: bold;font-size: 13px; padding: 8px">Status :</span>
                        </div>
                        <div class="col-lg-4">

                            <label class="radio-inline">
                                <input ng-model="riderObject.is_active"   type="radio" name="status" value="1" required>
                                <span  class="label label-default">Active</span>
                            </label>
                            <label class="radio-inline">
                                <input ng-model="riderObject.is_active" type="radio" name="status" value="0" required>
                                <span  class="label label-primary">Inactive</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-12 form-group">
                        <div class="col-lg-8" style="padding: 10px">
                            <span style="font-weight: bold;font-size: 13px; padding: 8px">Allow rider to collect payment using rider app? :</span>
                        </div>
                        <div class="col-lg-4">

                            <label class="radio-inline">
                                <input ng-model="riderObject.can_add_payment"   type="radio" name="payment" value="1" required>
                                <span  class="label label-default">Active</span>
                            </label>
                            <label class="radio-inline">
                                <input ng-model="riderObject.can_add_payment" type="radio" name="payment" value="0" required>
                                <span  class="label label-primary">Inactive</span>
                            </label>
                        </div>
                    </div>
                    <div class=" form-group " >
                        <button ng-disabled="riderUserNameCheck" type="submit" class="btn-success  btn-sm">Save</button>
                        <button type="button" ng-click="resetObject()" class="btn-success  btn-sm">Reset</button>
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </form>
        </modal>

        <!-- end: add new Zone -->

        <!-- start: Edit Zone -->


        <modal title="Update Rider" visible="showEditRider">

            <form role="form" class="form-group" ng-submit="editRiderFunction(riderObject)">
                <div class="col-lg-12 form-group" style="background-color: honeydew">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Name :</span>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" ng-model="riderObject.fullname" class="form-control"   required/>
                    </div>
                </div>
                <div class="col-lg-12 form-group"style="background-color: 	#FFF0F5">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Father Name :</span>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" ng-model="riderObject.father_name" class="form-control"   required/>
                    </div>
                </div>



                <div class="col-lg-12 form-group">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Usename :</span>
                    </div>

                    <div class="col-lg-8">
                        <!--<input type="text" ng-model="riderObject.userName" class="form-control"   required/>-->

                        <input type="text" ng-change="changeRiderUserName(riderObject.userName , riderObject.rider_id)" ng-model="riderObject.userName" class="form-control"   required/>
                        <span ng-show="riderUserNameCheck" style="color: green">This user name is Allready Exist</span>
                    </div>

                </div>

                <div class="col-lg-12 form-group" style="background-color: honeydew">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Password :</span>
                    </div>

                    <div class="col-lg-8">
                        <input type="text" ng-model="riderObject.password" class="form-control"   required/>
                    </div>

                </div>
                <div class="col-lg-12 form-group" style="background-color: 	#FFF0F5">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">CNIC No. :</span>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" ng-model="riderObject.cnic" class="form-control"   required/>
                    </div>
                </div>
                <div class="col-lg-12 form-group">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Address :</span>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" ng-model="riderObject.address" class="form-control"   required/>
                    </div>
                </div>
                <div class="col-lg-12 form-group">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Zone :</span>
                    </div>

                    <div class="col-lg-8">

                        <div class="col-lg-4" ng-repeat="zone in riderObject.zone">

                            <label class="checkbox-inline">
                                <input type="checkbox" ng-model="zone.isselected">{{zone.name}}
                            </label>
                        </div>
                    </div>

                </div>

                <div class="col-lg-12 form-group" style="background-color: honeydew">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Cell No 1 :</span>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" ng-model="riderObject.cell_no_1" class="form-control"   required/>
                    </div>

                </div>

                <div class="col-lg-12 form-group" style="background-color: 	#FFF0F5">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Cell No 2 :</span>
                    </div>

                    <div class="col-lg-8">
                        <input type="text" ng-model="riderObject.cell_no_2" class="form-control"   />
                    </div>

                </div>

                <div class="col-lg-12 form-group">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Residence Phone No</span>
                    </div>

                    <div class="col-lg-8">
                        <input type="text" ng-model="riderObject.residence_phone_no" class="form-control"   />
                    </div>

                </div>
                <div class="col-lg-12 form-group" style="background-color: honeydew">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Email</span>
                    </div>

                    <div class="col-lg-8">
                        <input type="text" ng-model="riderObject.email" class="form-control"   required/>
                    </div>
                </div>

                <div class="col-lg-12 form-group" style="background-color: honeydew">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">POS :</span>
                    </div>

                    <div class="col-lg-8">
                        <select class="form-control" ng-model="riderObject.pos_shop_id">
                            <option value="0">Select Shop</option>
                            <option value="{{list.pos_shop_id}}" ng-repeat="list in  posShopList">{{list.shop_name}}</option>
                        </select>
                    </div>
                </div>

                <div class="col-lg-12 form-group" style="background-color: honeydew">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Show Customer in App</span>
                    </div>

                    <div class="col-lg-8">
                        <select class="form-control" ng-model="riderObject.show_customers_in_app">
                            <option value="1">All Customer</option>
                            <option value="0">Delivered Customer</option>
                        </select>


                    </div>
                </div>

                <div class="col-lg-12 form-group">
                    <div class="col-lg-8" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Status :</span>
                    </div>
                    <div class="col-lg-4">

                        <label class="radio-inline">
                            <input ng-model="riderObject.is_active"   type="radio" name="status" value="1" required>
                            <span  class="label label-default">Active</span>
                        </label>
                        <label class="radio-inline">
                            <input ng-model="riderObject.is_active" type="radio" name="status" value="0" required>
                            <span  class="label label-primary">Inactive</span>
                        </label>
                    </div>
                </div>
                <div class="col-lg-12 form-group">
                    <div class="col-lg-8" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Allow rider to collect payment using rider app? :</span>
                    </div>
                    <div class="col-lg-4">

                        <label class="radio-inline">
                            <input ng-model="riderObject.can_add_payment"   type="radio" name="payment" value="1" required>
                            <span  class="label label-default">Active</span>
                        </label>
                        <label class="radio-inline">
                            <input ng-model="riderObject.can_add_payment" type="radio" name="payment" value="0" required>
                            <span  class="label label-primary">Inactive</span>
                        </label>
                    </div>
                </div>
                <div class=" form-group ">
                    <button ng-disabled="allow_delete[3]" type="submit" class="btn-success  btn-sm">Update</button>
                    <button ng-disabled="allow_delete[3]" type="button" ng-click="resetObject()" class="btn-success  btn-sm">Reset</button>
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                </div>
            </form>
        </modal>

        <!-- end: Edit Zone -->
    </div>
</div>

