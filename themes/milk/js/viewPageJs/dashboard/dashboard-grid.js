/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('riderDailyStockGridModule', ['ngSanitize', 'toggle-switch']);
app.controller('manageZone', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (baseURl) {

        // document.getElementById("testContainer").style.display = "block";
        // document.getElementById("loaderImage").style.display = "none";
        $scope.baseURl = baseURl;
        $scope.getNewCustomerLoader = false ;
        $scope.newScheduleLoader = false ;
        $scope.monthWiseNewCustomerLoader = false ;
        $scope.deliveryStatusLoader = false ;
        $scope.outStandingBalnceLoader = false ;
        $scope.paymentReciveTodayLoader = false ;
        // chart 3
        $scope.daily_sale = true ;
        $scope.getNewCustomer();


    }
    $scope.viewButton = function(list) {

        var getNewCustomer = $scope.baseURl+'/index.php/site/viewCustomer';

        $http.post(getNewCustomer ,list.client_id)
            .success(function (responce) {
                $scope.getNewCustomer();
                list.view_by_admin =0;

            })
            .error(function (responce) {

            })
    }

    $scope.viewButton2 = function(list) {

        var getNewCustomer = $scope.baseURl+'/index.php/site/viewCustomer2';

        $http.post(getNewCustomer ,list.client_id)
            .success(function (responce) {
                $scope.newScheduleFunction();
                list.view_by_admin =0;

            })
            .error(function (responce) {

            })
    }



    $scope.getNewCustomer = function() {

        $scope.getNewCustomerLoader = true ;
        var getNewCustomer = $scope.baseURl+'/index.php/site/getNewcustomer';
        $http.post(getNewCustomer)
            .success(function (responce) {
                $scope.newCustomer = responce.customerList;
                $scope.total_laptopsum = responce.total_laptopsum;
                $scope.total_mobilesum = responce.total_mobilesum;

                $scope.getNewCustomerLoader = false ;

                $scope.newScheduleFunction();

            })
            .error(function (responce) {

            })
    }

    $scope.newScheduleFunction = function() {

        var getNewCustomer = $scope.baseURl+'/index.php/site/getNewSchedule';
        $http.post(getNewCustomer)
            .success(function (responce) {
                $scope.newSchedule = responce.finalResult;
                $scope.newScheduleLoader = false ;
                $scope.total_laptopsum_sc = responce.total_laptopsum;
                $scope.total_mobilesum_sc = responce.total_mobilesum;

                $scope.pointOfSaleFunctionData();

            })
            .error(function (responce) {

            })
    }

    $scope.pointOfSaleFunctionData = function(){

        var pointOfSale = $scope.baseURl+'/index.php/pos/pointOfSaleGetData';

        $http.post(pointOfSale)
            .success(function (responce) {
                $scope.posData = responce;
                $scope.monthWiseNewCustomer();

            })
            .error(function (responce) {

            })
    }

    $scope.monthWiseNewCustomer = function() {

        $scope.monthWiseNewCustomerLoader = true;
        var getNewCustomer = $scope.baseURl+'/index.php/site/getMonthWiseNewCustomer';
        $http.post(getNewCustomer)
            .success(function (responce) {

                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawChart);
                var data = [];
                var firstObject = [];
                firstObject.push('Sale'.toString())
                firstObject.push('Sale2'.toString())
                data.push(firstObject);
                $scope.oneObject = '[' ;
                angular.forEach(responce , function (value ,key) {

                    $scope.oneObject = '[' ;
                    $scope.oneObject =+ "['Jan', 'Sales']" ;
                    $scope.oneObject =+ "['Jan', 12]" ;
                    $scope.oneObject =+ ']' ;


                });

                function drawChart() {
                    try {
                        var data = google.visualization.arrayToDataTable(responce);

                        var options = {
                            title: 'Month wise new Customers',
                            curveType: 'function',
                            legend: { position: 'bottom' }
                        };

                        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

                        chart.draw(data, options);

                    }
                    catch(err) {
                        $scope.deliveryStatus();
                    }


                }



                $scope.monthWiseNewCustomerLoader = false;
                $scope.month_wise_total_customer();

            })
            .error(function (responce) {

            })
    }
    $scope.month_wise_total_customer = function() {

        $scope.monthWiseNewCustomerLoader = true;
        var getNewCustomer = $scope.baseURl+'/index.php/site/getMonthWiseNewCustomer_end_month';
        $http.post(getNewCustomer)
            .success(function (responce) {

                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawChart);
                var data = [];
                var firstObject = [];
                firstObject.push('Sale'.toString())
                firstObject.push('Sale2'.toString())
                data.push(firstObject);
                $scope.oneObject = '[' ;
                angular.forEach(responce , function (value ,key) {

                    $scope.oneObject = '[' ;
                    $scope.oneObject =+ "['Jan', 'Sales']" ;
                    $scope.oneObject =+ "['Jan', 12]" ;
                    $scope.oneObject =+ ']' ;


                });



                 /*Total Customer*/
                function drawChart() {
                    try {
                        var data = google.visualization.arrayToDataTable(responce);

                        var options = {
                            title: 'Month wise new Customers',
                            curveType: 'function',
                            legend: { position: 'bottom' }
                        };

                        var chart = new google.visualization.LineChart(document.getElementById('curve_chart_total_customer'));

                        chart.draw(data, options);

                    }
                    catch(err) {
                        $scope.deliveryStatus();
                    }


                }

                $scope.monthWiseNewCustomerLoader = false;

            })
            .error(function (responce) {

            })
    }

    $scope.deliveryStatus = function () {

        var getDeleiveryStatusURl = $scope.baseURl+'/index.php/site/getDeliveryStatus';
        $scope.deliveryStatusLoader = true ;
        $http.post(getDeleiveryStatusURl)
            .success(function(responce){
                $scope.DeliveryStatus = responce ;
                $scope.deliveryStatusLoader = false ;
                $scope.outStandingBalnce();
            })
            .error(function(responce){

            });
    }

    $scope.outStandingBalnce = function () {

        var getDeleiveryStatusURl = $scope.baseURl+'/index.php/site/getoutStandingBalnce';
        $scope.outStandingBalnceLoader = true ;
        $http.post(getDeleiveryStatusURl)
            .success(function(responce){
                $scope.outstanding_balance = responce.outstanding_balance ;
                $scope.result_balnce_clientLIst = responce.result_balnce_clientLIst ;
                $scope.outStandingBalnceLoader = false ;
                $scope.paymentReciveToday();
            })
            .error(function(responce){
            });
    }

    $scope.paymentReciveToday = function() {
        var getDeleiveryStatusURl = $scope.baseURl+'/index.php/site/gepaymentReciveToday';
        $scope.paymentReciveTodayLoader = true ;
        $http.post(getDeleiveryStatusURl)
            .success(function(responce){
                $scope.total_OnlinePayment = responce.total_OnlinePayment;
                $scope.totalCash_from_app = responce.totalCash_from_app ;
                $scope.totalcheque_from_app = responce.totalcheque_from_app ;
                $scope.total_cshPayment_form_portal = responce.total_cshPayment_form_portal ;
                $scope.total_chequePayment_from_portal = responce.total_chequePayment_from_portal ;
                $scope.paymentReciveTodayLoader = false ;

                $scope.dailySale_graph();

            })
            .error(function(responce){

            });
    }

    $scope.dailySale_graph = function () {
        var getDeleiveryStatusURl = $scope.baseURl+'/index.php/site/get_daily_sale_graph';
        $http.post(getDeleiveryStatusURl)
            .success(function(responce){


                $scope.dailySale_graph_data(responce.quantityObject);
                $scope.dailySale_graph_data_amount(responce.amountObject);

            })
            .error(function(responce){

            });


    }

    $scope.dailySale_graph_data =function (data) {
        var graph_data = Array.from(data);
        function drawChart() {
            var data = google.visualization.arrayToDataTable(graph_data);
            var options = {title: ''};
            // Instantiate and draw the chart.
            var chart = new google.visualization.ColumnChart(document.getElementById('columnchart_material'));
            chart.draw(data, options);
        }
        google.charts.setOnLoadCallback(drawChart);
        $scope.daily_sale = false ;




    }
    $scope.dailySale_graph_data_amount =function (data) {
        var graph_data = Array.from(data);
        function drawChart() {
            var data = google.visualization.arrayToDataTable(graph_data);
            var options = {title: ''};
            // Instantiate and draw the chart.
            var chart = new google.visualization.ColumnChart(document.getElementById('daily_sale_amount'));
            chart.draw(data, options);
        }
        google.charts.setOnLoadCallback(drawChart);
        $scope.daily_sale = false ;

        $scope.newCustomer_graph();
    }

    $scope.newCustomer_graph = function () {
        var getDeleiveryStatusURl = $scope.baseURl+'/index.php/site/get_new_Cutomer';
        $http.post(getDeleiveryStatusURl)
            .success(function(responce){
                $scope.newCustomer_graph_data(responce);
            })
            .error(function(responce){
            });
    }

    $scope.newCustomer_graph_data =function (data) {
        var graph_data = Array.from(data);
        function drawChart() {
            var data = google.visualization.arrayToDataTable(graph_data);
            var options = {title: ''};
            // Instantiate and draw the chart.
            var chart = new google.visualization.ColumnChart(document.getElementById('new_customer'));
            chart.draw(data, options);
        }
        google.charts.setOnLoadCallback(drawChart);
        $scope.daily_sale = false ;
        $scope.year_Sale_graph();
    }


    $scope.year_Sale_graph = function () {
        var getDeleiveryStatusURl = $scope.baseURl+'/index.php/site/get_year_sale_graph';
        $http.post(getDeleiveryStatusURl)
            .success(function(responce){

                $scope.yearSale_graph_data(responce.finalObject_quantity);
                $scope.yearSale_graph_data_amount(responce.finalObject_amount);
            })
            .error(function(responce){
            });

    }


    $scope.yearSale_graph_data =function (data) {
        var graph_data = Array.from(data);
        function drawChart() {
            var data = google.visualization.arrayToDataTable(graph_data);
            var options = {title: ''};
            // Instantiate and draw the chart.
            var chart = new google.visualization.ColumnChart(document.getElementById('year_graph'));
            chart.draw(data, options);
        }
        google.charts.setOnLoadCallback(drawChart);
        $scope.daily_sale = false ;


    }
    $scope.yearSale_graph_data_amount =function (data) {

        var graph_data = Array.from(data);
        function drawChart() {
            var data = google.visualization.arrayToDataTable(graph_data);
            var options = {title: ''};
            // Instantiate and draw the chart.
            var chart = new google.visualization.ColumnChart(document.getElementById('year_graph_amount'));
            chart.draw(data, options);
        }
        google.charts.setOnLoadCallback(drawChart);
        $scope.daily_sale = false ;


    }
    $scope.view_manage_wiget_function =function (){
       $scope.view_manage_wiget_model =true;
        var getDeleiveryStatusURl = $scope.baseURl+'/index.php/site/view_manage_wiget_model_data_function';
        $http.post(getDeleiveryStatusURl)
            .success(function(responce){
                 $scope.manage_wiget_list =responce;

            })
            .error(function(responce){
            });
    }
    $scope.save_widget_function =function (){

        $scope.view_manage_wiget_model =false;
        var getDeleiveryStatusURl = $scope.baseURl+'/index.php/site/view_manage_wiget_model_update_function';
        $http.post(getDeleiveryStatusURl, $scope.manage_wiget_list)
            .success(function(responce){
               

            })
            .error(function(responce){
            });
    }



}]);


app.directive('modal', function () {
    return {
        template: '<div class="modal fade">' +
            '<div class="modal-dialog">' +
            '<div class="modal-content"  style="width: 70% ; margin-left: 25% ; margin-top: 80px">' +
            '<div class="modal-header">' +
            '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' +
            '<h4 class="modal-title">{{ title }}</h4>' +
            '</div>' +
            '<div class="modal-body" ng-transclude></div>' +
            '</div>' +
            '</div>' +
            '</div>',
        restrict: 'E',
        transclude: true,
        replace:true,
        scope:true,
        link: function postLink(scope, element, attrs) {
            scope.title = attrs.title;

            scope.$watch(attrs.visible, function(value){
                if(value == true)
                    $(element).modal('show');
                else
                    $(element).modal('hide');
            });

            $(element).on('shown.bs.modal', function(){
                scope.$apply(function(){
                    scope.$parent[attrs.visible] = true;
                });
            });

            $(element).on('hidden.bs.modal', function(){
                scope.$apply(function(){
                    scope.$parent[attrs.visible] = false;
                });
            });
        }
    };
});
