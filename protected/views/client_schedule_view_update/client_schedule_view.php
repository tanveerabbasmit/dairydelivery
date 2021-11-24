<style>
	.angularjs-datetime-picker{
		z-index: 99999 !important;
	}
</style>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/client_schedule_view_update/client_schedule_view_grad.js"></script>
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




<div class="panel row" id="testContainer" style="display: none" ng-app="clintManagemaent">
	<div ng-controller="clintManagemaent" ng-init='init(<?php echo $data; ?> , "<?php echo Yii::app()->createAbsoluteUrl('client_schedule_view_update/base'); ?>")'>


		<div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
			<button type="button" class="close" data-dismiss="alert">x</button>
			<strong>message! </strong>
			{{taskMessage}}
		</div>

		<ul class="nav nav-tabs nav-tabs-lg">
			<li>
				<a href="#tab_1" data-toggle="tab" aria-expanded="false">
                    Schedule View<span ng-show="showBalance" style="margin-left: 10px">  Current Balance : </span> <span style="color: #D3D3D3; margin-left: 2px"> {{OneCustomerOustaningBalance }} </span>
				</a>
			</li>
		</ul>
		<div class="panel-body">

			<form ng-submit="savePaymernt()">
				<!--<div class="col-lg-12">
					<div class="col-lg-12" style="background-color: honeydew">

						<div class="col-lg-4 " style="padding-top: 10px" >
							<span style="font-weight: bold;">Date :</span>
						</div>
						<div class="col-lg-8">
                            <input  class="form-control" datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="paymentObject.startDate" size="2">
                        </div>
					</div>

				</div>-->



				<div class="col-lg-12" style="background-color: 	honeydew">


                    <div class="col-lg-5">
                        <div class="col-lg-4" style="padding-top: 10px">
                            <span style="font-weight: bold;">Product : </span>
                        </div>
                        <div class="col-lg-5">
                            <select ng-model="product_id" class="form-control" ng-model="product_id">
                                <option value="0">Select</option>
                                <option ng-repeat="list in product_list" value="{{list.product_id}}">{{list.name}}</option>
                            </select>
                        </div>
                    </div>


				</div>
                <div class="panel-body">
                </div>
                <div class="col-lg-12" style="background-color: 	honeydew">

                    <div class="col-lg-5">
                        <div class="col-lg-4" style="padding-top: 10px">
                            <span style="font-weight: bold;">Customer : </span>
                        </div>
                        <div class="col-lg-8">
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
                            <img ng-show="loadClientLoader" style="margin: 10px" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">

                            <a  ng-show="client_id !='0'" href="" ng-click="get_today_schedual_function_button()" style="padding: 30px;"><i style="margin-top: 13px" class="fa fa-refresh" aria-hidden="true"></i></a>

                        </div>
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

            <div  class="col-lg-12" ng-show="address" style="background-color:honeydew; margin-top: 10px">
                <div style="float: left">
                    <span style="font-weight: bold;">Selected Schedule :  </span> {{schedule_data.order_type_name}}
                </div>
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


        <div class="col-lg-12">


            <table id="customers"  ng-show="schedule_data.order_type=='1'">
                <thead>
                <tr>
                    <th><a href="#"> #</a></th>
                    <th><a href="#">Day</a></th>
                    <th style="text-align: center"><a href="#">Quantity</a> </th>

                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="list in schedule_data.weekly_data">
                    <td>
                        {{$index + 1}}
                    </td>
                    <td><span ng-bind="list.day_name"></span> </td >
                    <td style="text-align: center"><span ng-bind="list.quantity"></span> </td >

                </tr>

                </tbody>
            </table>
            <table id="customers"  ng-show="schedule_data.order_type=='2'">

                <tbody>
                <tr>
                    <td>
                       Start date
                    </td>
                    <td>
                        {{schedule_data.weekly_data.start_interval_scheduler}}
                    </td>

                </tr>

                <tr>
                    <td>
                        Interval
                    </td>
                    <td>
                        {{schedule_data.weekly_data.interval_days}}
                    </td>

                </tr>

                <tr>
                    <td>
                        Quantity
                    </td>
                    <td>
                        121{{schedule_data.weekly_data.product_quantity}}
                    </td>

                </tr>

                </tbody>
            </table>
            <div class="panel-body">
            </div>
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

