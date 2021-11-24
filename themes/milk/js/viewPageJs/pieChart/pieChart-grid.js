/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('riderDailyStockGridModule', ['angularjs-datetime-picker']);
app.controller('manageZone', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (start_date, end_date,pieChartDataURL) {

        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";
        $scope.start_date = start_date;
        $scope.end_date = end_date;
        $scope.pieChartDataURL = pieChartDataURL ;

        $scope.getChartData();

        $scope.NoDataShow = false;
        $scope.dataLoad = false ;


    }

    $scope.getChartData =function(){
         var sendData = {
             'start_date': $scope.start_date,
             'end_date': $scope.end_date,
         }
        $scope.dataLoad = true;
        $http.post($scope.pieChartDataURL, sendData)
            .success(function (data, status, headers, config) {
                    $scope.customerList = data ;
                 if(data.length ==0){
                     $scope.NoDataShow = true;
                 }else {
                     $scope.NoDataShow = false;
                     $scope.applyChart(data);
                 }


            })
            .error(function (data, status, header, config) {
                alert(data.message);
                subject.showLoader = false;
            });

    }

    $scope.applyChart = function (data) {
        $scope.dataLoad = false ;
        var result = [];

        result.push(["Task", "Hours per Day"]);


        angular.forEach(data , function (value ,key) {
            result.push([value.reason , parseInt(value.total)]);
        });


        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            var data = google.visualization.arrayToDataTable(result);

            /* var data = google.visualization.arrayToDataTable([
                ["Task", "Hours per Day"],
                ['Work',     11],
                ['Eat',      2],
                ['Commute',  2],
                ['Watch TV', 2],
                ['Sleep',    7]
            ]);*/

            var options = {
                title: 'Drop Customer'
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));

            chart.draw(data, options);

        }
    }

}]);



app.directive('modal', function () {
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

