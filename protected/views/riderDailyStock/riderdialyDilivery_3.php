
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/js/table/table_style.css">

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/riderDialyDilivery/riderDialyDilivery-grid.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/FileSaver/FileSaver.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>
<?php $allow_delete = crudRole::getCrudrole(13); ?>

<div  id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">
    <div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo $payment_term ?> ,<?php echo $default_product_id ?> ,<?php echo $product_list ?> , <?php echo $allow_delete ?> ,<?php echo $company_id ?> , <?php echo $riderList; ?>,"<?php echo Yii::app()->createAbsoluteUrl('index.php/riderDailyStock/getDialyDeliveryCustomer'); ?>"," <?php echo Yii::app()->createAbsoluteUrl('index.php/riderDailyStock/googleMap');?>" ," <?php echo Yii::app()->createAbsoluteUrl('index.php/riderDailyStock/saveDeliveryFromPortal');?>")'>
        <div class="tabbable">
            <ul class="nav nav-tabs nav-tabs-lg">
                <li>
                    <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                        RIDER Daily Delivery Report
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">


                    <input style="float: left ; width: 12% ;" class="form-control"  ng-change="selectRiderOnChange(selectRiderID)"  datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="todate" size="2">

                    <select style="width: 12%; float: left;margin-bottom: 10px;margin-left: 10px ; margin-right: 10px" class="form-control" ng-model="selectRiderID" >
                        <option value="">Select Rider</option>
                        <option ng-repeat="list in riderList" value="{{list.rider_id}}">{{list.fullname}}
                        </option>
                    </select>

                    <select style="width: 12%; float: left;margin-bottom: 10px;margin-left: 10px ; margin-right: 10px" class="form-control" ng-model="filter_by" >
                        <option value="0">Filter By</option>
                        <option value="1">Customer Name</option>
                        <option value="2">Set Route</option>
                    </select>

                    <select style="width: 12%; float: left;margin-bottom: 10px;margin-left: 10px ; margin-right: 10px" class="form-control" ng-model="scheduled_customer" >
                        <option value="0">All Customer</option>
                        <option value="1">Scheduled Customer</option>

                    </select>

                    <select style="width: 12%; float: left;margin-bottom: 10px;margin-left: 10px ; margin-right: 10px" class="form-control" ng-model="product_id" >

                        <option value="0">All Product</option>
                        <option ng-repeat="list in product_list" value="{{list.product_id}}">{{list.name}}</option>
                    </select>

                    <select style="width: 12%; float: left;margin-bottom: 10px;margin-left: 10px ; margin-right: 10px" class="form-control" ng-model="payment_term_id" >

                        <option value="0">Payment Term</option>
                        <option ng-repeat="list in payment_term" value="{{list.payment_term_id}}">{{list.payment_term_name}}</option>
                    </select>



                    <button type="button"  ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-primary "> <i class="fa fa-search"></i> Search</button>

                    <a class="btn btn-primary " href="<?php echo Yii::app()->createUrl('riderDailyStock/exportDialyDeliveryCustomer')?>?date={{todate}}&riderID={{selectRiderID}}&product_id={{product_id}}&payment_term_id={{payment_term_id}}"><i class="fa fa-share"></i> Export </a>
                    <button ng-show="false" class="btn btn-info" style="float: left; margin-left: 5px" onclick="javascript:xport.toCSV('customers');"> <i class="fa fa-share"></i> Export</button>
                    <img ng-show="imageLoading" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">


                    <style>

                        [data-tooltip] {
                            position: relative;
                            z-index: 2;
                            cursor: pointer;
                        }

                        /* Hide the tooltip content by default */
                        [data-tooltip]:before,
                        [data-tooltip]:after {
                            visibility: hidden;
                            -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
                            filter: progid: DXImageTransform.Microsoft.Alpha(Opacity=0);
                            opacity: 0;
                            pointer-events: none;
                        }

                        /* Position tooltip above the element */
                        [data-tooltip]:before {
                            position: absolute;
                            bottom: 150%;
                            left: 50%;
                            margin-bottom: 5px;
                            margin-left: -80px;
                            padding: 7px;
                            width: 160px;
                            -webkit-border-radius: 3px;
                            -moz-border-radius: 3px;
                            border-radius: 3px;
                            background-color: #000;
                            background-color: hsla(0, 0%, 20%, 0.9);
                            color: #fff;
                            content: attr(data-tooltip);
                            text-align: center;
                            font-size: 14px;
                            line-height: 1.2;
                        }

                        /* Triangle hack to make tooltip look like a speech bubble */
                        [data-tooltip]:after {
                            position: absolute;
                            bottom: 150%;
                            left: 50%;
                            margin-left: -5px;
                            width: 0;
                            border-top: 5px solid #000;
                            border-top: 5px solid hsla(0, 0%, 20%, 0.9);
                            border-right: 5px solid transparent;
                            border-left: 5px solid transparent;
                            content: " ";
                            font-size: 0;
                            line-height: 0;
                        }

                        /* Show tooltip content on hover */
                        [data-tooltip]:hover:before,
                        [data-tooltip]:hover:after {
                            visibility: visible;
                            -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
                            filter: progid: DXImageTransform.Microsoft.Alpha(Opacity=100);
                            opacity: 1;
                        }

                    </style>

                    <table id="customers" style="" class="table table-fixed">
                        <thead>
                        <tr>
                            <th style="width: 2%"><a href="">#</a></th>
                            <th style="width: 14%"><a href="">Customer Name</a></th>
                            <th style="width: 7%"><a href="">Customer Category</a></th>
                            <th style="width: 7%"><a href="">Payment Term</a></th>
                            <th style="width: 7%"><a href=""> Zone</a></th>
                            <th style="width: 7%"><a href=""> Address</a></th>
                            <th style="width: 7%"><a href=""> Rate</a></th>
                            <th style="width: 7%"><a href=""> Product</a></th>
                            <th style="width: 7%"><a href=""> Regular</a></th>
                            <th style="width: 7%"><a href=""> One-Time</a></th>
                            <th style="width: 7%"><a href=""> Delivered</a></th>
                            <th style="width: 7%"><a href=""> Time</a></th>
                            <th style="width: 7%"><a href=""><i class="fa fa-money" aria-hidden="true"></i></a></th>
                            <th style="width: 7%"><a href=""> Make Delivery</a></th>
                        </tr>
                        </thead>
                        <tbody>

                        <tr  ng-show="regularOrderList.client_type=='1'" ng-repeat="regularOrderList in todayDeliveryproductList | orderBy:sortType:!sortReverse track by $index">
                            <td style="width: 2%">{{$index + 1}}_!</td>

                            <td style="width :14%;color: {{regularOrderList.edit_by_color}}"> <span ng-bind="regularOrderList.fullname"></span>
                                <br> {{regularOrderList.cell_no_1}}
                            </td>
                            <td style="width: 7%"><span ng-bind="regularOrderList.category_name"></span></td>

                            <td style="width: 7%"><span ng-bind="regularOrderList.payment_term_name"></span></td>

                            <td style="width: 7%"><span ng-bind="regularOrderList.zone_name"></span></td>
                            <td style="width: 7%"><span ng-bind="regularOrderList.address"></span></td>
                            <td style="width: 7%"><a ng-click="updateClietProductRate_model(regularOrderList)" href="#">{{regularOrderList.price | number}} </a></td>
                            <td style="width: 7%"><span ng-bind="regularOrderList.productName"></span></td>
                            <td style="width :7%;text-align: right"><span ng-bind="regularOrderList.regularQuantity"></span></td>
                            <td style="width :7%;text-align: right"><span ng-bind="regularOrderList.totalSpecialQuantity"></span></td>
                            <td style="width :7%;text-align: right;">
                                <span ng-show="regularOrderList.edit_by_color=='not'" ng-bind="regularOrderList.deliveredQuantity"></span>
                                <p  ng-show="regularOrderList.edit_by_color=='yes'"><button style="background-color: navajowhite" data-tooltip="{{regularOrderList.edit_by_name}}">{{regularOrderList.deliveredQuantity}}</button></p>

                            </td>
                            <td style="width: 7%">
                                <p  ng-show="regularOrderList.reject_delivery"><button style="background-color: rosybrown" data-tooltip="{{regularOrderList.reasonType_name}}">{{regularOrderList.time}}</button></p>
                                <span ng-show="!regularOrderList.reject_delivery" ng-bind="regularOrderList.time"></span>
                            </td>

                            <td style="width: 7%">
                                <a ng-show="!regularOrderList.current_balance_of_client" href="" ng-click="get_current_balance(regularOrderList)"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                <span ng-show="regularOrderList.current_balance_of_client" ng-bind="regularOrderList.current_balance_of_client" ></span>
                            </td>
                            <td style="width: 7%">
                                <input type="number" ng-show="regularOrderList.updateMode" ng-model="regularOrderList.quantity" style="width: 60px">
                                <button ng-disabled=" allow_delete[3]"  ng-show="!regularOrderList.updateMode" href="" ng-click="setCompanyLimit(regularOrderList)"  class = "btn btn-success btn-smSaveDelivery" title="Make Delivery"><i class="fa fa-truck"></i></button>
                                <button ng-disabled="regularOrderList.quantity<=0 ||regularOrderList.makeDeliveryLoader"  ng-show="regularOrderList.updateMode"  href="" ng-click="SaveDelivery(regularOrderList)"  class = "btn btn-success btn-sm" title="Save"><i class="fa fa-save"></i></button>
                                <button ng-disabled="regularOrderList.makeDeliveryLoader"  ng-show="regularOrderList.updateMode && !regularOrderList.makeDeliveryLoader"  href="" ng-click="closeDelivery(regularOrderList)"  class = "btn btn-info btn-sm" title="Save"><i class="fa fa-close"></i></button>
                                <button ng-disabled="regularOrderList.makeDeliveryLoader "  ng-show="regularOrderList.deliveredQuantity>0"  href="" ng-click="SaveDelivery_delete(regularOrderList)"  class = "btn btn-info btn-sm" title="Delete"><i class="fa fa-trash"></i></button>
                                <img ng-show="regularOrderList.makeDeliveryLoader" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
                            </td>

                        </tr>

                      
                        </tbody>


                    </table>
                </div>
            </div>

            <!--Company Limit Model-->
            <!--<modal title="Set Company Limit" visible="limitModelShow">
				<div class="row">
					<?php
            /*					$form = $this->beginWidget(
                                    'CActiveForm',
                                    array(
                                        'id' => 'agreement-form',
                                        'enableAjaxValidation' => false,
                                    )
                                );
                                */?>
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
						<?php /*$this->endWidget(); */?>
					</div>
			</modal>-->


            <!--update rate-->
            <modal title="Update Rate " visible="updateRateModel">
                <form ng-submit="save_new_rate()">
                    <div class="row">
                        <div class="form-group col-sm-12">
                            <div class="col-sm-4">
                                <label for="pwd" style="font-weight: bold;">Customer Name:</label>
                            </div>
                            <div class="col-sm-4">{{updateRatelistObject.fullname}}</div>
                        </div>

                        <div class="form-group col-sm-12">
                            <div class="col-sm-4">
                                <label for="pwd" style="font-weight: bold;">product Name:</label>
                            </div>
                            <div class="col-sm-4">{{updateRatelistObject.productName}}</div>
                        </div>

                        <div class="form-group col-sm-12">
                            <div class="col-sm-4">
                                <label for="pwd" style="font-weight: bold;">product Rate:</label>
                            </div>
                            <div class="col-sm-4">{{updateRatelistObject.price}}</div>
                        </div>
                        <div class="form-group col-sm-12">
                            <div class="col-sm-4">
                                <label for="pwd" style="font-weight: bold;">New Rate:</label>
                            </div>
                            <div class="col-sm-4">
                                <input type="type" class="form-control" id="pwd"  name="pwd" ng-model="updateRatelistObject.new_product_rate" required>
                            </div>
                        </div>

                        <div class="form-group col-sm-12">
                            <div class="col-sm-4">
                                <button type="submit" class="btn btn-default">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
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
