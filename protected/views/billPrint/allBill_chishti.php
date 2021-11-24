<style>
	.angularjs-datetime-picker{
		z-index: 99999 !important;
	}
</style>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/allBill/allBill-grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
	<div class="modal-content">
		<div class="modal-body">
			<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
			<span>&nbsp;&nbsp;Loading... </span>
		</div>
	</div>
</div>



<?php $company_id = Yii::app()->user->getState('company_branch_id');
     $company= Company::model()->findByPk(intval($company_id));
    $company_name = $company['company_name'];

?>
<div class="panel row" id="testContainer" style="display: none" ng-app="clintManagemaent">
	<div ng-controller="clintManagemaent" ng-init='init(<?php echo $payment_term_list ?> ,<?php echo json_encode($company_name); ?> , <?php echo $company_id ?> , <?php echo $riderList ?> , <?php echo $clientList ?> , "<?php echo Yii::app()->createAbsoluteUrl('client/getClientLedgherReport'); ?>", "<?php echo Yii::app()->createAbsoluteUrl('client/oneCustomerAmountListallCustomerList'); ?> " )'>

		<div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
			<button type="button" class="close" data-dismiss="alert">x</button>
			<strong>message! </strong>
			{{taskMessage}}
		</div>
		<ul class="nav nav-tabs nav-tabs-lg">
			<li>
				<a href="#tab_1" data-toggle="tab" aria-expanded="false">
					 Customer Ledger
				</a>
			</li>
		</ul>

		<div style="margin: 15px">
			<div class="col-lg-12">

				<div style="float: left;" ng-show="true">
					<button class="btn btn-default dropdown-toggle btn-sm" ng-click="showDropDownList()" type="button" id="dropdownMenu1" data-toggle="dropdown">{{SelectedCustomer}} <span  class="caret" style="margin: 9px"></span>
					</button>
					<ul style="width: 200px ;max-height: 500px ; overflow-x: hidden;overflow-y: scroll;" class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1" >
						<li role="presentation">
						   <div class="input-group input-group-sm search-control"> <span class="input-group-addon">
						<input type="text" class="form-control" placeholder="Search" ng-model="query" id="serachCustomerBar">
						</div>
						</li >
						<li  role="presentation" ng-click="abcd(item)" ng-repeat='item in clientList | filter:query'> <a href="#"> {{item.fullname}} </a>
						</li>
					</ul>
				</div>
				<select style="width: 200px; float: left;margin-bottom: 10px; margin-left: 2px;" class="form-control input-sm" ng-change="changeRiderFunction()" ng-model="riderClientObject" >
					<option value="0">Select Rider</option>
					<option ng-repeat="list in riderList" value="{{list.cleintList}}">{{list.fullname}}
					</option>
				</select>
                <select style="width: 180px; float: left;margin-bottom: 10px; margin-left: 2px;" class="form-control input-sm" ng-change="changeRiderFunction()" ng-model="payment_term_id" >
                    <option value="0">Payment Term</option>
                    <option value="{{list.payment_term_id}}" ng-repeat="list in payment_term_list"> {{list.payment_term_name}}</option>
                </select>
				<input style="float: left ; width: 18% ; margin-left: 1%" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">
				<button class="btn btn-info btn-sm" style="float: left"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
				<input style="width: 18% ; float: left"  class="form-control input-sm "    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">
				<button ng-disabled="imageLoader" class="btn btn-primary input-sm" style="float: left" ng-click="getCustomerLedgerReportFunction()"><i class="fa fa-search" style=""></i> Search</button>
				<button ng-disabled="imageLoader" class="btn btn-primary input-sm" style="float: left ; margin-left: 5px" ng-click="printFunction()"><i class="fa fa-print" style=""></i> Print</button>

				<img style="margin: 10px" ng-show="imageLoader"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loading.gif" alt="" class="loading">
			</div>

			<div class="col-lg-12" ng-show="showprograssBar">
				<div class="progress  ">
					<div class="progress-bar progress-bar-success" role="progressbar"  aria-valuemin="0"  style="width:{{loadPerCentage}}%">
						{{loadPerCentage | number:0}}% Complete (Load Data)
					</div>
				</div>
			</div>


 		</div>

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
		<div class="col-lg-12" ng-show="false" style="background-color: #FFF8DC ;margin-top: 10px ">
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
		<div class="col-lg-12" ng-show="true" style="margin-top: 10px ">
		</div>
		<style>
			#customers {
				border-collapse: collapse;
				width: 100%;
				margin-top: 10px;
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

			@media print
			{
				table { page-break-after:auto }
				tr    { page-break-inside:avoid; page-break-after:auto }
				td    { page-break-inside:avoid; page-break-after:auto }
				thead { display:table-header-group }
				tfoot { display:table-footer-group }
			}
			.ridge {border-style: ridge; margin-bottom: 20px}

		</style>
		<?php
		$company_id = Yii::app()->user->getState('company_branch_id');
		$company = Company::model()->findByPk(intval($company_id));
		$company_logo =  $company['company_logo'];
		$due_date =  $company['due_date'];


		$monthNum  =Date("m");
		$dateObj   = DateTime::createFromFormat('!m', $monthNum);
		$monthName = $dateObj->format('F'); // March
		$year = Date("Y");
		?>

		<div id="printTable">

			<div   class="col-lg-12 ridge" id="onepage" ng-repeat="mainList in finalOneObject">
				<div   style="page-break-after:always ; width: 100%;">

                        <div> <p style="text-align: center;font-weight: bold;font-size:28px;"></></div>
						<div style="width: 100% ;" >
							<!--<div style="width: 10% ; float: left">
								<img style="width: 50px ;height: 40px" src="<?php /*echo Yii::app()->theme->baseUrl; */?>/company_logo/<?php /*echo $company_logo  */?>" alt="" class="media-object img-circle">
							</div>-->
							<!--<div style="width: 60% ; float: left">
								<table style="background-color: white">
									<tr>
										<td style="text-align: center">
											<span style="font-family: Arial, Helvetica;text-align: center ;">Customer Invoice</span>
										</td>
									</tr>
									<tr>
										<td style="text-align: center">
											<span style="font-family: Arial, Helvetica;text-align: center">From <span style=" font-weight: bold;">{{startDate | date}}</span> To <span style=" font-weight: bold;">{{endDate | date}}</span></span>
										</td>
									</tr>
								</table>
							</div>-->
							<!--<div style="width: 30% ; float: left">
                                  <?php /*if($company_id !=4){ */?>
								<table style="background-color: white ;float: right" >
									<tr>
										<th colspan="4" style="font-family: Arial, Helvetica;text-align: center ;border-bottom: 1px solid black">Due Date</th>
									</tr>
									<tr style="border-bottom: 1px solid black">
										<th width="15px" style="border-bottom: 1px solid black"></th>
										<th height="0px" colspan="2" style="font-family: Arial, Helvetica; text-align: center ;border-bottom: 1px solid black;"><span style="margin-top: 10px">{{mainList.nextMonthDate | date}}</span></th>
										<th  width="15px" style="border-bottom: 1px solid black"></th>
									</tr>

								</table>
								 <?php /*}*/?>
							</div>-->
						<div>
						<div style="width: 100% ;clear:both;height: 0px" ></div>
						<div style="width: 100% ;clear:both;" >
							<div style="width: 100% ; float: left">
								<table style="width :100%;background-color: white">
									<tr>
										<th style="font-family: Arial, Helvetica;text-align: left;font-size: 150%;">Organic Milk</th>
                                        <th rowspan="5" style="text-align: right;">
                                            <img style="width: 180px ;height: 150px" src="<?php echo Yii::app()->theme->baseUrl; ?>/company_logo/<?php echo $company_logo  ?>" alt="" class="media-object img-circle">
                                        </th>
									</tr>
                                    <tr>
                                        <th style="font-family: Arial, Helvetica;text-align: left">A project by CHISHTI DAIRY FARMS.	</th>
                                    </tr>
                                    <tr >
										<th style="text-align: left"><br><span  style="text-align: left; font-family: Arial, Helvetica;margin-left: 0px ;font-size: 15px;">{{mainList.clientObject.fullname}}</span> </th>
									</tr>
                                    <tr>
										<th style="text-align: left"><span  style="text-align: left;font-family: Arial, Helvetica;margin-left: 0px ;font-size: 12px;">{{mainList.clientObject.address}}</span> </th>
									</tr>

                                    <tr>
                                       <th style="height: 70px"></th>
                                    </tr>
								</table>
							</div>

						<div>
						<!--<div style="font-family: Arial, Helvetica;width: 100% ;clear:both;font-size: 12px;">
								Please find below the detail of our Products supplied to you during  <span style=" font-weight: bold;">{{startDate | date}}</span> and  <span style=" font-weight: bold;">{{endDate | date}}</span>
						</div>-->
						<table   width="100%" border="1" cellpadding="3" id="customers" style=" border-collapse: collapse;" >
								<thead>
								<tr style="background-color: #F0F8FF">
									<th style="border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;">#</th>
									<th style="border: 1px solid black;font-family: Arial, Helvetica;width: 50px">
										<a style="text-decoration: none;font-size: 10px;color: black; " href="#" ng-click="sortType = 'date'; sortReverse = !sortReverse">
											Date

										</a>
									</th>
									<th style="border: 1px solid black;font-family: Arial, Helvetica">

										<a style="color: black ;text-decoration: none;font-size: 10px;" href="#" ng-click="sortType = 'discription'; sortReverse = !sortReverse">
											DESCRIPTION
										</a>

									</th>

                                    <th style="border: 1px solid black;font-family: Arial, Helvetica">

                                        <a style="color: black ;text-decoration: none;font-size: 10px;" href="#" ng-click="sortType = 'discription'; sortReverse = !sortReverse">
                                            Rate
                                        </a>

                                    </th>

									<!--<th style="border: 1px solid black;font-family: Arial, Helvetica">

										<a style="color: black ;text-decoration: none;font-size: 10px;" href="#" ng-click="sortType = 'reference_number'; sortReverse = !sortReverse">
											REFERENCE No.
										</a>
									</th>-->
									<th style="border: 1px solid black;font-family: Arial, Helvetica">
										<a style="color: black ;text-decoration: none;font-size: 10px;" href="#" ng-click="sortType = 'delivery'; sortReverse = !sortReverse">
     										DELIVERY
										</a>
									</th>
									<th style="border: 1px solid black;font-family: Arial, Helvetica">
										<a style="color: black ;text-decoration: none;font-size: 10px;" href="#" ng-click="sortType = 'reciveAmount'; sortReverse = !sortReverse">
    										RECEIVED

										</a>
									</th>
									<th style="border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;">


										<a style="color: black ;text-decoration: none;" href="#" ng-click="sortType = 'balance'; sortReverse = !sortReverse">
											BALANCE

										</a>
									</th>
								</tr>
								</thead>
								<tbody>

								<tr  ng-repeat="list in mainList.ledgerData | orderBy:sortType:!sortReverse track by $index ">
									<td style="border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;">{{$index + 1}}</td>
									<td style="border: 1px solid black;font-family: Arial, Helvetica;;font-size: 10px;width: 100px">{{changeDateFormate(list.date)}}</td>
									<td style="border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;">{{list.discription}}</td>
									<td style="border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;">{{list.rate | number :2}}</td>
									<!--<td style="border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;">{{list.reference_number| number :2}}</td>-->
									<td style="border: 1px solid black;font-family: Arial, Helvetica ;text-align: right;font-size: 10px;">{{list.delivery | number :2}}</td>
									<td style="border: 1px solid black ;text-align: right;font-family: Arial, Helvetica;font-size: 10px;">{{list.reciveAmount | number :2}}</td>
									<td style="border: 1px solid black;text-align: right;font-family: Arial, Helvetica;font-size: 10px;">{{list.balance |number :2}}</td>
								</tr>
                                <tr ng-repeat="summary in mainList.sumery">
                                    <td style="border: 1px solid black"></td>
                                    <th colspan="3" style="color: black; border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;">{{summary.product_name }}</th>
                                    <th colspan="1" style="color: black;text-align:right ; border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;">{{summary.deliveryQuantity_sum | number :2}}</th>
                                    <td colspan="1" style="text-align: right ; border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;"></td>
                                    <th colspan="1" style="color: black;text-align: right ; border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;">{{summary.deliverySum |  number :2}}</th>
                                </tr>

								</tbody>
						</table>

                        <div style="width: 100% ;clear:both;height: 0px" ></div>
                        <div style="page-break-after:always ;margin-top:5px;width: 100% ;clear:both;" >
                            <div style="width: 100% ; float: left">
                                <table style="width :100%;background-color: white">
                                    <tr>
                                        <th style="width: 92%;font-family: Arial, Helvetica;text-align: right">Payable: </th>
                                        <th style="font-family: Arial, Helvetica;text-align: right"> <span>{{mainList.sumery[0].deliverySum}}</span> </th>
                                    </tr>
                                    <tr>
                                        <th style="width: 92%;text-align: right"><span  style="text-align: right; font-family: Arial, Helvetica;margin-left: 0px ;font-size: 14px;"></span>Arrears : </th>
                                        <th style="text-align: right"><span  style="text-align: right; font-family: Arial, Helvetica;margin-left: 0px ;font-size: 14px;"></span><span style="">{{mainList.arrearer}}</span>  </th>
                                    </tr>
                                    <tr>
                                        <th style="width: 92%;text-align: right"><span  style="text-align: right; font-family: Arial, Helvetica;margin-left: 0px ;font-size: 14px;"></span> <span style=""></span>  </th>
                                        <th style="text-align: right"><span  style="text-align: right; font-family: Arial, Helvetica;margin-left: 0px ;font-size: 14px;"></span> <span style="">{{mainList.current_balance}}</span>  </th>
                                    </tr>

                                </table>
                                <table style="width :100%;background-color: white">
                                    <tr>
                                        <td style="font-family: Arial, Helvetica;text-align: left"><strong>Note:</strong> Please pay your outstanding dues before 5th of each month for our continuous Better Service.    </td>


                                    </tr>
                                    <tr>
                                        <td style="text-align: left"><span  style="text-align: left; font-family: Arial, Helvetica;margin-left: 0px ;font-size: 14px;">For feedback or any complaint Contact :0322 4377422,0346 4316811</span> </td>

                                    </tr>

                                </table>
                            </div>

                        <div>


                </div>
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


	</div>
</div>

