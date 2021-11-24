/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('riderDailyStockGridModule', ['ngSanitize', 'toggle-switch']);
app.controller('manageZone', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (riderList , getClientListURL ,saveOrOrderByLstURL) {

        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";
        $scope.riderList = riderList;
        $scope.getClientListURL = getClientListURL ;
        $scope.saveOrOrderByLstURL = saveOrOrderByLstURL;


        $scope.imageLoading = false ;
       $scope.riderId ='0';
    }

    $scope.getRiderList = function (riderId) {
        $scope.imageLoading = true;
        $http.post($scope.getClientListURL, riderId)
            .success(function (data, status, headers, config) {
               $scope.CustomerList = data ;
                $scope.imageLoading = false;
            })
            .error(function (data, status, header, config) {

            });
    }
    $scope.orderCustomerList =[];
    $scope.selectCleint = function(customer){
         var index =  $scope.orderCustomerList.indexOf(customer);
          if(index < 0){
              $scope.orderCustomerList.push(customer);
          }


    }
    $scope.CancelObject = function(list){
        var index= $scope.orderCustomerList.indexOf(list);
        $scope.orderCustomerList.splice(index , 1);
    }
    $scope.SaveCustomerOrderList = function(list) {
        $http.post($scope.saveOrOrderByLstURL , list)
            .success(function(responce ){

            })
            .error(function(responce){

            });
    }


}]);

app.directive('modal', function () {
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

