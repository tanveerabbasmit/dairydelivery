<style>
	.angularjs-datetime-picker{
		z-index: 99999 !important;
	}
</style>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/milkStockReport/milkStockReport-grid.js"></script>



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
<?php $company_id = Yii::app()->user->getState('company_branch_id'); ?>



<div class="panel row" id="testContainer" style="display: none" ng-app="clintManagemaent">
	<div  ng-controller="clintManagemaent" ng-init='init(<?php echo $data ?>,"<?php echo Yii::app()->createAbsoluteUrl('index.php/milkStockReport/base'); ?>")'>
		<div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
			<button type="button" class="close" data-dismiss="alert">x</button>
			<strong>message! </strong>
			{{taskMessage}}
		</div>
		<ul class="nav nav-tabs nav-tabs-lg">
			<li>
				<a href="#tab_1" data-toggle="tab" aria-expanded="false">
                    Milk stock report
				</a>
			</li>
		</ul>
        <div style="width: 800px; margin: 5px;margin-left: 200px" class="table-responsive">
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
            <input style="float: left ; width: 20% ;" class="form-control input-sm ng-pristine ng-untouched ng-valid ng-not-empty ng-valid-required ng-valid-date"  datetime-picker="" date-only="" date-format="yyyy-MM-dd" type="text" required="" ng-model="start_date" size="2">

           <!-- <button style="float: left" type="button" ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-info btn-sm"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>

            <input style="float: left ; width: 20% ;" class="form-control input-sm ng-pristine ng-untouched ng-valid ng-not-empty ng-valid-required ng-valid-date" ng-change="selectRiderOnChange(selectRiderID)" datetime-picker="" date-only="" date-format="yyyy-MM-dd" type="text" required="" ng-model="end_date" size="2">-->
            <button style="margin-left: 10px;" type="button" ng-click="carryForworded()" class="btn btn-primary btn-sm "> <i class="fa fa-search" style="margin: 4px"></i> Search</button>
            <img ng-show="imageLoading" src="/milk_Company/themes/milk/images/loader-transparent.gif" alt="" class="loading ng-hide">

            <a  href="<?php echo Yii::app()->baseUrl; ?>/index.php/milkStockReport/MiilkStockReport_view"  type="button"   class="btn btn-primary btn-sm"> Milk Stock Report </a>

            <table id="customers" style="margin-top: 10px">
                <tbody>
                <tr style="background-color: ">
                    <th><a href="">Carry Forwarded</a></th>
                     <th></th>
                    <th style="width: 35%;text-align: right"><a href="">{{total_carry_Forward | number :2}}</a></th>
                </tr>
                <tr style="background-color: ">
                    <td style="color: "><a target="_blank" href="<?php echo Yii::app()->baseUrl; ?>/cattleProduction/Production?date={{start_date}}">Morning Milking </a> </td>
                     <td>{{start_date}}</td>
                    <td style="text-align: right">{{productionList.morning | number :2}}</td>
                </tr>
                <tr style="background-color: ">
                    <td style="color: "><a target="_blank" href="<?php echo Yii::app()->baseUrl; ?>/cattleProduction/Production?date={{start_date_p}}">Afternoon Milking</a></td>

                    <td>{{start_date_p}}</td>
                    <td style="text-align: right">{{productionList.afternoun | number : 2}}</td>
                </tr>
                <tr style="background-color: ">
                    <td style="color: "><a target="_blank" href="<?php echo Yii::app()->baseUrl; ?>/cattleProduction/Production?date={{start_date_p}}">Evening Milking</a></td>
                     <td>{{start_date_p}}</td>
                    <td style="text-align: right">{{productionList.evenining | number :2}}</td>
                </tr>
                <tr style="background-color: ">
                    <th style="color: "><a href="">Total</a></th>
                    <td></td>
                    <th style="text-align: right"><a href="">{{total_production | number : 2}}</a></th>
                </tr>

                <tr style="background-color: " ng-repeat="list in farmList">
                    <td style="color: "><a target="_blank" href="<?php echo Yii::app()->baseUrl; ?>/dailyStock/viewDateRangeStock?date={{start_date_p}}">{{list.farm_name}}</a></td>
                    <td>{{start_date_p}}</td>
                    <td style="text-align: right">
                        {{list.quantity | number :2}}
                    </td>
                </tr>
                <tr style="background-color: ">
                    <th style="color: "><a href="">Total Farm Stock</a></th>
                    <td></td>
                    <th style="text-align: right"><a href="">{{total_farm_stock| number :2}}</a></th>
                </tr>
               <!-- <tr style="background-color: ">
                    <td style="color: "><a href="">Rider Return</a></td>
                    <td>12</td>
                </tr>-->
                <tr style="background-color: ">
                    <th style="color: "><a href="">Available For Sale</a></th>
                    <td></td>
                    <th style="text-align: right"><a href="#">{{availableForSale | number :2}}</a></th>
                </tr>
                <tr style="background-color: ">
                    <td style="color: "><a target="_blank" href="<?php echo Yii::app()->baseUrl; ?>/riderDailyStock/dateWisedeliveryReport?date={{start_date}}&client_id=0">Credit Sale </a></td>
                    <td>{{start_date}}</td>
                    <td style="text-align: right"> {{total_credit_sale | number :2}} </td>
                </tr>
                <tr style="background-color: " ng-repeat="sale in client_sale">
                    <td style="color: "><a target="_blank" href="<?php echo Yii::app()->baseUrl; ?>/riderDailyStock/dateWisedeliveryReport?date={{start_date}}&client_id={{sale.client_id}}"><span ng-bind="sale.fullname"></span> </a></td>
                     <td>{{start_date}}</td>
                    <td style="text-align: right">{{sale.quantity | number :2}}</td>
                </tr>
                <tr style="background-color: ">
                    <td style="color: "><a target="_blank" href="<?php echo Yii::app()->baseUrl; ?>/dailyStock/viewDateRangeStock?date={{start_date}}">Spoilage Milk</a></td>
                     <td>{{start_date}}</td>
                    <td style="text-align: right">{{wasteg | number :2}}</td>
                </tr>
                <tr style="background-color: ">
                    <th style="color: "><a href="">Closing Stock</a></th>
                     <th></th>
                    <th  ng-show="closing_day_stock >0" style="text-align: right"><a href="#"> {{closing_day_stock | number :2}} </a></th>
                    <th  ng-show="closing_day_stock <0" style="text-align: right"><a href="#"> <span style="color: red">{{closing_day_stock | number :2}}</span> </a></th>
                </tr>

                <tr style="background-color: ">
                    <td style="color: "><a href="">Actual Stock</a></td>
                    <th></th>
                    <td><input ng-disabled="todayData" type="text" class="form-control" ng-model="actual_stock"></td>
                </tr>

                <tr style="background-color: ">
                    <td style="color: "><a href="">Difference</a></td>
                    <th></th>
                    <td ng-show="(actual_stock - closing_day_stock)>=0" style="text-align: right"><span ng-show="actual_stock !=''">{{ calculateDifference(closing_day_stock , actual_stock) | number :2}}</span></td>
                    <td ng-show="(actual_stock - closing_day_stock)<0" style="text-align: right ;color: red"><span  ng-show="actual_stock !=''">{{calculateDifference(closing_day_stock ,actual_stock) | number :2}}</span></td>
                </tr>
                <tr style="background-color: " >
                    <td style="color: "><a href="">Reason</a></td>
                    <th></th>
                    <td><input ng-disabled="todayData" type="text" class="form-control" ng-model="reason"></td>
                </tr>
                <tr >
                    <td colspan="3" style="border: 0px solid black;">
                        <button ng-disabled="save_buton || todayData" style="margin-left: 10px;float: right" type="button" ng-click="saveReport()" class="btn btn-primary btn-sm "> <i class="fa fa-save" ></i> Save</button>
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

