<style xmlns="http://www.w3.org/1999/html">
	.angularjs-datetime-picker{
		z-index: 99999 !important;
	}
</style>

<style>
	.modal{
	//  display: block !important; /* I added this to see the modal, you don't need this */
	}

	/* Important part */
	.modal-dialog{
		overflow-y: initial !important
	}
	.modal-body{
		max-height: 600px;
		overflow-y: auto;
	}


</style>

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/sampleClient/sampleClient-grid.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/BootstrapDatepicker_files/moment-with-locales.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/BootstrapDatepicker_files/bootstrap-datetimepicker.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/BootstrapDatepicker_files/bootstrap.min.j"></script>


<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/BootstrapDatepicker_files/font-awesome.min.css">
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/BootstrapDatepicker_files/prettify-1.0.css" rel="stylesheet">
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/BootstrapDatepicker_files/base.cs" rel="stylesheet">
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/BootstrapDatepicker_files/bootstrap-datetimepicker.css" rel="stylesheet">

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
	<div class="modal-content">
		<div class="modal-body">
			<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
			<span>&nbsp;&nbsp;Loading... </span>
		</div>
	</div>
</div>

<?php $allow_delete = crudRole::getCrudrole(3); ?>



<div id="testContainer" style="display: none" class="panel row" ng-app="clintManagemaent">
	<div ng-controller="clintManagemaent" ng-init='init( <?php echo ($saleRepsList);?> ,<?php echo str_replace("'","&#39;",$reasonList ); ?>,<?php echo str_replace("'","&#39;",$blockList ); ?>,<?php echo str_replace("'","&#39;",$areaList ); ?>, <?php echo $allow_delete ?> ,<?php echo str_replace("'","&#39;",$CategoryList ); ?> ,<?php echo $companyID ?> ,  <?php echo str_replace("'","&#39;",$ClientOneObject ); ?> , <?php  echo $preferedTimeList ?>  , <?php echo $frequencyList ?> , <?php echo $productCount  ?> , <?php echo $clientCount ?> , <?php echo str_replace("'","&#39;",$zoneList ); ?> ,<?php echo str_replace("'","&#39;",$clientList ); ?>,"<?php echo Yii::app()->createAbsoluteUrl('client/saveNewClient'); ?>"
	, "<?php echo Yii::app()->createAbsoluteUrl('client/EditClient'); ?>", "<?php echo Yii::app()->createAbsoluteUrl('client/delete'); ?>" , "<?php echo Yii::app()->createAbsoluteUrl('client/checkAlredyExistClient'); ?>", "<?php echo Yii::app()->createAbsoluteUrl('client/searchClient'); ?>",
	 "<?php echo Yii::app()->createAbsoluteUrl('client/nextPagePagination_sample'); ?>", "<?php echo Yii::app()->createAbsoluteUrl('client/getProductList'); ?>"
	 , "<?php echo Yii::app()->createAbsoluteUrl('client/PnextPagePagination'); ?>", "<?php echo Yii::app()->createAbsoluteUrl('client/selectFrequencyForOrder'); ?>", "<?php echo Yii::app()->createAbsoluteUrl('client/saveChangedayObjectQuantity'); ?>", "<?php echo Yii::app()->createAbsoluteUrl('client/removeProductFormSchedual'); ?>", "<?php echo Yii::app()->createAbsoluteUrl('client/getProductPriceList'); ?>")'>
		<ul class="nav nav-tabs nav-tabs-lg">
			<li>
				<a href="#tab_1" data-toggle="tab" aria-expanded="false">
					Total Registered Customer &nbsp&nbsp&nbsp  {{clientCount}}
				</a>
			</li>
			<li>
				<a href="#tab_1" data-toggle="tab" aria-expanded="false" style="color: green">
					Total Active Customers &nbsp&nbsp&nbsp  <?php echo $activeRecord ?><br>
					<font size="1">Online : <?php echo $CustomerData['fetch_onlineCumtomer_activeCustomer']  ?></font>
					&nbsp&nbsp&nbsp&nbsp<span  style="text-align: right"><font size="1">Offline : <?php echo $CustomerData['fetch_offlieCumtomer_activeCustomer']  ?></font> </span>
				</a>
			</li>
			<li>
				<a href="#tab_1" data-toggle="tab" aria-expanded="false" style="color: darkred">
					Total Inactive Customers &nbsp&nbsp&nbsp  <?php echo $inactiveCount ?><br>
					<font size="1">Online : <?php echo $CustomerData['fetch_onlineCumtomer_inactiveCustomer']  ?></font>
					&nbsp&nbsp&nbsp&nbsp<span  style="text-align: right"><font size="1">Offline : <?php echo $CustomerData['fetch_offlineCumtomer_inactiveCustomer']  ?></font> </span>
				</a>
			</li>
		</ul>
		<div class="" style="margin: 10px">
			<div class="row" ng-show="mainPageSwitch">
				<div class="col-lg-3" ng-show="false">
						<button  ng-disabled="allow_delete[1]" type="button"  ng-click="addNewClient()" class="btn btn-primary btn-sm"> <i class="fa fa-plus"></i> Add New Customer</button>
		               <!-- <button class="btn btn-primary" ng-click="addNewClient()" data-toggle="modal" data-target="#addNewStockModel"><i class="fa fa-plus"></i> </button>-->
				</div>
				<div class="col-lg-3">
                    <select class="form-control input-sm" ng-model="serach_zone_id" ng-change="onchangeZoneAndStatus()">
						<option value="0">All Zone</option>
						<option ng-repeat="list in zoneList" value="{{list.zone_id}}">{{list.name}}</option>
					</select>
				</div>
				<div class="col-lg-3">
                    <select class="form-control input-sm" ng-model="serach_status_id" ng-change="onchangeZoneAndStatus()">
						<option value="0">All</option>
					<!--	<option value="1">Active Regular</option>-->
						<option value="2">Inactive Regular</option>
						<option value="3">Active Sample</option>
						<option value="4">Inactive Sample</option>
					</select>
				</div>
				<div class="col-lg-3">

						<input style="width: 80%;float: left" type="text" class="form-control input-sm" ng-model="search" ng-change="changeSearchBar(search)" ng-disabled="isLoading" id="search-dealer" placeholder="Search Customer">
						 <button  style="box-shadow: inset 0 0 0 5px #DCDCDC;float: left" class="btn btn-default btn-xs" ng-disabled="isLoading" ng-click="searchClientFuction(search)" type="button">
							 <i class="fa fa-search " style="margin: 7px"></i> </button>
              	</div>
			</div>
		  	<div style="margin-top:5px;" class="table-responsive" ng-show="mainPageSwitch">
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
				          	<th><a href="#">ID</a></th>
				          	<th><a href="#">Name</a> </th>

				          	<th><a href="#">Address</a></th>
				          	<th><a href="#">Zone Name </a></th>
				          <!--	<th width="100px"><a href="#">Last Login</a> </th>-->

							<th><a href="">Status</a> </th>
							<th><a href="">sale reps</a> </th>
							<!--<th>Delete</th>-->
				          	<th style="text-align: center"><a href="#"> Action</a></th>
		        		</tr>
		      		</thead>
		      		<tbody>
		        		<tr ng-repeat="clint in clientList">

							<td>{{clint.client_id}}</td>
							<td  ng-show="clint.is_active == '1'"  style="color: green">{{clint.fullname}}</td>
							<td  ng-show="clint.is_active == '0'" style="color: darkred">{{clint.fullname}}</td>

		          			<td>{{clint.address}}</td>

                            <span ng-show="clint.is_deleted == '0'" class="label label-primary">No</span>
                            <td>{{clint.zone_name}}</td>
		          			<!--<td>{{changeDateFormate(clint.LastTime_login)}}</td>-->

							<td>
								<span ng-show="clint.is_active == '1'" class="label label-default">Active</span>
								<span ng-show="clint.is_active == '0'" class="label label-primary">Inactive</span>
							</td>
							<td>
								<span ng-show="!clint.update">{{clint.sale_raps_name}}</span>
                                 <select ng-change="changesaleRape(clint)" ng-show="clint.update" class="form-control" ng-model="clint.sales_reps_id">
                                   <option value="0">Select</option>
                                   <option ng-repeat="list in aleRepsList" value="{{list.sales_reps_id}}">{{list.name}}</option>
                                 </select>
							</td>
							<td width="150px">
                                <button ng-show="!clint.update" title="Edit" type="button" ng-click="editClint(clint)" class="btn btn-default btn-xs"><i style="margin: 2px" class="fa fa-edit  "></i></button>
                                <button ng-show="clint.update" title="Edit" type="button" ng-click="saveClient(clint)" class="btn btn-primary btn-xs"><i style="margin: 2px" class="fa fa-save"></i></button>
                                <!--<button title="Delete " ng-disabled="allow_delete[2]" type="button" ng-click="deleteClientButton(clint)" class="btn btn-primary btn-xs"><i style="margin: 2px" class="fa fa-trash  "></i></button>
                                <button title="Set Schedular"  ng-click="scheduleSee(clint.fullname ,clint.client_id)" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-briefcase   "></i></button>-->
							</td>
		        		</tr>
					     <tr ng-show="hideAndShowPagination">
							 <td colspan="8">
								 <button style="background-color: #778899 ; color: white;" type="button" class="btn btn-sm  prev" title="Prev" ng-disabled="curPage == 1" ng-click="nextPagePagination(curPage =1)"> First </button>
								 <button  style="background-color: #778899 ; color: white" type="button" class="btn btn-sm  prev" title="Prev" ng-disabled="curPage == 1" ng-click="nextPagePagination( curPage =curPage - 1)"> &lt; PREV</button>
								 <span>Page {{curPage }} of {{totalPages}}</span>
								 <button  style="background-color: #778899 ; color: white" type="button" class="btn btn-sm  next" ng-disabled="curPage >= totalPages" ng-click="nextPagePagination(curPage = curPage + 1)"> NEXT &gt;</button>
								 <button  style="background-color: #778899 ; color: white" type="button" class="btn btn-sm  next" ng-disabled="curPage >= totalPages" ng-click="nextPagePagination(curPage  = totalPages)">Last</button>

								 <button style="margin-left: 50px" type="button" class="btn btn-sm  btn-link" title="Prev"  > Switch Page  </button>

								 <select  ng-model="curPage"  ng-change="nextPagePagination(curPage)">
									 <option  ng-repeat="list in switchObject" value="{{list.pageno}}">{{list.pageno}}</option>
								 </select>
							 </td>

						 </tr>
		      		</tbody>
		    	</table>
				<div   ng-show="false"  class="pagination pagination-centered">

					<button style="background-color: #778899 ; color: white;" type="button" class="btn btn-sm  prev" title="Prev" ng-disabled="curPage == 1" ng-click="nextPagePagination(curPage =1)"> First </button>
					<button  style="background-color: #778899 ; color: white" type="button" class="btn btn-sm  prev" title="Prev" ng-disabled="curPage == 1" ng-click="nextPagePagination( curPage =curPage - 1)"> &lt; PREV</button>
					<span>Page {{curPage }} of {{totalPages}}</span>
					<button  style="background-color: #778899 ; color: white" type="button" class="btn btn-sm  next" ng-disabled="curPage >= totalPages" ng-click="nextPagePagination(curPage = curPage + 1)"> NEXT &gt;</button>
					<button  style="background-color: #778899 ; color: white" type="button" class="btn btn-sm  next" ng-disabled="curPage >= totalPages" ng-click="nextPagePagination(curPage  = totalPages)">Last</button>

					<button style="margin-left: 50px" type="button" class="btn btn-sm  btn-link" title="Prev"  > Switch Page  </button>

					<select  ng-model="curPage"  ng-change="nextPagePagination(curPage)">
						<option  ng-repeat="list in switchObject" value="{{list.pageno}}">{{list.pageno}}</option>
					</select>
				</div>
		  	</div><!-- table-responsive -->
            <!--product List-->
           <div class="row" ng-show="!mainPageSwitch">
               <div class="col-lg-8">
                    <div class="btn-demo">
                        <button type="button"  ng-click="goBackManPage()" class="btn btn-primary btn-sm"> <i class="glyphicon glyphicon-arrow-left"></i> Go Back</button>
                        <button type="button"  ng-click="goBackManPage()" class="btn btn-default btn-sm"><span style="font-weight: bold;">Customer : </span> {{slectedClient}}</button>
                        <!-- <button class="btn btn-primary" ng-click="addNewClient()" data-toggle="modal" data-target="#addNewStockModel"><i class="fa fa-plus"></i> </button>-->
                    </div>
                </div>
                <div class="col-lg-4">


					<div class="form-group input-group">
						<input type="text" class="form-control text-capitalize ng-pristine ng-valid ng-touched" ng-model="search" ng-change="changeSearchBar(search)" ng-disabled="isLoading" id="search-dealer" placeholder="Search Customer">
						<span class="input-group-btn">
                                    <button class="btn btn-default" ng-disabled="isLoading" ng-click="searchClientFuction(search)" type="button"><i class="fa fa-search"></i>
                                    </button>
                            </span>
					</div>
                </div>
            </div>

            <table style="margin-top:5px;" class="table table-striped nomargin" ng-show="!mainPageSwitch">
                <thead>
                <tr>
                    <th></th>
                    <th>Product</th>
                    <th>Unit</th>
                    <th>Price</th>
                    <th style="text-align: center">Action</th>
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="product in productList">
                    <td><input ng-disabled="true" type="checkbox" ng-model="product.orderPlace"> </td>
                    <td>{{product.name}}</td>
                    <td>{{product.unit}}</td>
                    <td>{{product.price}}</td>
                    <td>
                        <ul class="table-options">
                             <li ng-show="product.order_type !='1'"><button title="Set weekly Schedule" ng-click="showDayScheduleFunction(product)" ><img style="" ng-show="true" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icone/weekly.png" alt="" class="loading"> </button> </li>
                             <li ng-show="product.order_type =='1'"><button title="Set weekly Schedule" ng-click="showDayScheduleFunction(product)" class="btn-info btn-md" ><img style="" ng-show="true" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icone/weekly.png" alt="" class="loading"> </button> </li>
                             <li ng-show="product.order_type !='2' || product.order_type =='1'"><button title="Set Interval Schedule" ng-click="showDayScheduleFunction_interval(product)" ><img style="" ng-show="true" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icone/calendar.png" alt="" class="loading"> </button> </li>
                             <li ng-show="product.order_type=='2'"><button title="Set Interval Schedule" ng-click="showDayScheduleFunction_interval(product)" class="btn-info btn-md"><img style="" ng-show="true" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icone/calendar.png" alt="" class="loading"> </button> </li>
                           <!-- <li><a class="btn-info btn-md" href=""  ng-click="showDayScheduleFunction(product)"  title="Set weekly Schedule"> <img style="margin: 8px" ng-show="true" src="<?php /*echo Yii::app()->theme->baseUrl; */?>/images/icone/weekly.png" alt="" class="loading"></a></li>
                            <li ng-show="true"><a href=""  ng-click="showDayScheduleFunction_interval(product)"  title="Set Interval Schedule"><img ng-show="true" src="<?php /*echo Yii::app()->theme->baseUrl; */?>/images/icone/calendar.png" alt="" class="loading">  </a></li>-->
                            <li ng-show="product.orderPlace"><a  class="btn-default" href="" ng-click="removeProductForSchedual(product)"  title="remove"> <span class="glyphicon glyphicon-remove"></span></a></li>
                            <img ng-show="product.saveLoading"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading.gif" alt="" class="loading">
                        </ul>
                    </td>
                </tr>
                </tbody>
            </table>
            <span ng-show="false">  {{PcurPage = temporyPcurPage}} </span>
		</div>
		<!-- start: add new Client -->
		<modal title="" visible="showAddNewClient">
			<form role="form" class="form-group" ng-submit="saveClient(ClientObject)">
				<p class="note">Fields with <span class="required" style="color: red">*</span> are required.</p>
				<div class="col-lg-12 form-group" style="background-color: #FFF0F5">
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px;">Full Name <span class="required" style="color: red">*</span> :</span>
					</div>
					<div class="col-lg-8">
						<input type="text"  ng-model="ClientObject.fullname"  ng-change="changeOnFullName(ClientObject.fullname)" class="form-control"   required/>
					</div>
				</div>
				<div class="col-lg-12 form-group">
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">User Name :</span>
					</div>
					<div class="col-lg-8">
						<input type="text"  ng-change="checkAlredyExistClientFunction(ClientObject.userName , 0)" ng-model="ClientObject.userName" class="form-control" />
						<span ng-show="checkAlredyExist" style="color: green">This username is already exist </span>
					</div>
				</div>
				<div class="col-lg-12 form-group" style="background-color: honeydew">
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">Password :</span>
					</div>
					<div class="col-lg-8">
						<input type="text"   ng-model="ClientObject.password" class="form-control" />
    				</div>
				</div>

                <div class="col-lg-12 form-group" >
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Email :</span>
                    </div>
                    <div class="col-lg-8">
                        <input type="email" ng-model="ClientObject.email" class="form-control" />
                    </div>
                </div>

				<div class="col-lg-12 form-group" ng-show="false">
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">Father/Husband Name :</span>
					</div>
					<div class="col-lg-8">
						<input type="text" ng-model="ClientObject.father_or_husband_name" class="form-control"  />
					</div>
				</div>
				<div class="col-lg-12 form-group" style="background-color: 	#FFF0F5">
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">Date of birth  :</span>
					</div>
					<div class="col-lg-8">
						<input type="text" class="form-control" id="datetimepicker10" ng-model="ClientObject.date_of_birth">
           			</div>
				</div>
				<div class="col-lg-12 form-group" >
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">Address <span class="required" style="color: red">*</span> :</span>
					</div>
					<div class="col-lg-8">
						<input type="text" ng-model="ClientObject.address" class="form-control"   required/>
					</div>
				</div>

                <div class="col-lg-12 form-group" >
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">House No.  :</span>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" ng-model="ClientObject.house_no" class="form-control"   />
                    </div>
                </div>

                <div class="col-lg-12 form-group" >
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Sub No.  :</span>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" ng-model="ClientObject.sub_no" class="form-control"   />
                    </div>
                </div>

                <div class="col-lg-12 form-group"  style="background-color: 	#FFF0F5">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Block  : </span>
                    </div>

                    <div class="col-lg-8">
                        <select ng-model="ClientObject.block_id"  class="form-control" >
                            <option value="">Select Block</option>
                            <option ng-repeat="zone in blockList" value="{{zone.block_id}}">{{zone.block_name}}</option>
                        </select>
                    </div>
                </div>

                <div class="col-lg-12 form-group"  style="background-color: 	#FFF0F5">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Area : </span>
                    </div>

                    <div class="col-lg-8">
                        <select ng-model="ClientObject.area_id"  class="form-control" >
                            <option value="">Select Area</option>
                            <option ng-repeat="zone in areaList" value="{{zone.area_id}}">{{zone.area_name}}</option>
                        </select>
                    </div>
                </div>

				<div class="col-lg-12 form-group" style="background-color: honeydew" ng-show="false">
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">CNIC :</span>
					</div>
					<div class="col-lg-8">
						<input type="text" ng-model="ClientObject.cnic" class="form-control" />
					</div>
				</div>
				<div class="col-lg-12 form-group" >
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">City :</span>
					</div>
					<div class="col-lg-8">
						<input type="text" ng-model="ClientObject.city" class="form-control"  />
					</div>
				</div>
				<div class="col-lg-12 form-group" style="background-color: 	#FFF0F5">
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">Cell No 1 <span class="required" style="color: red">*</span>  :</span>
             		</div>
					<div class="col-lg-8">
						<input  type="text" ng-model="ClientObject.cell_no_1" class="form-control"  placeholder="+923006053362"   ng-change="changePhoneNumber(ClientObject.cell_no_1)" required/>
						<span   ng-show="phoneNumberFormate" style="color: green">This +923006053362 format is required </span>
					</div>
				</div>
				<div class="col-lg-12 form-group" >
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">Cell No 2 :</span>
					</div>
					<div class="col-lg-8">
						<input type="text" ng-model="ClientObject.cell_no_2" class="form-control"   />
					</div>
				</div>
				<div class="col-lg-12 form-group" style="background-color: honeydew">
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">Residence Phone No :</span>
					</div>
					<div class="col-lg-8">
						<input type="text" ng-model="ClientObject.residence_phone_no" class="form-control"   />
					</div>
				</div>

				<div class="col-lg-12 form-group"  style="background-color: 	#FFF0F5">
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">Zone <span class="required" style="color: red">*</span> : </span>
					</div>

					<div class="col-lg-8">
						<select ng-model="ClientObject.zone_id"  class="form-control" required>
							<option value="">Select Zone</option>
							<option ng-repeat="zone in zoneList" value="{{zone.zone_id}}">{{zone.name}}</option>
						</select>
					</div>

				</div>
				<div class="col-lg-12 form-group"  style="background-color: honeydew">
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">Network <span class="required" style="color: red">*</span> : </span>
					</div>
					<div class="col-lg-8">
						<select ng-model="ClientObject.network_id"  class="form-control" required>
							<option value="0">Default</option>
							<option value="1">Ufone</option>
							<option value="2">Telenore</option>
							<option value="3">Mobilink</option>
							<option value="4">Warid</option>
							<option value="5">Zong</option>
						</select>
    				</div>
				</div>

                <div class="col-lg-12 form-group"  style="background-color: 	#FFF0F5">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Category : </span>
                    </div>

                    <div class="col-lg-8">
                        <select ng-model="ClientObject.customer_category_id"  class="form-control" >
                            <option value="0">Select Category</option>
                            <option ng-repeat="zone in CategoryList" value="{{zone.customer_category_id}}">{{zone.category_name}}</option>
                        </select>
                    </div>
                </div>

				<div class="col-lg-12 form-group"  >
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">Security </span> : </span>
					</div>
					<div class="col-lg-8">
						<input type="text" ng-model="ClientObject.security" class="form-control" />
					</div>
				</div>

				<div class="col-lg-12 form-group" >
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">Payment Terms :</span>
					</div>
					<div class="col-lg-8">

						<label class="radio-inline">
							<input ng-model="ClientObject.payment_term"   type="radio" name="payment_term" value="1" required>
							<span  class="label label-default">Credit</span>
						</label>
						<label class="radio-inline">
							<input ng-model="ClientObject.payment_term" type="radio" name="payment_term" value="0" required>
							<span  class="label label-primary">advance</span>
						</label>
					</div>
				</div>

				<div class="col-lg-12 form-group" style="background-color: honeydew">
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">Delivery Alert :</span>
					</div>
					<div class="col-lg-8">
						<label class="radio-inline">
							<input ng-model="ClientObject.daily_delivery_sms"   type="radio" name="delivery_alert" value="1" required>
							<span  class="label label-default">Active</span>
						</label>
						<label class="radio-inline">
							<input ng-model="ClientObject.daily_delivery_sms" type="radio" name="delivery_alert" value="0" required>
							<span  class="label label-primary">Inactive</span>
						</label>
					</div>
				</div>
				<div class="col-lg-12 form-group" >
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">New  Product Alert :</span>
					</div>
					<div class="col-lg-8">
						<label class="radio-inline">
							<input ng-model="ClientObject.alert_new_product"   type="radio" name="alert_new_product" value="1" required>
							<span  class="label label-default">Active</span>
						</label>
						<label class="radio-inline">
							<input ng-model="ClientObject.alert_new_product" type="radio" name="alert_new_product" value="0" required>
							<span  class="label label-primary">Inactive</span>
						</label>
					</div>
				</div>

				<div class="col-lg-12 form-group"  style="background-color: 	#FFF0F5">
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">Status :</span>
					</div>
					<div class="col-lg-8">
						<label class="radio-inline">
							<input ng-model="ClientObject.is_active"   type="radio" name="status" value="1" required>
							<span  class="label label-default">Active</span>
						</label>
						<label class="radio-inline">
							<input ng-model="ClientObject.is_active" type="radio" name="status" value="0" required>
							<span  class="label label-primary">Inactive</span>
						</label>
					</div>
				</div>

                <div class="col-lg-12 form-group"  style="background-color: 	#FFF0F5">
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">Client Type :</span>
					</div>
					<div class="col-lg-8">
						<label class="radio-inline">
							<input ng-model="ClientObject.client_type"   type="radio" name="client_type" value="1" required>
							<span  class="label label-default">Regular</span>
						</label>
						<label class="radio-inline">
							<input ng-model="ClientObject.client_type" type="radio" name="client_type" value="2" required>
							<span  class="label label-primary">Sample</span>
						</label>
					</div>
				</div>

				<div class="col-lg-12 form-group">
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">Product Price:</span>
					</div>
					<div class="col-lg-8">
					  <button ng-show="!showHideproductListPrice" type="button" ng-click="setProductPrice(0)"><span  class="label label-primary"><i class="fa fa-eye"></i> View</span></button>
					  <button ng-show="showHideproductListPrice" type="button" ng-click="hideProductShowTable()"><span  class="label label-primary"><i class="fa fa-eye"></i> Hide</span></button>
						<img ng-show="clientProductloaderImage" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
					</div>
				</div>


				<div class="col-lg-12">
					<table width="100%" border="1" ng-show="showHideproductListPrice">
						<tr>
							<th style="padding: 10px">Product </th>
							<th>Default Price</th>
							<th>New Price</th>
						</tr>
						<tr ng-repeat="list in ClientObject.clientProductObject">
							<td width="33%">{{list.name}}</td>
							<td width="33%" style="text-align: right">{{list.price}}</td>
							<td width="33%">
							  <input type="text"  ng-model="list.clientProductPrice" class="form-control">
							</td>
						</tr>
					</table>
				</div>
				<div class="col-lg-12 form-group" style="height: 10px" ></div>
				<div class=" form-group ">
					<button ng-disabled="allow_delete =='0'" ng-disabled="checkAlredyExist" type="submit" class="btn-success  btn-sm">Save</button>
					<button  type="button" class="btn-success  btn-sm" ng-click="rsetClientObject()">Reset</button>
					<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
				</div>
			</form>
		</modal>


		<!-- end: add new stock model -->
		<!-- start: Edit Clint -->

		<modal title="Update Customer" visible="showEditClient">
			<form role="form" class="form-group" ng-submit="editClientFunction(ClientObject)">
				<p class="note">Fields with <span class="required" style="color: red">*</span> are required.</p>
				<div class="col-lg-12 form-group" style="background-color: #FFF0F5">
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px;">Full Name <span class="required" style="color: red">*</span> :</span>
					</div>
					<div class="col-lg-8">
						<input type="text"  ng-model="ClientObject.fullname"   class="form-control"   required/>

					</div>
				</div>
				<div class="col-lg-12 form-group">
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">User Name :</span>
					</div>
					<div class="col-lg-8">
						<input type="text"  ng-change="checkAlredyExistClientFunction(ClientObject.userName , ClientObject.client_id)" ng-model="ClientObject.userName" class="form-control" />
						<span ng-show="checkAlredyExist" style="color: green">This username is already exist </span>
					</div>
				</div>

				<div class="col-lg-12 form-group" style="background-color: honeydew">
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">Password :</span>
					</div>

					<div class="col-lg-8">
						<input type="text"   ng-model="ClientObject.password" class="form-control" />

					</div>

				</div>

                <div class="col-lg-12 form-group" >
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Email :</span>
                    </div>
                    <div class="col-lg-8">
                        <input type="email" ng-model="ClientObject.email" class="form-control" />
                    </div>
                </div>

				<div class="col-lg-12 form-group" ng-show="false">
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">Father/Husband Name :</span>
					</div>

					<div class="col-lg-8">
						<input type="text" ng-model="ClientObject.father_or_husband_name" class="form-control"  />
					</div>

				</div>

				<div class="col-lg-12 form-group" style="background-color: 	#FFF0F5">
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">Date of birth  :</span>
					</div>

					<div class="col-lg-8">
					<!--	<input  class="form-control"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="ClientObject.date_of_birth" size="2">-->
						<input type="text" class="form-control" id="datetimepicker11" ng-model="ClientObject.date_of_birth">
					</div>

				</div>

				<div class="col-lg-12 form-group" >
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">Address <span class="required" style="color: red">*</span> :</span>
					</div>
					<div class="col-lg-8">
						<input type="text" ng-model="ClientObject.address" class="form-control"   required/>
					</div>
				</div>

                <div class="col-lg-12 form-group" >
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">House No.  :</span>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" ng-model="ClientObject.house_no" class="form-control"   />
                    </div>
                </div>

                <div class="col-lg-12 form-group" >
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Sub No. :</span>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" ng-model="ClientObject.sub_no" class="form-control"   />
                    </div>
                </div>

                <div class="col-lg-12 form-group"  style="background-color: 	#FFF0F5">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Block  : </span>
                    </div>

                    <div class="col-lg-8">
                        <select ng-model="ClientObject.block_id"  class="form-control" >
                            <option value="">Select Block</option>
                            <option ng-repeat="zone in blockList" value="{{zone.block_id}}">{{zone.block_name}}</option>
                        </select>
                    </div>
                </div>

                <div class="col-lg-12 form-group"  style="background-color: 	#FFF0F5">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Area: </span>
                    </div>

                    <div class="col-lg-8">
                        <select ng-model="ClientObject.area_id"  class="form-control" >
                            <option value="">Select Area</option>
                            <option ng-repeat="zone in areaList" value="{{zone.area_id}}">{{zone.area_name}}</option>
                        </select>
                    </div>
                </div>

				<div class="col-lg-12 form-group" style="background-color: honeydew" ng-show="false">
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">CNIC :</span>
					</div>
					<div class="col-lg-8">
						<input type="text" ng-model="ClientObject.cnic" class="form-control" />
					</div>
				</div>

				<div class="col-lg-12 form-group" >
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">City :</span>
					</div>

					<div class="col-lg-8">
						<input type="text" ng-model="ClientObject.city" class="form-control"  />
					</div>

				</div>

				<div class="col-lg-12 form-group" style="background-color: 	#FFF0F5">
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">Cell No 1 <span class="required" style="color: red">*</span>  :</span>
					</div>

					<div class="col-lg-8">

						<input  type="text" ng-model="ClientObject.cell_no_1" class="form-control"  placeholder="+923006053362"   ng-change="changePhoneNumber(ClientObject.cell_no_1)" required/>
						<span   ng-show="phoneNumberFormate" style="color: green">This +923006053362 format is required </span>
					</div>

				</div>

				<div class="col-lg-12 form-group" >
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">Cell No 2 :</span>
					</div>

					<div class="col-lg-8">
						<input type="text" ng-model="ClientObject.cell_no_2" class="form-control"   />
					</div>
				</div>
				<div class="col-lg-12 form-group" style="background-color: honeydew">
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">Residence Phone No :</span>
					</div>
					<div class="col-lg-8">
						<input type="text" ng-model="ClientObject.residence_phone_no" class="form-control"   />
					</div>
				</div>


				<div class="col-lg-12 form-group"  style="background-color: 	#FFF0F5">
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">Zone <span class="required" style="color: red">*</span> : </span>
					</div>

					<div class="col-lg-8">
						<select ng-model="ClientObject.zone_id"  class="form-control" required>
							<option value="">Select Zone</option>
							<option ng-repeat="zone in zoneList" value="{{zone.zone_id}}">{{zone.name}}</option>
						</select>
				    </div>
                 </div>

				<div class="col-lg-12 form-group"  style="background-color: honeydew">
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">Network <span class="required" style="color: red">*</span> : </span>
					</div>

					<div class="col-lg-8">
						<select ng-model="ClientObject.network_id"  class="form-control" required>
							<option value="0">Default</option>
							<option value="1">Ufone</option>
							<option value="2">Telenore</option>
							<option value="3">Mobilink</option>
							<option value="4">Warid</option>
							<option value="5">Zong</option>

						</select>
				    </div>
                </div>

                <div class="col-lg-12 form-group"  style="background-color: 	#FFF0F5">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Category <span class="required" style="color: red"></span> : </span>
                    </div>

                    <div class="col-lg-8">
                        <select ng-model="ClientObject.customer_category_id"  class="form-control" >
                            <option value="0">Select Category</option>
                            <option ng-repeat="zone in CategoryList" value="{{zone.customer_category_id}}">{{zone.category_name}}</option>
                        </select>
                    </div>
                </div>

				<div class="col-lg-12 form-group"  style="">
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">Security : </span>
					</div>
					<div class="col-lg-8">
						<input type="text" ng-model="ClientObject.security" class="form-control" />
					</div>
				</div>

				<div class="col-lg-12 form-group" >
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">Payment Terms :</span>
					</div>
					<div class="col-lg-8">

						<label class="radio-inline">
							<input ng-model="ClientObject.payment_term"   type="radio" name="payment_term" value="1" required>
							<span  class="label label-default">Credit</span>
						</label>
						<label class="radio-inline">
							<input ng-model="ClientObject.payment_term" type="radio" name="payment_term" value="0" required>
							<span  class="label label-primary">advance</span>
						</label>
					</div>
				</div>

				<div class="col-lg-12 form-group" style="background-color: honeydew">
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">Delivery Alert :</span>
					</div>
					<div class="col-lg-8">

						<label class="radio-inline">
							<input ng-model="ClientObject.daily_delivery_sms"   type="radio" name="delivery_alert" value="1" required>
							<span  class="label label-default">Active</span>
						</label>
						<label class="radio-inline">
							<input ng-model="ClientObject.daily_delivery_sms" type="radio" name="delivery_alert" value="0" required>
							<span  class="label label-primary">Inactive</span>
						</label>
					</div>
				</div>

				<div class="col-lg-12 form-group" >
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">New  Product Alert :</span>
					</div>
					<div class="col-lg-8">
						<label class="radio-inline">
							<input ng-model="ClientObject.alert_new_product"   type="radio" name="alert_new_product" value="1" required>
							<span  class="label label-default">Active</span>
						</label>
						<label class="radio-inline">
							<input ng-model="ClientObject.alert_new_product" type="radio" name="alert_new_product" value="0" required>
							<span  class="label label-primary">Inactive</span>
						</label>
					</div>
				</div>

				<div class="col-lg-12 form-group" style="background-color: honeydew">
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">Status :</span>
					</div>
					<div class="col-lg-8">
						<label class="radio-inline">
							<input ng-model="ClientObject.is_active" ng-change="inactiveCustomer(ClientObject.is_active)"  type="radio" name="status" value="1" required>
							<span  class="label label-default">Active</span>
						</label>
						<label class="radio-inline">
							<input ng-change="inactiveCustomer(ClientObject.is_active)" ng-model="ClientObject.is_active" type="radio" name="status" value="0" required>
							<span  class="label label-primary">Inactive</span>
						</label>
					</div>
				</div>

               <!-- <div class="col-lg-12 form-group" ng-show="inactivereason">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Reason :2</span>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" ng-model="ClientObject.inactive_reason" class="form-control" />
                    </div>
                </div>-->

                <div class="col-lg-12 form-group" ng-show="inactivereason">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Reason :</span>
                    </div>
                    <div class="col-lg-8">
                        <!--<input type="text" ng-model="ClientObject.inactive_reason" class="form-control" />-->

                        <select ng-model="ClientObject.inactive_reason"  class="form-control" >
                            <option value="">Select Reason</option>
                            <option ng-repeat="zone in reasonList" value="{{zone.sample_client_drop_reason_id}}">{{zone.reason}}</option>
                        </select>
                    </div>
                </div>

                <div class="col-lg-12 form-group" style="background-color: honeydew">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Cleint Type :</span>
                    </div>
                    <div class="col-lg-8">
                        <label class="radio-inline">
                            <input ng-model="ClientObject.client_type"   type="radio" name="client_type" value="1" required>
                            <span  class="label label-default">Regular</span>
                        </label>
                        <label class="radio-inline">
                            <input ng-model="ClientObject.client_type" type="radio" name="client_type" value="2" required>
                            <span  class="label label-primary">Sample</span>
                        </label>
                    </div>
                </div>


				<div class="col-lg-12 form-group">
					<div class="col-lg-4" style="padding: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">Product Price:</span>
					</div>
					<div class="col-lg-8">
						<button ng-show="!showHideproductListPrice" type="button" ng-click="setProductPrice(ClientObject.client_id)"><span  class="label label-primary"><i class="fa fa-eye"></i> View</span></button>
						<button ng-show="showHideproductListPrice" type="button" ng-click="hideProductShowTable()"><span  class="label label-primary"><i class="fa fa-eye"></i> Hide</span></button>
						<img ng-show="clientProductloaderImage" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
					</div>
				</div>


				<div class="col-lg-12">
					<table width="100%" border="1" ng-show="showHideproductListPrice">
						<tr>
							<th style="padding: 10px">Product </th>
							<th>Default Price</th>
							<th>New Price</th>
						</tr>
						<tr ng-repeat="list in ClientObject.clientProductObject">
							<td width="33%">{{list.name}}</td>
							<td width="33%" style="text-align: right">{{list.price}}</td>
							<td width="33%">
								<input type="text"  ng-model="list.clientProductPrice" class="form-control">
							</td>
						</tr>
					</table>
				</div>
				<div class="col-lg-12 form-group" style="height: 10px"></div>
				<div class=" form-group ">
					<button ng-disabled="allow_delete[3]"  ng-disabled="checkAlredyExist" type="submit" class="btn-success  btn-sm">Update</button>

				</div>
			</form>
		</modal>
		<!-- end: add Edit clint -->


		<!-- start: show Schedule See-->

		<modal title="Client product Schedule" visible="showScheduleSee">
			<table class="table table-striped nomargin">
				<thead>
				<tr>
					<th></th>
					<th>Name</th>
					<th>Unit</th>
					<th>Price</th>
					<th>Action</th>
				</tr>
				</thead>
				<tbody>
				<tr ng-repeat="product in productList">
					 <td> <input ng-disabled="true" type="checkbox" ng-model="product.orderPlace"> </td>
					<td>{{product.name}}</td>
					<td>{{product.unit}}</td>
					<td>{{product.price}}</td>
					<td>
						<ul class="table-options">

							<!--<li ng-show="!product.ObjectOFDayWiseQuantity"><a href=""   title="Edit"><i class="fa fa-edit"></i></a></li>-->
							<li ng-show="product.ObjectOFDayWiseQuantity"> <button ng-disabled="allow_delete =='0'"  ng-click="clientProductScheduleChangeSave(product)" type="button" class="btn btn-primary btn-xs"><i class="fa fa-save"></i> Save</button></li>
							<!--<li ng-show="product.ObjectOFDayWiseQuantity"><a href=""  class="btn btn-sm btn-primary"   title="Save"><i class="fa fa-save"></i></a></li>-->
							<li><a href="" ng-disabled="true" ng-click="showDayScheduleFunction(product)"  title="Set Schedule"><i class="glyphicon glyphicon-calendar"></i>  </a></li>
							<li ng-show="product.orderPlace"><a  class="btn-default" href="" ng-click="removeProductForSchedual(product)"  title="remove"> <span class="glyphicon glyphicon-remove"></span></a></li>
							<img ng-show="product.saveLoading"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading.gif" alt="" class="loading">

						</ul>
					</td>
				</tr>
				</tbody>
			</table>
                       <span ng-show="false">  {{PcurPage = temporyPcurPage}} </span>
			<div  ng-show="hideAndShowPagination"  class="pagination pagination-centered">
				<button type="button" class="btn btn-sm btn-info prev" title="Prev" ng-disabled="PcurPage == 0" ng-click="PnextPagePagination(PcurPage =0 )"> First </button>
				<button type="button" class="btn btn-sm btn-info prev" title="Prev" ng-disabled="PcurPage == 0" ng-click="PnextPagePagination( PcurPage =PcurPage - 1)"> &lt; PREV</button>
			 	<span>Page {{PcurPage +1}} of {{PtotalPages}}</span>
				<button type="button" class="btn btn-sm btn-info next" ng-disabled="PcurPage >= PtotalPages - 1" ng-click="PnextPagePagination(PcurPage = PcurPage + 1)"> NEXT &gt;</button>
				<button type="button" class="btn btn-sm btn-info next" ng-disabled="PcurPage >= PtotalPages - 1" ng-click="PnextPagePagination(PcurPage  = PtotalPages - 1)">Last</button>
				<button  type="button" class="btn btn-sm btn-primary next"  ng-click="showScheduleSeeOK()">OK</button>
			</div>
		</modal>
		<!-- end: show Schedule See -->
		<!-- start: show Schedule See-->
		<modal title="weekly schedule" visible="showDaySchedule">
			<span ng-show="false">{{changeStartOrderDate(orderStartDate)}}</span>

			<div class="col-lg-12" style="margin-bottom: 10px">

				<div class="col-lg-6">
					<span style="font-weight: bold;font-size: 13px; padding: 8px">Customer : </span>{{slectedClient}}
				</div>


				<div class="col-lg-6">
					<span style="font-weight: bold;font-size: 13px; padding: 8px">Product : </span>{{selectedProduct}}
				</div>

			</div>

			<div class="col-lg-12" style="margin-bottom: 10px">
				<div class="col-lg-4">
					<span style="font-weight: bold;font-size: 13px; padding: 8px">Start Order Date : </span>
				</div>
				<div class="col-lg-8">
					<!--<input type="text"   datetime-picker date-only  date-format="yyyy-MM-dd" class="form-control" ng-model="orderStartDate" ng-change="changeStartOrderDate(orderStartDate)">-->
					<input type="text"   datetime-picker date-only  date-format="yyyy-MM-dd" class="form-control" ng-model="orderStartDate" >
				</div>
			</div>
			<table class="table table-striped nomargin">
				<thead>
				<tr>
					<th></th>
					<th>Day</th>
					<th>Quantity</th>
					<!--<th>Preferred delivery  Time</th>-->
				</tr>
				</thead>
				<tbody>
				<tr ng-repeat="days in frequencyList">
					<td> <input type="checkbox" ng-model="days.slectDayForProducy"> </td>
					<td>{{days.day_name}}</td>
					<td>
					  <input type="text" ng-model="days.quantity" class="form-control" ng-change="changeDaysQuantity(days)">
					</td>
					<!--<td>
						<select ng-model="days.preferred_time_id" class="form-control">
							<option ng-repeat="time in preferedTimeList" value="{{time.preferred_time_id}}"> {{time.preferred_time_name}}</option>
						</select>
					</td>-->
				</tr>
				</tbody>
			</table>
			<button type="button" class="btn btn-sm btn-default btn-xs" title="Close"  ng-click="showDayScheduleok()"> Close</button>
			<button ng-disabled="allow_delete[3]" ng-click="clientProductScheduleChangeSave2(frequencyList , orderStartDate)" type="button" class="btn btn-primary btn-xs"><i class="fa fa-save"></i> Save</button>
		</modal>
		<!-- end: show Schedule See -->

		<!-- start: show interval Schedule See-->
		<modal title="Interval schedule" visible="showDaySchedule_interval">
			<span ng-show="false">{{changeStartOrderDate(orderStartDate)}}</span>
              <form ng-submit="clientProductScheduleChangeSave_interval(intervalScheduleObject)">
				<div class="col-lg-12" style="margin-bottom: 10px">

					<div class="col-lg-6">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">Customer : </span>{{slectedClient}}
					</div>


					<div class="col-lg-6">
						<span style="font-weight: bold;font-size: 13px; padding: 8px">Product : </span>{{selectedProduct}}
					</div>

				</div>

				<div class="col-lg-12" style="margin-bottom: 10px;background-color: honeydew">

					<div class="col-lg-5" style="padding-top: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px;">Start Order Date : </span>
					</div>
					<div class="col-lg-7">
						<input type="text"   datetime-picker date-only  date-format="yyyy-MM-dd" class="form-control" ng-model="intervalScheduleObject.start_interval_scheduler" required>
					</div>
				</div>


				<div class="col-lg-12" style="margin-bottom: 10px;background-color: #FFF0F5">

					<div class="col-lg-5" style="padding-top: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px;">Quantity : </span>
					</div>
					<div class="col-lg-7">
						<input type="text"    class="form-control" ng-model="intervalScheduleObject.product_quantity" required>
					</div>
				</div>



				<div class="col-lg-12" style="margin-bottom: 10px;background-color: honeydew">

					<div class="col-lg-5" style="padding-top: 10px">
						<span style="font-weight: bold;font-size: 13px; padding: 8px;">Interval : </span>
					</div>
					<div class="col-lg-7">
						<input type="text"    class="form-control" ng-model="intervalScheduleObject.interval_days" required>
						<div class="help-block" style="color: #008000"> Hint : If you set the interval as “2”, then supplies shall be made on every Second Day
							e.g. Supplies shall be made on Monday and then on Wednesday.</div>
					</div>
				</div>
				<button type="button" class="btn btn-sm btn-default btn-xs" title="Close"  ng-click="showDayScheduleok_interval()"> Close </button>
				<button ng-disabled="allow_delete[3]"  title="" ng-click="" type="submit" class="btn btn-primary btn-xs"><i class="fa fa-save"></i> Update</button>
			</form>
		</modal>
		<!-- end: show interval Schedule See -->

	</div>
</div>


<script type="text/javascript">
	$(function () {
		$('#datetimepicker10').datetimepicker({
			viewMode: 'years',
			format: 'YYYY-MM-DD'
		});
	});
</script>
<script type="text/javascript">
	$(function () {
		$('#datetimepicker11').datetimepicker({
			viewMode: 'years',
			format: 'YYYY-MM-DD'
		});
	});
</script>

