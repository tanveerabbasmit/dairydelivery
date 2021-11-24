/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('productGrid', ['angularjs-datetime-picker']);
app.controller('manageProduct', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function(get_data , today_date, productList ,getdateWiseDataDeliveryData) {


        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";

        $scope.curPage = 0;
        $scope.pageSize = 10;

         $scope.get_data = get_data ;

        $scope.today_date = get_data.today_date ;
        $scope.end_date = get_data.end_date ;

        $scope.productList = productList ;

        $scope.totalQuantity =0;
        $scope.totalAmount =0;
         angular.forEach($scope.productList ,function (value, key) {
             $scope.totalQuantity =  Number($scope.totalQuantity) +Number(value.quantity);
             $scope.totalAmount =  Number($scope.totalAmount) +Number(value.amount);
         });
        $scope.getdateWiseDataDeliveryData = getdateWiseDataDeliveryData ;

        $scope.selectDateWiseData();
    }




    $scope.selectDateWiseData = function () {

         $scope.imageLoading = true ;



        $http.post($scope.getdateWiseDataDeliveryData,$scope.get_data )
            .success(function (data, status, headers, config) {

                $scope.productList = data ;
                $scope.totalQuantity =0;
                $scope.totalAmount =0;
                angular.forEach($scope.productList ,function (value, key) {

                    $scope.totalQuantity =  Number($scope.totalQuantity) +Number(value.quantity);
                    $scope.totalAmount =  Number($scope.totalAmount) +Number(value.amount);


                });

                $scope.imageLoading = false;

            })
            .error(function (data, status, header, config) {
                alert(data.message);
                subject.showLoader = false;
            });
    }





}]);

app.directive('modal', function () {
    return {
        template: '<div class="modal fade">' +
        '<div class="modal-dialog">' +
        '<div class="modal-content"  style="width: 70% ; margin-left: 25% ; margin-top: 0%">' +
        '<div class="modal-header" style="background-color: #D8DCE3">' +
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

