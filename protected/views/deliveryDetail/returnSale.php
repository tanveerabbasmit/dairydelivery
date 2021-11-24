<style>
	.angularjs-datetime-picker{
		z-index: 99999 !important;
	}
</style>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/dateRangeChangeRate/returnSale-grid.js"></script>

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
				<!--<button class="btn btn-info btn-sm" style="float: left"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
				<input style="width: 15% ; float: left"  class="form-control input-sm "    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">
				<button class="btn btn-primary input-sm" style="float: left" ng-click="getCustomerLedgerReportFunction()"><i class="fa fa-search" style=""></i> Search</button>-->
				<!--<a ng-disabled="false" style="margin-left: 5px" class="btn btn-primary btn-sm" href="<?php /*echo Yii::app()->createUrl('client/customerLedgerExport')*/?>?clientID={{clientID}}&startDate={{startDate}}&endDate={{endDate}}"><i class="fa fa-share " style="margin: 5px"></i> Export </a>-->
				<img style="margin: 20px" ng-show="reportLoader"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading.gif" alt="" class="loading">
			</div>
 		</div>

        <div class="panel-body">
            <div class="col-lg-12 row">

                <select ng-change="selectProduct()" class="form-control input-sm" ng-model="product_id" style="width: 20% ;float: left" required>
                   <option value="">Select Product</option>
                   <option ng-repeat="list in productList" value="{{list.product_id}}">{{list.name}}</option>
                </select>
                <input placeholder="rate" ng-disabled="true" type="text" class="form-control input-sm" ng-model="product_rate" style="width: 100px ;float: left ;margin-left: 5px" >
                <input placeholder="Quantity" ng-disabled="false" type="text" class="form-control input-sm"ng-model="quantity" style="width: 100px ;float: left ;margin-left: 5px" required="">
                <button  ng-disabled="reportLoader_rateChange" ng-click="returnSaleFunction(0)" class="btn btn-primary input-sm" style="float: left ; margin-left: 5px"> Return</button>
                <!--<button  ng-disabled="reportLoader_rateChange"  ng-click="returnSaleFunction(1)"  class="btn btn-success input-sm" style="float: left ; margin-left: 5px"> Sale</button>-->
                <img style="margin: 8px" ng-show="reportLoader_rateChange"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading.gif" alt="" class="loading">

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


	</div>


	</div>
</div>

