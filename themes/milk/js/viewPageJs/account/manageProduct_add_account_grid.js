/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('productGrid', []);
app.controller('manageProduct', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (account_list,allow_delete , productCount , productList , saveNewProductURL , editProductURL ,deleteURL , searchProductURL , checkAlredyExistURL ,nextPageForpaginationURL) {

        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";

        $scope.curPage = 0;
        $scope.pageSize = 10;

        $scope.allow_delete = allow_delete ;
        $scope.account_list = account_list ;
        $scope.totalPages = Math.ceil(productCount/ $scope.pageSize);

        $scope.productList = productList ;

        angular.forEach( $scope.productList ,function (value ,key) {
            value.update = false;

        });

        $scope.saveNewProductURL = saveNewProductURL ;
        $scope.editProductURL = editProductURL ;

         $scope.deleteURL  = deleteURL ;

         $scope.searchProductURL  = searchProductURL ;
         $scope.checkAlredyExistURL = checkAlredyExistURL ;



        $scope.get_gl_account_list();


    }

    $scope.change_acount_function = function(flag,product){
        if(flag==1){
            angular.forEach($scope.account_list ,function (value,key) {
                if(product.product_sale_account_id==value.id){
                    product.product_sale_account_name = value.name;



                }
            });
        }else {
            angular.forEach($scope.account_list ,function (value,key) {
                if(product.product_receivable_account_id==value.id){
                    product.product_receivable_account_name = value.name;
                }
            });
        }

    }

    $scope.update_account = function(product){

        product.update = true;
    }
    $scope.save_acount_account = function(product){

        $http.post($scope.saveNewProductURL+"_save_account_function", product)
            .success(function (data, status, headers, config) {

                product.update=false;
            })
            .error(function (data, status, header, config) {

            });
    }
    $scope.get_gl_account_list = function(){

        $http.post($scope.saveNewProductURL+"_get_account_list")
            .success(function (data, status, headers, config) {

                $scope.account_list = data;
            })
            .error(function (data, status, header, config) {

            });
    }





    $scope.addnewProduct = function(){
        $scope.showAddNewPro = !$scope.showAddNewPro;
    };


    $scope.saveProduct = function (product) {
             var sendData = angular.toJson(product);
        $http.post($scope.saveNewProductURL, sendData)
            .success(function (data, status, headers, config) {
                if(data.success){

                    $scope.productList.push(data.product[0]);
                    $scope.showAddNewPro = !$scope.showAddNewPro;
                    $scope.productObject  =  $scope.temporary;
                }else{
                    alert(angular.toJson(data.message));
                }

            })
            .error(function (data, status, header, config) {
                alert(data.message);
                subject.showLoader = false;
            });
    }

   $scope.editProduct = function(product){
       $scope.productObject = product;
       $scope .showEditProduct = !$scope.showEditProduct;
   }
    $scope.editProductSave = function (product) {
             var sendData = angular.toJson(product);
        $http.post($scope.editProductURL, sendData)
            .success(function (data, status, headers, config) {
                if(data.success){
                    $scope .showEditProduct = !$scope.showEditProduct;

                }else{
                    alert(angular.toJson(data.message));
                }

            })
            .error(function (data, status, header, config) {
                alert(data.message);
                subject.showLoader = false;
            });
    }

    $scope.delete = function (product) {


             var sendData = angular.toJson(product);
        $http.post($scope.deleteURL, sendData)
            .success(function (data, status, headers, config) {
                if(data.success){

                    product.is_deleted = 1;
                    /*var index = $scope.productList.indexOf(product);
                    $scope.productList.splice(index, 1);*/

                }else{
                    alert(angular.toJson(data.message));
                }

            })
            .error(function (data, status, header, config) {
                alert(data.message);
                subject.showLoader = false;
            });
    }


    $scope.searchProduct = function(search) {

          $http.post($scope.searchProductURL , search)
              .success(function(responceData , status) {

                  $scope.productList = responceData ;
                  $scope.hideAndShowPagination = false ;

              })
              .error(function (data , status) {
                  
              })

    }

    $scope.changeSearchBar = function (search) {

        if(!$scope.search){
            $scope.productList =  $scope.temporaryProductList ;
            $scope.hideAndShowPagination = true ;
        }

    }
    $scope.checkAlreadyExistFunction = function(name){
        $scope.checkAlredyExist = false ;
        $http.post($scope.checkAlredyExistURL , name)
            .success(function(responcedata , status) {
               if(responcedata == 'yes'){
                   $scope.checkAlredyExist = true ;
               }


            })
            .error(function (data , status) {

            })


    }

    $scope.nextPagePagination = function(page) {

        $http.post($scope.nextPageForpaginationURL , page)
            .success(function(responceData , status) {

                $scope.productList = responceData ;
                $scope.temporaryProductList =  responceData ;

            })
            .error(function (data , status) {

            })
    }
    $scope.resetProjectObject = function(){
        $scope.productObject = {'name':'' , 'unit':'' , 'price' : '' , 'is_active':'1' ,'is_deleted':'0','order_type':1}
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

