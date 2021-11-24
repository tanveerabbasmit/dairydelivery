/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var riderDailyStockGridModule = angular.module('riderDailyStockGridModule', ['angularjs-datetime-picker']);
riderDailyStockGridModule.controller('riderDailyStockGridCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter , $timeout) {
    //INITIALIZATION ===============================================================
    $scope.init = function (selectDate ,lableObject ,productList , todayData , riderList , getDialyDeliveryCustomerURL ,googleMapURL , saveDeliveryURL) {
         
         document.getElementById("testContainer").style.display = "block";
         document.getElementById("loaderImage").style.display = "none";
         $scope.lableObject = lableObject ;
         $scope.productList = productList ;
         $scope.todayData = todayData.report_data ;
         $scope.end_total_sum = todayData.end_total_sum ;
         $scope.riderList= riderList;
         $scope.getDialyDeliveryCustomerURL = getDialyDeliveryCustomerURL ;
         $scope.selectRiderID ='0';
         $scope.product_id ='0';
         $scope.deliveredQuantityShowDive = false;
         $scope.imageLoading = false ;
         $scope.googleMapURL = googleMapURL ;
         var date = new Date();
         date.setDate(date.getDate());
         var selectYear = date.getFullYear() ;
          var month = date.getMonth()+1;
        var date = date.getDate();
        if(month<10){
            month = '0'+month
        }
        if(date<10){
            date = '0'+date
        }

        $scope.startDate = selectDate.start_date;
        $scope.endDate = selectDate.end_date;



        $scope.saveDeliveryURL = saveDeliveryURL ;

       // $scope.selectRiderOnChange();



    }

     $scope.changeRider = function (riderID) {

         $http.post($scope.getDialyDeliveryCustomerURL, riderID)
             .success(function (data, status, headers, config) {

             })
             .error(function (data, status, header, config) {

             });

     }

     $scope.selectRiderOnChange = function(productID){



         if($scope.product_id=='0'){
            alert('Select first product');
         }else {
              var data = {
                  'startDate' : $scope.startDate ,
                  'endDate' : $scope.endDate ,
                  'RiderID'  :$scope.selectRiderID,
                  'product_id'  :$scope.product_id
              }
              var RiderObject = angular.toJson(data);

             $scope.imageLoading = true ;
             $http.post($scope.getDialyDeliveryCustomerURL ,RiderObject)
                 .success(function(reponseData , status ){


                     $scope.todayData = reponseData.report_data ;
                     $scope.end_total_sum = reponseData.end_total_sum ;
                     $scope.imageLoading = false ;

                     $scope.getShopData();
                 })
                 .error(function () {
                 });
         }
     }
     $scope.getShopData = function(){

         var data = {
             'startDate' : $scope.startDate ,
             'endDate' : $scope.endDate ,
             'RiderID'  :$scope.selectRiderID
         }
         var RiderObject = angular.toJson(data);

         $http.post($scope.getDialyDeliveryCustomerURL+"_shop_data" ,RiderObject)
             .success(function(reponseData , status ){

                $scope.shop_sale_data =  reponseData ;

             })
             .error(function () {
             });

     }

     $scope.checkObjectlenght = function (x , y ) {
         var itemsLength = Object.keys(x).length;
           if(itemsLength == 0){
              return false
           }else {
               return true ;
           }
     }


    $scope.showMap=function (lat,long){
        if(long.length == '0'  || long.length =='0' ){
            alert("Latitude And Longitude field Are required");
        }else{
            window.open($scope.googleMapURL+"?lat="+lat+"&lon="+long, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=0,left=0,width=800,height=600");
        }

    }

    $scope.setCompanyLimit = function(regularOrderList){
        regularOrderList.updateMode = true ;
    }
    $scope.SaveDelivery = function(regularOrderList) {
        regularOrderList.makeDeliveryLoader = true ;
          var productObject = {
              'price':regularOrderList.price
          }

         var sendData = {

             'client_id':regularOrderList.client_id,
             'company_branch_id':$scope.company_id,
              'rider_id' :$scope.selectRiderID,
              'selectDate' :$scope.todate,
              'deliveredQuantity' :regularOrderList.deliveredQuantity,
               'lat':'0' ,
               'longi':'0' ,
               'broken':'0' ,
               'perfect':'0' ,
                'data':[regularOrderList]
         }

        $http.post($scope.saveDeliveryURL ,sendData)
            .success(function(reponseData , status ){
                  if(reponseData.success){
                     regularOrderList.updateMode = false;
                      regularOrderList.makeDeliveryLoader = false ;
                      regularOrderList.deliveredQuantity = regularOrderList.quantity;
                      regularOrderList.time = reponseData.delivery_time;
                      regularOrderList.reject_delivery = false ;
                  }else {
                      alert(reponseData.message);

                      regularOrderList.updateMode = false;
                      regularOrderList.makeDeliveryLoader = false ;
                  }


            })
            .error(function () {
            });



    }
    $scope.closeDelivery = function(regularOrderList) {
        regularOrderList.updateMode = false;
    }

    $scope.print_function_medicineusage_report_view = function () {


        var innerContents = document.getElementById('printForm').innerHTML;
        var popupWinindow = window.open('', '_blank', 'width=1200px,height=1000px,scrollbars=no,menubar=no,toolbar=no,location=no,status=no,titlebar=no');
        popupWinindow.document.open();
        popupWinindow.document.write('<html><head><link rel="stylesheet" type="text/css" href="style.css" /></head><body onload="window.print()">' + innerContents + '</html>');
        popupWinindow.document.close();

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


