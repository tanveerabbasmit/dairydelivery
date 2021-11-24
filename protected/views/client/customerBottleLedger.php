<style>
	.angularjs-datetime-picker{
		z-index: 99999 !important;
	}
</style>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/customerBottleLedger/customerBottleLedger-grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
	<div class="modal-content">
		<div class="modal-body">
			<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
			<span>&nbsp;&nbsp;Loading... </span>
		</div>
	</div>
</div>


<div class="panel row" id="testContainer" style="display: none" ng-app="clintManagemaent">
	<div ng-controller="clintManagemaent" ng-init='init( <?php echo $productList ?> , <?php echo $clientList ?> , "<?php echo Yii::app()->createAbsoluteUrl('client/getClientLedgherReport_bottle'); ?>")'>

		<div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
			<button type="button" class="close" data-dismiss="alert">x</button>
			<strong>message! </strong>
			{{taskMessage}}
		</div>

		<ul class="nav nav-tabs nav-tabs-lg">
			<li>
				<a href="#tab_1" data-toggle="tab" aria-expanded="false">
					 Customer Bottle Ledger
				</a>
			</li>
		</ul>
		<div class="panel-body">
			<div class="col-lg-12">
				<!--<select ng-model="clientID" class="form-control" style="width: 30%; float: left" >
					<option value="">Select Customer </option>
				  <option ng-repeat="list in clientList" value="{{list.client_id}}">{{list.fullname}}</option>
				</select>-->
				<div style="float: left;">
					<button class="btn btn-default dropdown-toggle" ng-click="showDropDownList()" type="button" id="dropdownMenu1" data-toggle="dropdown">{{SelectedCustomer}} <span  class="caret"></span>
					</button>
					<ul style="width: 200px ;max-height: 500px ; overflow-x: hidden;overflow-y: scroll;" class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1" >
						<li role="presentation">
							<div class="input-group input-group-sm search-control"> <span class="input-group-addon">
                    <span ></span>
                    </span>
								<input autofocus type="text" class="form-control" placeholder="Search" ng-model="query" id="serachCustomerBar">
							</div>
						</li >
						<li  role="presentation" ng-click="abcd(item)" ng-repeat='item in clientList | filter:query'> <a href="#"> {{item.fullname}} </a>
						</li>
					</ul>
				</div>
				<select ng-show="hide" ng-model="product_id" class="form-control" style="float: left ; width: 20% ; margin-left: 1%">
                   <option ng-repeat="product in productList" value="{{product.product_id}}">{{product.name}}</option>
				</select>
				<input style="float: left ; width: 20% ; margin-left: 3%" class="form-control"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">
				<button class="btn btn-info" style="float: left">To</button>
				<input style="width: 20% ; float: left"  class="form-control"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">
				<button class="btn btn-primary" style="float: left" ng-click="getCustomerLedgerReportFunction()">Search</button>
				<img style="margin: 10px" ng-show="reportLoader"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading.gif" alt="" class="loading">
			</div>
 		</div>

		<table  class="table table-striped nomargin" >
			<thead>
			<tr>
				<th>#</th>
				<th>Date</th>
				<th>DESCRIPTION</th>
				<th>Delivered</th>
				<th>RECEIVED </th>
				<th>Broken</th>

				<th>Balance</th>
			</tr>
			</thead>
			<tbody>

			<tr ng-repeat="list in responceData track by $index">
               <td>{{$index + 1}}</td>
               <td>{{changeDateFormate(list.date)}}</td>
               <td>{{list.discription}}</td>
               <td>{{list.delivery}}</td>
               <td>{{list.perfect}}</td>
               <td>{{list.broken}}</td>

               <td>{{list.balance}}</td>
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

