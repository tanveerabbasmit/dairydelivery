

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/Userallowdeliverypayment/manageZone_delete_option_grid.js"></script>

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

    <div ng-controller="manageZone" ng-init='init(<?php echo $allow_delete ?>,<?php echo $zoneList ?>,  <?php echo $companyBranchList ?> ,"<?php echo Yii::app()->createAbsoluteUrl('Userallowdeliverypayment/saveNewZone'); ?>","<?php echo Yii::app()->createAbsoluteUrl('Userallowdeliverypayment/editZone'); ?>","<?php echo Yii::app()->createAbsoluteUrl('Userallowdeliverypayment/delete'); ?>","<?php echo Yii::app()->createAbsoluteUrl('Userallowdeliverypayment/saveRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('Userallowdeliverypayment/deleteRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('Userallowdeliverypayment/search'); ?>")'>
        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                   Advanced Rights
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
                        <th width="40%"><a href="#">USer</a></th>
                        <th width="20%"><a href="#">Phone Number</a></th>
                        <th width="20%"><a href="#">Role Name</a></th>
                        <th style="text-align: center"><a href="#">Cutomer Receipt</a></th>
                        <th style="text-align: center"><a href="#">Delivery</a></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="zone in zoneList track by $index">
                        <td>{{$index + 1}}</td>
                        <td>{{zone.full_name}}</td>
                        <td>{{zone.phone_number}}</td>
                        <td>{{zone.role_name}}</td>
                        <td width="150px" style="width: 250px">
                            <label class="checkbox-inline">
                                <input ng-change="change_allow_option(zone,'receipt_add')" ng-model="zone.receipt_add" type="checkbox" value="">Add
                            </label>
                            <label class="checkbox-inline" style="margin-left: 0px">
                                <input ng-change="change_allow_option(zone,'receipt_edit')" ng-model="zone.receipt_edit" type="checkbox" value="">Edit
                            </label>
                            <label class="checkbox-inline" style="margin-left: 0px">
                                <input ng-change="change_allow_option(zone,'receipt_delete')" ng-model="zone.receipt_delete" type="checkbox" value="">Delete
                            </label>

                        </td>
                        <td>
                            <label class="checkbox-inline">
                                <input ng-change="change_allow_option(zone,'delivery_add')" ng-model="zone.delivery_add" type="checkbox" value="">Add
                            </label>
                            <label class="checkbox-inline" style="margin-left: 0px">
                                <input ng-change="change_allow_option(zone,'delivery_edit')" ng-model="zone.delivery_edit" type="checkbox" value="">Edit
                            </label>
                            <label class="checkbox-inline" style="margin-left: 0px">
                                <input ng-change="change_allow_option(zone,'delivery_delete')" ng-model="zone.delivery_delete" type="checkbox" value="">Delete
                            </label>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <div style="margin-top: 10px" ng-repeat="main in rider_list">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <span style="font-weight: bold;font-size: 130%">Edit Delivery Right </span>({{main.user.full_name}})
                        </div>
                        <div class="panel-body">
                            <table class="table">
                                <thead>
                                  <tr style="background-color: white">
                                    <th>Rider</th>
                                    <th>Allow Add</th>
                                    <th>Add for past days</th>
                                    <th>Allow Edit</th>
                                    <th>Edit for past days</th>
                                    <th>Allow Delete</th>
                                    <th>Delete For past days</th>
                                    <th>Password</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="list in main.rider">

                                    <td style="background-color: white">

                                        {{list.fullname}}
                                    </td>

                                    <td style="background-color: white" style="text-align: center">
                                        <label class="checkbox-inline">
                                            <input ng-disabled="!list.update"  ng-model="list.allow_add" type="checkbox" value="">Add
                                        </label>
                                    </td>

                                    <td style="background-color: white">
                                        <input ng-disabled="!list.update" style="width: 60px"  type="text" ng-model="list.add_past_days">
                                    </td>

                                    <td style="background-color: white" style="text-align: center">
                                        <label class="checkbox-inline">
                                            <input ng-disabled="!list.update"  ng-model="list.allow_edit" type="checkbox" value="">Edit
                                        </label>
                                    </td>


                                    <td style="background-color: white">
                                        <input ng-disabled="!list.update" style="width: 80px" type="text" ng-model="list.edit_past_days">
                                    </td>


                                    <td style="background-color: white">
                                        <label class="checkbox-inline">
                                            <input  ng-disabled="!list.update" ng-model="list.allow_delete" type="checkbox" value="">Delete
                                        </label>
                                    </td>


                                    <td style="background-color: white">
                                        <input ng-disabled="!list.update" style="width: 80px" type="text" ng-model="list.delete_past_days">
                                    </td>

                                    <td style="background-color: white">
                                        <input ng-disabled="!list.update" type="text" ng-model="list.password_for_edit_delete">
                                    </td>

                                    <!--<td style="background-color: white">
                                        <input style="width: 80px" type="text" ng-model="list.edit_past_days">
                                    </td>-->


                                    <td style="background-color: white">
                                        <a   ng-show="list.update" ng-click="save_rider_change_right(main,list)" href="" class="label label-info"> <span >Save</span></a>
                                        <a   ng-show="!list.update" ng-click="update_right_function(main,list)" href="" class="label label-info"> <span >update</span></a>
                                      <!--  <a ng-show="list.update_right && list.update"  class="label label-info"  ng-click="save_rider_change_right(main,list)" href="">
                                            <img src="<?php /*echo Yii::app()->theme->baseUrl; */?>/images/loader-transparent.gif" alt="" class="loading">
                                        </a>-->
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


            </div><!-- table-responsive -->
        </div>




    </div>
</div>

