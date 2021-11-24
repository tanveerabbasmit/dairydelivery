/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('clintManagemaent', ['angularjs-datetime-picker']);
app.controller('clintManagemaent', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function(productList ,todayDate ,allow_delete ,listResult , qualityreport , saveQuanlityReportUrl ){

         document.getElementById("testContainer").style.display = "block";
         document.getElementById("loaderImage").style.display = "none";
         $scope.allow_delete = allow_delete ;
         $scope.qualityreport = qualityreport ;
         $scope.saveQuanlityReportUrl = saveQuanlityReportUrl;
         $scope.listResult = listResult ;
         $scope.todayDate = todayDate ;
         $scope.productList = productList ;
         $scope.animal_type = '0';

    }

    $scope.chageValue = function(){
       // alert("mesba");
    }
    $scope.selectOneDateData = function(){
        if($scope.animal_type =='0'){
            alert("First Select Product");
        }else {
            $scope.imageLoading = true  ;
            var send_data = {
                animal_type:$scope.animal_type,
                todayDate : $scope.todayDate ,
            }
            $http.post($scope.saveQuanlityReportUrl+"_selectData" ,send_data)
                .success(function(responce){
                    $scope.imageLoading = false ;
                    $scope.qualityreport = responce;
                })
                .error(function (responce) {


                });
        }

    }

   $scope.saveQualityReport = function() {

       if($scope.animal_type =='0'){
           alert("First Select Product");
       }else {

           $scope.imageLoader = true  ;

           var send_data = {
               animal_type:$scope.animal_type,
               todayDate : $scope.todayDate ,
               qualityreport : $scope.qualityreport

           }
           $http.post($scope.saveQuanlityReportUrl ,send_data)
               .success(function(responce){
                   $scope.imageLoader = false ;
                   $scope.listResult = responce;
               })
               .error(function (responce) {

               });

       }



   }
    $scope.abcd = function (y) {

        $scope.SelectedCustomer = y.fullname;
        $scope.paymentObject.client_id = y.client_id;

        $scope.getOneCustomerPaymentList(y.client_id);
    }
    $scope.getOneCustomerPaymentList = function (client_id) {


        $http.post($scope.oneCustomerAmontListURL ,client_id )
            .success(function(responce){
               $scope.OneCustomerPaymentData = responce ;

            })
            .error(function (responce) {

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

