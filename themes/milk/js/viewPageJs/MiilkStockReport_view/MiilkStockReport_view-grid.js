/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var riderDailyStockGridModule = angular.module('riderDailyStockGridModule', ['angularjs-datetime-picker']);
riderDailyStockGridModule.controller('riderDailyStockGridCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter , $timeout) {
    //INITIALIZATION ===============================================================
    $scope.init = function (data ,base_url) {



        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";

         $scope.base_url =base_url ;

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

        $scope.saveReport_get();
    }

     $scope.saveReport_get = function () {


         $scope.imageLoading = true;
         $http.post($scope.base_url+"_getMilkReport" ,$scope.todate)
             .success(function (responce) {
                $scope.data = responce ;

                 $scope.imageLoading = false ;
                 $scope.updateMode = false ;
             })
             .error(function (responce) {

             });
     }




}]);


