
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/ProductionComparative/ProductionComparative-grid.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/FileSaver/FileSaver.js"></script>
<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>

<div  id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">
    <div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo json_encode($fiveDayAgo) ?> , <?php echo $company_id ?> , <?php echo $riderList; ?>,"<?php echo Yii::app()->createAbsoluteUrl('cattleProduction/productionComprative_report'); ?>"," <?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/googleMap');?>" ," <?php echo Yii::app()->createAbsoluteUrl('riderDailyStock/saveDeliveryFromPortal');?>")'>
        <div class="tabbable">

            <ul class="nav nav-tabs nav-tabs-lg">
                <li>
                    <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                        Production Comparative Report
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class=" active" id="tab_1" style="margin: 10px">

                    <div class="col-lg-12">
                        <div class="col-lg-1">
                           <!-- {{resultList}}-->
                               <div class="button-group">
                                            <button style="padding: 7px" type="button" class="btn btn-info btn-sm btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-cog"></span> <span class="caret"></span></button>
                                            <ul class="dropdown-menu dropdown-menu_2" style="overflow:scroll; max-height:400px;">
                                                <input ng-click="select_un_select_resultList_function()" ng-model="select_all_cattle" type="checkbox"/>&nbsp&nbsp&nbsp&nbspSelect All
                                                <li><input style="height: 20px" type="text" class="form-control" ng-model="searchproduct"></li>
                                                <li ng-click="select_un_select_resultList_function_2()" ng ng-repeat="list in resultList_drop_down | filter:searchproduct:strict"><a  ng-click="select_un_select_resultList_function_2()" href="#" class="small" data-value="option6" tabIndex="-1"><input ng-click="click_on_cattle()" ng-model="list.selected" type="checkbox"/>&nbsp&nbsp&nbsp&nbsp{{list.number}}</a></li>
                                            </ul>
                              </div>
                        </div>
                        <div class="col-lg-7">
                            <input ng-blur="productinData_drop_down()" style="float: left ; width: 22% ;margin-bottom: 10px" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">
                            <button  style="float: left" type="button"  ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-info btn-sm"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
                            <input ng-disabled="false" style="float: left ; width: 22% ;" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">
                            <button type="button"  ng-click="productinData()" class="btn btn-primary btn-sm"> <i class="fa fa-search" style="margin: 5px"></i> Search</button>
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
                    <table id="customers">
                        <thead>
                        <tr>
                            <th><a href="#">#</a></th>
                            <th width="100px" style="width: 150px">
                               <!-- <input style="width: 80px" type="text" class="form-control" ng-model="searchproduct">-->
                                <a href="">Tag#</a>
                            </th>
                            <th ng-repeat="list in lable"><a href="#">{{list}}</a></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="list in resultList">
                            <td>{{$index + 1}}</td>
                            <td>{{list.number}} </td>
                            <td ng-repeat="listDate in list.date_count_production track by $index" style="text-align: right;background-color: {{listDate.color}}">
                                <span ng-show="listDate.quantity>0">{{listDate.quantity | number : 2}}</span>
                                <span ng-show="listDate.quantity =='0'">-</span>
                            </td>
                        </tr>
                        <tr ng-show="searchproduct==''">
                            <th colspan="2"><a href="#">Total</a> </th>
                            <th ng-repeat="list in total track by $index" style="text-align: right;"><a href=""> {{list | number}}</a></th>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>



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
<script>
    var options = [];

    $( '.dropdown-menu_2 a' ).on( 'click', function( event ) {

        var $target = $( event.currentTarget ),
            val = $target.attr( 'data-value' ),
            $inp = $target.find( 'input' ),
            idx;

        if ( ( idx = options.indexOf( val ) ) > -1 ) {
            options.splice( idx, 1 );
            setTimeout( function() { $inp.prop( 'checked', false ) }, 0);
        } else {
            options.push( val );
            setTimeout( function() { $inp.prop( 'checked', true ) }, 0);
        }

        $( event.target ).blur();

        console.log( options );
        return false;
    });
</script>
