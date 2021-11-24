/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var riderDailyStockGridModule = angular.module('riderDailyStockGridModule', ['angularjs-datetime-picker']);
riderDailyStockGridModule.controller('riderDailyStockGridCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter , $timeout) {
    //INITIALIZATION ===============================================================
    $scope.init = function (fiveDayAgo,riderList , getDialyDeliveryCustomerURL ,googleMapURL , saveDeliveryURL) {


        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";


         $scope.startDate= fiveDayAgo;
         $scope.riderList= riderList;
         $scope.getDialyDeliveryCustomerURL = getDialyDeliveryCustomerURL ;
         $scope.selectRiderID ='';
         $scope.deliveredQuantityShowDive = false;
         $scope.imageLoading = false ;
        $scope.googleMapURL = googleMapURL ;

        var date = new Date();
        date.setDate(date.getDate());
        var selectYear = date.getFullYear() ;
        var month = date.getMonth()+1;
        var date = date.getDate();
        if(month<10){
            month = '0'+month
        }
        if(date<10){
            date = '0'+date
        }
        $scope.todate = selectYear + '-' + month + '-' + date;


       $scope.endDate =  $scope.todate;



        $scope.saveDeliveryURL = saveDeliveryURL ;

        $scope.sortReverse = true ;

        $scope.selectedRider = 'non';

        $scope.client_type ='0';
        $scope.rider_id = '0';



        $scope.loadData();

    }

     $scope.changeRider = function (riderID) {

         $http.post($scope.getDialyDeliveryCustomerURL, riderID)
             .success(function (data, status, headers, config) {

             })
             .error(function (data, status, header, config) {

             });

     }
     $scope.loadData =function(){
         var data = {
             startDate :  $scope.startDate,
             endDate : $scope.endDate

         }
         $scope.imageLoading =true;
         $http.post($scope.getDialyDeliveryCustomerURL, data)
             .success(function (data, status, headers, config) {

                 $scope.chartFunction(data);
                 $scope.imageLoading =false;
             })
             .error(function (data, status, header, config) {

             });
     }
     $scope.chartFunction =function (data) {

           var  chart_data =[];
         angular.forEach(data ,function (value ,key) {
             chart_data.push(value)
         });

         google.charts.load('current', {'packages':['corechart']});
         google.charts.setOnLoadCallback(drawChart);

         function drawChart() {
             var data = google.visualization.arrayToDataTable(chart_data);

             var options = {
                 title: 'Last Delivery Time',
                 curveType: 'function',
                 legend: { position: 'bottom' }
             };

             var chart = new google.visualization.LineChart(document.getElementById('chart_div'));

             chart.draw(data, options);
         }

        /* google.charts.load('current', {'packages':['corechart']});
         google.charts.setOnLoadCallback(drawChart);

         function drawChart() {
             var data = google.visualization.arrayToDataTable([
                 ['Year', 'Sales', 'Expenses'],
                 ['2004',  1000,      400],
                 ['2005',  1170,      460],
                 ['2006',  660,       1120],
                 ['2007',  1030,      540]
             ]);

             var options = {
                 title: 'Company Performance',
                 curveType: 'function',
                 legend: { position: 'bottom' }
             };

             var chart = new google.visualization.LineChart(document.getElementById('chart_div'));

             chart.draw(data, options);
         }*/



         /*google.charts.load('current', {'packages':['bar']});
         google.charts.setOnLoadCallback(drawChart);

         function drawChart() {

             var data = new google.visualization.DataTable();
             data.addColumn('timeofday', 'Time of Day');
             data.addColumn('number', 'Emails Received');

             data.addRows([
                 [[8, 30, 45], 5],
                 [[9, 0, 0], 10],
                 [[10, 0, 0, 0], 12],
                 [[10, 45, 0, 0], 13],
                 [[11, 0, 0, 0], 15],
                 [[12, 15, 45, 0], 20],
                 [[13, 0, 0, 0], 22],
                 [[14, 30, 0, 0], 25],
                 [[15, 12, 0, 0], 30],
                 [[16, 45, 0], 32],
                 [[16, 59, 0], 42]
             ]);

             var options = {
                 title: 'Total Emails Received Throughout the Day',
                 height: 450
             };

             var chart = new google.charts.Bar(document.getElementById('chart_div'));

             chart.draw(data, google.charts.Bar.convertOptions(options));
         }*/

/*
         google.charts.load('current', {'packages':['corechart']});
         google.charts.setOnLoadCallback(drawChart);

         function drawChart() {

             var data = new google.visualization.DataTable();
             data.addColumn('date', 'Time of Day');
             data.addColumn('number', 'Rating');

             data.addRows([
                 [new Date(2015, 0, 1), 51],  [new Date(2015, 0, 2), 7],  [new Date(2015, 0, 3), 3],
                 [new Date(2015, 0, 4), 1],  [new Date(2015, 0, 5), 3],  [new Date(2015, 0, 6), 4],
                 [new Date(2015, 0, 7), 3],  [new Date(2015, 0, 8), 4],  [new Date(2015, 0, 9), 2],
                 [new Date(2015, 0, 10), 5], [new Date(2015, 0, 11), 8], [new Date(2015, 0, 12), 6],
                 [new Date(2015, 0, 13), 3], [new Date(2015, 0, 14), 3], [new Date(2015, 0, 15), 5],
                 [new Date(2015, 0, 16), 7], [new Date(2015, 0, 17), 6], [new Date(2015, 0, 18), 6],
                 [new Date(2015, 0, 19), 3], [new Date(2015, 0, 20), 1], [new Date(2015, 0, 21), 2],
                 [new Date(2015, 0, 22), 4], [new Date(2015, 0, 23), 6], [new Date(2015, 0, 24), 5],
                 [new Date(2015, 0, 25), 9], [new Date(2015, 0, 26), 4], [new Date(2015, 0, 27), 9],
                 [new Date(2015, 0, 28), 8], [new Date(2015, 0, 29), 6], [new Date(2015, 0, 30), 4],
                 [new Date(2015, 0, 31), 6], [new Date(2015, 1, 1), 7],  [new Date(2015, 1, 2), 9]
             ]);


             var options = {
                 title: 'Rate the Day on a Scale of 1 to 10',
                 width: 900,
                 height: 500,
                 hAxis: {
                     format: 'M/d/yy',
                     gridlines: {count: 15}
                 },
                 vAxis: {
                     gridlines: {color: 'none'},
                     minValue: 0
                 }
             };

             var chart = new google.visualization.LineChart(document.getElementById('chart_div'));

             chart.draw(data, options);

             var button = document.getElementById('change');

             button.onclick = function () {

                 // If the format option matches, change it to the new option,
                 // if not, reset it to the original format.
                 options.hAxis.format === 'M/d/yy' ?
                     options.hAxis.format = 'MMM dd, yyyy' :
                     options.hAxis.format = 'M/d/yy';

                 chart.draw(data, options);
             };
         }*/










         /* google.charts.load('current', {'packages':['timeline']});
          google.charts.setOnLoadCallback(drawChart);

          function drawChart() {
              var data = google.visualization.arrayToDataTable([
                  ['Activity', 'Start Time', 'End Time'],
                  ['Sleep',
                      new Date(2014, 10, 15, 10, 30),
                      new Date(2014, 10, 15, 15, 30)],
                  ['Eat Breakfast',
                      new Date(2014, 10, 15, 6, 45),
                      new Date(2014, 10, 15, 7)],
                  ['Get Ready',
                      new Date(2014, 10, 15, 7, 4),
                      new Date(2014, 10, 15, 7, 30)],
                  ['Commute To Work',
                      new Date(2014, 10, 15, 7, 30),
                      new Date(2014, 10, 15, 8, 30)],
                  ['Work',
                      new Date(2014, 10, 15, 8, 30),
                      new Date(2014, 10, 15, 17)],
                  ['Commute Home',
                      new Date(2014, 10,  15, 17),
                      new Date(2014, 10,  15, 18)],
                  ['abbas',
                      new Date(2014, 10, 15, 7, 45),
                      new Date(2014, 10,  15, 16, 45)],

              ]);

              var options = {
                  height: 450,
              };

              var chart = new google.visualization.Timeline(document.getElementById('chart_div'));

              chart.draw(data, options);
          }
 */

     }

}]);


riderDailyStockGridModule.directive('modal', function () {
    return {
        template: '<div class="modal fade">' +
        '<div class="modal-dialog">' +
        '<div class="modal-content"  style="width: 70% ; margin-left: 25% ; margin-top: 0%">' +
        '<div class="modal-header"  style="background-color: #D8DCE3">' +
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


