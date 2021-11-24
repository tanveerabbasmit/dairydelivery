/**
 * Created by Muhammad.Imran on 4/1/2016.
 */

var app = angular.module('riderDailyStockGridModule', ['ngSanitize', 'toggle-switch']);
app.controller('manageZone', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (allow_delete , zoneList, companyBranchList , saveNewZoneURL ,editZoneURL , deleteURL) {


        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";
        $scope.allow_delete = allow_delete;
        $scope.account_list = zoneList;
        $scope.voucher_type = companyBranchList;

        $scope.baseUrl = saveNewZoneURL;




    }

    $scope.edit_account = function (list){
       list.update = true;
    }


   $scope.save_account_function = function (list){

        var send_data = {
           'account_list' :$scope.account_list,
           'list' :list
        }

       $http.post($scope.baseUrl+"save_account_of_voucher", send_data)
           .success(function (data, status, headers, config) {

               if(data.success){
                   list.update = false;
               }else {
                   alert(data.message);
               }

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

