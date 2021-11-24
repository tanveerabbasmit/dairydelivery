/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('riderDailyStockGridModule', []);
app.controller('manageZone', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (allow_delete , ComplainTypeList , saveNewComplianURL , EditComplainURL , deleteComplainTypeURL) {

        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";


         $scope.allow_delete = allow_delete ;
        $scope.ComplainTypeList = ComplainTypeList;
        $scope.saveNewComplianURL = saveNewComplianURL ;
        $scope.EditComplainURL = EditComplainURL;
         $scope.deleteComplainTypeURL = deleteComplainTypeURL ;



        $scope.search = '';
        $scope.searchBar = ''
    }




    $scope.addnewZone = function(){
        $scope.showAddNewZone = !$scope.showAddNewZone;
        $scope.zoneObject = {'name':'' }
    };


    $scope.saveZone = function (saveZone) {
             var sendData = angular.toJson(saveZone);
        $http.post($scope.saveNewComplianURL, sendData)
            .success(function (data, status, headers, config) {

                if(data.success){

                    $scope.ComplainTypeList.push(data.zone[0]);
                    $scope.showAddNewZone = !$scope.showAddNewZone;
                }else{
                    alert(angular.toJson(data.message));
                }

            })
            .error(function (data, status, header, config) {
                alert(data.message);
                subject.showLoader = false;
            });
    }
    $scope.editZone = function(zone){
        $scope.zoneObject = zone ;
        $scope.showEditZone = !$scope.showEditZone;
    };


    $scope.editZoneFunction = function (saveZone) {
        var sendData = angular.toJson(saveZone);

        $http.post($scope.EditComplainURL, sendData)
            .success(function (data, status, headers, config) {
                if(data.success){
                    $scope.showEditZone = !$scope.showEditZone;
                }else{
                    alert(angular.toJson(data.message));
                }
            })
            .error(function (data, status, header, config) {
                alert(data.message);
                subject.showLoader = false;
            });
    }

    $scope.zoneDelete = function (saveZone) {
        var sendData = angular.toJson(saveZone);
        $http.post($scope.deleteURL, sendData)
            .success(function (data, status, headers, config) {
                if(data.success){
                    saveZone.is_deleted =1;
                    /*var index = $scope.zoneList.indexOf(saveZone);
                    $scope.zoneList.splice(index, 1);*/

                }else{
                    alert(angular.toJson(data.message));
                }

            })
            .error(function (data, status, header, config) {
                alert("You can't delete this Product");
            });
    }
    $scope.searchZone = function(search){
        $scope.search = search ;
    }
    $scope.searchBarOnzero = function (search) {
        if(!search){
            $scope.search = '' ;
        }
    }

    $scope.setReset = function() {

        $scope.zoneObject = {'name':'' }

    }

    $scope.complainTypeDelate = function(complainType){

        $http.post($scope.deleteComplainTypeURL, complainType.complain_type_id)
            .success(function (data, status, headers, config) {
                if(data.success){

                    var index = $scope.ComplainTypeList.indexOf(complainType);
                     $scope.ComplainTypeList.splice(index, 1);

                }else{
                    alert(angular.toJson(data.message));
                }

            })
            .error(function (data, status, header, config) {
                alert("You can't delete this complain Type");
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

