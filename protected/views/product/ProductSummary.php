
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/js/table/table_style.css">
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/ProductSummary/ProductSummary-grid.js"></script>

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/FileSaver/FileSaver.js"></script>
<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>



<div id="testContainer" style="display: none" class="panel row" ng-app="productGrid">
    <div ng-controller="manageProduct" ng-init='init(<?php echo $today_date; ?>, <?php echo $productList ?> ,"<?php echo Yii::app()->createAbsoluteUrl('product/getdateWiseDataDeliveryData'); ?>")'>
        <ul class="nav nav-tabs nav-tabs-lg">
            <li style="text-align: center">

                    Rate wise Customer Summary

            </li>

        </ul>
        <div  style="margin: 10px">

            <div style="margin-top:5px;" class="table-responsive">

                <style>
                    #Product_Summary_rate_wise {

                        border-collapse: collapse;
                        width: 100%;
                    }
                    #Product_Summary_rate_wise td, #Product_Summary_rate_wise th {
                        border: 1px solid #ddd;
                        padding: 8px;
                        color: black;
                    }
                    #Product_Summary_rate_wise tr:nth-child(even){background-color: #F8F8FF;}
                    #Product_Summary_rate_wise tr:hover {background-color: #FAFAD2;}
                    #Product_Summary_rate_wise th {
                        padding-top: 12px;
                        padding-bottom: 12px;
                        text-align: left;
                        color: white;
                    }
                </style>

                <input  style="float: left ; width: 20% ;" class="form-control input-sm"  ng-change="selectRiderOnChange(selectRiderID)"  datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="today_date" size="2">

                <button style="float: left" type="button" ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-info btn-sm"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>

                <input  style="float: left ; width: 20% ;" class="form-control input-sm"  ng-change="selectRiderOnChange(selectRiderID)"  datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="end_date" size="2">
                <button style="margin-left: 10px;" type="button"  ng-click="selectDateWiseData()" class="btn btn-primary btn-sm "> <i class="fa fa-search" style="margin: 4px"></i> Search</button>

                <button class="btn btn-info btn-sm" style=" margin-left: 5px" onclick="javascript:xport.toCSV('Product_Summary_rate_wise');"> <i class="fa fa-share"></i> Export</button>

                <img ng-show="imageLoading" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">

                <table id="customers"  class="table table-fixed" style="margin-top: 10px">
                    <thead>
                    <tr  style="background-color: #F0F8FF">
                        <th class="col-xs-1"><a href="#">#</a></th>
                        <th class="col-xs-3"><a href="#">Product</a></th>
                        <th class="col-xs-2"><a href="#">No. of customer</a></th>
                        <th class="col-xs-2"><a href="#">Rate</a> </th>
                        <th class="col-xs-2"><a href="#">Liter</a> </th>
                        <th class="col-xs-2"><a href="#">Amount</a> </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="product in productList track by $index ">
                        <td class="col-xs-1">{{$index + 1}}</td>
                        <td class="col-xs-3"><a  href="<?php echo Yii::app()->baseUrl; ?>/product/customerWithRate?rate={{product.rate}}&today_date={{today_date}}&end_date={{end_date}}" > {{product.product_name}}</a></td>

                        <td class="col-xs-2" style="text-align: right"><a  href="<?php echo Yii::app()->baseUrl; ?>/product/customerWithRate?rate={{product.rate}}&today_date={{today_date}}&end_date={{end_date}}" >{{product.length }}</a></td>
                        <td class="col-xs-2" style="text-align: right">{{product.rate | number : 2}}</td>
                        <td class="col-xs-2" style="text-align: right">{{product.quantity}}</td>
                        <td class="col-xs-2" style="text-align: right">{{product.amount |number}}</td>
                    </tr>
                    <tr>
                        <td class="col-xs-1" colspan="">Total</td>
                        <td class="col-xs-3" colspan=""></td>
                        <td class="col-xs-2" colspan=""></td>
                        <td class="col-xs-2" colspan=""></td>
                        <td class="col-xs-2" style="text-align: right">{{totalQuantity |number}}</td>
                        <td class="col-xs-2" style="text-align: right">{{totalAmount |number}}</td>
                    </tr>
                    </tbody>
                </table>

            </div><!-- table-responsive -->
        </div>


        <!-- start: add new Zone -->


        <modal title="Add New product" visible="showAddNewPro">
            <form role="form" class="form-group" ng-submit="saveProduct(productObject)">
                <div class="col-lg-12 form-group" style="background-color: honeydew">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Name :</span>
                    </div>
                    <div class="col-lg-8">
                        <input ng-change="checkAlreadyExistFunction(productObject.name)" type="text" ng-model="productObject.name" class="form-control"   required/>
                        <span ng-show="checkAlredyExist" style="color: green">This product is already exist </span>
                    </div>
                </div>
                <div class="col-lg-12 form-group" style="background-color: 	#FFF0F5">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Unit :</span>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" ng-model="productObject.unit" class="form-control"   required/>
                    </div>
                </div>
                <div class="col-lg-12 form-group">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Price :</span>
                   </div>
                    <div class="col-lg-8">
                        <input type="text" ng-model="productObject.price" class="form-control"   required/>
                    </div>
                </div>
                <div class="col-lg-12 form-group" style="background-color: honeydew">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Order Type :</span>
                    </div>
                    <div class="col-lg-8" style="padding: 10px">
                        <label class="radio-inline">
                            <input ng-model="productObject.order_type"   type="radio" name="order_type" value="1" required>
                            <span  class="label label-default">One-Time</span>
                        </label>
                        <label class="radio-inline">
                            <input ng-model="productObject.order_type" type="radio" name="order_type" value="0" required>
                            <span  class="label label-primary">Regular</span>
                        </label>
                    </div>
                </div>

                <div class="col-lg-12 form-group" style="background-color: 	#FFF0F5">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Status :</span>
                    </div>
                    <div class="col-lg-8" style="padding: 10px">
                        <label class="radio-inline">
                            <input ng-model="productObject.is_active"   type="radio" name="status" value="1" required>
                            <span  class="label label-default">Active</span>
                        </label>
                        <label class="radio-inline">
                            <input ng-model="productObject.is_active" type="radio" name="status" value="0" required>
                            <span  class="label label-primary">Inactive</span>
                        </label>
                    </div>
                </div>
                <div class=" form-group ">
                    <button ng-disabled="checkAlredyExist"  type="submit" class="btn-success  btn-sm">Save</button>
                    <button   type="button" class="btn-success  btn-sm" ng-click="resetProjectObject()"> Reset</button>
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                </div>
            </form>
        </modal>

        <!-- end: add new Zone -->


        <!-- start: edit Product -->
        <modal title="Update product" visible="showEditProduct">
            <form role="form" class="form-group" ng-submit="editProductSave(productObject)">
                <div class="col-lg-12 form-group" style="background-color: honeydew">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Name :</span>
                    </div>
                    <div class="col-lg-8" style="padding: 10px">
                        <input  ng-change="checkAlreadyExistFunction(productObject.name)" type="text" ng-model="productObject.name" class="form-control"   required/>
                        <span ng-show="checkAlredyExist" style="color: green">This product is already exist </span>
                   </div>
                </div>
                <div class="col-lg-12 form-group">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Unit :</span>
                    </div>
                    <div class="col-lg-8" style="padding: 10px">
                        <input type="text" ng-model="productObject.unit" class="form-control"   required/>
                    </div>
                </div>
                <div class="col-lg-12 form-group">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Price :</span>
                    </div>
                    <div class="col-lg-8" style="padding: 10px">
                        <input type="text" ng-model="productObject.price" class="form-control"   required/>
                    </div>
                </div>
                <div class="col-lg-12 form-group" style="background-color: honeydew">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Order Type :</span>
                    </div>
                    <div class="col-lg-8" style="padding: 10px">
                        <label class="radio-inline">
                            <input ng-model="productObject.order_type"   type="radio" name="order_type" value="1" required>
                            <span  class="label label-default">One-Time</span>
                        </label>
                        <label class="radio-inline">
                            <input ng-model="productObject.order_type" type="radio" name="order_type" value="0" required>
                            <span  class="label label-primary">Regular</span>
                        </label>
                    </div>
                </div>
                <div class="col-lg-12 form-group">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Status :</span>
                    </div>
                    <div class="col-lg-8" style="padding: 10px">
                        <label class="radio-inline">
                            <input ng-model="productObject.is_active"   type="radio" name="status" value="1" required>
                            <span  class="label label-default">Active</span>
                        </label>
                        <label class="radio-inline">
                            <input ng-model="productObject.is_active" type="radio" name="status" value="0" required>
                            <span  class="label label-primary">Inactive</span>
                        </label>
                    </div>
                </div>
                <div class=" form-group ">
                    <button ng-disabled="checkAlredyExist" type="submit" class="btn-success  btn-sm">Update</button>
                    <button   type="button" class="btn-success  btn-sm" ng-click="resetProjectObject()"> Reset</button>
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                </div>
            </form>
        </modal>

        <!-- end: edit Product -->





    </div>
</div>

