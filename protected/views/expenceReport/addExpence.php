<style xmlns="http://www.w3.org/1999/html">
    .angularjs-datetime-picker{
        z-index: 99999 !important;
    }
</style>

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/addExpence/addExpence-grid.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/FileSaver/FileSaver.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">uuuuu</div>
    </div>
</div>

<?php $allow_delete = crudRole::getCrudrole(40); ?>

<div  id="testContainer" style="display: none" class="panel row" ng-app="riderDailyStockGridModule">
    <div ng-controller="manageZone" ng-init='init(<?php echo $allow_delete ?>, <?php echo str_replace("'","/",$todayDate);  ?> , <?php echo str_replace("'","/",$expencetype);    ?> , <?php echo str_replace("'","/",$expenceRecord);   ?> , "<?php echo Yii::app()->createAbsoluteUrl('ExpenceReport/saveNewExpence'); ?>","<?php echo Yii::app()->createAbsoluteUrl('ExpenceReport/editRider'); ?>","<?php echo Yii::app()->createAbsoluteUrl('ExpenceReport/delete'); ?>"
    ,"<?php echo Yii::app()->createAbsoluteUrl('ExpenceReport/getZoneAgainstRider'); ?>","<?php echo Yii::app()->createAbsoluteUrl('ExpenceReport/checkDuplicateRiderUseName'); ?>")'>
        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                    Manage Expense
                </a>
            </li>
        </ul>

        <div class="row" style="margin: 5px">
            <div class="col-lg-2">
                <button ng-disabled="allow_delete[1]" type="button" ng-click="addnewRider(1,'')" class="btn btn-primary btn-sm"> <i class="fa fa-plus"></i> Add New</button>
                <!-- <button class="btn btn-primary" ng-click="addNewClient()" data-toggle="modal" data-target="#addNewStockModel"><i class="fa fa-plus"></i> Add New Customer</button>-->
            </div>
            <div class="col-lg-2">
                <select class="form-control input-sm" ng-model="searchObject.expenses_type_id" required>
                    <option value="" >All Type</option>
                    <option value="{{list.expence_type}}" ng-repeat="list in expencetype">{{list.type}}</option>
                </select>
            </div>
            <div class="col-lg-8">
                <input style="float: left ; width: 30% ;margin-left: 5px" class="form-control input-sm " datetime-picker="" date-only="" date-format="yyyy-MM-dd" type="text" required="" ng-model="searchObject.startDate" size="2">
                <button class="btn btn-info btn-sm" style="float: left"> <span style="margin-top: 7px;font-size: 14px">To </span></button>
                <input style="width: 30% ; float: left" class="form-control input-sm " datetime-picker="" date-only="" date-format="yyyy-MM-dd" type="text" required="" ng-model="searchObject.endDate" size="2">
                <button class="btn btn-primary btn-sm" style="float: left" ng-click="geexpenceListFunction()">  <span class="glyphicon glyphicon-search"><span style="font-size: 14px; margin-top: 5px" > Search </span> </span></button>
                <button class="btn btn-info btn-sm" style="float: left; margin-left: 5px" onclick="javascript:xport.toCSV('customers');"> <i class="fa fa-share"></i> Export</button>
                <img ng-show="imageLoader" style="margin: 5px" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            </div>

        </div>

        <div  style="margin: 10px">



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
                    <tr ng-show="false">
                        <td></td>
                        <td>Type</td>
                        <td>{{selecedExpenceName}}</td>
                    </tr>
                    <tr ng-show="false">
                        <td></td>
                        <td>Start Date</td>
                        <td>{{searchObject.startDate}}</td>
                    </tr>
                    <tr ng-show="false">
                        <td></td>
                        <td>End Date</td>
                        <td>{{searchObject.endDate}}</td>
                    </tr>
                    <tr style="background-color: #F0F8FF">
                        <th> <a href="#">#</a> </th>
                        <th><a href="#">Expense Type</a></th>
                        <th><a href="#">Activity</a> </th>
                        <th><a href="#">Date</a> </th>

                        <th><a href="#">Remarks </a></th>
                       <!-- <th>Delete</th>-->
                      <!--  <th>Zone </th>-->
                        <th style="text-align: center"><a href="#">Amount</a></th>
                        <th>

                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="rider in riderList | filter:search:stric track by $index">
                        <td>{{$index + 1}}</td>
                        <td>{{rider.type}}</td>
                        <td>{{rider.activity}}</td>
                        <td>{{rider.date}}</td>
                        <td>{{rider.remarks}}</td>
                        <td style="text-align: right">{{rider.amount | number :2}}</td>
                        <td>
                            <button title="Delete "  type="button" ng-click="deleteexpence(rider)" class="btn btn-primary btn-xs"><i style="margin: 2px" class="fa fa-trash  "></i></button>
                            <button title="Edit" type="button" ng-click="addnewRider(2,rider)" class="btn btn-default btn-xs"><i style="margin: 2px" class="fa fa-edit  "></i></button>
                          <!--  <button title="Save" type="button" ng-click="editClint(rider)" class="btn btn-default btn-xs"><i style="margin: 2px" class="fa fa-save"></i></button>-->

                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Total</td>
                        <td style="text-align: right">{{total | number:2}}</td>
                        <td>

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
        <modal title="Expence" visible="showAddNewRider">


                <form role="form" class="form-group" ng-submit="saveRider(riderObject)">

                    <div class="col-lg-12 form-group" style="background-color: honeydew">
                        <div class="col-lg-4" style="padding: 10px">
                            <span style="font-weight: bold;font-size: 13px; padding: 8px">Expense Type:</span>
                        </div>
                        <div class="col-lg-8">

                            <select class="form-control" ng-model="riderObject.expence_type" required>
                                <option value="" >Select Type</option>
                                <option value="{{list.expence_type}}" ng-repeat="list in expencetype">{{list.type}}</option>
                            </select>
                        </div>

                    </div>
                    <div class="col-lg-12 form-group" style="background-color: 	#FFF0F5">
                        <div class="col-lg-4" style="padding: 10px">
                            <span style="font-weight: bold;font-size: 13px; padding: 8px">Activity :</span>
                        </div>

                        <div class="col-lg-8">
                            <input type="text" ng-model="riderObject.activity" class="form-control"   required/>
                        </div>

                    </div>

                    <div class="col-lg-12 form-group">
                        <div class="col-lg-4" style="padding: 10px">
                            <span style="font-weight: bold;font-size: 13px; padding: 8px">Date :</span>
                        </div>

                        <div class="col-lg-8">
                            <input type="text" datetime-picker=""   ng-model="riderObject.date" class="form-control"  date-only="" date-format="yyyy-MM-dd" class="form-control ng-pristine ng-valid ng-not-empty ng-valid-date ng-touched"  required/>

                        </div>

                    </div>

                    <div class="col-lg-12 form-group" style="background-color: honeydew">
                        <div class="col-lg-4" style="padding: 10px">
                            <span style="font-weight: bold;font-size: 13px; padding: 8px">Remarks:</span>
                        </div>

                        <div class="col-lg-8">
                            <input type="text" ng-model="riderObject.remarks" class="form-control"   required/>
                        </div>

                    </div>

                    <div class="col-lg-12 form-group" style="background-color: 	#FFF0F5">
                        <div class="col-lg-4" style="padding: 10px">
                            <span style="font-weight: bold;font-size: 13px; padding: 8px">Amount:</span>
                        </div>

                        <div class="col-lg-8">
                            <input type="number" ng-model="riderObject.amount" class="form-control"   required/>
                        </div>

                    </div>

                    <div class=" form-group " >
                        <button ng-disabled="riderUserNameCheck" type="submit" class="btn-success  btn-sm">{{title_name}}</button>
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
                    <button type="submit" class="btn-success  btn-sm">Update</button>
                    <button type="button" ng-click="resetObject()" class="btn-success  btn-sm">Reset</button>
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                </div>
            </form>
        </modal>

        <!-- end: Edit Zone -->
    </div>
</div>

