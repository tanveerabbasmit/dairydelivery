/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var riderDailyStockGridModule = angular.module('riderDailyStockGridModule', ['angularjs-datetime-picker']);
riderDailyStockGridModule.controller('riderDailyStockGridCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter , $timeout) {
    //INITIALIZATION ===============================================================
    $scope.init = function (data,base_url) {

         $scope.today_data = data.today_data;
         $scope.project_list = data.project_list;
         $scope.company_title = data.company_title;
         $scope.base_url = base_url;

         $scope.product_id = '0';
        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";


    }


     $scope.get_data_function = function (){
        $scope.loading = true;

         var send_data ={
           'today_data' : $scope.today_data,
           'product_id' : $scope.product_id
         }

         $http.post($scope.base_url, send_data)
             .success(function (data, status, headers, config) {
                   $scope.opening_stock = data.opening_stock;
                   $scope.production_stock = data.production_stock;
                   $scope.total_production = data.total_production;
                   $scope.rider_return = data.rider_return;

                   $scope.rider_return_size = data.rider_return_size;

                   $scope.one_day_credit_sale_list = data.one_day_credit_sale_list;
                   $scope.in_house_usage = data.in_house_usage;
                   $scope.purchase_list = data.purchase_list;
                   $scope.purchase_list_size = data.purchase_list_size;

                   $scope.grand_total_stock_in = data.grand_total_stock_in;

                   $scope.grand_total_sale_and_use = data.grand_total_sale_and_use;
                   $scope.next_day_carry = data.next_day_carry;

                   $scope.loading = false;
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


