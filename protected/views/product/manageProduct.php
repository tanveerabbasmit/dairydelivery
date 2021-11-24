

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/manageProduct/manageProduct-grid.js"></script>

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
    <div ng-controller="manageProduct" ng-init='init(<?php echo $product_category  ?> ,<?php echo $allow_delete  ?> ,  <?php echo $productCount ?> , <?php echo $productList ?> ,"<?php echo Yii::app()->createAbsoluteUrl('product/saveNewProduct'); ?>","<?php echo Yii::app()->createAbsoluteUrl('product/editProduct'); ?>","<?php echo Yii::app()->createAbsoluteUrl('product/delete'); ?>","<?php echo Yii::app()->createAbsoluteUrl('product/searchProduct'); ?>","<?php echo Yii::app()->createAbsoluteUrl('product/checkAlredyExistProduct'); ?>","<?php echo Yii::app()->createAbsoluteUrl('product/nextPageForpagination'); ?>")'>
        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                    Manage Product
                </a>
            </li>

        </ul>
        <div  style="margin: 10px">
            <div class="">

                <div class="col-lg-4">
                    <div class="btn-demo">
                        <button ng-disabled="allow_delete[1]" class="btn btn-primary btn-sm" ng-click="addnewProduct()" ><i class="fa fa-plus"></i> Add New Product...</button>
                    </div>
                </div>

                <div class="col-lg-5">
                </div>

                <div class="form-group input-group col-lg-3">
                    <input style="" type="text" class="form-control " ng-model="search" ng-change="changeSearchBar(search)" ng-disabled="isLoading" id="search-dealer" placeholder="Search Product">
                    <span class="input-group-btn">

                                    <button  style="box-shadow: inset 0 0 0 5px #DCDCDC;" class="btn btn-default" ng-disabled="isLoading" ng-click="searchProduct(search)" type="button"><i class="fa fa-search"></i>
                                    </button>
                      </span>
                </div>
            </div>
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

                <table id="customers">
                    <thead>
                    <tr  style="background-color: #F0F8FF">
                        <th><a href="#">#</a></th>
                        <th><a href="#">ID</a></th>
                        <th><a href="#">Name</a> </th>
                        <th><a href="#">Description</a> </th>

                        <th><a href="#">Category</a> </th>
                         <th><a href="#">Unit</a> </th>
                         <th><a href="#">Price</a></th>
                        <th><a href="#">Status</a></th>
                        <th><a href="#">Image<br> <span style="font-size: 10px;color: rebeccapurple">(Click image for change)</span></a></th>
                       <!-- <th>Delete</th>-->
                        <th ><a href="#">Action</a></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="product in productList track by $index ">
                        <td>{{$index + 1}}</td>
                        <td>{{product.product_id}}</td>
                        <td>{{product.name}}</td>
                        <td>{{product.description}}</td>
                        <td>{{product.category_name}}</td>


                        <td>{{product.unit}}</td>
                        <td style="text-align: right">{{product.price | number : 2}}</td>
                        <td>
                            <span ng-show="product.is_active == '1'" class="label label-default">Active</span>
                            <span ng-show="product.is_active == '0'" class="label label-primary">Inactive</span>

                        </td>
                        <td width="60px">
                            <span  > <img  ng-click="change_image(product)" style="height: 50px ;width: 50px" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/product/{{product.image}}" alt="" class="loading"></span>
                        </td>
                        <td>
                            <button ng-disabled="allow_delete[3]" title="Edit" type="button" ng-click="editProduct(product)" class="btn btn-default btn-xs"><i style="margin: 2px" class="fa fa-edit"></i></button>
                            <button ng-disabled="allow_delete[2]" title="Delete" type="button" ng-click="delete(product)" class="btn btn-default btn-xs"><i style="margin: 2px" class="fa fa-trash "></i></button>

                        </td>
                    </tr>
                    </tbody>
                </table>
                <div  ng-show="hideAndShowPagination"  class="pagination pagination-centered">
                    <button style="background-color: #778899 ; color: white;" type="button" class="btn btn-sm  prev" title="Prev" ng-disabled="curPage == 0" ng-click="nextPagePagination(curPage =0 )"> First </button>
                    <button style="background-color: #778899 ; color: white;" type="button" class="btn btn-sm  prev" title="Prev" ng-disabled="curPage == 0" ng-click="nextPagePagination( curPage =curPage - 1)"> &lt; PREV</button>
                    <span>Page {{curPage + 1}} of {{totalPages}}</span>
                    <button style="background-color: #778899 ; color: white;" type="button" class="btn btn-sm  next" ng-disabled="curPage >= totalPages - 1" ng-click="nextPagePagination(curPage = curPage + 1)"> NEXT &gt;</button>
                    <button style="background-color: #778899 ; color: white;" type="button" class="btn btn-sm  next" ng-disabled="curPage >= totalPages - 1" ng-click="nextPagePagination(curPage  = totalPages - 1)">Last</button>
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

                <div class="col-lg-12 form-group" style="background-color: 	#FFF0F5">
                    {{productObject.description}}
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Description :</span>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" ng-model="productObject.description" class="form-control"   required/>
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

                <div class="col-lg-12 form-group">



                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Product Category :</span>
                   </div>
                    <div class="col-lg-8">
                        <select class="form-control" ng-model="productObject.product_category_id">
                            <option value="0">Select </option>
                            <option ng-repeat="list in product_category" value="{{list.product_category_id}}">{{list.category_name}} </option>
                        </select>
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

                <div class="col-lg-12 form-group" style="background-color: honeydew">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Description :</span>
                    </div>
                    <div class="col-lg-8" style="padding: 10px">
                        <input   type="text" ng-model="productObject.description" class="form-control"   required/>
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

                <div class="col-lg-12 form-group">
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Product Category :</span>
                    </div>
                    <div class="col-lg-8">
                        <select class="form-control" ng-model="productObject.product_category_id">
                            <option value="0">Select </option>
                            <option ng-repeat="list in product_category" value="{{list.product_category_id}}">{{list.category_name}} </option>
                        </select>
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


        <!-- Change Image -->

        <modal title="Update Image" visible="change_img_model">

             <?php
                $form = $this->beginWidget(
                    'CActiveForm',
                    array(
                        'id' => 'agreement-form',
                        'action'=>Yii::app()->createUrl('/product/manageProduct_update_iamge'),
                        'htmlOptions' => array('enctype' => 'multipart/form-data'),
                        'enableAjaxValidation' => false,
                    )
                );
            ?>


                <div class="col-lg-12 form-group" style="background-color: honeydew">
                    <!--{{selected_image}}-->
                    <div class="col-lg-4" style="padding: 10px">
                        <span style="font-weight: bold;font-size: 13px; padding: 8px">Image :</span>
                    </div>
                    <div class="col-lg-8" style="padding: 10px">
                        <input   type="file"  class="form-control" name="image"   required/>
                        <input  ng-show="false" type="text" ng-model="selected_image.product_id" name ="product_id"   required/>
                    </div>
                </div>
                <div class="col-lg-12 form-group" style="background-color: honeydew">

                    <div class="col-lg-12" style="padding: 10px">
                        <span> <img   style="height: 100px ;width: 100px" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/product/{{selected_image.image}}" alt="" class="loading"></span>
                    </div>

                </div>

                <div class=" form-group " >
                    <button  type="submit" class="btn-success  btn-sm">Update</button>

                    <button style="float: right" type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                </div>
            <?php $this->endWidget(); ?>
        </modal>
        <!-- Change Image -->




    </div>
</div>

