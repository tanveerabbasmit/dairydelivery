/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var riderDailyStockGridModule = angular.module('riderDailyStockGridModule', ['angularjs-datetime-picker']);
riderDailyStockGridModule.controller('riderDailyStockGridCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter , $timeout) {
    //INITIALIZATION ===============================================================
    $scope.init = function (data, saveNewStockUrl, saveReturnDetailURl , getRiderDailyStockURl) {

        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";

        $scope.searchDate = data.currentDate;
        $scope.riderList = data.riderList;
        $scope.productList = data.productList ;
        $scope.ridersListForGrid = data.ridersListForGrid;
        $scope.currentDate = data.currentDate;

        $scope.riderProductList = [];
        $scope.saveNewStockUrl = saveNewStockUrl;
        $scope.saveReturnDetailURl = saveReturnDetailURl;
        $scope.getRiderDailyStockURl = getRiderDailyStockURl ;

        $scope.selectProductID = $scope.productList[0].product_id;
        $scope.showLoaderImage = false ;

         $scope.taskMessage = 'Task compte';

         $scope.searchRider ='';

         $scope.selectRiderID = '';

    }

    $scope.addNewStock = function () {

        $scope.riderId = '';
        $scope.riderProductList = [];
    }

    $scope.updateStock = function (stockObj) {
        stockObj.updateMode= true;
    }

    $scope.saveUpdateStock = function (stockObj) {
        if (stockObj.return_quantity>stockObj.quantity) {
            alert('Return stock cannot be grater then stock quantity');
            return false;
        }
        var data = angular.toJson(stockObj);
        $http.post($scope.saveRiderStockUrl, data, $scope.config)
        .success(function (data, status, headers, config) {
            if(data.status){
                stockObj.updateMode = false;
                $scope.ridersListForGrid = data.data.ridersListForGrid;
            }else{
                alert(angular.toJson(data.message));
            }
        })
        .error(function (data, status, header, config) {
            alert(data.message);
        });
    }

    $scope.deleteRiderStock = function (stockObj) {
        var data = angular.toJson(stockObj);
        $http.post($scope.deleteRiderStockUrl, data, $scope.config)
        .success(function (data, status, headers, config) {
            if(data.status){
                $scope.ridersListForGrid = data.data.ridersListForGrid;
                $scope.riderStockDetailList = data.data.riderStockDetailList;
            }else{
                alert(angular.toJson(data.message));
            }
        })
        .error(function (data, status, header, config) {
            alert(data.message);
        });
    }
    
   /* $scope.saveUpdateStock = function (stockObj) {
        console.log(stockObj);
        stockObj.updateMode = false;
    }*/


    $scope.dateChange = function () {
        var data = angular.toJson($scope.searchDate);
        $http.post($scope.searchUrl, data, $scope.config)
        .success(function (data, status, headers, config) {
            if(data.status){
                $scope.dailyStockList = data.data.dailyStockList;
            }else{
                alert(angular.toJson(data.message));
            }
        })
        .error(function (data, status, header, config) {
            alert(data.message);
        });
    }

    $scope.getRiderProducts = function () {
        var params = {rider_id:$scope.riderId, date: $scope.searchDate};
        $http.post($scope.getRiderProductsUrl, params, $scope.config)
        .success(function (data, status, headers, config) {
            if(data.status){
                $scope.riderProductList = data.data.riderDailyStockRequired;
            }else{
                alert(angular.toJson(data.message));
            }
        })
        .error(function (data, status, header, config) {
            alert(data.message);
        });
    }

    $scope.saveNewRiderStock = function () {


            var checkSelectOne = false;
         angular.forEach($scope.riderList , function(value , key){
             if(value.selectQuantity || value.wastage_quantity){
                 checkSelectOne = true;
             }

         });
         if(checkSelectOne){
             $scope.taskMessage = 'task complete successfully.';
             $scope.showLoaderImage =true;
             var params = {productID:$scope.selectProductID, riderProductList:$scope.riderList,currentDate:$scope.currentDate };
             $http.post($scope.saveNewStockUrl, params, $scope.config)
                 .success(function (data, status, headers, config) {
                     if(data.status){
                         angular.forEach($scope.riderList , function(value , key){
                             value.selectQuantity= '';
                         });
                         $scope.showLoaderImage = false ;
                         document.getElementById("alertMessage").style.display = "block";
                         setTimeout(function(){
                                 document.getElementById("alertMessage").style.display = 'none';
                             },
                             1500);
                     }else{
                         alert(angular.toJson(data.message));
                     }
                 })
                 .error(function (data, status, header, config) {
                     alert(data.message);
                 });

         }else {
             $scope.taskMessage = 'Put at least One  quantity ';
             document.getElementById("alertMessage").style.display = "block";
             setTimeout(function(){
                     document.getElementById("alertMessage").style.display = 'none';
                 },
                 1500);
         }

    }


    $scope.saveReturnRiderStock = function () {

            var checkSelectOne = false;
         angular.forEach($scope.riderList , function(value , key){
             if(value.selectQuantity){
                 checkSelectOne = true;
             }

         });
         if(checkSelectOne){
             $scope.taskMessage = 'task complete successfully.';
             $scope.showLoaderImage =true;
             var params = {
                 productID:$scope.selectProductID,
                 riderProductList:$scope.riderList,
                 currentDate:$scope.currentDate
             };
             $http.post($scope.saveReturnDetailURl, params, $scope.config)
                 .success(function (data, status, headers, config) {
                     if(data.status){
                         angular.forEach($scope.riderList , function(value , key){
                             value.selectQuantity= '';
                         });
                         $scope.showLoaderImage = false ;
                         document.getElementById("alertMessage").style.display = "block";
                         setTimeout(function(){
                                 document.getElementById("alertMessage").style.display = 'none';
                             },
                             1500);
                     }else{
                         alert(angular.toJson(data.message));
                     }
                 })
                 .error(function (data, status, header, config) {
                     alert(data.message);
                 });

         }else {
             $scope.taskMessage = 'Put at least One  quantity ';
             document.getElementById("alertMessage").style.display = "block";
             setTimeout(function(){
                     document.getElementById("alertMessage").style.display = 'none';
                 },
                 1500);
         }

    }

    $scope.riderStockDetail = function (riderId) {

        var params = {rider_id:riderId, date: $scope.searchDate };
        $http.post($scope.riderStockDetailUrl, params, $scope.config)
        .success(function (data, status, headers, config) {
            if(data.status){
                $scope.riderStockDetailList = data.data.riderStockDetailList;
            }else{
                alert(angular.toJson(data.message));
            }
        })
        .error(function (data, status, header, config) {
            alert(data.message);
        });
    }

    $scope.dateChange = function () {
        var data = angular.toJson($scope.searchDate);
        $http.post($scope.searchUrl, data, $scope.config)
        .success(function (data, status, headers, config) {
            if(data.status){
                $scope.ridersListForGrid = data.data.ridersListForGrid;
                $scope.riderId = '';
                $scope.riderProductList = [];
            }else{
                alert(angular.toJson(data.message));
            }
        })
        .error(function (data, status, header, config) {
            alert(data.message);
        });
    }

    $scope.assignStockReset = function(rider){
        angular.forEach($scope.riderList , function(value , key){
            value.selectQuantity= '';
        });
    }

    $scope.changeRiderForGetTodayStock = function(riderID){
         var search_data = {
             riderID:riderID,
             currentDate:$scope.currentDate
         }
        $http.post($scope.getRiderDailyStockURl, search_data)
            .success(function (data, status, headers, config) {
                 $scope.riderDailyStock = data ;
            })
            .error(function (data, status, header, config) {
                alert(data.message);
            });
    }
    $scope.click_tab1 = function(){
        $scope.riderDailyStock = ''
    }

}]);

