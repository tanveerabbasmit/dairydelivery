

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/vendor/voucher_account_grid.js"></script>

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

    <div ng-controller="manageZone" ng-init='init(<?php echo $allow_delete ?>,<?php echo $zoneList ?>,  <?php echo $companyBranchList ?> ,"<?php echo Yii::app()->createAbsoluteUrl('account/Assignaccount/base'); ?>","<?php echo Yii::app()->createAbsoluteUrl('employee/edit_vendor'); ?>","<?php echo Yii::app()->createAbsoluteUrl('employee/delete'); ?>","<?php echo Yii::app()->createAbsoluteUrl('employee/saveRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('employee/deleteRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('employee/search'); ?>")'>
        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                    Voucher Account
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
                        <th><a href="#">Voucher Type Name</a></th>
                        <th><a href="#">Debit Account Name</a></th>
                        <th><a href="#">Credit Account Name</a></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                         <tr ng-repeat="list in voucher_type">
                             <td><span ng-bind="$index+1"></span></td>
                             <td><span ng-bind="list.name"></span></td>
                             <td>
                                 <select class="form-control" ng-disabled="!list.update" ng-model="list.debit_account_id">
                                     <option value="0">Select</option>
                                     <option ng-repeat="acc  in account_list" value="{{acc.id}}">{{acc.name}} </option>
                                 </select>
                             </td>
                             <td>

                                 <select class="form-control" ng-disabled="!list.update" ng-model="list.credit_account_id">
                                     <option value="0">Select</option>
                                     <option ng-repeat="acc  in account_list" value="{{acc.id}}">{{acc.name}} </option>
                                 </select>
                             </td>
                             <td>
                                 <button ng-show="list.update"  ng-click="save_account_function(list)" class="btn btn-sm btn-info next btn-xs"> <i class="fa fa-save"></i> </button>
                                 <button ng-show="!list.update"  ng-click="edit_account(list)" class="btn btn-sm btn-default next btn-xs"> <i class="fa fa-edit"></i> </button>
                             </td>
                         </tr>
                    </tbody>
                </table>
            </div>
        </div>





    </div>
</div>

