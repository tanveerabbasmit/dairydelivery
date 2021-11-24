<style>
    .angularjs-datetime-picker{
        z-index: 99999 !important;
    }
</style>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/makePayment/paymentnew_form_grad.js"></script>
<link href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css" />


<link href="<?php echo Yii::app()->theme->baseUrl; ?>/js/monthPickerFile/examples.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/js/monthPickerFile/MonthPicker.min.css" rel="stylesheet" type="text/css" />


<!------ Include the above in your HEAD tag ---------->

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>



<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>




<div class="panel row" id="testContainer" style=" display: none; background-color: white !important;padding: 10px" ng-app="clintManagemaent" ng-controller="clintManagemaent" ng-init='init(<?php echo $data; ?>)'>


       <div class="col-md-5">
                <div class="form-group col-md-12 ">
                    <div class="form-group col-md-12 ">
                        <h2><span ng-bind="form_name"></span></h2>
                    </div>

                </div>
                 <form ng-submit="save_payment_function()">
                     <div class="form-group col-md-12">
                         <label for="email" style="font-weight: bold;">Collection Vault:</label>
                         <select  class="form-control" ng-model="main_object.collection_vault_id" required>
                             <option value="">Select</option>
                             <option ng-repeat="list in collectionvault" value="{{list.collection_vault_id}}">{{list.collection_vault_name}}</option>
                         </select>

                     </div>
                     <div class="form-group col-md-12">

                         <label for="email" style="font-weight: bold;">Party Type:</label>
                         <select id="" ng-change="change_party_type_function(main_object.vendor_type_id)" class="form-control" ng-model="main_object.vendor_type_id" required>
                             <option value="">Select</option>
                             <option ng-repeat="list in vendor_type" value="{{list.vendor_type_id}}">{{list.vendor_type_name}}</option>
                             <option value="3">Farm</option>
                         </select>
                     </div>

                     <div class="form-group col-md-12">

                         <label for="email" style="font-weight: bold;">Pay to party:</label>

                         <select id="change_party_id" ng-change="get_payment_list_function()"  id="vendor_id_value" ng-model="main_object.vendor_id" class="form-control select2 input-sm" style="width: 100%;">
                             <option value="">Select</option>
                             <option ng-repeat="list in main_list" value="{{list.vendor_id}}">{{list.vendor_name}}</option>
                         </select>


                     </div>



                     <div class="form-group col-md-12">
                         <label style="font-weight: bold;" for="pwd">Date:</label>
                         <input class="form-control" datetime-picker="" date-only="" date-format="yyyy-MM-dd" type="text" required="" ng-model="main_object.date" required>

                     </div>

                     <div class="form-group col-md-12">
                         <label for="email" style="font-weight: bold;">Transaction Type:</label>
                         <select class="form-control" ng-model="main_object.transaction_type" required>
                             <option value="">Select</option>
                             <option value="Liability">Liability</option>
                             <option value="Expense">Expense</option>
                             <option value="Asset">Asset</option>
                         </select>
                     </div>

                     <div class="form-group col-md-12">

                         <label for="email" style="font-weight: bold;">Head:</label>
                         <select class="form-control" ng-model="main_object.expence_type" required>
                             <option value="">Select</option>
                             <option value="{{list.expence_type}}" ng-repeat="list in get_expence_list">{{list.type}}</option>

                         </select>

                     </div>




                     <div class="form-group col-md-12">
                         <label style="font-weight: bold;" for="pwd">Amount Paid :</label>
                         <input type="text" class="form-control" id="pwd" placeholder="Enter Amount" ng-model="main_object.amount_paid" required>
                     </div>

                     <div class="form-group col-md-12">
                         <label style="font-weight: bold;" for="pwd">Reference No. :</label>
                         <input type="text" class="form-control"  placeholder="Reference No."  ng-model="main_object.reference_no" required>
                     </div>
                     <div class="form-group col-md-12">
                         <button ng-disabled="loading" type="submit" class="btn btn-success">Submit</button>

                         <img ng-show="loading" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
                     </div>

                 </form>
       </div>

    <style>
        table, td, th {
            border: 1px solid black;
            padding: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
    </style>

        <div ng-show="payment_list.length>0" class=" col-md-12" style="margin-top: 10px">


            <div class=" col-md-12 ">

                <table class="" >
                    <thead>
                    <tr style="background-color: BlanchedAlmond">
                        <th>#</th>
                        <th>Date</th>
                        <th>Vault</th>
                        <th>Head</th>
                        <th>Transaction Type</th>
                        <th>Reference No.</th>
                        <th>Amount</th>

                        <th style="width: 80px"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="list in payment_list">
                        <td>
                            <span ng-bind="$index+1"></span> </td>
                        <td>
                            <span ng-show="!list.update" ng-bind="list.date"></span>
                            <span ng-show="list.update">
                                 <input class="form-control" datetime-picker="" date-only="" date-format="yyyy-MM-dd" type="text" required="" ng-model="list.date" required>
                            </span>
                        </td>
                        <td>

                            <span ng-show="!list.update" ng-bind="list.collection_vault_name"></span>
                            <span ng-show="list.update">
                                    <select  class="form-control" ng-model="list.collection_vault_id" required>
                                            <option value="">Select</option>
                                            <option ng-repeat="list in collectionvault" value="{{list.collection_vault_id}}">{{list.collection_vault_name}}</option>
                                    </select>
                            </span>
                        </td>

                        <td>

                            <span ng-show="!list.update" ng-bind="list.type"></span>
                            <span ng-show="list.update">
                                    <select  class="form-control" ng-model="list.expence_type" required>
                                            <option value="0">Select</option>
                                            <option ng-repeat="list in get_expence_list" value="{{list.expence_type}}">{{list.type}}</option>
                                    </select>
                            </span>
                        </td>
                        <td>
                            <span ng-show="!list.update" ng-bind="list.transaction_type"></span>

                            <select ng-show="list.update" class="form-control" ng-model="list.transaction_type" >
                                <option value="">Select</option>
                                <option value="Liability">Liability</option>
                                <option value="Expence">Expence</option>
                                <option value="Asset">Asset</option>
                            </select>
                        </td>
                        <td>
                            <span  ng-show="!list.update"  ng-bind="list.reference_no"></span>
                            <input ng-show="list.update" type="text" class="form-control" ng-model="list.reference_no">

                        </td>
                        <td style="text-align: center">
                            <span ng-show="!list.update" ng-bind="list.amount_paid"></span>
                            <input ng-show="list.update" type="text" class="form-control" ng-model="list.amount_paid">
                        </td>

                        <td>
                            <button title="Edit" ng-show="!list.update"  ng-click="edit_payment(list)"  class="btn btn-sm btn-default next btn-xs" > <i class="fa fa-edit "></i> </button>
                            <button   title="Save" ng-show="list.update"  ng-click="save_payment(list)"  class="btn btn-sm btn-default next btn-xs" > <i class="fa fa-save "></i> </button>
                            <button ng-disabled=" allow_delete[2]" ng-click="delete_new_payment(list)"  class="btn btn-sm btn-info next btn-xs" > <i class="fa fa-trash "></i></button>
                        </td>

                    </tr>
                    <tr>
                        <td colspan="8" style="text-align: center">
                            <a href="" style="float: left" ng-disabled="true" ng-click="get_payment_list_function_perious()">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                            </a>
                            <a href="" >
                                Pgae->{{page+1}}
                            </a>
                          <a href="" ng-click="get_payment_list_function_next()" style="float: right">
                              <i class="fa fa-arrow-right" aria-hidden="true"></i>
                          </a>

                        </td>

                    </tr>

                    </tbody>
                </table>
            </div>

        </div>


        <style>
            .dropdown.dropdown-scroll .dropdown-menu {
                max-height: 200px;
                width: 60px;
                overflow: auto;
            }
        </style>
</div>


    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/monthPickerFile/examples.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/monthPickerFile/MonthPicker.min.js"></script>

<style>

    element.style {

        left: 11px !important;

    }
</style>
<script>
    $('.select2').select2();

</script>

<style>
    .select2-selection--single {
        height: 33px !important;
    }
</style>