<style>
	.angularjs-datetime-picker{
		z-index: 99999 !important;
	}
</style>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/followUP/followUp-grid.js"></script>



<link href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css" />


<link href="<?php echo Yii::app()->theme->baseUrl; ?>/js/monthPickerFile/examples.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/js/monthPickerFile/MonthPicker.min.css" rel="stylesheet" type="text/css" />
<script src="https://code.jquery.com/jquery-1.12.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
	<div class="modal-content">
		<div class="modal-body">
			<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
			<span>&nbsp;&nbsp;Loading... </span>
		</div>
	</div>
</div>
<?php $company_id = Yii::app()->user->getState('company_branch_id'); ?>

<?php $allow_delete = crudRole::getCrudrole(43); ?>

<div class="panel row" id="testContainer" style="display: none" ng-app="clintManagemaent">
	<div ng-controller="clintManagemaent" ng-init='init( <?php echo  $allow_delete ?> , <?php echo  $dropReasonList ?> , <?php echo $todayYear ?> , <?php echo $todayMonth ?> , <?php  echo $company_id  ?> , <?php echo $clientList ?> ,
	 "<?php echo Yii::app()->createAbsoluteUrl('followUp/saveFollowUp'); ?>" ,
	  "<?php echo Yii::app()->createAbsoluteUrl('followUp/oneCustomerAmountList'); ?>" , "<?php echo Yii::app()->createAbsoluteUrl('api/checkAccountBalnce'); ?>")'>


		<div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
			<button type="button" class="close" data-dismiss="alert">x</button>
			<strong>message! </strong>
			{{taskMessage}}
		</div>

		<ul class="nav nav-tabs nav-tabs-lg">
			<li>
				<a href="#tab_1" data-toggle="tab" aria-expanded="false">
					Follow Up ON SAMPLES
				</a>
			</li>
		</ul>


		<div class="panel-body">

			<form ng-submit="savePaymernt()">

                <div class="col-lg-6" style="background-color: 	honeydew">
                    <div class="col-lg-2" style="padding-top: 10px">
                        <span style="font-weight: bold;">Customer</span>
                    </div>
                    <div class="col-lg-10">


                        <div style="float: left;">
                            <button class="btn btn-default dropdown-toggle " ng-click="showDropDownList()" type="button" id="dropdownMenu1" data-toggle="dropdown">{{SelectedCustomer}} <span  class="caret"></span>
                            </button>
                            <ul style="width: 200px ;max-height: 500px ; overflow-x: hidden;overflow-y: scroll;" class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1" >
                                <li role="presentation">
                                    <div class="input-group input-group-sm search-control"> <span class="input-group-addon">
									<span ></span>
									</span>
                                        <input autofocus type="text" class="form-control" placeholder="Search" ng-model="query" id="serachCustomerBar">
                                    </div>

                                </li >
                                <li  role="presentation" ng-click="abcd(item)" ng-repeat='item in clientList | filter:query'> <a href="#"> {{item.fullname}} </a>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>

                <div class="col-lg-6" style="background-color: honeydew">

                    <div class="col-lg-2" style="padding-top: 10px" >
                        <span style="font-weight: bold;">Date :</span>
                    </div>
                    <div class="col-lg-10">
                        <input  class="form-control"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="paymentObject.startDate" size="2">
                    </div>
                </div>

                <div class="panel-body">
                </div>

				<div class="col-lg-12">
					<div class="col-lg-12" style="background-color: honeydew">

						<div class="col-lg-3 " style="padding-top: 10px" >
							<span style="font-weight: bold;">Follow up Remarks:</span>
						</div>
						<div class="col-lg-7">
							<input  class="form-control"     required ng-model="paymentObject.amount_paid" size="2">
						</div>
					</div>

				</div>
				<div class="panel-body">
				</div>

				<div class="col-lg-12" style="background-color: 	honeydew">

					<div class="col-lg-2">
						<button ng-disabled="imageLoader || allow_delete[1]" type="submit" class="btn btn-sm btn-info next" > <i class="fa fa-edit"></i> Save Follow Up</button>
						<img ng-show="imageLoader" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
					</div>

                    <div class="col-lg-2">
                        <button ng-disabled="imageLoader2 || allow_delete[1]" ng-click="makeRegular(1)" type="button" class="btn btn-sm btn-info next" > <i class=""></i> Make Regular</button>
                        <img ng-show="imageLoader2" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
                    </div>

                    <div class="col-lg-2">
                        <button ng-disabled=" allow_delete[2]" ng-click="dropSection()" type="button" class="btn btn-sm btn-info next" > <i class=""></i> Drop</button>

                    </div>
				</div>


                <div class="panel-body">
                </div>

                <div ng-show="selectReasonSection" class="col-lg-12" style="background-color: 	honeydew">

                    <div class="col-lg-2 " style="padding: 10px">

                        <span style="font-weight: bold;">Select Reason:</span>
                    </div>

                    <div class="col-lg-7">
                       <select  class="form-control" ng-model="sample_client_drop_reason_id">
                           <option value="0">Select Reason</option>
                           <option  value="{{list.sample_client_drop_reason_id}}" ng-repeat="list in dropReasonList">{{list.reason}}</option>
                       </select>
                    </div>

                    <div class="col-lg-2" style="padding: 10px">
                        <button ng-disabled="imageLoader3" ng-click="makeDrop(2)" type="button" class="btn btn-sm btn-info next" > <i class=""></i> Save</button>
                        <img ng-show="imageLoader3" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
                    </div>

                </div>

			</form>

			<div  class="col-lg-12" ng-show="address" style="background-color: #FFF8DC; margin-top: 10px">
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
                    <th><a href="#">Date</a></th>
                    <th><a href="#">follow up Remarks</a></th>

                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="product in OneCustomerPaymentData track by $index">
                    <td>{{$index + 1}}</td>
                    <td>{{product.date}}</td>
                    <td>{{product.remarks}}</td>



                </tr>
                </tbody>
            </table>



        </div><!-- table-responsive -->
        <div class="panel-body">
        </div>

	<style>
		.dropdown.dropdown-scroll .dropdown-menu {
			max-height: 200px;
			width: 60px;
			overflow: auto;
		}
	</style>
</div>


	<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/monthPickerFile/examples.js"></script>
	<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/monthPickerFile/MonthPicker.min.js"></script>

