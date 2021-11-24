/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var riderDailyStockGridModule = angular.module('riderDailyStockGridModule', ['angularjs-datetime-picker']);
riderDailyStockGridModule.controller('riderDailyStockGridCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter , $timeout) {
    //INITIALIZATION ===============================================================
    $scope.init = function (riderList_list , company_id , riderList , getDialyDeliveryCustomerURL ,googleMapURL , saveDeliveryURL) {

            document.getElementById("testContainer").style.display = "block";
            document.getElementById("loaderImage").style.display = "none";
            $scope.riderList_list = riderList_list ;
            $scope.company_id = company_id ;
            $scope.totalCustomer= riderList;
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

            $scope.riderList_list =  $scope.riderList_list
            $scope.startDate =  $scope.todate
            $scope.endDate =  $scope.todate
            $scope.saveDeliveryURL = saveDeliveryURL ;
            $scope.sortReverse = true ;

            $scope.lastReceiptCustomerList =[];
            $scope.search =0;
            $scope.rider_id = '0';
           // $scope.getLastReceiptCustomer_all();

    }


     $scope.getLastReceiptCustomer_all = function(){
          $scope.imageLoading =true ;
             $http.post($scope.getDialyDeliveryCustomerURL,$scope.rider_id)
             .success(function (data, status, headers, config) {
              $scope.list_data = data.list;
                 $scope.imageLoading =false ;
             })
             .error(function (data, status, header, config) {
             });
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


