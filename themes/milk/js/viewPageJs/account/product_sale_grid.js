/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('productGrid', ['angularjs-datetime-picker']);
app.controller('manageProduct', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function(today_date, productList ,base_url) {


        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";
        $scope.today_date = today_date ;
        $scope.productList = productList ;

        $scope.product_id = '0';
        $scope.base_url = base_url ;
        $scope.save_vocher = false;

    }




    $scope.get_sale = function () {

         $scope.imageLoading = true ;

          var send_Data ={
              start_date:$scope.today_date,
              product_id:$scope.product_id,
          }

        $http.post($scope.base_url+"_get_today_sale",send_Data )
            .success(function (data, status, headers, config) {
                $scope.sale_list = data.result;
                $scope.total = data.total;
                $scope.imageLoading = false;

            })
            .error(function (data, status, header, config) {
                alert(data.message);
                subject.showLoader = false;
            });
    }
    $scope.save_vocher_function = function(){
        $scope.save_vocher = true;
        var send_data = {
            product_sale_account_id :  $scope.product_sale_account_id,
            product_receivable_account_id :  $scope.product_receivable_account_id,
            total :  $scope.total,
            today_date :  $scope.today_date,
            product_id :  $scope.product_id,
        }
        $http.post($scope.base_url+"_save_vocher",send_data )
            .success(function (data, status, headers, config) {

                $scope.save_vocher = false;
                if(data.success){

                }else {
                    alert(data.message);
                }

            })
            .error(function (data, status, header, config) {
                alert(data.message);

            });
    }
    $scope.change_product = function(){
        $scope.selected_product={};
        angular.forEach($scope.productList ,function(value ,key){

               if(value.product_id== $scope.product_id){

                   $scope.selected_product = value;
                    $scope.product_sale_account_id = value.product_sale_account_id;
                    $scope.product_sale_account_name = value.product_sale_account_name;
                    $scope.product_receivable_account_id = value.product_receivable_account_id;
                    $scope.product_receivable_account_name = value.product_receivable_account_name;
                    $scope.prduct_name = value.name;




               }
        })
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

