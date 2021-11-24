/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var riderDailyStockGridModule = angular.module('riderDailyStockGridModule', ['angularjs-datetime-picker']);
riderDailyStockGridModule.controller('riderDailyStockGridCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter , $timeout) {
    //INITIALIZATION ===============================================================
    $scope.init = function (riderList ,data , selectDateBaseMessageURL ,clientListUrl) {

        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";
         $scope.riderList = riderList ;
         $scope.clientListUrl = clientListUrl ;

         $scope.selectRiderID = '0';
         $scope.data = data ;
            $scope.totalSms = 0;
         angular.forEach($scope.data ,function (value ,key) {
              var smsLength=  value.smsCount;
             $scope.totalSms = Number($scope.totalSms) + Number(smsLength);

         } );
         $scope.selectDateBaseMessageURL = selectDateBaseMessageURL ;
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
        $scope.startDate = selectYear + '-' + month + '-' + date;
        $scope.endDate = selectYear + '-' + month + '-' + date;

        $scope.getAllCustomerList();
        $scope.loadClientLoader = true;
        $scope.SelectedCustomer = 'Select Cutomer'
        $scope.client_id ='0'
    }

    $scope.getAllCustomerList = function(){
        $http.post($scope.clientListUrl)
            .success(function(responce){
                $scope.clientList = responce ;
                $scope.loadClientLoader = false;
            })
            .error(function (responce) {
            });
    }
    $scope.changeRider = function(){
        $scope.SelectedCustomer = 'Select Cutomer';
        $scope.client_id = '0';

    }
     $scope.getMessageList = function () {
              $scope.imageLoading = true ;
              var data = {
                   selectRiderID : $scope.selectRiderID ,
                   startDate : $scope.startDate ,
                   endDate : $scope.endDate ,
                   client_id : $scope.client_id
              }
             $http.post($scope.selectDateBaseMessageURL ,data)
                 .success(function (responce) {
                     $scope.data = responce ;
                     $scope.imageLoading = false ;
                     $scope.totalSms = 0;
                     angular.forEach($scope.data ,function (value ,key) {
                         var smsLength=  value.smsCount;
                         $scope.totalSms = Number($scope.totalSms) + Number(smsLength);

                     } );

                 })
                 .error(function (responce) {

                 });
     }

    $scope.abcd = function (y) {

            $scope.SelectedCustomer = y.fullname;
           $scope.client_id = y.client_id;
            $scope.selectRiderID = '0';

    }




}]);


