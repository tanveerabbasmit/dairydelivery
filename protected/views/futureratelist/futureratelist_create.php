<style>
    .angularjs-datetime-picker{
        z-index: 99999 !important;
    }
</style>

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/makePayment/futureratelist_create_grid.js"></script>
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/js/monthPickerFile/examples.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/js/monthPickerFile/MonthPicker.min.css" rel="stylesheet" type="text/css" />

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>




<div class="panel row" id="testContainer" style="display: none" ng-app="clintManagemaent">
    <div ng-controller="clintManagemaent" ng-init='init(<?php echo $data ?>)'>

        <ul class="nav nav-tabs nav-tabs-lg">
            <li>
                <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                    make Payment<span ng-show="showBalance" style="margin-left: 10px">  Current Balance : </span> <span style="color: #D3D3D3; margin-left: 2px"> {{OneCustomerOustaningBalance }} </span>
                </a>
            </li>
        </ul>
        <div class="panel-body">


            <div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ;z-index: 1;">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <strong>message! </strong>
                {{taskMessage}}
            </div>

            <div  class="col-lg-12" ng-show="address"  style="margin-bottom: 5px">
                <div  class="col-lg-12" style="background-color: #FFF8DC; margin-top: 10px">
                    <h3> {{closing_balance}}</h3>
                </div>
            </div>
            <br>

            <form ng-submit="save_future_rate_function()" style="margin-top: 5px">


                <div class="col-lg-12">
                    <div class="col-lg-6" style="background-color: honeydew">
                        <div class="col-lg-4 " style="padding-top: 10px" >
                            <span style="font-weight: bold;">Start date :</span>
                        </div>
                        <div class="col-lg-8">
                            <input   class="form-control"  datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="start_date" size="2">
                        </div>
                    </div>
                    <div class="col-lg-6" style="background-color: honeydew">
                        <div class="col-lg-4" style="padding-top: 10px">
                            <span style="font-weight: bold;">End Date</span>
                        </div>
                        <div class="col-lg-8">
                            <input   class="form-control"  datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="end_date" size="2">

                        </div
                    </div>
                </div>



                <div class="panel-body">
                </div>
                <div class="col-lg-6" style="background-color: 	honeydew">
                    <div class="col-lg-4" style="padding-top: 10px">
                        <span style="font-weight: bold;">Customer : </span>
                    </div>
                    <div class="col-lg-8">
                        <div style="float: left;">
                            <button class="btn btn-default dropdown-toggle " ng-click="showDropDownList()" type="button" id="dropdownMenu1" data-toggle="dropdown">{{SelectedCustomer}} <span  class="caret"></span>
                            </button>
                            <ul style="width: 200px ;max-height: 500px ; overflow-x: hidden;overflow-y: scroll;" class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1" >
                                <li role="presentation">
                                    <div class="input-group input-group-sm search-control"> <span class="input-group-addon">
									<span ></span>
									</span>
                                        <input autofocus type="text" class="form-control" placeholder="Search" ng-model="query" id="serachCustomerBar">
                                    </div>

                                </li >
                                <li  role="presentation" ng-click="abcd(item)" ng-repeat='item in clientList | filter:query'> <a href="#"> {{item.fullname}} </a>
                                </li>
                            </ul>
                        </div>
                        <img ng-show="loadClientLoader" style="margin: 10px" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">

                    </div>
                </div>

                <div class="col-lg-6" style="background-color: 	honeydew">
                    <div class="col-lg-4" style="padding-top: 10px">
                        <span style="font-weight: bold;">Rate : </span>
                    </div>
                    <div class="col-lg-8">
                        <input   class="form-control"  type="text"  ng-model="rate" required>
                    </div>
                </div>
                <div class="panel-body">
                </div>
                <div class="col-lg-12" style="background-color: 	honeydew;" >
                    <div class="col-lg-6" style="background-color: 	honeydew">
                        <div class="col-lg-4" style="padding-top: 10px">
                            <span style="font-weight: bold;">Product : </span>
                        </div>
                        <div class="col-lg-8">
                           <select class="form-control" ng-model="product_id" required>
                               <option value="">Select</option>
                               <option ng-repeat="list in product_list" value="{{list.product_id}}">{{list.name}}</option>
                           </select>
                        </div>
                    </div>
                    <div class="col-lg-6">

                        <button style="float: right;margin-left: 5px"  ng-disabled="imageLoader || allow_delete[1]" type="submit" class="btn btn-sm btn-info next" > <i class="fa fa-edit"></i> Save</button>
                        <img style="float: right" ng-show="imageLoader" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
                    </div>
                </div>
            </form>

            <div  class="col-lg-12" ng-show="address" style="background-color: #FFF8DC; margin-top: 10px">
                <div style="float: left">
                    <span style="font-weight: bold;">Address :  </span> {{address}}
                </div>
                <div style="float: left ; margin-left: 20px">
                    <span style="font-weight: bold;">Contact Number :  </span> {{cell_no_1}}
                </div>

                <div style="float: left ; margin-left: 20px">
                    <span style="font-weight: bold;">Zone :  </span> {{zone_name}}
                </div>

                <div style="float: left ; margin-left: 20px">
                    <span style="font-weight: bold;">Payment Term  :  </span> {{payment_term}}
                </div>
            </div>

        </div>

        <div class="col-lg-12">

            <table style="margin-top:25px;" class="table table-striped nomargin" ng-show="!mainPageSwitch">
                <thead>
                <tr>
                   <th>Start Date </th>
                    <th>End Date </th>
                    <th>Rate </th>
                    <!--<th></th>-->
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="list in rate_list">
                    <td>{{list.start_date}}</td>
                    <td>{{list.end_date}}</td>
                    <td>{{list.rate}}</td>
                </tr>
                </tbody>

        </div>

        <style>
            .dropdown.dropdown-scroll .dropdown-menu {
                max-height: 200px;
                width: 60px;
                overflow: auto;
            }
        </style>
    </div>

