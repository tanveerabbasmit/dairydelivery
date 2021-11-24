/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('riderDailyStockGridModule', ['angularjs-datetime-picker']);
app.controller('manageZone', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (date , baseURL) {

       $scope.baseURL =  baseURL;
       document.getElementById("testContainer").style.display = "block";
       document.getElementById("loaderImage").style.display = "none";

        $scope.loading = false ;

        $scope.getMessage();
    }




    $scope.getMessage = function () {
        $http.post($scope.baseURL+"_get_message")
            .success(function (data, status, headers, config) {
                 $scope.messageObject =data.messageObject ;
                 $scope.company =data.result ;
                angular.forEach($scope.company ,function (value ,key) {
                     if(value.company_selected){
                         value.check_company = true
                     }else {
                         value.check_company = false
                     }
                 });
            })
            .error(function (data, status, header, config) {
                alert("You can't save This Message");
            });
    }

    $scope.saveMessage =function(){

        var send_data = {
            messageObject:$scope.messageObject,
            company: $scope.company
        }

        $scope.loading = true ;
        $http.post($scope.baseURL+"_save_message" , send_data)
            .success(function (data, status, headers, config) {
                $scope.loading = false ;
            })
            .error(function (data, status, header, config) {
                alert("You can't save This Message");
            });
    }


}]);

app.directive('modal', function () {
    return {
        template: '<div class="modal fade">' +
            '<div class="modal-dialog">' +
            '<div class="modal-content"  style="width: 70% ; margin-left: 25% ; margin-top: 0%">' +
            '<div class="modal-header">' +
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

