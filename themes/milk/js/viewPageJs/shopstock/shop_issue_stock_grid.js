/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var riderDailyStockGridModule = angular.module('riderDailyStockGridModule', ['angularjs-datetime-picker']);
riderDailyStockGridModule.controller('riderDailyStockGridCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter , $timeout) {
    //INITIALIZATION ===============================================================
    $scope.init = function (data ,base_url) {

        $scope.today_date = data.today_date;
        $scope.base_url = data.base_url;

        $scope.shop_list = data.shop_list;
        $scope.product_list = data.product_list;

        angular.forEach($scope.product_list , function (value,key) {
            value.quantity ='';
        });


        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";


        $scope.pos_shop_id = '0';
    }


    $scope.selectRiderOnChange = function(today){
        $scope.imageLoading = true ;
        $http.post($scope.getDialyDeliveryCustomerURL ,today)
            .success(function(reponseData , status ){

            })
            .error(function () {
            });
    }

    $scope.save_stock  = function () {
        var send_data = {
            pos_shop_id : $scope.pos_shop_id,
            product_list : $scope.product_list,
        }

        $http.post($scope.base_url+'save_shop_issue_stock' ,send_data)
            .success(function(reponseData , status ){

            })
            .error(function () {
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


