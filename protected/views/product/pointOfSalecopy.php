

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/pointOfSale/pointOfSale-grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>

<?php $allow_delete = crudRole::getCrudrole(4); ?>

<div id="testContainer" style="display: none" class="panel row" ng-app="productGrid">
    <div ng-controller="manageProduct" ng-init='init(<?php echo $allow_delete  ?> ,  <?php echo $productCount ?> , <?php echo $productList ?> ,"<?php echo Yii::app()->createAbsoluteUrl('product/saveNewProduct'); ?>","<?php echo Yii::app()->createAbsoluteUrl('product/editProduct'); ?>","<?php echo Yii::app()->createAbsoluteUrl('product/delete'); ?>","<?php echo Yii::app()->createAbsoluteUrl('product/searchProduct'); ?>","<?php echo Yii::app()->createAbsoluteUrl('product/checkAlredyExistProduct'); ?>","<?php echo Yii::app()->createAbsoluteUrl('product/nextPageForpagination'); ?>")'>
        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                    Point Of Sale
                </a>
            </li>

        </ul>
        <div  style="margin: 10px">
            <div class="form-group input-group col-lg-4" ng-show="false">
                <!--<input style="" type="text" class="form-control " ng-model="search" ng-change="changeSearchBar(search)" ng-disabled="isLoading" id="search-dealer" placeholder="Search Product">-->
                <select ng-model="selectProduct" ng-change="addNewProduct(selectProduct)" selectProduct() class="form-control">
                    <option value="0">Select</option>
                    <option ng-repeat="list in productList" value="{{list}}">{{list.name}}</option>
                </select>
                <span class="input-group-btn">
                        <button  style="box-shadow: inset 0 0 0 5px #DCDCDC;" class="btn btn-default" ng-disabled="isLoading" ng-click="searchProduct(search)" type="button"><i class="fa fa-search"></i>
                        </button>
                 </span>
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





                <div class="dataTables_length  col-md-12" id="datatable_products_length">

                    <div class="form-group input-group col-lg-3" ng-show="false">
                        <!--<input style="" type="text" class="form-control " ng-model="search" ng-change="changeSearchBar(search)" ng-disabled="isLoading" id="search-dealer" placeholder="Search Product">-->
                        <select ng-model="selectProduct" ng-change="addNewProduct(selectProduct)" class="form-control">
                            <option value="0">Select</option>
                            <option ng-repeat="list in productList" value="{{list.product_id}}">{{list.name}}</option>
                        </select>
                        <span class="input-group-btn">

                               <button  style="box-shadow: inset 0 0 0 5px #DCDCDC;" class="btn btn-default" ng-disabled="isLoading" ng-click="searchProduct(search)" type="button"><i class="fa fa-search"></i>
                                </button>
                      </span>
                    </div>
                     <!-- {{productList}}-->
                    <table id="customers">
                        <thead>
                        <tr style="background-color: #F0F8FF">
                           <th width="40%"><a href="#">Product</a></th>
                            <th width="20%"><a href="#">Price</a></th>
                            <th width="20%"><a href="#">Quantity</a></th>
                            <th style="text-align: center"><a href="#">Total Price</a></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="list in productList">
                            <td>{{list.name}}</td>
                            <td>{{list.price}}</td>
                           <!-- <td><input ng-change="quantityChange()"  type="text" class="form-control" ></td>-->

                           <td><input  class="form-control" type="text" ng-change="myFunc()" ng-model="myvalue" /></td>
                            <td style="text-align: right">50</td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td style="text-align: right">
                                <button ng-disabled="allow_delete[1]" type="button" ng-click="addnewZone()" class="btn btn-primary btn-sm"> <i class="fa fa-save"></i> Submit</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <!--<div class="dataTables_length  col-md-12" style="height: 18px"></div>
                    <table width="100%" class="table table-striped table-bordered ">
                        <thead>
                        <tr style="background-color: #ececec;">
                            <th>
                                <a href="#">Item Name</a>
                            </th>
                            <th>
                                <a href="#">Unit </a>
                            </th>
                            <th>
                                <a href="#">Price</a>
                            </th>
                            <th>
                                <a href="#"> Quantity</a>
                            </th>
                            <th>
                                <a href="#"> Total</a>
                            </th>
                            <th>
                            </th>
                        </tr>
                        </thead>
                        <tbody>

                        <tr ng-repeat="product in  saleValueItem">
                            <td >
                                <span style="font-size: 12px" >{{product.product_name}} </span>
                            </td>
                            <td >
                                <span style="font-size: 12px" > {{product.unit_name}}</span>
                            </td>
                            <td >
                                <input type="text"  ng-change="selectPriceForSale(product)"  ng-model="product.sale_price" ng-disabled ="product.priceEditAble == 0"  style="width: 120px">
                            </td>
                            <td >
                                {{selectQuantityForSale(product)}}
                                <input type="text" ng-change="selectQuantityForSale(product)" ng-model="product.SaleQuantity"  style="width: 120px">
                                <br>
                                <span ng-show="product.checkAvailAbleStock"> Not Avaible</span>
                            </td>
                            <td >
                                {{product.totalPrice | number:0}}
                            </td>
                            <td>
                                <button type="button" ng-click="removeSaleButton(product)" title="REMOVE" class="btn btn-success btn-circle btn-sm"><i class="fa fa-minus"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">
                            </td>
                            <td colspan="2">
                                <button type="button"  ng-disabled="saleButtonQuantityValidation &&  saleButton ||  saleDisabled " ng-click="saleItemFunction(saleValueItem)" class="btn btn-success btn-sm">Submit</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>-->
                </div>


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

