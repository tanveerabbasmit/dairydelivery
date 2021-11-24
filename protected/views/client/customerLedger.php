<style>
	.angularjs-datetime-picker{
		z-index: 99999 !important;
	}
</style>
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/js/table/table_style.css">
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/customerLedger/customerLedger-grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
	<div class="modal-content">
		<div class="modal-body">
			<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
			<span>&nbsp;&nbsp;Loading... </span>
		</div>
	</div>
</div>


<div class="panel row" id="testContainer" style="display: none" ng-app="clintManagemaent">
	<div ng-controller="clintManagemaent" ng-init='init(<?php echo $client_object ?>, <?php echo $clientList ?> , "<?php echo Yii::app()->createAbsoluteUrl('index.php/client/getClientLedgherReport'); ?> " , "<?php echo Yii::app()->createAbsoluteUrl('index.php/client/oneCustomerAmountListallCustomerList');?>")'>


		<div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
			<button type="button" class="close" data-dismiss="alert">x</button>
			<strong>message! </strong>
			{{taskMessage}}
		</div>
		<!--<ul class="nav nav-tabs nav-tabs-lg">
			<li>
				<a href="#tab_1" data-toggle="tab" aria-expanded="false">
					 Customer Ledger  {{responce.startDate}} --{{responce.endDate}}
				</a>
			</li>
		</ul>-->

		<div class="" style="padding: 1px">
			<div class="col-lg-12 row">
				<div style="float: left;">
					<button class="btn btn-default dropdown-toggle btn-sm" ng-click="showDropDownList()" type="button" id="dropdownMenu1" data-toggle="dropdown">{{SelectedCustomer}} <span  class="caret" style="margin: 9px"></span>
					</button>
					<ul style="width: 200px ;max-height: 500px ; overflow-x: hidden;overflow-y: scroll;" class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1" >
						<li role="presentation">
						   <div class="input-group input-group-sm search-control"> <span class="input-group-addon">
						<input autocomplete="off" autofocus type="text" class="form-control" placeholder="Search" ng-model="query" id="serachCustomerBar">
						</div>
						</li >
						<li  role="presentation" ng-click="abcd(item)" ng-repeat='item in clientList | filter:query'> <a href="#"> {{item.fullname}} </a>
						</li>
					</ul>
				</div>

                <select ng-click="getAllCustomerList(client_type)"  style="width: 15% ; float: left" class="form-control input-sm ng-pristine ng-valid ng-not-empty ng-touched" ng-model="client_type" ng-change="onchangeZoneAndStatus()">
                    <option value="1">Active Regular</option>
                    <option value="2">Inactive Regular</option>
                    <option value="3">Active Sample</option>
                    <option value="4">Inactive Sample</option>
                </select>

                <img ng-show="loadClientLoader" style="margin: 15px;float: left" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
				<input style="float: left ; width: 12% ; margin-left: 1%" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">
				<button class="btn btn-info btn-sm" style="float: left"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
				<input style="width: 12% ; float: left"  class="form-control input-sm "    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">
				<button class="btn btn-primary input-sm" style="float: left" ng-click="getCustomerLedgerReportFunction()"><i class="fa fa-search" style=""></i> Search</button>
				<a  style="margin-left: 5px" class="btn btn-primary btn-sm" href="<?php echo Yii::app()->createUrl('client/customerLedgerExport')?>?clientID={{clientID}}&startDate={{startDate}}&endDate={{endDate}}"><i class="fa fa-share " style="margin: 5px"></i> Export  </a>
				<img style="margin: 20px" ng-show="reportLoader"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading.gif" alt="" class="loading">
			</div>
 		</div>
		<div class="col-lg-12" ng-show="address" style="margin-top: 5px;  background-color: #FFF8DC">
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



		<table  id="customers" style="margin-top: 15px;" class="table table-fixed" >
			<thead>
			<tr>
				<th style="width: 4%">#</th>
				<th style="width: 19%">
                    <a  href="#" ng-click="sortType = 'date'; sortReverse = !sortReverse">
                        Date
                        <span ng-show="sortType == 'date' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
                        <span ng-show="sortType == 'date' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
                    </a>
				</th>
				<th style="width: 22.7%">

					<a href="#" ng-click="sortType = 'discription'; sortReverse = !sortReverse">
						DESCRIPTION
						<span ng-show="sortType == 'discription' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
						<span ng-show="sortType == 'discription' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
					</a>

				</th>
				<th style="width: 22%">

					<a href="#" ng-click="sortType = 'reference_number'; sortReverse = !sortReverse">
						 REFERENCE No.
						<span ng-show="sortType == 'reference_number' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
						<span ng-show="sortType == 'reference_number' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
					</a>
				</th>
				<th style="width: 10%">
					<a href="#" ng-click="sortType = 'delivery'; sortReverse = !sortReverse">
						DELIVERY
						<span ng-show="sortType == 'delivery' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
						<span ng-show="sortType == 'delivery' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
					</a>
				</th>
				<th style="width: 10%">
					<a href="#" ng-click="sortType = 'reciveAmount'; sortReverse = !sortReverse">
						RECEIVED
						<span ng-show="sortType == 'reciveAmount' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
						<span ng-show="sortType == 'reciveAmount' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
					</a>
				</th>
				<th style="width: 12.3%">
					<a href="#" ng-click="sortType = 'balance'; sortReverse = !sortReverse">
						BALANCE
						<span ng-show="sortType == 'balance' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
						<span ng-show="sortType == 'balance' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
					</a>
				</th>
			</tr>
			</thead>
			<tbody>

			<tr style="background-color: white" ng-repeat="list in responceData | orderBy:sortType:!sortReverse track by $index ">

               <td  style="width: 4%;background-color: white">
                   {{$index + 1}}
               </td>

               <td style="width: 19.3%;background-color: white">
                   {{changeDateFormate(list.date)}}
               </td>

               <td style="width: 23%;background-color: white">
                   {{list.discription}}
               </td>

               <td style="width: 22%;background-color: white">
                   {{list.reference_number}}
               </td>

               <td style="width: 10%;background-color: white;text-align: right">
                   {{list.delivery | number :2}}
               </td>

               <td style="width: 10%;background-color: white;text-align: right">
                   {{list.reciveAmount | number :2}}
               </td>

               <td style="width: 11%;background-color: white;text-align: right">
                   {{list.balance | number :2}}
               </td>

			</tr>

			<tr ng-show="showOpeningBalance">
                <td  style="width: 4%; background-color: white">

                </td>
				<td  style="width: 19.3%;background-color: white">
                    <a href="#">Bad Debt Amount</a>
                </td>
				<td style="width: 23%;background-color: white;text-align: right">
                    <a href="">{{bad_debt_amount | number :0}} </a>
                </td>

                <td style="width: 22%;background-color: white">
                    <a href="#"> Total Delivery</a>
                </td>
				<td style="width: 10%;background-color: white">
                    <a href="">{{totalDelievry}}</a>
                </td>

				<td style="width: 10%;background-color: white">
                    <a href="#">Total Received</a>
                </td>
				<td style="width: 11%;background-color: white;text-align: right">
                    <a href="#">{{totalRecive | number :2}}</a>
                </td>
			</tr>

			<!--<tr ng-show="acountSumery.length>0" style="background-color: #E0FFFF">
               <th style="width:100%;background-color: white;" colspan="7">
                   <a href="#">Product Summary</a>
               </th>
			</tr>-->
			<tr >
				<td style="width:33.3%;background-color: white" colspan="4">Product</td>
				<td style="width:33%;background-color: white" colspan="2">Quantity</td>
				<td style="width:33%;background-color: white">Amount</td>
			</tr>
			<tr ng-show="acountSumery.length>0" ng-repeat="product in acountSumery">
				<td style="width:33.3%;background-color: white" colspan="4">{{product.product_name}}</td>
				<td style="width:33%;background-color: white" colspan="2">{{product.deliveryQuantity_sum}} {{product.unit}}</td>
				<td style="width:33%;text-align: right">{{product.deliverySum | number :2}} </td>
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

