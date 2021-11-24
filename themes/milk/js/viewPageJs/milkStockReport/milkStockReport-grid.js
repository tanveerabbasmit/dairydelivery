/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('clintManagemaent', ['angularjs-datetime-picker']);
app.controller('clintManagemaent', ['$scope', '$http', '$filter', function ($scope, $http, $filter,$timeout) {
    //INITIALIZATION ===============================================================
    $scope.init = function (data ,base_url){


       $scope.start_date = data.date;
       $scope.base_url = base_url;
       document.getElementById("testContainer").style.display = "block";
       document.getElementById("loaderImage").style.display = "none";
       $scope.selected_discount_list =[];
       setTimeout(function(){
            $scope.carryForworded();
        }, 300);
       $scope.reason = '' ;
       $scope.actual_stock ='';
       $scope.save_buton =false ;
    }
    $scope.carryForworded = function(){
        $http.post($scope.base_url+"_carryForworded" ,$scope.start_date)
            .success(function(responce){
                $scope.total_carry_Forward = responce ;
                $scope.getProductionList();

            })
            .error(function (responce) {
            });
    }

    $scope.getProductionList = function (){
        $http.post($scope.base_url+"_production" ,$scope.start_date)
            .success(function(responce){
                $scope.productionList = responce ;
                $scope.start_date_p = responce.start_date_p ;

                $scope.total_production = Number($scope.productionList.morning) + Number($scope.productionList.evenining) + Number($scope.productionList.afternoun);
                $scope.productionList_fram();
            })
            .error(function (responce) {
            });
    }

    $scope.productionList_fram = function (){

        $http.post($scope.base_url+"_production_fram",$scope.start_date)
            .success(function(responce){
                $scope.farmList = responce ;
                $scope.total_farm_stock =0;
                angular.forEach($scope.farmList ,function(value ,key){
                    $scope.total_farm_stock = Number($scope.total_farm_stock) + Number(value.quantity);
                });
                $scope.credit_stock();
            })
            .error(function (responce) {
            });
    }
    $scope.credit_stock = function (){

        $http.post($scope.base_url+"_credit_stock",$scope.start_date)
            .success(function(responce){
                var credit_sale =responce.credit_sale ;
                 $scope.wasteg =responce.wasteg ;
                 $scope.client_sale = responce.client_sale ;
                 $scope.totalClient_sale = 0;
                 $scope.todayData = responce.todayData;



                 angular.forEach($scope.client_sale ,function (value ,key) {
                     $scope.totalClient_sale = Number($scope.totalClient_sale) + Number(value.quantity);
                 });
                 $scope.total_credit_sale = Number(credit_sale) - Number($scope.totalClient_sale) ;
                 $scope.total_uses = Number(credit_sale);
                  $scope.availableForSale = Number($scope.total_farm_stock) +Number($scope.total_production) + Number($scope.total_carry_Forward);
                  $scope.closing_day_stock = Number($scope.availableForSale) - Number($scope.total_credit_sale) - ($scope.wasteg);

                  $scope.today_remarks();
            })
            .error(function (responce) {
            });
    }
    $scope.today_remarks = function (){

        $http.post($scope.base_url+"today_remarks",$scope.start_date)
            .success(function(responce){
              $scope.actual_stock =responce.actual_stock;
              $scope.reason =responce.reason;

            })
            .error(function (responce) {

            });
    }

    $scope.saveReport = function () {
        $scope.save_buton =true ;
         var send_data ={
             'carry_forworded':$scope.total_carry_Forward,
             'available_for_sale':(Number($scope.total_farm_stock) + Number($scope.total_production)) ,
             'credit_sale':$scope.total_credit_sale,
             'closing_stock':$scope.closing_day_stock,
             'reason':$scope.reason,
             'actual_stock':$scope.actual_stock,
             'productionList':$scope.productionList,
             'farm_stock':$scope.actual_stock,
             'total_farm_stock':$scope.total_farm_stock,
             'start_date':$scope.start_date,
         }
        $http.post($scope.base_url+"_saveReport",send_data)
            .success(function(responce){
              //   $scope.save_buton =false ;
            })
            .error(function (responce) {
        });
    }

     $scope.calculateDifference = function(closing_day_stock ,actual_stock){
         if(Number(closing_day_stock) <0){
             actual_stock = -(actual_stock);
         }
          return (Number(closing_day_stock) - Number(actual_stock))
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

