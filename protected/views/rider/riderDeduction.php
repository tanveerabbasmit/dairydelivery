
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/riderDeduction/riderDeduction-grid.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/FileSaver/FileSaver.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
	<div class="modal-content">
		<div class="modal-body">
			<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
			<span>&nbsp;&nbsp;Loading... </span>
		</div>
	</div>
</div>


<div  id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">
	<div ng-controller="riderDailyStockGridCtrl" ng-init='init( <?php echo $year ?>  , <?php echo $company_id ?> , <?php echo $riderList; ?>,"<?php echo Yii::app()->createAbsoluteUrl('rider/oneYearDeduction'); ?>"," <?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/googleMap');?>" ," <?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/saveDeliveryFromPortal');?>")'>

			<div class="tabbable">
			<ul class="nav nav-tabs nav-tabs-lg">
				<li>
					<a href="#tab_1" data-toggle="tab" aria-expanded="false">
						Rider Wise Recovery Report
					</a>
				</li>
			</ul>
			<div class="tab-content">
				<div class=" active" id="tab_1" style="margin: 10px">
                        <!--<select ng-model="clientID" class="form-control" style="width: 30%; float: left" >
                            <option value="">Select Customer </option>
                          <option ng-repeat="list in clientList" value="{{list.client_id}}">{{list.fullname}}</option>
                        </select>-->
                   <!-- <select style="float: left ; width: 15% ;" class="form-control input-sm" ng-model="monthNum">
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>-->

					<!--<input style="float: left ; width: 22% ;" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">-->
					<!--<button  style="float: left" type="button"  ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-info btn-sm"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
					<input ng-disabled="false" style="float: left ; width: 22% ;" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">-->

                    <select style="float: left ; width: 15% ;" class="form-control input-sm"  ng-model="year">
                        <option value="2016">2016</option>
                        <option value="2017">2017</option>
                        <option value="2018">2018</option>
                        <option value="2019">2019</option>
                        <option value="2020">2020</option>
                        <option value="2021">2021</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                        <option value="2027">2027</option>
                        <option value="2028">2028</option>
                        <option value="2029">2029</option>
                    </select>

					<select style="width: 23%; float: left;margin-bottom: 10px;margin-left: 10px ; margin-right: 10px" class="form-control input-sm" ng-model="selectRiderID" ng-change="changeSelectRider(selectRiderID)">
						<option value="0">Select Rider</option>
						<option ng-repeat="list in riderList" value="{{list.rider_id}}">{{list.fullname}}
						</option>
					</select>
					<button type="button"  ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-primary btn-sm"> <i class="fa fa-search" style="margin: 5px"></i> Search</button>
					<button ng-show="false" type="button"  ng-click="printFunction()" class="btn btn-primary btn-sm"> <i class="fa fa-print" style="margin: 5px"></i> Print</button>

					<button ng-show="false" class="btn btn-info btn-sm" style="margin-left: 5px;" onclick="javascript:xport.toCSV('customers');"> <i class="fa fa-share"></i> Export</button>

					<img ng-show="imageLoading" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
                     <!-- {{todayDeliveryproductList}}-->

				</div>
			</div>



            <div style="margin-top:0px;" class="table-responsive">
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
                            <th><a href="#">#</a></th>
                            <th><a href="#">Month</a></th>
                            <th width="30%" ><a href="#">Deduction</a></th>
                            <th ><a href="#">Action</a></th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="zone in deductionAmount  track by $index ">
                            <td>{{$index + 1}}</td>
                            <td>{{zone.month_name}}</td>
                            <td>
                               <span ng-show="!zone.update">{{zone.deduction_amount}}</span>
                               <span ng-show="zone.update"><input class="form-control" ng-model="zone.deduction_amount"> </span>
                            </td>
                           <td >
                                <button title="Edit" ng-show="!zone.update" ng-disabled=" allow_delete[3]" ng-click="editDeduction(zone)"  class="btn btn-sm btn-default next btn-xs" > <i class="fa fa-edit "></i> </button>
                                <button  title="Save" ng-show="zone.update" ng-disabled=" allow_delete[3]" ng-click="SaveDeduction(zone)"  class="btn btn-sm btn-info next btn-xs" > <i class="fa fa-save "></i> </button>

                            </td>
                        </tr>
                        </tbody>
                    </table>



                </div><!-- table-responsive -->

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
