

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/account/manageProduct_add_account_grid.js"></script>

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
    <div ng-controller="manageProduct" ng-init='init(<?php echo $account_list  ?> ,<?php echo $allow_delete  ?> ,  <?php echo $productCount ?> , <?php echo $productList ?> ,"<?php echo Yii::app()->createAbsoluteUrl('account/productaccounting/base'); ?>","<?php echo Yii::app()->createAbsoluteUrl('account/productaccounting'); ?>","<?php echo Yii::app()->createAbsoluteUrl('account/productaccounting'); ?>","<?php echo Yii::app()->createAbsoluteUrl('account/productaccounting'); ?>","<?php echo Yii::app()->createAbsoluteUrl('account/productaccounting'); ?>","<?php echo Yii::app()->createAbsoluteUrl('account/productaccounting'); ?>")'>
        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                    Manage Product
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

                <table id="customers">
                    <thead>
                    <tr  style="background-color: #F0F8FF">
                        <th><a href="#">#</a></th>
                        <th><a href="#">ID</a></th>
                        <th><a href="#">Name</a> </th>
                        <th><a href="#">Unit</a> </th>
                        <th><a href="#">Price</a></th>
                        <th><a href="#">Status</a></th>
                        <th><a href="#">product sale account</a></th>
                        <th><a href="#">Product Receivable Account</a></th>
                        <!-- <th>Delete</th>-->
                        <th ><a href="#">Action</a></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="product in productList track by $index ">
                        <td>{{$index + 1}}</td>
                        <td>{{product.product_id}}</td>
                        <td>{{product.name}}</td>


                        <td>{{product.unit}}</td>
                        <td style="text-align: right">{{product.price | number : 2}}</td>
                        <td>
                            <span ng-show="product.is_active == '1'" class="label label-default">Active</span>
                            <span ng-show="product.is_active == '0'" class="label label-primary">Inactive</span>

                        </td>
                        <td>
                            <select ng-change="change_acount_function(1,product)" ng-disabled="!product.update" class="form-control" ng-model="product.product_sale_account_id">
                                <option value="0">Select</option>
                                <option ng-repeat="list in account_list" value="{{list.id}}">{{list.name}}</option>
                            </select>
                        </td>
                        <td>
                            <select ng-change="change_acount_function(2,product)" ng-disabled="!product.update" class="form-control" ng-model="product.product_receivable_account_id">
                                <option value="0">Select</option>
                                <option ng-repeat="list in account_list" value="{{list.id}}">{{list.name}}</option>
                            </select>
                        </td>
                        <td>
                            <button  ng-show="!product.update" title="Edit" type="button" ng-click="update_account(product)"  class="btn btn-default btn-xs"><i style="margin: 2px" class="fa fa-edit"></i></button>
                            <button ng-show="product.update" title="Save" type="button"  ng-click="save_acount_account(product)" class="btn btn-info btn-xs"><i style="margin: 2px" class="fa fa-save"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>

            </div><!-- table-responsive -->
        </div>







    </div>
</div>

