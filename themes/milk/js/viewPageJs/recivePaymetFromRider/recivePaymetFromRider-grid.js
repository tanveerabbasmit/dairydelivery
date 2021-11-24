/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var riderDailyStockGridModule = angular.module('riderDailyStockGridModule', ['angularjs-datetime-picker']);
riderDailyStockGridModule.controller('riderDailyStockGridCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter , $timeout) {
    //INITIALIZATION ===============================================================
    $scope.init = function (data , pickAmountByAdminURl , searchNewAllURL) {

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

         $scope.pickAmountByAdminURl = pickAmountByAdminURl ;
         $scope.searchNewAllURL = searchNewAllURL ;



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

        $scope.updateMode = false ;
        $scope.imageLoading = false ;
    }

     $scope.savePickAmount = function () {
          var sendData = {
              todayDate :$scope.todate,
               data :  $scope.data
          }
         $scope.imageLoading = true;
         $http.post($scope.pickAmountByAdminURl ,angular.toJson(sendData))
             .success(function (responce) {
                $scope.data = responce ;

                 $scope.imageLoading = false ;
                 $scope.updateMode = false ;
             })
             .error(function (responce) {

             });
     }

     $scope.searchNewAllFunction = function () {

         $scope.imageLoading = true;
         $http.post($scope.searchNewAllURL ,  $scope.todate)
             .success(function (responce) {
                $scope.data = responce ;

                 $scope.imageLoading = false ;
                 $scope.updateMode = false ;
             })
             .error(function (responce) {

             });
     }

     $scope.updatefunction = function () {

         angular.forEach($scope.data , function (value ,key) {
             value.update_mode = true ;
         })

         $scope.updateMode = true;
     }
    $scope.changePickPaymentAmount = function (list) {
           var temporary_balance = list.balance ;
         if(list.pick_by_admin){

             list.balance = temporary_balance - list.pick_by_admin;
         }else {
             list.balance = temporary_balance;
         }

    }


}]);


