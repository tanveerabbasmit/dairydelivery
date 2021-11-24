/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var riderDailyStockGridModule = angular.module('riderDailyStockGridModule', ['angularjs-datetime-picker','toggle-switch']);
riderDailyStockGridModule.controller('riderDailyStockGridCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter , $timeout) {
    //INITIALIZATION ===============================================================
    $scope.init = function(companyLimit , totalResult, customerList , startDateSearchCustomerListURL , saveCompanyLimit) {

        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";

        $scope.curPage = 0;
        $scope.pageSize = 10;
        $scope.totalPages = Math.ceil(totalResult/ $scope.pageSize);
         $scope.companyLimit = companyLimit ;
         $scope.customerList= customerList;
         $scope.startDateSearchCustomerListURL = startDateSearchCustomerListURL ;
         $scope.saveCompanyLimit =saveCompanyLimit ;

         $scope.selectRiderID = '';

         $scope.total_count =0;
         angular.forEach($scope.customerList, function(value ,key){
             if(value.banlanceAmount >=$scope.companyLimit){
                 value.limitFlag = true;
             }else {
                 value.limitFlag = false;
             }
             $scope.total_count = $scope.total_count + Number(value.banlanceAmount)
         });

       $scope.showScheduleSee = true;


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


        $scope.imageLoader = false ;


    }
     $scope.startDateSearch = function () {
         $scope.imageLoader = true;
         $http.post($scope.startDateSearchCustomerListURL, $scope.todate)
             .success(function (data, status, headers, config) {
                 $scope.customerList = data;

                 angular.forEach($scope.customerList, function(value ,key){
                     if(value.banlanceAmount >=$scope.companyLimit){
                         value.limitFlag = true;
                     }else {
                         value.limitFlag = false;
                     }
                     $scope.total_count = $scope.total_count + Number(value.banlanceAmount)
                 });
                 $scope.imageLoader = false ;
             })
             .error(function (data, status, header, config) {
             });
     }

     $scope.startDateSearch_all = function () {
         $scope.imageLoader = true ;
         $http.post($scope.startDateSearchCustomerListURL+'_all', $scope.todate)
             .success(function (data, status, headers, config) {
                 $scope.customerList = data;
                 $scope.imageLoader = false ;
             })
             .error(function (data, status, header, config) {
             });
     }

     $scope.limitAcross = function(amount) {
          var result;
        if(amount >= $scope.companyLimit ){
            result = true ;
        }else {
             result = false ;
        }
        return result
     }
     $scope.setCompanyLimit = function(){
        $scope.limitModelShow = !$scope.limitModelShow ;
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



