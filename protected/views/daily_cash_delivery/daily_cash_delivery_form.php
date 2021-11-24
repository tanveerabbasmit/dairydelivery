
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/PosDateRang/daily_cash_delivery_form_grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>


<div  id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">
    <div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo str_replace("'","&#39;",$data ); ?>," <?php echo Yii::app()->createAbsoluteUrl('dailymilksummary/dailymilksummary_view_report_data');?>")'>

        <div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <strong>message! </strong>
            {{taskMessage}}
        </div>


        <div class="tabbable">
            <ul class="nav nav-tabs nav-tabs-lg">
                <li>
                    <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                        Daily Cash Delivery
                    </a>
                </li>
            </ul>
            <!--  {{productList}}
              {{todayData}}-->


            <div class="tab-content">
                <div class=" active" id="tab_1" style="margin-bottom: 10px; margin: 10px">




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
                    <form ng-submit="savePaymernt()" style="margin-top: 5px">

                        <div class="col-lg-12" style="margin-top: 10px">
                            <div class="col-lg-12">
                                <div class="col-lg-6" >

                                    <div class="col-lg-3 " style="padding-top: 10px" >
                                        <span style="font-weight: bold;">Customer:</span>
                                    </div>

                                    <div class="col-lg-8">

                                        <select   class="form-control ">
                                            <option value="0">Select</option>
                                            <option value="0">Mr Raheel</option>
                                            <option value="0">Mrs Salman 523/1 Z</option>
                                            <option value="0">	Mr Zulfiqar</option>
                                            <!--<option ng-repeat="list in project_list" value="{{list.product_id}}">{{list.name}}</option>-->
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-12" style="margin-top: 10px">
                           <div class="col-lg-12">
                               <div class="col-lg-6" >

                                <div class="col-lg-3 " style="padding-top: 10px" >
                                    <span style="font-weight: bold;">Date:</span>
                                </div>

                                <div class="col-lg-8">

                                    <input   class="form-control"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="today_data" size="2">

                                </div>
                            </div>
                           </div>
                        </div>


                        <div class="col-lg-12" style="margin-top: 10px">
                            <div class="col-lg-12">
                                <div class="col-lg-6" >

                                    <div class="col-lg-3 " style="padding-top: 10px" >
                                        <span style="font-weight: bold;">Product:</span>
                                    </div>

                                    <div class="col-lg-8">

                                        <select ng-model="product_id"  class="form-control ">
                                            <option value="0">Select</option>
                                            <option ng-repeat="list in project_list" value="{{list.product_id}}">{{list.name}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12" style="margin-top: 10px">
                            <div class="col-lg-12">
                                <div class="col-lg-6" >

                                    <div class="col-lg-3 " style="padding-top: 10px" >
                                        <span style="font-weight: bold;">Amount Recived:</span>
                                    </div>

                                    <div class="col-lg-8">
                                        <input   class="form-control"    datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="paymentObject.startDate" size="2">


                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="panel-body">
                        </div>
                        <div class="col-lg-12" style=" margin-bottom: 30px" >

                            <div class="col-lg-6" >
                                <div class="col-lg-11 " style="text-align: right">
                                    <button  ng-disabled="imageLoader || allow_delete[1]" type="submit" class="btn btn-sm btn-info next" > <i class="fa fa-edit"></i> Save</button>
                                    <img ng-show="imageLoader" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
                                </div>


                            </div>


                        </div>



                    </form>


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
