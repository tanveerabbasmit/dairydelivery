<style>
	.angularjs-datetime-picker{
		z-index: 99999 !important;
	}
</style>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/dateRangeChangeRate/dateRangeChangeRate-grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
	<div class="modal-content">
		<div class="modal-body">
			<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
			<span>&nbsp;&nbsp;Loading... </span>
		</div>
	</div>
</div>



<div class="panel row" id="testContainer" style="display: none" ng-app="clintManagemaent">
	<div ng-controller="clintManagemaent" ng-init='init(<?php echo  $productList ?> , <?php echo $clientList ?> , "<?php echo Yii::app()->createAbsoluteUrl('index.php/deliveryDetail/getClientDeliveryList');?>" , "<?php echo Yii::app()->createAbsoluteUrl('index.php/client/oneCustomerAmountListallCustomerList');?>")'>



		<div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
			<button type="button" class="close" data-dismiss="alert">x</button>
			<strong>message! </strong>
			{{taskMessage}}
		</div>
		<ul class="nav nav-tabs nav-tabs-lg">
			<li>
				<a href="#tab_1" data-toggle="tab" aria-expanded="false">
					Change Rate
				</a>
			</li>
		</ul>

		<div class="panel-body">
			<div class="col-lg-12 row">
				<!--<select ng-model="clientID" class="form-control" style="width: 30%; float: left" >
					<option value="">Select Customer </option>
				  <option ng-repeat="list in clientList" value="{{list.client_id}}">{{list.fullname}}</option>
				</select>-->



				<div style="float: left;">
					<button class="btn btn-default dropdown-toggle btn-sm" ng-click="showDropDownList()" type="button" id="dropdownMenu1" data-toggle="dropdown">{{SelectedCustomer}} <span  class="caret" style="margin: 9px"></span>
					</button>
					<ul style="width: 200px ;max-height: 500px ; overflow-x: hidden;overflow-y: scroll;" class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1" >
						<li role="presentation">
						   <div class="input-group input-group-sm search-control"> <span class="input-group-addon">
						<input autofocus type="text" class="form-control" placeholder="Search" ng-model="query" id="serachCustomerBar">
						</div>
						</li >
						<li  role="presentation" ng-click="abcd(item)" ng-repeat='item in clientList | filter:query'> <a href="#"> {{item.fullname}} </a>
						</li>
					</ul>
				</div>

                <!--<select ng-click="getAllCustomerList(client_type)"  style="width: 15% ; float: left" class="form-control input-sm ng-pristine ng-valid ng-not-empty ng-touched" ng-model="client_type" ng-change="onchangeZoneAndStatus()">
                    <option value="1">Active Regular</option>
                    <option value="2">Inactive Regular</option>
                    <option value="3">Active Sample</option>
                    <option value="4">Inactive Sample</option>
                </select>-->

                <img ng-show="loadClientLoader" style="margin: 15px;float: left" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
				<input style="float: left ; width: 15% ; margin-left: 1%" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">
				<button class="btn btn-info btn-sm" style="float: left"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
				<input  style="width: 15% ; float: left"  class="form-control input-sm "    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">
				<button class="btn btn-primary input-sm" style="float: left" ng-click="getCustomerLedgerReportFunction()"><i class="fa fa-search" style=""></i> Search</button>
				<!--<a ng-disabled="false" style="margin-left: 5px" class="btn btn-primary btn-sm" href="<?php /*echo Yii::app()->createUrl('client/customerLedgerExport')*/?>?clientID={{clientID}}&startDate={{startDate}}&endDate={{endDate}}"><i class="fa fa-share " style="margin: 5px"></i> Export </a>-->
				<img style="margin: 20px" ng-show="reportLoader"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading.gif" alt="" class="loading">
			</div>
 		</div>

        <div class="panel-body">
            <div class="col-lg-12 row">
                <form ng-submit="change_rate_function(product_id ,new_rate)" >
                   <select class="form-control input-sm" ng-model="product_id" style="width: 20% ;float: left" required>
                       <option value="">Select Product</option>
                       <option ng-repeat="list in productList" value="{{list.product_id}}">{{list.name}}</option>
                   </select>
                   <input  placeholder="put new rate" type="text" class="form-control input-sm"ng-model="new_rate" style="width: 15% ;float: left ;margin-left: 5px" required>
                    <button  type="submit" class="btn btn-primary input-sm" style="float: left ; margin-left: 5px"> change</button>
                    <img style="margin: 8px" ng-show="reportLoader_rateChange"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading.gif" alt="" class="loading">
                </form>
            </div>

            <div class="col-lg-12 " ng-show="product_id >0 && new_rate>0 && address ">

               <h3> <a href="#" ng-click="change_rate_function_for_parmanant(product_id ,new_rate)">Click Me</a> for parmanant change rate</h3>

            </div>

        </div>


		<div class="col-lg-12" ng-show="address" style="background-color: #FFF8DC">
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


		<table  id="customers" style="margin-top: 6px" >
			<thead>
			<tr style="background-color: #F0F8FF">
				<th><a href="#">#</a></th>
				<th><a href="#">Product</a></th>
				<th><a href="#">Date</a></th>
				<th><a href="#">Quantity</a></th>
				<th><a href="#">Rate</a></th>
				<th><a href="#">Amount</a></th>

			</tr>
			</thead>
			<tbody>

			<tr ng-repeat="list in result">
               <td>{{$index + 1}}</td>
               <td>{{list.name}}</td>
               <td>{{list.date}}</td>
               <td style="text-align: right">{{list.quantity}}</td>
               <td style="text-align: right">{{list.rate | number :2}}</td>
               <td style="text-align: right">{{list.amount | number :2}}</td>
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

