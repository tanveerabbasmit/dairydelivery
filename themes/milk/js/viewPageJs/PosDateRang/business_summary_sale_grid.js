/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var riderDailyStockGridModule = angular.module('riderDailyStockGridModule', ['angularjs-datetime-picker']);
riderDailyStockGridModule.controller('riderDailyStockGridCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter , $timeout) {
    //INITIALIZATION ===============================================================
    $scope.init = function (data  , baseurl) {

        $scope.start_date = data.start_date;
        $scope.end_date = data.start_date;
        $scope.baseurl = baseurl;


        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";

        $scope.get_data();
    }

     $scope.get_data = function () {

         var send_data = {
             'start_date':$scope.start_date,
             'end_date':$scope.end_date
         }
         $scope.imageLoading = true;
         $http.post($scope.baseurl+"_business_summary_sale_data", send_data)
             .success(function (data, status, headers, config) {
                 $scope.result_list = data.list;
                 $scope.total_amount = data.total_amount;
                 $scope.total_quantity = data.total_quantity;

                 $scope.imageLoading = false;
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


