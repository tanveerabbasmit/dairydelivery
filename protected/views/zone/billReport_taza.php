<style>
    .angularjs-datetime-picker{
        z-index: 99999 !important;
    }
</style>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/billReport/billReport-grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>


<div class="panel row" id="testContainer" style="display: none" ng-app="clintManagemaent">
    <div ng-controller="clintManagemaent" ng-init='init(<?php echo $LableList  ?> , <?php echo $productList ?> , <?php echo $clientList ?> , "<?php echo Yii::app()->createAbsoluteUrl('index.php/client/getClientLedgherReport_bill'); ?>", "<?php echo Yii::app()->createAbsoluteUrl('index.php/client/oneCustomerAmountListallCustomerList'); ?>")'>

        <div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <strong>message! </strong>
            {{taskMessage}}
        </div>
        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                    Bill Report Taza Farm
                </a>
            </li>
        </ul>

        <div class="col-sm-12 row" style="margin-top: 12px">
             <div style="float: left">
                 <button class="btn btn-default dropdown-toggle" ng-click="showDropDownList()" type="button" id="dropdownMenu1" data-toggle="dropdown">{{SelectedCustomer}} <span  class="caret"></span> </button>
                 <ul style="width: 200px ;max-height: 500px ; overflow-x: hidden;overflow-y: scroll;" class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1" >
                     <li role="presentation">
                         <div class="input-group input-group-sm search-control"> <span class="input-group-addon">
                </span>
                             <input autofocus type="text" class="form-control" placeholder="Search" ng-model="query" id="serachCustomerBar">
                         </div>
                     </li >
                     <li  role="presentation" ng-click="abcd(item)" ng-repeat='item in clientList | filter:query'> <a href="#"> {{item.fullname}} </a>
                     </li>
                 </ul>
            </div>
            <input style="float: left ; width: 20% ;margin-left: 5px" class="form-control"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">
            <button class="btn btn-info" style="float: left">To</button>
            <input style="width: 20% ; float: left"  class="form-control"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">
            <button class="btn btn-primary" style="float: left" ng-click="getCustomerLedgerReportFunction()">  <span class="glyphicon glyphicon-search"> Search</button>
            <button ng-disabled="false" class="btn btn-primary" style="float: left;margin-left:  12px" ng-click="printMonthlyRepot()"><span class="glyphicon glyphicon-print"></span> Print</button>
            <img style="margin: 4px" ng-show="reportLoader"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading.gif" alt="" class="loading">
       </div>
        <div class="col-sm-12" style="margin-top: 8px; margin-bottom: 5px">
        </div>
        <table  class="table table-striped nomargin" >
            <thead>
            <tr>
                 <th></th>
                <th colspan="3" ng-repeat="product in productList">{{product.name}}</th>
            </tr>
            </thead>
            <tbody >
                <tr>
                    <td>Date</td>
                    <td>Rate</td>
                    <td  ng-repeat="product in LableList track by $index">
                        {{product}}
                    </td>
                </tr>
                <tr ng-repeat="list in responceData">
                   <td ng-repeat="x in list track by $index">{{x | number :2}}</td>
                </tr>
                 <tr>
                     <th colspan="2">Total</th>
                     <th>{{totalQuantity | number :2}}</th>
                     <th>{{totalAmount | number :2}}</th>
                 </tr>
             </tbody>
        </table>

        <div id="printForm" ng-show="false">
             <table border="1px" width="100%" style="border-collapse: collapse;border: 1px solid black; ">
                 <tr>
                     <td colspan="4" style="text-align: center" style="border: 1px solid black; ">
                         <?php
                                $company_id = Yii::app()->user->getState('company_branch_id');
                                $companyObject = Company::model()->findByPk(intval($company_id));
                                echo "<h2 style='margin: 10px'>".$companyObject['company_title']."</h2>";
                         ?>
                     </td>
                 </tr>

                 <tr>
                    <td  style="text-align: center" style="border: 1px solid black; ">
                        Billing Period
                    </td>
                     <td style="text-align: center" style="border: 1px solid black;">
                         MONTH OF
                     </td>
                     <td colspan="2" style="border: 1px solid black;">
                         {{startDate}}-to-{{endDate}}
                     </td>
                 </tr>
                 <tr>
                     <td>
                     </td>
                     <td>
                        Name
                     </td>
                     <td colspan="2">
                         {{SelectedCustomer}}
                     </td>
                 </tr>
                 <tr>
                     <td>
                     </td>
                     <td>
                         Address
                     </td>
                     <td colspan="2">
                         {{address}}
                     </td>
                 </tr>

                 <tr>
                     <td>
                     </td>
                     <td>
                         Phone Number
                     </td>
                     <td colspan="2">
                         {{cell_no_1}}
                     </td>
                 </tr>
                 <tr>
                     <td>
                     </td>
                     <td>
                       Zone
                     </td>
                     <td colspan="2">
                        {{Zone}}
                     </td>
                 </tr>
                 <tr>
                     <td></td>
                     <td> Refrence Number</td>
                     <td colspan="2">
                          <?php
                              date_default_timezone_set("Asia/Karachi");
                               echo   $todaydate = date("Y-m-d");
                          ?>
                          _{{clientID}}
                     </td>
                 </tr>

                 <tr>
                     <th></th>

                     <th colspan="2" ng-repeat="product in productList">{{product.name}}</th>
                 </tr>

                 <tbody >
                 <tr>
                     <td>Date</td>
                     <td>Rate</td>
                     <td  ng-repeat="product in LableList track by $index">
                         {{product}}
                     </td>
                 </tr>
                 <tr ng-repeat="list in responceData">
                     <td style="text-align: center" ng-repeat="x in list track by $index">{{x}}</td>
                 </tr>
                 <tr>
                     <th colspan="2">Total</th>
                     <th>{{totalQuantity | number}}</th>
                     <th>{{totalAmount | number}}</th>


                 </tr>
                 </tbody>
        </div>
        <style>
            .dropdown.dropdown-scroll .dropdown-menu {
                max-height: 200px;
                width: 60px;
                overflow: auto;
            }

        </style>
    </div>
</div>
</div>

