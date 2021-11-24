
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/qualityValue/qualityValue-grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
	<div class="modal-content">
		<div class="modal-body">
			<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
			<span>&nbsp;&nbsp;Loading... </span>
		</div>
	</div>
</div>


<div  id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">
	<div ng-controller="riderDailyStockGridCtrl" ng-init='init( <?php echo str_replace("'","&#39;",$Quality ); ?>,<?php echo str_replace("'","&#39;",$farmList ); ?>,"<?php echo Yii::app()->createAbsoluteUrl('FarmQuality/getFarmQualityValue'); ?>"," <?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/googleMap');?>" ," <?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/saveDeliveryFromPortal');?>")'>

		<div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
			<button type="button" class="close" data-dismiss="alert">x</button>
			<strong>message! </strong>
			{{taskMessage}}
		</div>

		<div class="tabbable">
			<ul class="nav nav-tabs nav-tabs-lg">
				<li>
                    <a  href="<?php echo Yii::app()->baseUrl; ?>/qualityList/qualityListManage"  type="button"   class="btn btn-primary btn-sm"> <i class=""></i>
                        Quality
                    </a>
				</li>
                <li style="margin-left: 2px">
                    <a  href="<?php echo Yii::app()->baseUrl; ?>/Farm/farmManage"  type="button"   class="btn btn-primary btn-sm"> <i class=""></i>
                        Farm
                    </a>
                </li>

                <li style="margin-left: 2px">
                    <a  href="<?php echo Yii::app()->baseUrl; ?>/FarmQuality/valueList"  type="button"   class="btn btn-primary btn-sm"> <i class=""></i>
                        Quality Report
                    </a>
                </li>
			</ul>
          <!--  {{productList}}
			{{todayData}}-->


			<div class="tab-content">
				<div class=" active" id="tab_1" style="margin-bottom: 10px; margin: 10px">
					<input  style="float: left ; width: 25% ;" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="todate" size="2">

					<select ng-show="true" style="width: 25%; float: left;margin-bottom: 10px;margin-left: 10px ; margin-right: 10px" class="form-control input-sm" ng-model="selectFarmID" >
						<option value="0">Select Farm</option>
						<option ng-repeat="list in farmList" value="{{list.farm_id}}">{{list.farm_name}}
						</option>
					</select>

					<button style="margin-left: 10px;" type="button"  ng-click="selectQalityValie()" class="btn btn-primary btn-sm "> <i class="fa fa-search" style="margin: 4px"></i> Search</button>

					<a ng-disabled="true" class="btn btn-primary btn-sm" href="<?php echo Yii::app()->createUrl('#')?>"><i class="fa fa-share" style="margin: 4px"></i> Export </a>
					<img ng-show="imageLoading" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">

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

					<table id="customers" style="margin-top: 6px">
						<thead>
						<tr style="background-color: #F0F8FF">
							<th><a href="#"> #</a></th>

							<th>
								<a href="#"> Quality</a>
							</th>
                            <th width="250px">
                                <a href="#"> Value</a>
                            </th>


						</tr>
						</thead>
						<tbody>

                          <tr ng-repeat="data in farmQualityValueList track by $index">
							  <td>{{$index+1}}</td>

							  <td>{{data.quality_name}}</td>
							  <td width="250px"><input  type="text" class="form-control" ng-model="data.quantity_value"></td>

						  </tr>
                          <tr ng-show="loadData">
                              <td colspan="2"></td>
                              <td>
                                  <button  ng-disabled="save_button" style="margin-left: 10px;" type="button"  ng-click="saveVAlue()" class="btn btn-primary btn-sm "> <i class="fa fa-save" style="margin: 4px"></i> Save</button>
                              </td>
                          </tr>



						</tbody>
					</table>
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
