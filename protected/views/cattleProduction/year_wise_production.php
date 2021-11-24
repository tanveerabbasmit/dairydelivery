
<style>
    .angularjs-datetime-picker{
        z-index: 99999 !important;
    }
</style>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/production/year_wise_production_grid.js"></script>

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/FileSaver/FileSaver.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>


<?php  $allow_delete = crudRole::getCrudrole(1); ?>

<div id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">
    <div ng-controller="manageZone" ng-init='init(<?php echo $allow_delete ?>,<?php echo $CattleList ?>,  <?php echo $todaydate ?> ,"<?php echo Yii::app()->createAbsoluteUrl('cattleProduction/production'); ?>","<?php echo Yii::app()->createAbsoluteUrl('zone/editZone'); ?>","<?php echo Yii::app()->createAbsoluteUrl('zone/delete'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/saveRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/deleteRiderStock'); ?>","<?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/search'); ?>")'>
        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                    Manage Cattle
                </a>
            </li>
        </ul>
        <div class="" style="margin: 10px">
            <div class="">

               <!--{{zoneObject}}-->
                <div class="col-lg-1">
                    <div >
                        <a  href="<?php echo Yii::app()->baseUrl; ?>/cattleRecord/addCattle/0"  type="button"   class="btn btn-primary btn-sm"> <i class="fa fa-plus"></i> Add New</a>
                    </div>
                </div>
                <div class="col-lg-1">
                    <div >

                        <a  href="<?php echo Yii::app()->baseUrl; ?>/cattleRecord/manageCattle"  type="button"   class="btn btn-primary btn-sm"> <i class=""></i> Cattle List</a>
                    </div>
                </div>
                <div class="col-lg-1">
                    <a ng-show="!editMode" href="#" ng-click="edit_mode_function()" type="button"   class="btn btn-primary btn-sm"> <i class="fa fa-edit"></i> Edit</a>
                     <a ng-show="editMode" href="#" ng-click="saveProduction()" type="button"   class="btn btn-success btn-sm"> <i class="fa fa-save"></i> save</a>
                </div>

                <div class="col-lg-1">
                    <button class="btn-primary btn-sm" style="float: left; margin-left: 5px" onclick="javascript:xport.toCSV('production_export');"> <i class="fa fa-share"></i> Export</button>
                </div>

                <div class="col-lg-1">
                    <button class="btn-primary btn-sm" style="float: left; margin-left: 5px" ng-click="printProduction()"> <i class="fa fa-print"></i> Print</button>
                </div>


                <style>

                </style>
                <div class="form-group input-group col-lg-3">
                        <select ng-change="searchCattleProduction(todaydate)" class="form-control" ng-model="todaydate">
                              <option value="2018">2018</option>
                              <option value="2019">2019</option>
                              <option value="2020">2020</option>
                              <option value="2021">2021</option>
                              <option value="2022">2022</option>
                              <option value="2023">2023</option>
                              <option value="2024">2024</option>
                              <option value="2025">2025</option>
                              <option value="2026">2026</option>
                              <option value="2027">2027</option>
                              <option value="2028">2028</option>
                        </select>
                </div>

            </div>
            <div style="margin-top:0px;" class="table-responsive">
                <style>
                    #production_report {
                        border-collapse: collapse;
                        width: 100%;
                    }
                    #production_report td, #production_report th {
                        border: 1px solid #ddd;
                        padding: 8px;
                        color: black;
                    }
                    #production_report tr:nth-child(even){background-color: #F8F8FF;}
                    #production_report tr:hover {background-color: #FAFAD2;}
                    #production_report th {
                        padding-top: 12px;
                        padding-bottom: 12px;
                        text-align: left;
                        color: white;
                    }
                </style>
                <table id="production_report">
                    <thead>

                    <tr style=" background-color: #F0F8FF">
                      <th ><a href="#">Month</a></th>
                       <th width="180px"><a href="#">Production</a></th>

                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="zone in zoneList ">
                         <td><span ng-bind="zone.name"></span></td>
                         <td style="text-align: center"><span ng-bind="zone.total_production | number:2"></span></td>
                    </tr>

                    </tbody>
                </table>

            </div><!-- table-responsive -->
        </div>

        <!--export table-->

        <table ng-show="false" id="production_export">
            <thead>
            <tr style="background-color: #F0F8FF">
                <th>Date :</th>
                <th>{{todaydate}} </th>
            </tr>
            <tr style="background-color: #F0F8FF">
                <th ><a href="#">#</a></th>
                <th> Number</th>
                <th>Morning</th>
                <th>Afternoon</th>
                <th>Evening</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            <tr ng-repeat="zone in zoneList  track by $index ">
                <td>{{$index + 1}}</td>
                <td>{{zone.number}}  </td>
                <td >{{zone.morning}}</td>
                <td>{{ zone.afternoun}}</td>
                <td>{{ zone.evenining}} </td>
                <td>{{zone.total | number}}</td>
            </tr>
            <td></td>
            <td>Total</td>
            <td style="text-align: right">{{morning | number}}</td>
            <td style="text-align: right">{{afternoun | number}}</td>
            <td style="text-align: right"> {{evenining | number}}</td>
            <td style="text-align: right">{{grandTotal | number}}</td>

            </tr>
            </tbody>
        </table>

        <!-- start: add new Zone -->
<!--
        <modal title="Add New Zone" visible="showAddNewZone">
            <img  ng-click="closeImg()"  src="<?php /*echo Yii::app()->theme->baseUrl; */?>/images/cattle/{{selectImg}}" alt="" class="loading">
        </modal>-->

        <!-- end: add new Zone -->

        <!--INvoiceREport-->
        <div id="printInvoice" ng-show="false">

            <?php date_default_timezone_set("Asia/Karachi"); ?>

            <h4 style="text-align: center"> <span style=" font-family: Arial, Helvetica, sans-serif;">Cattle Production</span></h4>


            <table   width="100%" border="1" cellpadding="3" id="customers" style="page-break-after:always ; border-collapse: collapse;" >

                <tbody>
                     <tr >
                        <th style="color:black;border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;padding: 5px">#</th>
                        <th style="color:black;border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;padding: 5px">Number</th>
                        <th style="color:black;border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px; padding: 5px">Morning</th>
                        <th style="color:black;border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;padding: 5px">Afternoon</th>
                        <th style="color:black;border: 1px solid black;font-family: Arial, Helvetica;font-size: 10px;padding: 5px">Evening</th>

                    </tr>

                     <tr ng-repeat="zone in zoneList  track by $index">

                         <td  style="border: 1px solid black;padding: 0px;font-size: 12px;">{{$index + 1}}</td>
                         <td  style="border: 1px solid black;padding: 0px;font-size: 12px;">{{zone.number}}  </td>
                         <td  style="border: 1px solid black;padding: 0px;font-size: 12px;" ><span ng-show="zone.milking_time_morning=='0'"></span></td>
                         <td  style="border: 1px solid black;padding: 0px;font-size: 12px;"><span ng-show="zone.milking_time_afternoun=='0'"></span></td>
                         <td  style="border: 1px solid black;padding: 0px;font-size: 12px;"><span ng-show="zone.milking_time_evening=='0'"></span></td>

                   </tr>
               </tbody>
            </table>

        </div>
    </div>
</div>

