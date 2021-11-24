<style>
	.angularjs-datetime-picker{
		z-index: 99999 !important;
	}
</style>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/viewcompanystock/viewcompanystock_ledger-grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
	<div class="modal-content">
		<div class="modal-body">
			<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
			<span>&nbsp;&nbsp;Loading... </span>
		</div>
	</div>
</div>



<div class="panel row" id="testContainer" style="display: none" ng-app="clintManagemaent">
	<div ng-controller="clintManagemaent" ng-init='init(<?php echo $farmList; ?> , <?php echo $productList ?> , "<?php echo Yii::app()->createAbsoluteUrl('company/getCompanyStockList_ledger'); ?>")'>



		<div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
			<button type="button" class="close" data-dismiss="alert">x</button>
			<strong>message! </strong>
			{{taskMessage}}
		</div>
		<ul class="nav nav-tabs nav-tabs-lg">
			<li>
				<a href="#tab_1" data-toggle="tab" aria-expanded="false">
					 Company Stock Ledger
				</a>
			</li>
		</ul>

       <!-- {{farmList}}-->

		<div class="panel-body">
			<div class="col-lg-12">
				<!--<select ng-model="clientID" class="form-control" style="width: 30%; float: left" >
					<option value="">Select Customer </option>
				  <option ng-repeat="list in clientList" value="{{list.client_id}}">{{list.fullname}}</option>
				</select>-->
				<div style="float: left;">
                    <select class="form-control input-sm" ng-model="selectProductID">
                      <option ng-repeat="list in productList" value="{{list.product_id}}" class="ng-binding ng-scope">
                          {{list.name}}
                        </option>
                    </select>
				</div>

                <div style="float: left;">
                    <select class="form-control input-sm" ng-model="farm_id">
                        <option value="0">All Farm</option>
                        <option ng-repeat="list in farmList" value="{{list.farm_id}}" class="ng-binding ng-scope">
                            {{list.farm_name}}
                        </option>
                    </select>
                </div>

				<input style="float: left ; width: 25% ; margin-left: 3%" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">
				<button class="btn btn-info btn-sm" style="float: left"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
				<input style="width: 25% ; float: left"  class="form-control input-sm "    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">
				<button class="btn btn-primary input-sm" style="float: left" ng-click="getCustomerLedgerReportFunction()"><i class="fa fa-search" style=""></i> Search</button>
				<a ng-disabled="false" style="margin-left: 5px" class="btn btn-primary btn-sm" href="<?php echo Yii::app()->createUrl('client/customerLedgerExport')?>?clientID={{clientID}}&startDate={{startDate}}&endDate={{endDate}}"><i class="fa fa-share " style="margin: 5px"></i> Export </a>
				<img style="margin: 10px" ng-show="reportLoader"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading.gif" alt="" class="loading">
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

         <!--{{farmList}}-->
		<table  id="customers" style="margin-top: 6px" >
			<thead>
			<tr style="background-color: #F0F8FF">
				<th><a href="#">#</a></th>
				<th>
				<a href="#" ng-click="sortType = 'date'; sortReverse = !sortReverse">
					Date
					<span ng-show="sortType == 'date' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
					<span ng-show="sortType == 'date' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
				</a>
				</th>
				<th>

					<a href="#" ng-click="sortType = 'discription'; sortReverse = !sortReverse">
                        Received
						<span ng-show="sortType == 'discription' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
						<span ng-show="sortType == 'discription' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
					</a>

				</th>
				<th>

					<a href="#" ng-click="sortType = 'reference_number'; sortReverse = !sortReverse">
						Delivered
						<span ng-show="sortType == 'reference_number' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
						<span ng-show="sortType == 'reference_number' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
					</a>
				</th>
				<th>
					<a href="#" ng-click="sortType = 'delivery'; sortReverse = !sortReverse">
						Return
						<span ng-show="sortType == 'delivery' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
						<span ng-show="sortType == 'delivery' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
					</a>
				</th>

                <th>
                    <a href="#" ng-click="sortType = 'delivery'; sortReverse = !sortReverse">
                        Wastage
                        <span ng-show="sortType == 'delivery' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
                        <span ng-show="sortType == 'delivery' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
                    </a>
                </th>

                <th>
                    <a href="#" ng-click="sortType = 'delivery'; sortReverse = !sortReverse">
                        Net
                        <span ng-show="sortType == 'delivery' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
                        <span ng-show="sortType == 'delivery' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
                    </a>
                </th>
                <th>
                    <a href="#">
                        Balance
                    </a>
                </th>



			</tr>
			</thead>
			<tbody>

			<tr ng-repeat="list in responceData | orderBy:sortType:!sortReverse track by $index ">
               <td>{{$index + 1}}</td>
               <td>
                   <span ng-show="list.Date=='Opening Stock'" style="color: #3a47ff">{{list.Date}}</span>
                   {{changeDateFormate(list.Date)}}
               </td>
               <td style="text-align: right">{{list.total_recive | number :2}}</td>
               <td style="text-align: right">{{list.total_rider | number:2}}</td>
               <td style="text-align: right">{{list.total_return | number:2}}</td>
                <td style="text-align: right">{{list.wastage | number:2}}</td>
               <td style="text-align: right">{{ list.net| number:2}}</td>
               <td style="text-align: right">{{ list.balance| number:2}}</td>


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

