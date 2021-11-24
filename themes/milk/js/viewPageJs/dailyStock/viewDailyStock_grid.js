/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var DailyStockGridModule = angular.module('DailyStockGridModule', ['angularjs-datetime-picker']);
DailyStockGridModule.controller('DailyStockGridCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (data, saveUrl, searchUrl, saveNewStockUrl, stockDetailUrl, deleteStockUrl) {

        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";

        $scope.riderList = data.riderList ;
        $scope.productList = data.productList ;
        $scope.dailyStockList = data.dailyStockList;
        $scope.newProductList = data.productList;
        $scope.productList = data.productList;
        $scope.currentData = data.currentData;
        $scope.searchDate = data.currentData;
        $scope.stockDetailList = [];
        $scope.saveUrl = saveUrl;
        $scope.searchUrl = searchUrl;
        $scope.saveNewStockUrl = saveNewStockUrl;
        $scope.stockDetailUrl = stockDetailUrl;
        $scope.deleteStockUrl = deleteStockUrl;
        $scope.productName = '';

        $scope.tab = true ;

        $scope.showLoaderImage = false ;
        $scope.selectProductID = $scope.productList[0].product_id ;

        angular.forEach($scope.riderList , function(value , key){
            $scope.selectQuantity='12';
        });


    }

    $scope.addNewStock = function () {
        $scope.productList = angular.copy($scope.newProductList);
    }

    $scope.updateStock = function (stockObj) {
        /*$scope.stockModel = stockObj;*/
        stockObj.updateMode= true;
    }

    /*$scope.saveUpdateStock = function (stockObj) {
        $scope.stockModel = stockObj;
        stockObj.updateMode = false;
    }*/

    $scope.deleteStock = function (stockObj) {
        var data = angular.toJson(stockObj);
        $http.post($scope.deleteStockUrl, data, $scope.config)
        .success(function (data, status, headers, config) {
            if(data.status){
                $scope.stockDetailList = data.data.stockDetailList;
                $scope.dailyStockList = data.data.dailyStockList;
            }else{
                alert(angular.toJson(data.message));
            }
        })
        .error(function (data, status, header, config) {
            alert(data.message);
        });
    }

    $scope.saveNewStock = function () {
        $scope.showLoaderImage = true ;
        var data = angular.toJson($scope.productList);

        var sendData ={
            product : $scope.productList,
            date  : $scope.searchDate
        };

        $http.post($scope.saveNewStockUrl, sendData, $scope.config)
        .success(function (data, status, headers, config) {
            if(data.status){
                $scope.dailyStockList = data.data.dailyStockList;
                angular.forEach($scope.productList , function (value , key) {
                    value.stockModel.quantity = '';
                    value.stockModel.description = '';
                });
                $scope.showLoaderImage = false ;
            }else{
                alert(angular.toJson(data.message));
            }
        })
        .error(function (data, status, header, config) {
            alert(data.message);
        });
    }
    $scope.saveNewStockReset = function () {
        var data = angular.toJson($scope.productList);
        alert(data);
        angular.forEach($scope.productList , function (value , key) {
            value.stockModel.quantity = '';
            value.stockModel.description = '';
        });
    }

    $scope.saveUpdateStock = function (stockObj) {
        if (stockObj.description==undefined || stockObj.quantity==undefined || stockObj.quantity == 0 || stockObj.return_quantity==undefined ) {
            if (parseInt(stockObj.quantity) < parseInt(stockObj.return_quantity)) {
                alert('Return stock quantity cannot be exceed from avalaible stock.');
            } else {
                alert('All fields are required');
            }
            return false;
        } 
        var data = angular.toJson(stockObj);
        $http.post($scope.saveUrl, data, $scope.config)
        .success(function (data, status, headers, config) {
            if(data.status){
                stockObj.updateMode = false;
                $scope.dailyStockList = data.data.dailyStockList;
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
                $scope.dailyStockList = data.data.dailyStockList;
            }else{
                alert(angular.toJson(data.message));
            }
        })
        .error(function (data, status, header, config) {
            alert(data.message);
        });
    }

    $scope.stockDetail = function (stockObj) {
        var data = angular.toJson(stockObj);
        $http.post($scope.stockDetailUrl, data, $scope.config)
        .success(function (data, status, headers, config) {
            if(data.status){
                $scope.productName = stockObj.product_name;
                $scope.stockDetailList = data.data.stockDetailList;
            }else{
                alert(angular.toJson(data.message));
            }
        })
        .error(function (data, status, header, config) {
            alert(data.message);
        });
    }

    $scope.assignStockReset = function(riderStock){
      angular.forEach()
    }

}]);

