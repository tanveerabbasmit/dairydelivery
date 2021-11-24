/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('riderDailyStockGridModule', ['ngSanitize', 'toggle-switch']);
app.controller('manageZone', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (baseURl) {

       // document.getElementById("testContainer").style.display = "block";
       // document.getElementById("loaderImage").style.display = "none";


        alert();

       $scope.baseURl = baseURl;

        $scope.getNewCustomerLoader = false ;
        $scope.newScheduleLoader = false ;
        $scope.monthWiseNewCustomerLoader = false ;
        $scope.deliveryStatusLoader = false ;
        $scope.outStandingBalnceLoader = false ;
        $scope.paymentReciveTodayLoader = false ;

        // chart 3


        $scope.getNewCustomer();


    }


    $scope.getNewCustomer = function() {
        $scope.getNewCustomerLoader = true ;
        var getNewCustomer = $scope.baseURl+'/site/getNewcustomer';
        $http.post(getNewCustomer)
            .success(function (responce) {

                $scope.newCustomer = responce ;
                $scope.getNewCustomerLoader = false ;

                $scope.newScheduleFunction();

            })
            .error(function (responce) {
                
            })
    }

    $scope.newScheduleFunction = function() {


        var getNewCustomer = $scope.baseURl+'/site/getNewSchedule';

        $http.post(getNewCustomer)
            .success(function (responce) {
              $scope.newSchedule = responce ;
                $scope.newScheduleLoader = false ;

                $scope.monthWiseNewCustomer();

            })
            .error(function (responce) {

            })
    }

    $scope.monthWiseNewCustomer = function() {

       $scope.monthWiseNewCustomerLoader = true;
        var getNewCustomer = $scope.baseURl+'/site/getMonthWiseNewCustomer';
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
                    var data = google.visualization.arrayToDataTable(responce);

                    var options = {
                        title: 'Month wise new Customers',
                        curveType: 'function',
                        legend: { position: 'bottom' }
                    };

                    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

                    chart.draw(data, options);

                   $scope.deliveryStatus();


                }
                $scope.monthWiseNewCustomerLoader = false;

            })
            .error(function (responce) {

            })
    }

    $scope.deliveryStatus = function () {

        var getDeleiveryStatusURl = $scope.baseURl+'/site/getDeliveryStatus';
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

        var getDeleiveryStatusURl = $scope.baseURl+'/site/getoutStandingBalnce';
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
        var getDeleiveryStatusURl = $scope.baseURl+'/site/gepaymentReciveToday';
        $scope.paymentReciveTodayLoader = true ;
        $http.post(getDeleiveryStatusURl)
            .success(function(responce){
                 $scope.total_OnlinePayment = responce.total_OnlinePayment;
                 $scope.totalCash_from_app = responce.totalCash_from_app ;
                 $scope.totalcheque_from_app = responce.totalcheque_from_app ;
                 $scope.total_cshPayment_form_portal = responce.total_cshPayment_form_portal ;
                 $scope.total_chequePayment_from_portal = responce.total_chequePayment_from_portal ;
                $scope.paymentReciveTodayLoader = false ;

            })
            .error(function(responce){

            });
    }



}]);

