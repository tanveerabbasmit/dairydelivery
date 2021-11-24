<style>
    .angularjs-datetime-picker{
        z-index: 99999 !important;
    }
</style>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/makePayment/receipt_form_grid.js"></script>
<link href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css" />


<link href="<?php echo Yii::app()->theme->baseUrl; ?>/js/monthPickerFile/examples.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/js/monthPickerFile/MonthPicker.min.css" rel="stylesheet" type="text/css" />
<script src="https://code.jquery.com/jquery-1.12.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>



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
                        <h2>Receipt Form</h2>
                    </div>

                </div>
                 <form ng-submit="save_payment_function()">
                     <div class="form-group col-md-12">


                         <label for="email" style="font-weight: bold;">Type:</label>
                         &nbsp;&nbsp;&nbsp; &nbsp;
                         <label class="radio-inline">
                             <input ng-click="change_type_function(1)" ng-model="main_object.type" type="radio" name="type" value="receipt_vendor" >Vendor
                         </label>
                         <label class="radio-inline">
                             <input ng-click="change_type_function(2)" ng-model="main_object.type" type="radio" name="type" value="other_income_source">Other Income Source
                         </label>
                        

                     </div>

                     <div class="form-group col-md-12">

                         <label for="email" style="font-weight: bold;">Pay to party:</label>
                         <select ng-change="change_party_function()" class="form-control" ng-model="main_object.pay_to_party_id" required>
                             <option value="">Select</option>
                             <option ng-repeat="list in main_list" value="{{list.id}}">{{list.name}}</option>
                         </select>

                     </div>

                     <div class="form-group col-md-12">
                         <label for="email" style="font-weight: bold;">Payment Type:</label>
                         <select class="form-control" ng-model="main_object.payment_type_id" required>
                             <option value="">Select</option>
                             <option value="Expense">Expense</option>
                             <option value="Purchase">Purchase</option>
                             <option value="Other">Other</option>
                         </select>

                     </div>

                     <div class="form-group col-md-12">
                         <label style="font-weight: bold;" for="pwd">Date:</label>
                         <input class="form-control" datetime-picker="" date-only="" date-format="yyyy-MM-dd" type="text" required="" ng-model="main_object.date" required>

                     </div>

                     <div class="form-group col-md-12">
                         <label for="email" style="font-weight: bold;">Head:</label>
                         <select class="form-control" ng-model="main_object.head" required>
                             <option value="">Select</option>
                             <option value="Expense">Expense</option>
                             <option value="Salary">Salary</option>
                         </select>

                     </div>

                     <div class="form-group col-md-12">
                         <label for="email" style="font-weight: bold;">Mode:</label>
                         <select ng-model="main_object.payment_mode" class="form-control" required>
                             <option value="">Select</option>
                             <option value="2">cheque</option>
                             <option value="3">Cash</option>
                             <option value="5">Bank Transaction</option>
                             <option value="6">Card Transaction</option>
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
      a              <tr style="background-color: BlanchedAlmond">
                        <th>#</th>
                        <th>Received Date</th>
                        <th>Refernce No.</th>
                        <th>Received Amount</th>
                        <th>Mode</th>
                       <!-- <th>Action</th>-->

                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="list in payment_list">
                        <td><span ng-bind="$index+1"></span> </td>
                        <td><span ng-bind="list.action_date"></span> </td>
                        <td><span ng-bind="list.reference_no"></span> </td>
                        <td style="text-align: center"><span ng-bind="list.amount"></span> </td>
                        <td><span ng-bind="list.payment_mode"></span> </td>

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