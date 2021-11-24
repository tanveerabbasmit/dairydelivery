/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('riderDailyStockGridModule', ['angularjs-datetime-picker']);
app.controller('manageZone', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (todayDate ,allow_delete , zoneList, cattle_id , saveNewZoneURL ,editZoneURL , deleteURL) {

        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";

        $scope.todayDate =todayDate ;
        $scope.allow_delete = allow_delete;
        $scope.zoneList = zoneList;
        $scope.cattle_id = cattle_id ;
        $scope.saveNewZoneURL = saveNewZoneURL ;
        $scope.editZoneURL = editZoneURL ;
        $scope.deleteURL = deleteURL ;
        $scope.zoneObject = {'name':'' , 'companyBranch':'1', 'is_active':'1' ,'is_deleted':'1'}



        $scope.search = '';
        $scope.searchBar = ''
        $scope.switchStatus = true ;
    }

    $scope.mouseoveronimg = function(selectImg){
       $scope.showAddNewZone =true;
       $scope.selectImg = selectImg;

    }

    $scope.closeImg = function(){
       $scope.showAddNewZone =false;


    }



    $scope.addnewZone = function(){
        $scope.showAddNewZone = !$scope.showAddNewZone;
        $scope.zoneObject = {'name':'' ,'date':'$scope.todayDate' ,'companyBranch':'1', 'is_active':'1' ,'is_deleted':'1'}
    };


    $scope.saveZone = function (saveZone) {
             var sendData = angular.toJson(saveZone);
        $http.post($scope.saveNewZoneURL, sendData)
            .success(function (data, status, headers, config) {
                if(data.success){
                    saveZone.zone_id=data.zone_id;
                    $scope.zoneList.push(data.zone[0]);
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
        $scope.zoneObject = {'name':'' ,'date':'$scope.todayDate' , 'companyBranch':'1', 'is_active':'1' ,'is_deleted':'1'}
    }

}]);

app.directive('modal', function () {
    return {
        template: '<div class="modal fade">' +
        '<div class="modal-dialog">' +
        '<div   style="text-align: center">' +

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

