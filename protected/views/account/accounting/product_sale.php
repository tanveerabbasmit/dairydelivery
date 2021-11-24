

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/account/product_sale_grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>



<div id="testContainer" style="display: none" class="panel row" ng-app="productGrid">
    <div ng-controller="manageProduct" ng-init='init(<?php echo $today_date; ?>, <?php echo $productList ?> ,"<?php echo Yii::app()->createAbsoluteUrl('account/accounting/basic'); ?>")'>
        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                    Sale Voucher
                </a>
            </li>

        </ul>
        <div  style="margin: 10px">

            <div style="margin-top:5px;" class="table-responsive">

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

                <div class="col-md-12" style="margin-bottom: 5px">

                    <input  style="float: left ; width: 20% ;" class="form-control input-sm"  ng-change="selectRiderOnChange(selectRiderID)"  datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="today_date" size="2">


                    <select ng-change="change_product()"  class="form-control input-sm"  style="float: left ; width: 20% ;" ng-model="product_id" >
                        <option value="0">Select</option>
                        <option value="{{list.product_id}}" ng-repeat="list in productList">{{list.name}}er</option>
                    </select>
                    <button style="margin-left: 10px;" type="button"  ng-click="get_sale()" class="btn btn-primary btn-sm "> <i class="fa fa-search" style="margin: 4px"></i> Search</button>
                    <img ng-show="imageLoading" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">

                </div>
                <div ng-show="product_id>0" class="col-md-12" style="margin-top: 10px;margin-bottom: 10px">
                    <table style="">
                        <tr>
                            <th style="padding: 15px">Product Sale Account :</th>
                            <td style="padding: 15px"><span style="margin-left: 15px">{{product_sale_account_name}}</span></td>

                            <th style="padding: 15px">Product Receivable Account :</th>
                            <td style="padding: 15px"><span style="margin-left: 15px">{{product_receivable_account_name}}</span></td>
                        </tr>
                        <tr>
                            <th style="padding: 15px">Product:</th>
                            <td style="padding: 15px"><span style="margin-left: 15px">{{prduct_name}}</span></td>

                            <th style="padding: 15px">Total Amount :</th>
                            <td style="padding: 15px"><span style="margin-left: 15px">{{total}}</span></td>
                        </tr>
                        <tr>
                            <td colspan="4" style="padding: 10px">

                                <button ng-disabled="save_vocher" style="float: right;margin-left: 10px;" type="button"  ng-click="save_vocher_function()" class="btn btn-primary btn-sm "> Save Vocher</button>
                                <img  ng-show="save_vocher" style="float: right;margin-left: 10px;"  src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
                            </td>
                        </tr>
                   </table>
                </div>




                <table id="customers" style="margin-top: 10px">
                    <thead>
                    <tr  style="background-color: #F0F8FF">
                        <th><a href="#">#</a></th>
                        <th><a href="#">Customer Name</a></th>
                        <th><a href="#">Quantity</a></th>
                        <th><a href="#">Amount</a></th>

                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="product in sale_list track by $index ">
                        <td>{{$index + 1}}</td>
                        <td>

                            <a  href="#" > {{product.fullname}}</a>
                        </td>
                        <td>

                            <a  href="#" > {{product.quantity}}</a>
                        </td>
                        <td>

                            <a  href="#" > {{product.amount}}</a>
                        </td>


                    </tr>

                   <!-- <tr>
                        <td colspan="4">Total</td>
                        <td style="text-align: right">{{totalQuantity |number}}</td>
                        <td style="text-align: right">{{totalAmount |number}}</td>
                    </tr>-->
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

