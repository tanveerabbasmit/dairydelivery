<style>
	.angularjs-datetime-picker{
		z-index: 99999 !important;
	}
</style>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/QualityReport/QualityReport-grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
	<div class="modal-content">
		<div class="modal-body">
			<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
			<span>&nbsp;&nbsp;Loading... </span>
		</div>
	</div>
</div>
<?php $company_id = Yii::app()->user->getState('company_branch_id'); ?>
<?php $allow_delete = crudRole::getCrudrole(21); ?>



<div class="panel row" id="testContainer" style="display: none" ng-app="clintManagemaent">
	<div ng-controller="clintManagemaent" ng-init='init(<?php echo $productList  ?> ,<?php echo json_encode($todayDate);  ?> , <?php echo $allow_delete ?> ,<?php echo $listResult ?> , <?php echo $qualityreport ?> , "<?php echo Yii::app()->createAbsoluteUrl('milkDailyQuallityreport/saveQuanlityReport'); ?>" )'>

		<div id="alertMessage"  class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
			<button type="button" class="close" data-dismiss="alert">x</button>
			<strong>message! </strong>
			{{taskMessage}}
		</div>

		<ul class="nav nav-tabs nav-tabs-lg">
			<li>
				<a href="#tab_1" data-toggle="tab" aria-expanded="false">
					Quality Report
				</a>
			</li>
		</ul>
		
		<div class="panel-body">

            <div class="tab-content col-lg-12">
                <input ng-change="chageValue()" style="float: left ; width: 20% ;" class="form-control input-sm"  datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="todayDate" size="2">
                <!--  <button class="btn btn-info btn-sm" style="float: left"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
                  <input style="float: left ; width: 20% ;" class="form-control input-sm"  ng-change="selectRiderOnChange(selectRiderID)"  datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">-->
                <select ng-change="chageValue()" style="width: 20%; float: left;margin-bottom: 10px;margin-left: 10px ; margin-right: 10px" class="form-control input-sm" ng-model="animal_type" >
                    <option value="0">Select Product</option>
                    <option ng-repeat="list in productList" value="{{list.product_id}}">{{list.name}}</option>
                </select>
                <button type="button"  ng-click="selectOneDateData()" class="btn btn-primary btn-sm"> <i style="margin: 4px" class="fa fa-search"></i> Search</button>
                <a ng-disabled="true" class="btn btn-primary btn-sm " href="#"><i style="margin: 4px" class="fa fa-share"></i> Export </a>
                <button ng-show="false" class="btn btn-info btn-sm" style="float: left; margin-left: 5px" onclick="javascript:xport.toCSV('customers');"> <i class="fa fa-share"></i> Export</button>
                <img ng-show="imageLoading" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            </div>

			<form ng-submit="saveQualityReport()">
				<div class="col-lg-12">
					<div class="col-lg-6" style="background-color: honeydew">

						<div class="col-lg-4 " style="padding-top: 10px" >
							<span style="font-weight: bold;">Protein :</span>
						</div>
						<div class="col-lg-8">
									<input  class="form-control"    type="text" required ng-model="qualityreport.protein" size="2">
						</div>
					</div>
					<div class="col-lg-6" style="background-color: honeydew">
						<div class="col-lg-4" style="padding-top: 10px">
							<span style="font-weight: bold;">Lactose :</span>
						</div>
						<div class="col-lg-8">
							<input  type="text" ng-model="qualityreport.lactose" class="form-control">
						</div
					</div>
				</div>
				<div class="panel-body">
				</div>
				<div class="col-lg-6" style="background-color: 	#FFF0F5">
					<div class="col-lg-4" style="padding-top: 10px">
						<span style="font-weight: bold;">Fat :</span>
					</div>
					<div class="col-lg-8">
						<input  type="text" ng-model="qualityreport.fat" class="form-control" required>
					</div>
				</div>

				<div class="col-lg-6" style="background-color: 	#FFF0F5">
					<div class="col-lg-4" style="padding-top: 10px">
						<span style="font-weight: bold;">Salt :</span>
					</div>
					<div class="col-lg-8">

						<input  type="text" ng-model="qualityreport.salt" class="form-control" required>
					</div>
				</div>
				<div class="col-lg-6">

				</div>

				<div class="panel-body">
				</div>
				<div class="col-lg-6" style="background-color: 	honeydew">
					<div class="col-lg-4" style="padding-top: 10px">
						<span style="font-weight: bold;" ng-show="false">Adulterants :</span>
						<span style="font-weight: bold;">Density  :</span>
					</div>
					<div class="col-lg-8">

						<input  type="text" ng-model="qualityreport.adulterants" class="form-control" required>
					</div>
				</div>

				<div class="col-lg-6" style="background-color: 	honeydew">
					<div class="col-lg-4" style="padding-top: 10px">

					</div>
					<div class="col-lg-8">
						<button ng-disabled="allow_delete[1]" ng-disabled="imageLoader" type="submit" class="btn  btn-info next" > <i class="fa fa-edit"></i> Save</button>
						<img ng-show="imageLoader" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
					</div>
				</div>

				<div class="panel-body">
				</div>

			</form>
		</div>

		<div class="col-lg-12" style="height: 10px"></div>
		<table class="table table-striped nomargin">
			<thead>
			<tr>
				<th>Product</th>
				<th>Date</th>
				<th>Protein</th>
				<th>Lactose</th>
				<th>Fat</th>
				<th>Salt</th>
				<th>Density</th>
			</tr>
			</thead>
			<tbody>
			<tr ng-repeat="zone in listResult">
				<td>
                    <span ng-bind="zone.name"></span>

                </td>
				<td>{{zone.date}}</td>
				<td>{{zone.protein}}</td>
				<td>{{zone.lactose}}</td>
				<td>{{zone.fat}}</td>
				<td>{{zone.salt}}</td>
				<td>{{zone.adulterants}}</td>

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

