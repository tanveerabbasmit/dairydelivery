/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var riderDailyStockGridModule = angular.module('riderDailyStockGridModule', ['angularjs-datetime-picker']);
riderDailyStockGridModule.controller('riderDailyStockGridCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter , $timeout) {
    //INITIALIZATION ===============================================================
    $scope.init = function (select_date,data , baseurl) {


        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";

        $scope.data = data ;
        $scope.total_recive_amount =0 ;
        $scope.total_submit_amount = 0;
        $scope.total_balance = 0;
        angular.forEach( $scope.data , function(value ,key){
             $scope.total_submit_amount =  Number($scope.total_submit_amount) + Number(value.submit_amount);
             $scope.total_recive_amount =  Number($scope.total_recive_amount) + Number(value.recive_amount);
             $scope.total_balance =  Number($scope.total_balance) + Number(value.balance);
        })
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
        $scope.start_date = select_date.start_date;
        $scope.end_date = select_date.end_date;
        $scope.baseURl = baseurl;
        $scope.updateMode = false ;
        $scope.imageLoading = false ;
        $scope.get_today_graph();

    }
     $scope.get_today_graph = function(){

          var send_data ={
              start_date :$scope.start_date,
              end_date :$scope.end_date,
          }
               $scope.imageLoading = true ;
         var getDeleiveryStatusURl = $scope.baseURl+'_get_newCustomer_graph';
         $scope.outStandingBalnceLoader = true ;
         $http.post(getDeleiveryStatusURl ,send_data)
             .success(function(responce){
                 $scope.imageLoading = false;
                 $scope.savePickAmount(responce.amountObject);


             })
             .error(function(responce){

             });


     }
     $scope.savePickAmount = function (data2) {

         var graph_data = Array.from(data2);
         google.charts.load("current", {packages:['corechart']});
         google.charts.setOnLoadCallback(drawChart);
         function drawChart() {
             var data = google.visualization.arrayToDataTable(graph_data);
             $scope.imageLoading = false ;
            /*var data = google.visualization.arrayToDataTable([
                 ["Element", "Density", { "role": "style" } ],
                 ["Copper", 8.94, "#b87333"],
                 ["Silver", 10.49, "silver"],
                 ["Gold", 19.30, "gold"],
                 ["Gold", 19.30, "gold"],
                 ["Gold", 19.30, "gold"],
                 ["Gold", 23.30, "gold"],
                 ["Platinum", 24, "color: #e5e4e2"]
             ]);*/
             var view = new google.visualization.DataView(data);
             view.setColumns([0, 1,
                 { calc: "stringify",
                     sourceColumn: 1,
                     type: "string",
                     role: "annotation" },
                 2]);

             var options = {
                 title: "New Customer",

                 bar: {groupWidth: "100%"},
                 legend: { position: "none" },
             };
             var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
             chart.draw(view, options);
         }
     }



}]);


