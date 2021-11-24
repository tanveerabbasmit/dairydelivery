/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var riderDailyStockGridModule = angular.module('riderDailyStockGridModule', ['angularjs-datetime-picker']);
riderDailyStockGridModule.controller('riderDailyStockGridCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter , $timeout) {
    //INITIALIZATION ===============================================================
    $scope.init = function ( year  ,  company_id , riderList , getDialyDeliveryCustomerURL ,googleMapURL , saveDeliveryURL) {


        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";

        $scope.year = year+'';

        $scope.company_id = company_id ;
        $scope.riderList= riderList;
        $scope.getDialyDeliveryCustomerURL = getDialyDeliveryCustomerURL ;
        $scope.selectRiderID ='0';
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

        $scope.startDate =  $scope.todate ;
        $scope.endDate =  $scope.todate;



        $scope.saveDeliveryURL = saveDeliveryURL ;

        $scope.sortReverse = true ;

        $scope.selectedRider = 'non'
    }

    $scope.changeRider = function (riderID) {

        $http.post($scope.getDialyDeliveryCustomerURL, riderID)
            .success(function (data, status, headers, config) {

            })
            .error(function (data, status, header, config) {

            });

    }

    $scope.selectRiderOnChange = function(riderid){

        if(riderid ==0){
          alert('Select Rider');
        }else {
            var data = {
                'year' : $scope.year ,
                'monthNum' : '' ,
                'RiderID'  :riderid
            }
            var RiderObject = angular.toJson(data);

            $scope.imageLoading = true ;
            $http.post($scope.getDialyDeliveryCustomerURL ,RiderObject)
                .success(function(reponseData , status ){
                    $scope.deductionAmount =reponseData ;
                    $scope.imageLoading = false ;
                })
                .error(function () {
                });
        }
    }

    $scope.editDeduction = function (list) {

        list.update =true;

    }

    $scope.SaveDeduction = function (list) {

        list.update =false;

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


