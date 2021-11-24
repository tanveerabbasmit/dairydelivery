<style>
	.angularjs-datetime-picker{
		z-index: 99999 !important;
	}
</style>

<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/js/table/table_style.css">

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/reciveBillReport/reciveBillReport-grid.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/FileSaver/FileSaver.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
	<div class="modal-content">
		<div class="modal-body">
			<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
			<span>&nbsp;&nbsp;Loading... </span>
		</div>
	</div>
</div>


<div class="panel row" id="testContainer" style="display: none" ng-app="clintManagemaent">
	<div ng-controller="clintManagemaent" ng-init='init(<?php echo $riderList;  ?> , <?php echo $clientList ?> , "<?php echo Yii::app()->createAbsoluteUrl('index.php/user/reciveBillReportReport'); ?>")'>

		<div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
			<button type="button" class="close" data-dismiss="alert">x</button>
			<strong>message! </strong>
			{{taskMessage}}
		</div>
		<ul class="nav nav-tabs nav-tabs-lg">
			<li style="padding: 5px">

					Recive Bill Report

			</li>
		</ul>
      <!--  {{riderList}}-->
		<div class="row" style="margin: 10px">
			<div class="col-lg-12">
                <select ng-model="rider_id" class="form-control input-sm" style="float: left ; width: 18% ;margin-right: 10px">
                    <option value="0">All Rider</option>
                    <option ng-repeat="list in riderList" value="{{list.rider_id}}">{{list.fullname}}</option>
                </select>

                <select style="margin-left: 20px; margin-right: 5px; float: left ; width: 18% ;" ng-model="payment_mode" class="form-control input-sm ng-pristine ng-valid ng-not-empty ng-valid-required ng-touched" required="">
                    <option value="0">All</option>
                    <option value="2">cheque</option>
                    <option value="3">Cash</option>
                    <option value="1">Online</option>
                    <option value="5">Bank Transaction</option>
                    <option value="6">Debit / Credit Card</option>
                </select>

				<input style="float: left ; width: 18% ;" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">
				<button class="btn btn-info btn-sm" style="float: left"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
				<input style="width: 18% ; float: left"  class="form-control input-sm "    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">
				<button class="btn btn-primary input-sm" style="float: left" ng-click="getCustomerLedgerReportFunction()"><i class="fa fa-search" style=""></i> Search</button>
				<!--<a nh-show="false" ng-disabled="false" style="margin-left: 5px" class="btn btn-primary btn-sm" href="<?php /*echo Yii::app()->createUrl('client/customerLedgerExport')*/?>?clientID={{clientID}}&startDate={{startDate}}&endDate={{endDate}}"><i class="fa fa-share " style="margin: 5px"></i> Export </a>-->
                <button class="btn btn-primary btn-sm " style="margin-left: 5px" onclick="javascript:xport.toCSV('customers');"> <i class="fa fa-share" style="margin: 5px"></i> Export</button>
				<img style="margin: 10px" ng-show="reportLoader"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading.gif" alt="" class="loading">
			</div>
 		</div>
		<div class="col-lg-12" ng-show="address" style="background-color: #FFF8DC ;">
			<div style="float: left">
              <span style="font-weight: bold;">Address :  </span> {{address}}
			</div>

			<div style="float: left ; margin-left: 20px">
              <span style="font-weight: bold;">Contact Number :  </span> {{cell_no_1}}
			</div>
			<div style="float: left ; margin-left: 20px">
              <span style="font-weight: bold;">Zone :  </span> {{zone_name}}
			</div>
		</div>


		<table  id="customers"  class="table table-fixed"  id="payment_report" style="margin-top: 6px" >
			<thead>
			<tr style="background-color: #F0F8FF">
				<th class="col-xs-1"><a href="#">ID</a></th>
				<th class="col-xs-2"><a href="#">Date</a></th>
				<th class="col-xs-2"><a href="#">Name</a></th>
                <th class="col-xs-3"><a href="#">Address</a></th>
				<th class="col-xs-1"><a href="#">Rider</a></th>
				<th class="col-xs-1"><a href="#">Collect By</a></th>
				<th class="col-xs-1"><a href="#"> Payment Mode</a></th>
				<th class="col-xs-1"><a href="#">Amount</a></th>
			</tr>
			</thead>
			<tbody>

			<tr ng-repeat="list in responce  track by $index ">
               <td class="col-xs-1">{{list.client_id}}</td>
               <td class="col-xs-2">{{changeDateFormate(list.date)}}</td>
               <td class="col-xs-2">{{list.fullname}}({{list.cell_no_1}})</td>
                <td class="col-xs-3">{{list.address}}</td>
               <td class="col-xs-1">{{list.rider_fullname}}</td>
               <td class="col-xs-1">{{list.user_full_name}}</td>


               <td class="col-xs-1" style="text-align: right">
                   <span ng-show="list.payment_mode==1">Online</span>
                   <span ng-show="list.payment_mode==2">cheque</span>
                   <span ng-show="list.payment_mode==3">Cash</span>
                   <span ng-show="list.payment_mode==4"></span>
                   <span ng-show="list.payment_mode==5">Bank Transaction</span>
                   <span ng-show="list.payment_mode==6">Debit / Credit Card </span>
               </td>
                <td class="col-xs-1" style="text-align: right">{{list.amount_paid | number :2}}</td>
			</tr>
			<tr>
				<td class="col-xs-1" ><a href="#">Total</a></td>
                 <td class="col-xs-2"></td>
                 <td class="col-xs-2"></td>
                 <td class="col-xs-3"></td>
                 <td class="col-xs-1"></td>
                 <td class="col-xs-1"></td>
                 <td class="col-xs-1"></td>
				 <td class="col-xs-1" style="text-align: right">{{totalAmount | number :2}}</td>
			</tr>

			</tbody>
		</table>
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

