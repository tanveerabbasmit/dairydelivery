<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/customerList/customerList-grid.js"></script>
<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
	<div class="modal-content">
		<div class="modal-body">
			<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
			<span>&nbsp;&nbsp;Loading... </span>
		</div>
	</div>
</div>


<div id="testContainer" style="display: none" class="panel row" ng-app="riderDailyStockGridModule">
	<div ng-controller="riderDailyStockGridCtrl" ng-init='init(  <?php  echo $companyLimit ?> ,<?php echo str_replace("'","&#39;", $totalResult);  ?> , <?php echo str_replace("'","&#39;", $customerList); ?>,
	 "<?php echo Yii::app()->createAbsoluteUrl('companyLimit/startDateSearchCustomerList'); ?>","<?php echo Yii::app()->createAbsoluteUrl('companyLimit/saveCompanyLimit'); ?>")'>

		<div class="">

				<ul class="nav nav-tabs nav-tabs-lg">
					<li>
						<a href="#tab_1" data-toggle="tab" aria-expanded="false">
							Customer Exceeding Credit Limit Bill paid After That Date
						</a>
					</li>
					<li>
						<a href="#tab_1" data-toggle="tab" aria-expanded="false" ng-click="setCompanyLimit()">
							Set Company  Limit
						</a>
					</li>
				</ul>
				<div class="tab-content">

					<div class=" active" id="tab_1" style="margin-bottom: 10px; margin: 10px">
						<span style="float: left ;margin-top: 13px"><strong>Search by last bill paid : </strong></span>
						<input  style="float: left ; width: 25% ;margin-bottom: 10px ; margin-left: 10px" class="form-control input-sm"  ng-change="selectRiderOnChange(selectRiderID)"  datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="todate" size="2">
						<select ng-show="false" style="width: 25%; float: left;margin-bottom: 10px;margin-left: 10px ; margin-right: 10px" class="form-control input-sm" ng-model="selectRiderID" >
							<option value="0">Select Rider</option>
							<option ng-repeat="list in riderList" value="{{list.rider_id}}">{{list.fullname}}
							</option>
						</select>
						<button ng-disabled="showProgressBar" style="margin-left: 10px;" type="button"  ng-click="startDateSearch()" class="btn btn-primary btn-sm "> <i class="fa fa-search" style="margin: 4px"></i> Search</button>
						<button ng-disabled="showProgressBar" style="margin-left: 10px;" type="button"  ng-click="startDateSearch_all()" class="btn btn-primary btn-sm "> <i class="fa fa-search" style="margin: 4px"></i> Search All</button>

						<img ng-show="imageLoader" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
					</div>
					<div style=" margin: 10px"  id="tab_1">
                  <!-- {{riderList}}-->

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
							<tr style="background-color: #F0F8FF">
								<th><a href="#">#</a> </th>
								<th>
									<a href="#" ng-click="sortType = 'address'; sortReverse = !sortReverse">
										Customer Name
										<span ng-show="sortType == 'address' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
										<span ng-show="sortType == 'address' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
									</a>
								</th>
								<th>
									<a href="#" ng-click="sortType = 'address'; sortReverse = !sortReverse">
										Contact No.
										<span ng-show="sortType == 'address' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
										<span ng-show="sortType == 'address' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
									</a>
								</th>
								<th>
									<a href="#" ng-click="sortType = 'address'; sortReverse = !sortReverse">
										Address
										<span ng-show="sortType == 'address' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
										<span ng-show="sortType == 'address' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
									</a>
								</th>
								<th>
									<a href="#" ng-click="sortType = 'zone_name'; sortReverse = !sortReverse">
										Zone
										<span ng-show="sortType == 'address' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
										<span ng-show="sortType == 'address' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
									</a>
								</th>
								<th width="15%">
									<a href="#">
										Balance
										<span ng-show="sortType == 'selectQuantity' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
										<span ng-show="sortType == 'selectQuantity' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
									</a>
								</th>
								<th width="15%">
									<a href="#">
										Limit
										<span ng-show="sortType == 'selectQuantity' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
										<span ng-show="sortType == 'selectQuantity' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
									</a>
								</th>
							</tr>
							</thead>
							<tbody>
							<tr  ng-repeat="regularOrderList in customerList  | orderBy:'-banlanceAmount' track by $index" >
								<td>{{$index+1}}</td >
								<td> <span ng-bind="regularOrderList.fullname"></span></td >
								<td><span ng-bind="regularOrderList.cell_no_1"></span></td >
								<td> <span ng-bind="regularOrderList.address"></span></td >
								<td > <span ng-bind="regularOrderList.zone_name"></span></td >
								<td style="text-align: center"><span ng-bind="regularOrderList.banlanceAmount | number  :2"></span></td>
								<td><span ng-bind="companyLimit"></span></td>
							</tr>
                            <tr>
                                <td colspan="5">Total</td>
                                 <td style="text-align: center">{{total_count | number :2}}</td>
                                 <td></td>
                            </tr>
							</tbody>
						</table>
					<!--	<div class="pagination pagination-centered">
							<button type="button" class="btn btn-sm btn-info prev" title="Prev" ng-disabled="curPage == 0" ng-click="nextPagePagination(curPage =0 )"> First </button>
							<button type="button" class="btn btn-sm btn-info prev" title="Prev" ng-disabled="curPage == 0" ng-click="nextPagePagination( curPage =curPage - 1)"> &lt; PREV</button>
							<span>Page {{curPage +1}} of {{totalPages}}</span>
							<button type="button" class="btn btn-sm btn-info next" ng-disabled="curPage >= totalPages - 1" ng-click="nextPagePagination(curPage = curPage + 1)"> NEXT &gt;</button>
							<button type="button" class="btn btn-sm btn-info next" ng-disabled="curPage >= totalPages - 1" ng-click="nextPagePagination(curPage  = totalPages - 1)">Last</button>

						</div>-->

					</div>
  				</div>

			<!--Company Limit Model-->
			<modal title="Set Company Limit" visible="limitModelShow">
				<div class="row">
					<?php
					$form = $this->beginWidget(
					'CActiveForm',
					array(
					'id' => 'agreement-form',
					'enableAjaxValidation' => false,

					)
					);
				?>
					<div class="col-sm-12">
						<div class="col-sm-12">

							<label for="email" style="font-weight: bold;float: left;margin: 10px">Company Limit :</label>
							<input style="float: left; width: 40%" type="number" class="form-control" name="companyLimit" placeholder="" ng-model="companyLimit" required />

						</div>
						<div class="col-sm-12">
							<div style="margin: 12px">
							<button  type="submit" class="btn-success  btn-sm">Save</button>
							<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
					    </div>
					</div>

				<?php $this->endWidget(); ?>
				</div>

			</modal>

			<!--Company Limit Model-->

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
