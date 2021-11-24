/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var riderDailyStockGridModule = angular.module('riderDailyStockGridModule', ['angularjs-datetime-picker']);
riderDailyStockGridModule.controller('riderDailyStockGridCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter , $timeout) {
    //INITIALIZATION ===============================================================
    $scope.init = function (data ,basr_url) {

        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";
        $scope.data = data;
        $scope.halt_result = data.halt_result;
        $scope.basr_url = basr_url;
        $scope.today_date = data.today_date;

    }

    $scope.cancel_order = function(){
        $scope.showProgressBar =true;
        $http.post($scope.basr_url+"_cancel_order",  $scope.data)
            .success(function (data) {

                $scope.showProgressBar =false;

            })
            .error(function (data, status, header, config) {

            });
    }
    $scope.riderData2 = function(todate) {
        $scope.todate = todate;

        $scope.showProgressBar = true ;
        $scope.riderStockListloading = [];
        var numberOfLoadRider= 0;
        var startAray = 0;
          var check_increment = 0;
        for(startAray ; startAray<=3;startAray++){

            var sendData = {
                todate: todate ,
                DayNumber  : check_increment,
            }
            $http.post($scope.getDialyDeliveryCustomerURL+'2', sendData)
                .success(function (data, status, headers, config) {

                    $scope.testObject  = data;
                    $scope.riderStockListloading.push(data);
                    $scope.loadPerCentage = ((Number($scope.pervousDate_count)/Number(check_increment)))*100
                    check_increment++;
                     if(check_increment ==check_increment ){
                         $scope.showProgressBar = false;
                     }


                })
                .error(function (data, status, header, config) {

                });
        }




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


