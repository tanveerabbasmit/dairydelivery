<style>
    .angularjs-datetime-picker{
        z-index: 99999 !important;
    }
</style>


<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/viewPageJs/MessageLog/MessageLog-grid.js"></script>

<div class="modal-dialog"  id="loaderImage" style="width: 50% ; margin-top: 80px">
    <div class="modal-content">
        <div class="modal-body">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/loader-transparent.gif" alt="" class="loading">
            <span>&nbsp;&nbsp;Loading... </span>
        </div>
    </div>
</div>

<link href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css" />
<div  id="testContainer" style="display: none"  class="panel row" ng-app="riderDailyStockGridModule">
    <div ng-controller="riderDailyStockGridCtrl" ng-init='init(<?php echo $riderList ?> , <?php echo str_replace("'","&#39;",$data ); ?>,
    "<?php echo Yii::app()->createAbsoluteUrl('smslog/selectDateBaseMessage'); ?>",
    "<?php echo Yii::app()->createAbsoluteUrl('client/oneCustomerAmountListallCustomerList'); ?>")'>


        <div id="alertMessage" class="alert alert-success" id="success-alert" style="position: absolute ; margin-left: 75% ; display: none ">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <strong>message! </strong>
            {{taskMessage}}
        </div>

        <div class="tabbable">
            <ul class="nav nav-tabs nav-tabs-lg">
                <li>
                    <a href="#tab_1" data-toggle="tab" aria-expanded="false">
                        Message Log
                    </a>
                </li>
            </ul>
            <!--  {{productList}}
              {{todayData}}-->


            <div class="tab-content">
                <div class=" active" id="tab_1" style="margin-bottom: 10px; margin: 10px">
                    <div class="col-lg-12">
                        <div class="col-lg-12">
                            <input  style="float: left ; width: 25% ;margin-bottom: 10px;" class="form-control input-sm"  ng-change="selectRiderOnChange(selectRiderID)"  datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="startDate" size="2">
                            <button class="btn btn-info btn-sm" style="float: left"><i class="fa fa-calendar" aria-hidden="true" style="margin: 5px"></i></button>
                            <input  style="float: left ; width: 25% ;margin-bottom: 10px;" class="form-control input-sm"  ng-change="selectRiderOnChange(selectRiderID)"  datetime-picker date-only  date-format="yyyy-MM-dd" type="text" required ng-model="endDate" size="2">
                        </div>
                    </div>

                    <div class="col-lg-12">

                        <div class="col-lg-3">
                            <select ng-show="true" style=" float: left;margin-bottom: 10px ; margin-right: 10px" class="form-control input-sm" ng-change="changeRider()" ng-model="selectRiderID" >
                                <option value="0">All</option>
                                <option ng-repeat="list in riderList" value="{{list.rider_id}}">{{list.fullname}}
                                </option>
                            </select>
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


                            <button style="margin-left: 10px;" type="button"  ng-click="getMessageList()" class="btn btn-primary btn-sm "> <i class="fa fa-search" style="margin: 4px"></i> Search</button>

                            <a ng-show="false" ng-disabled="true" class="btn btn-primary btn-sm" href="<?php echo Yii::app()->createUrl('#')?>"><i class="fa fa-share" style="margin: 4px"></i> Export </a>
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

                    <table id="customers" style="margin-top: 6px">
                        <thead>
                        <tr style="background-color: #F0F8FF">
                            <th><a href="#"> #</a></th>
                            <th><a href="#">ID</a></th>
                            <th>
                                <a href="#" ng-click="sortType = 'fullname'; sortReverse = !sortReverse" >
                                    Customer Name
                                    <span ng-show="sortType == 'fullname' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
                                    <span ng-show="sortType == 'fullname' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
                                </a>
                            </th>

                            <th>
                                <a href="#" ng-click="sortType = 'address'; sortReverse = !sortReverse" >
                                   Phone No.
                                    <span ng-show="sortType == 'address' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
                                    <span ng-show="sortType == 'address' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
                                </a>
                            </th>
                            <th>
                                <a href="#" ng-click="sortType = 'zone_name'; sortReverse = !sortReverse" >
                                    Date
                                    <span ng-show="sortType == 'address' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
                                    <span ng-show="sortType == 'address' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
                                </a>
                            </th>
                            <th>
                                <a href="#" ng-click="sortType = 'zone_name'; sortReverse = !sortReverse" >
                                    Time
                                    <span ng-show="sortType == 'address' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
                                    <span ng-show="sortType == 'address' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
                                </a>
                            </th>
                            <th>
                                <a href="#" ng-click="sortType = 'zone_name'; sortReverse = !sortReverse" >
                                    Message
                                    <span ng-show="sortType == 'address' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
                                    <span ng-show="sortType == 'address' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
                                </a>
                            </th>
                            <th>
                                <a href="#" ng-click="sortType = 'zone_name'; sortReverse = !sortReverse" >
                                    Message Count
                                    <span ng-show="sortType == 'address' &amp;&amp; !sortReverse" class="fa fa-caret-down ng-hide"></span>
                                    <span ng-show="sortType == 'address' &amp;&amp; sortReverse" class="fa fa-caret-up"></span>
                                </a>
                            </th>

                        </tr>
                        </thead>
                        <tbody>

                        <tr ng-repeat="data in data track by $index">
                            <td>{{$index+1}}</td>
                            <td>{{data.client_id}}</td>
                            <td> {{data.client_name}}</td>
                            <td>{{data.phone_number}}</td>
                            <td>{{data.date}}</td>
                            <td>{{data.setTime}}</td>
                            <td>{{data.text_message}}</td>
                            <td style="text-align: right">{{data.smsCount}}</td>
                        </tr>
                        <tr>
                            <th colspan="7"><a href="#"> Total Message </a></th>
                            <th colspan="" style="text-align: right"><a href="#">{{totalSms | number}}</a> </th>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!--Company Limit Model-->



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
