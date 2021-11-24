

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/weeklyQuantity/weeklyQuantity-grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>


<div id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">

    <div ng-controller="manageZone" ng-init='init(<?php echo $riderList ?>,<?php echo $data ?>,"<?php echo Yii::app()->createAbsoluteUrl('zone/saveNewZone'); ?>","<?php echo Yii::app()->createAbsoluteUrl('zone/editZone'); ?>","<?php echo Yii::app()->createAbsoluteUrl('zone/delete'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/saveRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/deleteRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/search'); ?>")'>
        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                  Demand Quntity
                </a>
            </li>
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                  <span style="color: #006400"> {{startWeakDate}}</span> -To- <span style="color: #006400"> {{endWeakDate}}</span>
                </a>
            </li>

        </ul>

        <div class="panel-body">


            <input style="float: left ; width: 20% ;" class="form-control input-sm"   datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startWeakDate" size="2">
            <button style="float: left" type="button" ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-info btn-sm"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
            <input style="float: left ; width: 20% ;" class="form-control input-sm"   datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endWeakDate" size="2">
            <select ng-show="true" style="width: 20%; float: left;margin-left: 10px ; margin-right: 10px" class="form-control input-sm" ng-model="selectRiderID" >
                <option value="0">All Rider</option>
                <option ng-repeat="list in riderList" value="{{list.rider_id}}">{{list.fullname}}
                </option>
            </select>

            <a class="btn btn-primary btn-sm" href="<?php echo Yii::app()->createUrl('zone/weeklyQuantity')?>?startWeakDate={{startWeakDate}}&endWeakDate={{endWeakDate}}&selectRiderID={{selectRiderID}}"><i class="fa fa-search"></i> Search </a>


            <img ng-show="imageLoading" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">

        </div>

            <div ng-show="false" class="row">
                <div class="col-sm-12">
                    <input style="float: left ; width: 25% ;" class="form-control"   datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startWeakDate" size="2">
                    <input style="width: 30%; float: left;margin-left: 2px" class="form-control"   datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endWeakDate" size="2">



                    <a class="btn btn-primary btn-sm" href="<?php echo Yii::app()->createUrl('zone/weeklyQuantity')?>?startWeakDate={{startWeakDate}}&endWeakDate={{endWeakDate}}"><i class="fa fa-search"></i> Search </a>

                    <a ng-show="false" class="btn btn-primary " href="<?php echo Yii::app()->createUrl('zone/weeklyQuantity')?>?startWeakDate={{startWeakDate}}&endWeakDate={{endWeakDate}}"><i class="fa fa-share"></i> Export </a>
                    <img ng-show="imageLoading" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
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

            <div style="padding:5px;" class="table-responsive">
                <table id="customers">
                    <thead>
                    <tr>
                        <th width="40%"><a href="#"> Product </a></th>
                        <th width="30%"><a href="#">Unit</a> </th>
                       <!-- <th>Delete</th>-->
                        <th><a href="#">Quantity</a></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="zone in data.product">
                        <td>{{zone.product_name}}</td>
                        <td>{{zone.unit}}</td>
                        <td>{{zone.quantity |  number: 2}}</td>
                    </tr>
                    </tbody>
                </table>



            </div><!-- table-responsive -->
        </div>

    </div>
</div>

