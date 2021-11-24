<style>
    .angularjs-datetime-picker{
        z-index: 99999 !important;
    }
</style>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/makePayment/paymentransfercollectionvaultoother_form_grad.js"></script>
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

                <label for="email" style="font-weight: bold;">Collection Vault From:</label>
                <select ng-change="get_payment_list_function();"  class="form-control" ng-model="main_object.collection_vault_id_from" required>
                    <option value="">Select</option>
                    <option ng-repeat="list in collectionvault" value="{{list.collection_vault_id}}">{{list.collection_vault_name}}</option>
                </select>

            </div>
            <div class="form-group col-md-12">

                <label for="email" style="font-weight: bold;">Collection Vault To:</label>
                <select  class="form-control" ng-model="main_object.collection_vault_id_to" required>
                    <option value="">Select</option>
                    <option ng-repeat="list in collectionvault" value="{{list.collection_vault_id}}">{{list.collection_vault_name}}</option>
                </select>

            </div>




            <div class="form-group col-md-12">
                <label style="font-weight: bold;" for="pwd">Date:</label>
                <input class="form-control" datetime-picker="" date-only="" date-format="yyyy-MM-dd" type="text" required="" ng-model="main_object.action_date" required>

            </div>

            <div class="form-group col-md-12">
                <label style="font-weight: bold;" for="pwd">Amount :</label>
                <input type="text" class="form-control"  placeholder="amount"  ng-model="main_object.amount" required>
            </div>


            <div class="form-group col-md-12">
                <label style="font-weight: bold;" for="pwd">Remarks:</label>
                <input type="text" class="form-control"  placeholder="Remarks"  ng-model="main_object.remarks" required>
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
                    <th>Collection Vault From</th>
                    <th>Collection Vault To</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Remarks</th>
                   <th style="width: 80px"></th>
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="list in payment_list">
                    <td>
                        <span ng-bind="$index+1"></span> </td>
                    <td>
                        <span ng-show="!list.update" ng-bind="list.collection_vault_name_from"></span>
                        <span ng-show="list.update">
                                 <select  class="form-control" ng-model="list.collection_vault_id_from" required>
                                            <option value="">Select</option>
                                            <option ng-repeat="list in collectionvault" value="{{list.collection_vault_id}}">{{list.collection_vault_name}}</option>
                                 </select>
                        </span>
                    </td>
                    <td>

                        <span ng-show="!list.update" ng-bind="list.collection_vault_name_to"></span>
                        <span ng-show="list.update">
                                 <select  class="form-control" ng-model="list.collection_vault_id_to" required>
                                            <option value="">Select</option>
                                            <option ng-repeat="list in collectionvault" value="{{list.collection_vault_id}}">{{list.collection_vault_name}}</option>
                                 </select>
                        </span>
                    </td>

                    <td>

                        <span ng-show="!list.update" ng-bind="list.action_date"></span>
                        <span ng-show="list.update">
                            <input class="form-control" datetime-picker="" date-only="" date-format="yyyy-MM-dd" type="text" required="" ng-model="list.action_date" required>
                        </span>
                    </td>

                    <td style="text-align: right">

                        <span ng-show="!list.update" ng-bind="list.amount"></span>
                        <span ng-show="list.update">
                              <input class="form-control"   type="text" required="" ng-model="list.amount" required>
                        </span>
                    </td>
                    <td>
                        <span ng-show="!list.update" ng-bind="list.remarks"></span>
                        <span ng-show="list.update">
                              <input class="form-control"   type="text" required="" ng-model="list.remarks" required>
                        </span>

                    </td>


                    <td>
                        <button title="Edit" ng-show="!list.update"  ng-click="edit_payment(list)"  class="btn btn-sm btn-default next btn-xs" > <i class="fa fa-edit "></i> </button>
                        <button   title="Save" ng-show="list.update"  ng-click="save_payment(list)"  class="btn btn-sm btn-default next btn-xs" > <i class="fa fa-save "></i> </button>
                        <button ng-disabled=" allow_delete[2]" ng-click="delete_new_payment(list)"  class="btn btn-sm btn-info next btn-xs" > <i class="fa fa-trash "></i></button>
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