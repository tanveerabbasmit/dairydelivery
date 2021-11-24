


<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/setDeliveryRout/sortList/angular-drag-and-drop-lists.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/setDeliveryRout/sortList/demo-framework.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/setDeliveryRout/sortList/angular-route.min.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/setDeliveryRout/setDeliveryRout-grid.js"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/setDeliveryRout/sortList/simple.css"">

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>


<div id="testContainer" style="display: none"  class="panel row" ng-app="demo">

    <div ng-controller="SimpleDemoController" ng-init='init(<?php echo $riderList?>,"<?php echo Yii::app()->createAbsoluteUrl('delivery/getClientList'); ?>" ,"<?php echo Yii::app()->createAbsoluteUrl('delivery/saveOrOrderByLst') ?>", "<?php echo Yii::app()->createAbsoluteUrl('delivery/saverearrangeOrderList') ?>" )'>
        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                    Set Delivery Route
                </a>
            </li>
        </ul>

        <div class="panel-body">

            <div class=" row">
                <div class="col-lg-12">

                    <div class="col-lg-3">
                        <select class="form-control" ng-model="riderId" ng-change="getRiderList(riderId,0)">
                            <option value="0">Select Rider</option>
                            <option ng-repeat="list in riderList" value="{{list.rider_id}}">{{list.fullname}}</option>
                        </select>
                    </div>
                    <div class="col-lg-4">
                       <!-- <button type="button"  ng-click="getRiderList(riderId)" class="btn btn-primary "> <i class="fa fa-search"></i> Search</button>-->

                        <button type="button"  ng-click="getRiderList(riderId,0)" class="btn btn-primary ">Reset</button>
                        <button type="button"  ng-click="saveOrderList()" class="btn btn-primary "> <i class="fa fa-save"></i> Update</button>

                        <!--<a href="" ng-click="order_by_zone()" class="btn btn-default" style="color: #1aa71c"> Order By Zone</a>-->
                        <img ng-show="imageLoading" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
                    </div>

                </div>

            </div>

            <div class="simpleDemo row">

                <div ng-repeat="(listName, list) in models.lists">

                    <div class="panel-body" >
                        <ul dnd-list="list">

                            <li ng-repeat="item in list"
                                dnd-draggable="item"
                                dnd-moved="list.splice($index, 1)"
                                dnd-effect-allowed="move"
                                dnd-selected="models.selected = item"
                                ng-class="{'selected': models.selected === item}"
                                 style="background-color: white"
                            >
                            <h4>    {{objectAndIndex(item.fullname , $index)}}
                            <span style=" font-weight: bold;color: green" > {{$index+1}}-  </span>{{item.fullname}} <span style=" font-weight: bold;margin-left: 5px;margin-left: 5px;color: green;margin-left: 15px">Address : </span>{{item.address}} </span>

                                <span style=" font-weight: bold;margin-left: 5px;margin-left: 5px;color: green;margin-left: 15px">Zone : </span><span >{{item.zone_name}}</span></span>
                            </h4>
                            </li>
                        </ul>
                    </div>
                  <!--  {{CustomerList}}-->
                </div>

            </div>
        </div>

    <div>



