
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/PosDateRang/business_summary_sale_grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>


<div  id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">
    <div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo $data ?> ,"<?php echo Yii::app()->createAbsoluteUrl('pos/base'); ?>")'>

        <div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <strong>message! </strong>
            {{taskMessage}}
        </div>

        <div class="tabbable">
            <ul class="nav nav-tabs nav-tabs-lg">
                <li>
                    <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                        Daily sales summary
                    </a>
                </li>
            </ul>
            <!--  {{productList}}
              {{todayData}}-->


            <div class="tab-content">
                <div class=" active" id="tab_1" style="margin-bottom: 10px; margin: 10px">

                    <input  style="float: left ; width: 18% ;" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="start_date" size="2">
                    <button style="float: left" type="button" ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-info btn-sm"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
                    <input  style="float: left ; width: 18% ;" class="form-control input-sm"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="end_date" size="2">
                    <button style="margin-left: 10px;" type="button"  ng-click="get_data()" class="btn btn-primary btn-sm "> <i class="fa fa-search" style="margin: 4px"></i> Search</button>

                    <!--<a ng-disabled="true" class="btn btn-primary btn-sm" href="<?php /*echo Yii::app()->createUrl('#')*/?>"><i class="fa fa-share" style="margin: 4px"></i> Export </a>-->
                    <img ng-show="imageLoading" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">

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


                    <table id="customers" style="margin-top: 6px">
                        <thead>
                        <tr style="background-color: #F0F8FF">
                            <th><a href="#"> #</a></th>
                            <th><a href="#">Product</a></th>
                            <th><a href="#">Qty</a></th>
                            <th><a href="#">Amount</a></th>
                            <th><a href="#">Avg</a></th>

                        </tr>
                        </thead>
                        <tbody>

                        <tr ng-repeat="data in result_list track by $index">


                            <td>{{$index+1}}</td>
                            <td>{{data.name}}</td>
                            <td style="text-align: center">{{data.quantity}}</td>
                            <td style="text-align: center">{{data.amount}}</td>
                            <td style="text-align: center">{{data.rate}}</td>


                        </tr>

                        <tr ng-show="true">

                            <th colspan="2" ><a href="#">Total</a> </th>
                            <td style="text-align: center">{{total_quantity | number}}</td>
                            <td style="text-align: center">{{total_amount | number}}</td>
                            <td></td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>

            <!--Company Limit Model-->
            <modal title="Set Company Limit" visible="limitModelShow">
                <div class="row">
                    <?php
                    $form = $this->beginWidget(
                        'CActiveForm',
                        array(
                            'id' => 'agreement-form',
                            'enableAjaxValidation' => false,
                        )
                    );
                    ?>
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
                        <?php $this->endWidget(); ?>
                    </div>
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
