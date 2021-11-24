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
        $scope.zoneList = zoneList;

        angular.forEach($scope.zoneList ,function (value ,key) {
            value.update=false;
        })


        $scope.companyBranchList = companyBranchList ;
        $scope.saveNewZoneURL = saveNewZoneURL ;
        $scope.editZoneURL = editZoneURL ;
        $scope.deleteURL = deleteURL ;
        $scope.zoneObject = {'name':'' , 'companyBranch':'1', 'is_active':'1' ,'is_deleted':'1'}

        $scope.search = '';
        $scope.searchBar = ''
        $scope.switchStatus = true ;
    }




    $scope.addnewZone = function(){


        $scope.zoneList.push({"pos_shop_id":"0","shop_name":"","address":"","update":true});
    };


    $scope.editZone = function(zone){

        zone.update = true ;

    };
    $scope.saveZone =function(zone){


        $http.post($scope.saveNewZoneURL+"_save_new", zone)
            .success(function (data, status, headers, config) {
                if(data.success){
                    zone.tag_color_id=data.message;
                    zone.update=false;

                }else{
                    alert(angular.toJson(data.message));
                }

            })
            .error(function (data, status, header, config) {

            });
    }


    $scope.editZoneFunction = function (saveZone) {
        var sendData = angular.toJson(saveZone);
        $http.post($scope.editZoneURL, sendData)
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
        var r = confirm("Are you sure?");
        if (r == true) {
            var sendData = angular.toJson(saveZone);
            $http.post($scope.deleteURL, sendData)
                .success(function (data, status, headers, config) {
                    if(data.success){
                       // saveZone.is_deleted =1;
                        var index = $scope.zoneList.indexOf(saveZone);
                         $scope.zoneList.splice(index, 1);

                    }else{
                        alert("Cannot delete or update a parent row");
                    }

                })
                .error(function (data, status, header, config) {
                    alert("You can't delete this Product");
                });

        } else {

        }


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
        $scope.zoneObject = {'name':'' , 'companyBranch':'1', 'is_active':'1' ,'is_deleted':'1'}
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

