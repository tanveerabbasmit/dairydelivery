<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/riderStock/riderStock-grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
	<div class="modal-content">
		<div class="modal-body">
			<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
			<span>&nbsp;&nbsp;Loading... </span>
		</div>
	</div>
</div>


<div id="testContainer" style="display: none" class="panel row" ng-app="riderDailyStockGridModule">
	<div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo $data; ?>,"<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/saveRiderDialyStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/ReturnSaveDetail'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/getRiderDailyStock'); ?>")'>

		<div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
			<button type="button" class="close" data-dismiss="alert">x</button>
			<strong>message! </strong>
			 {{taskMessage}}
		</div>

		<div class="tabbable">
				<ul class="nav nav-tabs nav-tabs-lg">
					<li class="active">
						<a href="#tab_1" ng-click="click_tab1()" data-toggle="tab" aria-expanded="true">
							Assign Stock</a>
					</li>
					<li class="">
						<a href="#tab_2" data-toggle="tab" aria-expanded="false" ng-click="addNewStock()">
							 Return Stock
						</a>
					</li>
					<li class="">
						<a href="#tab_3" data-toggle="tab"  aria-expanded="false" ng-click="addNewStock()">
							 View Rider Stock
						</a>
					</li>
					<li class="">
						<a href="#tab_4" data-toggle="tab"  aria-expanded="false" ng-click="addNewStock()">
                            Wastage
						</a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="tab_1">
                  <!-- {{riderList}}-->
						<div class="col-lg-12" style="margin-bottom: 10px;">
							<div class="col-lg-2" style="  font-weight: bold; font-size: 120%;" >
								Select Product
							</div>
							<div class="col-lg-3" style="" >
								<select class="form-control" ng-model="selectProductID">
									<option ng-repeat="list in productList" value="{{list.product_id}}">{{list.name}}
										<span ng-bind=""></span>
									</option>
								</select>
							</div>
							<div class="col-lg-3"  >
								<input type="text" class="form-control" placeholder="Search Rider" ng-model="searchRider">
							</div>
                            <div class="col-lg-3"  >
                                <input style="" class="form-control  ng-pristine ng-valid ng-not-empty ng-valid-required ng-valid-date ng-touched" datetime-picker="" date-only="" date-format="yyyy-MM-dd" type="text" required="" ng-model="currentDate" size="2">
							</div>
						</div>

						<table class="table table-striped nomargin">
							<thead>
							<tr>
								<th>#</th>
								<th>
									<a href="#" ng-click="sortType = 'fullname'; sortReverse = !sortReverse">
										Rider
										<span ng-show="sortType == 'fullname' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
										<span ng-show="sortType == 'fullname' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
									</a>
								</th>
								<th>
									<a href="#" ng-click="sortType = 'address'; sortReverse = !sortReverse">
										Addrers
										<span ng-show="sortType == 'address' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
										<span ng-show="sortType == 'address' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
									</a>
								</th>
								<th>
									<a href="#" ng-click="sortType = 'cell_no_1'; sortReverse = !sortReverse">
										Phone No.
										<span ng-show="sortType == 'cell_no_1' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
										<span ng-show="sortType == 'cell_no_1' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
									</a>
								</th>
								<th width="15%">
									<a href="#">
										Quantity
										<span ng-show="sortType == 'selectQuantity' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
										<span ng-show="sortType == 'selectQuantity' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
									</a>
								</th>
                               <!-- <th>
                                    <a href="#">Wastage</a>
                                </th>-->
							</tr>
							</thead>
							<tbody>
							<tr  ng-show="searchRider ==''" ng-repeat="list in riderList | orderBy:sortType:!sortReverse track by $index">
								<td>{{$index+1}}</td>
								<td> <span ng-bind="list.fullname"></span> </td>
								<td> <span ng-bind="list.address"></span></td>
								<td><span ng-bind="list.cell_no_1"></span></td>
								<td>
									<input type="number" class="form-control input-sm" ng-model="list.selectQuantity">
								</td>

							</tr>
							<tr  ng-show="searchRider !=''" ng-repeat="list in riderList | filter:searchRider:strict">
								<td> <span ng-bind="list.fullname"></span> </td>
								<td> <span ng-bind="list.address"></span></td>
								<td><span ng-bind="list.cell_no_1"></span></td>
								<td>
									<input type="number" class="form-control input-sm" ng-model="list.selectQuantity">
								</td>
							</tr>
							</tbody>
						</table>

						<div class="modal-footer">
							<img  ng-show="showLoaderImage"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
							<button type="submit" ng-click="saveNewRiderStock()"   class="btn btn-success  ">Assign</button>
							<button type="button"  ng-click="assignStockReset(riderList)" class="btn btn-default" data-dismiss="modal">Reset</button>
						</div>
					</div>
					<div class="tab-pane" id="tab_4">
                  <!-- {{riderList}}-->
						<div class="col-lg-12" style="margin-bottom: 10px;">
							<div class="col-lg-2" style="  font-weight: bold; font-size: 120%;" >
								Select Product
							</div>
							<div class="col-lg-3" style="" >
								<select class="form-control" ng-model="selectProductID">
									<option ng-repeat="list in productList" value="{{list.product_id}}">{{list.name}}
										<span ng-bind=""></span>
									</option>
								</select>
							</div>
							<div class="col-lg-3"  >
								<input type="text" class="form-control" placeholder="Search Rider" ng-model="searchRider">
							</div>
                            <div class="col-lg-3"  >
                                <input style="" class="form-control  ng-pristine ng-valid ng-not-empty ng-valid-required ng-valid-date ng-touched" datetime-picker="" date-only="" date-format="yyyy-MM-dd" type="text" required="" ng-model="currentDate" size="2">
							</div>
						</div>

						<table class="table table-striped nomargin">
							<thead>
							<tr>
								<th>#</th>
								<th>
									<a href="#" ng-click="sortType = 'fullname'; sortReverse = !sortReverse">
										Rider
										<span ng-show="sortType == 'fullname' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
										<span ng-show="sortType == 'fullname' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
									</a>
								</th>
								<th>
									<a href="#" ng-click="sortType = 'address'; sortReverse = !sortReverse">
										Addrers
										<span ng-show="sortType == 'address' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
										<span ng-show="sortType == 'address' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
									</a>
								</th>
								<th>
									<a href="#" ng-click="sortType = 'cell_no_1'; sortReverse = !sortReverse">
										Phone No.
										<span ng-show="sortType == 'cell_no_1' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
										<span ng-show="sortType == 'cell_no_1' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
									</a>
								</th>
								<th width="15%">
									<a href="#">
										Wastage
										<span ng-show="sortType == 'selectQuantity' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
										<span ng-show="sortType == 'selectQuantity' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
									</a>
								</th>
                               <!-- <th>
                                    <a href="#">Wastage</a>
                                </th>-->
							</tr>
							</thead>
							<tbody>
							<tr  ng-show="searchRider ==''" ng-repeat="list in riderList | orderBy:sortType:!sortReverse track by $index">
								<td>{{$index+1}}</td>
								<td> <span ng-bind="list.fullname"></span> </td>
								<td> <span ng-bind="list.address"></span></td>
								<td><span ng-bind="list.cell_no_1"></span></td>
								<td>
									<input type="number" class="form-control input-sm" ng-model="list.wastage_quantity">
								</td>

							</tr>
							<tr  ng-show="searchRider !=''" ng-repeat="list in riderList | filter:searchRider:strict">
								<td> <span ng-bind="list.fullname"></span> </td>
								<td> <span ng-bind="list.address"></span></td>
								<td><span ng-bind="list.cell_no_1"></span></td>
								<td>
									<input type="number" class="form-control input-sm" ng-model="list.wastage_quantity">
								</td>
							</tr>
							</tbody>
						</table>

						<div class="modal-footer">
							<img  ng-show="showLoaderImage"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
							<button type="submit" ng-click="saveNewRiderStock()"   class="btn btn-success  ">Save</button>
							<button type="button"  ng-click="assignStockReset(riderList)" class="btn btn-default" data-dismiss="modal">Reset</button>
						</div>
					</div>
					<div class="tab-pane" id="tab_2">

						<div class="col-lg-12" style="margin-bottom: 10px;">
							<div class="col-lg-2" style="  font-weight: bold; font-size: 120%;" >
								Select Product
							</div>
							<div class="col-lg-3" style="" >
								<select class="form-control" ng-model="selectProductID">
									<option ng-repeat="list in productList" value="{{list.product_id}}">{{list.name}}
										<span ng-bind=""></span>
									</option>
								</select>
							</div>
							<div class="col-lg-3"  >
								<input type="text" class="form-control" placeholder="Search Rider" ng-model="searchRider">
							</div>
                            <div class="col-lg-3"  >
                                <input style="" class="form-control  ng-pristine ng-valid ng-not-empty ng-valid-required ng-valid-date ng-touched" datetime-picker="" date-only="" date-format="yyyy-MM-dd" type="text" required="" ng-model="currentDate" size="2">
                            </div>
						</div>
						<table class="table table-striped nomargin">
							<thead>
							<tr>
								<th>
									<a href="#" ng-click="sortType = 'fullname'; sortReverse = !sortReverse">
										Rider
										<span ng-show="sortType == 'fullname' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
										<span ng-show="sortType == 'fullname' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
									</a>

								</th>
								<th>
									<a href="#" ng-click="sortType = 'address'; sortReverse = !sortReverse">
										Addrers
										<span ng-show="sortType == 'address' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
										<span ng-show="sortType == 'address' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
									</a>
								</th>
								<th>
									<a href="#" ng-click="sortType = 'cell_no_1'; sortReverse = !sortReverse">
										Phone No.
										<span ng-show="sortType == 'cell_no_1' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
										<span ng-show="sortType == 'cell_no_1' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
									</a>
								</th>

								<th width="15%">
									<a href="#">
										Quantity
										<span ng-show="sortType == 'selectQuantity' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
										<span ng-show="sortType == 'selectQuantity' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
									</a>
								</th>
							</tr>
							</thead>
							<tbody>

							<tr  ng-show="searchRider ==''" ng-repeat="list in riderList | orderBy:sortType:!sortReverse">
								<td> <span ng-bind="list.fullname"></span> </td>
								<td> <span ng-bind="list.address"></span></td>
								<td><span ng-bind="list.cell_no_1"></span></td>
								<td>
									<input type="number" class="form-control" ng-model="list.selectQuantity">
								</td>


							</tr>

							<tr  ng-show="searchRider !=''" ng-repeat="list in riderList | filter:searchRider:strict">
								<td> <span ng-bind="list.fullname"></span> </td>
								<td> <span ng-bind="list.address"></span></td>
								<td><span ng-bind="list.cell_no_1"></span></td>
								<td>
									<input type="number" class="form-control" ng-model="list.selectQuantity">
								</td>


							</tr>
							</tbody>
						</table>

						<div class="modal-footer">
							<img  ng-show="showLoaderImage"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
							<button type="submit" ng-click="saveReturnRiderStock()"   class="btn btn-success btn-quirk btn-wide mr5">Return</button>
							<button type="button"  ng-click="assignStockReset(riderList)" class="btn btn-default" data-dismiss="modal">Reset</button>
						</div>


					</div>
					<div class="tab-pane" id="tab_3">

						<div class="col-lg-12" style="margin-bottom: 10px;">
							<div class="col-lg-2" style="  font-weight: bold; font-size: 120%;" >
								Select Rider <!--{{riderList}}-->
							</div>
							<div class="col-lg-3" style="" >
								<select class="form-control" ng-model="selectRiderID" ng-change="changeRiderForGetTodayStock(selectRiderID)">
									<option value="">Select Rider</option>
									<option ng-repeat="list in riderList" value="{{list.rider_id}}">{{list.fullname}}
										<span ng-bind=""></span>
									</option>
								</select>
							</div>

                            <div class="col-lg-3"  >
                                <input style="" class="form-control  ng-pristine ng-valid ng-not-empty ng-valid-required ng-valid-date ng-touched" datetime-picker="" date-only="" date-format="yyyy-MM-dd" type="text" required="" ng-model="currentDate" size="2">
                            </div>

						</div>

						<table class="table table-striped nomargin">
							<thead>
							<tr>
								<th>
									<a href="#" ng-click="sortType = 'fullname'; sortReverse = !sortReverse">
										Product
										<span ng-show="sortType == 'fullname' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
										<span ng-show="sortType == 'fullname' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
									</a>
								</th>
								<th>
									<a href="#"> Recived</a>
								</th>
                                <th>
                                    <a href="#"> Wastage</a>
                                </th>

                                <th>
                                    <a href="#"> Return</a>
                                </th>

                                <th>
                                    <a href="#"> Net Stock</a>
                                </th>

							</tr>
							</thead>
							<tbody>

							<tr  ng-show="searchRider ==''" ng-repeat="list in riderDailyStock | orderBy:sortType:!sortReverse">
								<td> <span ng-bind="list.name"></span> </td>
								<td> <span ng-bind="list.recive"></span></td>
								<td> <span ng-bind="list.return_stock"></span></td>
								<td> <span ng-bind="list.wastage_quantity"></span></td>
								<td> <span ng-bind="list.net_stock"></span></td>

							</tr>

							<tr  ng-show="searchRider !=''" ng-repeat="list in riderList | filter:searchRider:strict">
								<td> <span ng-bind="list.fullname"></span> </td>
								<td> <span ng-bind="list.address"></span></td>
								<td><span ng-bind="list.cell_no_1"></span></td>
								<td>
									<input type="number" class="form-control" ng-model="list.selectQuantity">
								</td>


							</tr>
							</tbody>
						</table>


					</div>

				</div>
			</div>

	<!-- start: add new stock model -->
		<div id="addNewStockModel" class="modal fade" role="dialog">
		  	<div class="modal-dialog" style="width:800px !important;">
		    	<div class="modal-content">
		      		<div class="modal-header">
		        		<button type="button" class="close" data-dismiss="modal">&times;</button>
			        	<h4 class="modal-title">Stock</h4>
			      	</div>
			      	<form ng-submit="saveNewStock()" class="form-horizontal">
			      		<div class="modal-body">
			      			<div ng-repeat="product in productList">
			      				<div class="form-group" style="margin-top:10px;">
			                    	<label class="col-sm-2 control-label">{{product.name}}</label>
			                    	<div class="col-sm-1">
			                      		<input style="margin-top: 13px;" type="checkbox" ng-model="product.stockModel.select" name="select" />
			                    	</div>
			                    	<div class="col-sm-4">
			                      		<input ng-disabled="!product.stockModel.select" type="text" placeholder="Description" ng-model="product.stockModel.description" name="description" class="form-control" required />
			                    	</div>
			                    	<div class="col-sm-4">
			                      		<input ng-disabled="!product.stockModel.select" type="text" placeholder="Purchase Quantity" ng-model="product.stockModel.quantity" name="quantity" class="form-control " required />
			                    	</div>
		                    	</div>
			      			</div>
				      	</div>
				      	<div class="modal-footer">
				      		<button type="submit" class="btn btn-success btn-quirk btn-wide mr5">Save</button>
				        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				      	</div>
			      	</form>
			    </div>
		  	</div>
		</div>

		<!-- end: add new stock model -->
		<!-- start: stock details -->
		<div id="dailyStockDetailModal" class="modal fade" role="dialog">
		  	<div class="modal-dialog" style="width:900px !important;">
		    	<div class="modal-content">
		      		<div class="modal-header">
		        		<button type="button" class="close" data-dismiss="modal">&times;</button>
			        	<h4 class="modal-title">{{productName}}</h4>
			      	</div>
			      	<div class="modal-body">
				      	<div style="margin-top:5px;" class="table-responsive">
					    	<table class="table table-striped nomargin">
					      		<thead>
					        		<tr>
							          	<th>Description</th>
							          	<th>Stock Avalaible</th>
							          	<th>Stock Return</th>
							          	<th>StockIn Date&Time</th>
							          	<th style="width:100px"></th>
					        		</tr>
					      		</thead>
					      		<tbody>
					        		<tr ng-repeat="row in stockDetailList">
					          			<td ng-if="!row.updateMode">{{row.description}}</td>
					          			<td ng-if="row.updateMode"><input type="text" placeholder="Description" ng-model="row.description" name="description" class="form-control" required /></td>
							          	
							          	<td ng-if="!row.updateMode">{{row.quantity}}</td>
							          	<td ng-if="row.updateMode"><input type="text" placeholder="Stock In" ng-model="row.quantity" name="quantity" class="form-control" required /></td>
							          	
							          	<td ng-if="!row.updateMode">{{row.return_quantity}}</td>
							          	<td ng-if="row.updateMode"><input type="text" placeholder="Return Stock" ng-model="row.return_quantity" name="return_quantity" class="form-control" required /></td>

							          	<td>{{row.created_at}}</td>
								        <td style="width:100px">
								            <ul class="table-options">
								              	<li ng-if="!row.updateMode"><a href="" ng-click="updateStock(row)" title="Update"><i class="fa fa-pencil"></i></a></li>
								              	<li ng-if="row.updateMode"><a href="" ng-click="saveUpdateStock(row)" title="Update"><i class="fa fa-save"></i></a></li>
								              	<li><a href="" ng-click="deleteStock(row)"><i class="fa fa-trash"></i></a></li>
								            </ul>
								        </td>
					        		</tr>
					      		</tbody>
					    	</table>
					  	</div>
				  	</div>
			    </div>
		  	</div>
		</div>
		<!-- end: add new stock model -->
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
