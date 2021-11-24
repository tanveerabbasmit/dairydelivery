/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('productGrid', ['angularjs-datetime-picker']);
app.controller('manageProduct', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function(today_date, productList ,getdateWiseDataDeliveryData) {

        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";



        $scope.curPage = 0;
        $scope.pageSize = 10;

        $scope.today_date = today_date ;
        $scope.end_date = today_date ;

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

          var send_Data ={
              start_date:$scope.today_date,
              end_date:$scope.end_date,
          }

        $http.post($scope.getdateWiseDataDeliveryData,send_Data )
            .success(function (data, status, headers, config) {

                $scope.delivery_list = data.delivery_list;
                $scope.make_payment = data.make_payment;
                $scope.pos = data.pos;
                $scope.expence_type = data.expence_type;
                $scope.vendor_bill_amount = data.vendor_bill_amount;
                $scope.vendor_payment = data.vendor_payment;
                $scope.farm_purchase = data.farm_purchase;
                $scope.final_balance = data.final_balance;




                $scope.imageLoading = false;
               // $scope.get_payment_list_between_range()

            })
            .error(function (data, status, header, config) {
                alert(data.message);
                subject.showLoader = false;
            });
    }

    $scope.get_payment_list_between_range = function () {

        var send_Data ={
            start_date:$scope.today_date,
            end_date:$scope.end_date,
        }

        $http.post($scope.getdateWiseDataDeliveryData+"_payment_list",send_Data )
            .success(function (data, status, headers, config) {


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

