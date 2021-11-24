<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/dailyStock/viewDailyStock_grid.js"></script>
<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
	<div class="modal-content">
		<div class="modal-body">
			<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
			<span>&nbsp;&nbsp;Loading... </span>
		</div>
	</div>
</div>


<div id="testContainer" style="display: none"  class="panel row" ng-app="DailyStockGridModule">
	<div ng-controller="DailyStockGridCtrl" ng-init='init(<?php echo $data; ?>,"<?php echo Yii::app()->createAbsoluteUrl('dailyStock/saveStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('dailyStock/searchStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('dailyStock/saveNewStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('dailyStock/stockDetail'); ?>","<?php echo Yii::app()->createAbsoluteUrl('dailyStock/deleteStock'); ?>")'>

			<div class="tabbable">
				<ul class="nav nav-tabs nav-tabs-lg">
					<li class="active">
						<a href="#tab_1" data-toggle="tab" aria-expanded="true">
							View Stock	 </a>
					</li>
					<li class="">
						<a href="#tab_2" data-toggle="tab" aria-expanded="false" ng-click="addNewStock()">
							 Add Stock
						</a>
					</li>
				</ul>
				<div class="col-lg-12">
					<div class="col-lg-4" style="margin: 10px">
						<div class="input-group">
							<input  class="form-control"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="searchDate" size="2">
							<span class="input-group-addon" ng-click="dateChange()"><i class="glyphicon glyphicon-search"></i></span>
						</div>
					</div>
				</div>

				<div class="tab-content">
					<div class="tab-pane active" id="tab_1">
						<table class="table table-striped nomargin">
							<thead>
							<tr>
								<th>Product Name</th>
								<th>Stock Avalaible</th>
								<th>Stock Return</th>
								<th>Wastage</th>
								<th>Date</th>
								<th></th>
							</tr>
							</thead>
							<tbody>
							<tr ng-repeat="dailyStock in dailyStockList">
								<td> <span ng-bind="dailyStock.product_name"></span> </td>
								<td> <span ng-bind="dailyStock.total_quantity"></span></td>
								<td><span ng-bind="dailyStock.total_return_quantity"></span></td>
								<td><span ng-bind="dailyStock.total_wastage"></span></td>
								<td>  <span ng-bind="dailyStock.date"></span></td>
								<td>
									<ul class="table-options">
										<li><a href="" ng-click="stockDetail(dailyStock)" data-toggle="modal" data-target="#dailyStockDetailModal" title="Stock Details"><i class="fa fa-eye"></i></a></li>
										<!-- <li><a href="" ng-click="deleteStock(dailyStock)"><i class="fa fa-trash"></i></a></li> -->
									</ul>
								</td>
							</tr>
							</tbody>
						</table>
					</div>
					<div class="tab-pane" id="tab_2">
                          {{productList}}
						<form  class="form-horizontal">

							<table class="table table-striped nomargin">
								<thead>
								<tr>
									<th>Product Name</th>
									<th>Quantity</th>
									<th>Return</th>
									<th>Wastage</th>
									<th>Remarks</th>
								</tr>
								</thead>
								<tbody>
								<tr ng-repeat="product in productList">
									<th> <span ng-bind="product.name"></span> </th>
									<td> <input ng-disabled="!product.stockModel.select" type="number"  ng-model="product.stockModel.quantity" name="quantity" class="form-control" /></td>
									<td><input ng-disabled="!product.stockModel.select" type="text"  ng-model="product.stockModel.description" name="description" class="form-control"/></td>
									<td>{{product.stockModel.return_quantity}} <input ng-disabled="!product.stockModel.select" type="text"  ng-model="product.stockModel.return_quantity" name="description" class="form-control"/></td>
									<td><input ng-disabled="!product.stockModel.select" type="text"  ng-model="product.stockModel.wastage" name="description" class="form-control"/></td>
								</tr>
								</tbody>
							</table>
							<div class="modal-footer">
								<img  ng-show="showLoaderImage"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
								<button type="submit" ng-click="saveNewStock()"   class="btn btn-success btn-quirk btn-wide mr5">Save</button>
								<button type="button"  ng-click="saveNewStockReset()" class="btn btn-default" data-dismiss="modal">Reset</button>
							</div>
						</form>
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
			                      		<input ng-disabled="!product.stockModel.select" type="text" placeholder="Purchase Quantity" ng-model="product.stockModel.quantity" name="quantity" class="form-control" required />
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
							          	<th>Wastage</th>
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

                                        <td ng-if="!row.updateMode">{{row.wastage}}</td>
                                        <td ng-if="row.updateMode"><input type="text" placeholder="Return Stock" ng-model="row.wastage" name="return_quantity" class="form-control" required /></td>

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
