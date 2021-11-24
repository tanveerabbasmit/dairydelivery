

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/deliveryduration/all_type_payment_report_grid.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/FileSaver/FileSaver.js"></script>
<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>


<div  id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">
    <div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo json_encode($fiveDayAgo) ?> , <?php echo $company_id ?> , <?php echo $riderList; ?>,"<?php echo Yii::app()->createAbsoluteUrl('Paymentnew/all_type_payment_report_list'); ?>"," <?php echo Yii::app()->createAbsoluteUrl('BillFromVendor/all_type_payment_report_list');?>" ," <?php echo Yii::app()->createAbsoluteUrl('Paymentnew/all_type_payment_report_list');?>")'>


        <div class="tabbable">
            <ul class="nav nav-tabs nav-tabs-lg">
                <li>
                    <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                        All Type Payment
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class=" active" id="tab_1" style="margin: 10px">
                    <input style="float: left ; width: 18% ;margin-bottom: 10px" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">
                    <button  style="float: left" type="button"  ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-info btn-sm"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
                    <input ng-disabled="false" style="float: left ; width: 18% ;" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">


                   <!-- <select style="margin-left :10px;margin-right:10px ;float: left ; width: 18% ;margin-bottom: 10px" class="form-control input-sm" ng-model="vendor_id">
                        <option value="0">Select</option>
                        <option value="{{list.vendor_id}}" ng-repeat="list in riderList">{{list.vendor_name}}</option>
                    </select>-->


                    <button type="button"  ng-click="getDataFunction()" class="btn btn-primary btn-sm"> <i class="fa fa-search" style="margin: 5px"></i> Search</button>

                    <img ng-show="imageLoading" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
                    <!-- {{todayDeliveryproductList}}-->
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
                        <tr>
                            <th><a href="#"> #</a></th>
                            <th><a href="#">Date</a></th>
                            <th><a href="#">Vault</a></th>
                            <th><a href="#">Head</a> </th>
                            <th><a href="#">Transaction Type </a></th>
                            <th><a href="#">Reference No. </a></th>
                            <th><a href="#">Amount </a></th>


                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="list in list track by $index">
                            <td>{{$index + 1}}</td>
                            <td>{{list.date}}</td>
                            <td>{{list.collection_vault_name}}</td>
                            <td>{{list.type}}</td>
                            <td>{{list.transaction_type}}</td>
                            <td>{{list.reference_no}}</td>
                            <td style="text-align: right">{{list.amount_paid |number}}</td>


                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>



        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $('#searchDate').datepicker({
            format: "yyyy-mm-dd"
        });
        $('#stockDate').datepicker({
            format: "yyyy-mm-dd"
        });
    });
</script>

<style type="text/css">
    .angularjs-datetime-picker {
        z-index: 99999 !important;
    }
</style>
