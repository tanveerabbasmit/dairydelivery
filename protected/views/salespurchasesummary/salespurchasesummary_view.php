
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/dateWisedeliveryReport/salespurchasesummary_view_grid.js"></script>

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
    <div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo $data; ?> )'>
        <div class="tabbable">
            <ul class="nav nav-tabs nav-tabs-lg">
                <li>
                    <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                        Sales & Purchase Summary
                    </a>
                </li>
            </ul>

            <div class="tab-content">

                <div class=" active" id="tab_1" style="margin-bottom: 10px;  margin: 10px">
                    <input style="float: left ; width: 20% ;" class="form-control input-sm"  ng-change="selectRiderOnChange(selectRiderID)"  datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="start_date" size="2">
                    <button style="float: left" type="button" ng-click="selectRiderOnChange(selectRiderID)" class="btn btn-info btn-sm"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
                    <input style="float: left ; width: 20% ;" class="form-control input-sm"  ng-change="selectRiderOnChange(selectRiderID)"  datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="end_date" size="2">
                    <select ng-show="true" style="width: 25%; float: left;margin-bottom: 10px;margin-left: 10px ; margin-right: 10px" class="form-control input-sm" ng-model="product_id" >
                        <option value="0">Select Product</option>
                        <option ng-repeat="list in product_list" value="{{list.product_id}}">{{list.name}}
                        </option>
                    </select>
                    <button style="margin-left: 5px;" type="button"  ng-click="selectRiderOnChange()" class="btn btn-primary  btn-sm"> <i class="fa fa-search" style="margin: 4px"></i> Search</button>

                    <button class="btn btn-primary  btn-sm " style=" margin-left: 5px" onclick="javascript:xport.toCSV('customers');"> <i class="fa fa-share"></i> Export</button>
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

                        /* table thead tr{
                             display:block;
                         }

                         table th,table td{
                            width:auto;//fixed width
                         }


                         table  tbody{
                             display:block;
                             height:350px;
                            // overflow:auto;//set tbody to auto
                         }*/
                    </style>

                    <table id="customers" style="margin-top: 6px;width: 100%">
                        <thead>
                        <tr style="background-color: #F0F8FF">
                            <th><a href="#" style="text-align: center">Date</a></th>
                            <th><a href="#" style="text-align: center">Sale Units</a></th>
                            <th><a href="#" style="text-align: center">Sale Amount</a></th>
                            <th><a href="#" style="text-align: center">Sale/Unit</a></th>
                            <th><a href="#" style="text-align: center">Purchase Amount</a></th>
                            <th><a href="#" style="text-align: center">Purchase Ltrs</a></th>
                            <th><a href="#" style="text-align: center">Price/Ltr</a></th>
                            <th><a href="#" style="text-align: center">Sale-Purchase</a></th>
                            <th><a href="#" style="text-align: center">Expenses</a></th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="list in data_list">
                            <td><span ng-bind="list.date"></span></td>
                            <td style="text-align: center"><span ng-bind="list.sale_object.quantity"></span></td>
                            <td style="text-align: center"><span ng-bind="list.sale_object.amount"></span></td>
                            <td style="text-align: center"><span ng-bind="list.sale_object.rate"></span></td>
                            <td style="text-align: center"><span ng-bind="list.purchase_object.total_price"></span></td>
                            <td style="text-align: center"><span ng-bind="list.purchase_object.total_quantity"></span></td>
                            <td style="text-align: center"><span ng-bind="list.purchase_object.purchase_rate"></span></td>


                            <td style="text-align: center"><span ng-bind="list.sale_Purchase"></span></td>
                            <td style="text-align: center"><span ng-bind="list.get_total_expence"></span></td>
                            <td></td>


                        </tr>
                        <tr>
                            <th style="text-align: center"><a href="">Total</a> </th>
                            <th style="text-align: center"><a href=""><span ng-bind="total_object.total_sale_lts"></span></a> </th>
                            <th style="text-align: center"><a href=""><span ng-bind="total_object.total_sale_amount"></span></a> </th>
                            <th style="text-align: center"><a href=""></a> </th>
                            <th style="text-align: center"><a href=""><span ng-bind="total_object.total_purchase_amount"></span></a> </th>
                            <th style="text-align: center"><a href=""><span ng-bind="total_object.total_purchase_ltrs"></span></a> </th>
                            <th style="text-align: center"><a href=""></a> </th>
                            <th style="text-align: center"><a href=""><span ng-bind="total_object.total_sale_purchase"></span></a> </th>
                            <th style="text-align: center"><a href=""><span ng-bind="total_object.total_expenses"></span></a> </th>

                        </tr>

                        </tbody>


                    </table>
                </div>
            </div>


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
