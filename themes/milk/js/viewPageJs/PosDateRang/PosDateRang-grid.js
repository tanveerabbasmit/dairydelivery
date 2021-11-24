/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var riderDailyStockGridModule = angular.module('riderDailyStockGridModule', ['angularjs-datetime-picker']);
riderDailyStockGridModule.controller('riderDailyStockGridCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter , $timeout) {
    //INITIALIZATION ===============================================================
    $scope.init = function (lableObject ,productList , todayData , riderList , getDialyDeliveryCustomerURL ,googleMapURL , saveDeliveryURL) {


        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";
         $scope.lableObject = lableObject ;
         $scope.productList = productList ;
         $scope.todayData = todayData.report_data ;
         $scope.end_total_sum = todayData.end_total_sum ;

         $scope.riderList= riderList;

          $scope.totalQuantitypos =0;
          $scope.totalAmountpos =0;
         angular.forEach( $scope.riderList ,function (value ,key) {
             $scope.totalQuantitypos =  Number($scope.totalQuantitypos)+ Number(value.quantity);
             $scope.totalAmountpos =  Number($scope.totalAmountpos)+ Number(value.total_price);
         });
         $scope.getDialyDeliveryCustomerURL = getDialyDeliveryCustomerURL ;
         $scope.selectRiderID ='0';
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
        $scope.todate = selectYear + '-' + month + '-' + date;

        $scope.selectRiderOnChange($scope.todate);

        $scope.saveDeliveryURL = saveDeliveryURL ;

    }

     $scope.changeRider = function (riderID) {

         $http.post($scope.getDialyDeliveryCustomerURL, riderID)
             .success(function (data, status, headers, config) {

             })
             .error(function (data, status, header, config) {

             });

     }

     $scope.selectRiderOnChange = function(today){


         $scope.imageLoading = true ;
         $http.post($scope.getDialyDeliveryCustomerURL ,today)
             .success(function(reponseData , status ){
                 $scope.riderList= reponseData;
                 $scope.totalQuantitypos =0;
                 $scope.totalAmountpos =0;
                 angular.forEach( $scope.riderList ,function (value ,key) {
                     $scope.totalQuantitypos =  Number($scope.totalQuantitypos)+ Number(value.quantity);
                     $scope.totalAmountpos =  Number($scope.totalAmountpos)+ Number(value.total_price);
                     value.update = false;
                 });
                 $scope.imageLoading = false ;
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


    $scope.delete_sale_report = function (object) {

        $http.post($scope.saveDeliveryURL+"_delete_pos_id" ,object.pos_id)
            .success(function(reponseData , status ){

                $scope.selectRiderOnChange($scope.todate);

            })
            .error(function () {
            });
    }

    $scope.edit_pos = function (data) {
      data.update =true;
    }
    $scope.update_pos_function=function (data) {

        var txt;

        var code = prompt("Please enter code:", "");

        data.code = code;
        
        $http.post($scope.saveDeliveryURL+"_update_pos" ,data)
            .success(function(responce , status ){
                if(responce.success){
                    $scope.selectRiderOnChange($scope.todate);
                }else {
                    alert(responce.message);
                }



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


