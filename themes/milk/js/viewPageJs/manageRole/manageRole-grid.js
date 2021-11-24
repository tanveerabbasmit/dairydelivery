/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('riderDailyStockGridModule', []);
app.controller('manageZone', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (allow_delete,menuList , assignRole, saveNewRoleURL ,editRoleULR , deleteURL , getAssignRoleManuURL ,changeRoleURL) {

        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";
        $scope.allow_delete = allow_delete ;
        $scope.menuList = menuList ;
        $scope.assignRole = assignRole;
        $scope.saveNewRoleURL = saveNewRoleURL ;
        $scope.editRoleULR = editRoleULR ;
        $scope.deleteURL = deleteURL ;
         $scope.getAssignRoleManuURL = getAssignRoleManuURL ;
         $scope.changeRoleURL = changeRoleURL ;

        $scope.search = '';
        $scope.searchBar = '';

        $scope.saveCrudList = false ;



        angular.forEach($scope.menuList ,function (value , key) {
             value.assignTo = false;
        })


    }




    $scope.addnewZone = function(){
        $scope.showAddNewZone = !$scope.showAddNewZone;
        $scope.zoneObject = {'name':'' , 'key':'' }
    };


    $scope.assignRoleFunction = function(role){

        $scope.saveCrudList = true ;
        angular.forEach($scope.menuList ,function (value , key) {
            value.assignTo = false;
        })
          $scope.SelectRoleID = role.role_id;
        $http.post($scope.getAssignRoleManuURL , role)
            .success(function (data, status, headers, config) {


                $scope.menuList = data ;
                $scope.saveCrudList = false ;


                $scope.popUPassignRole = !$scope.popUPassignRole;
            })
            .error(function (data, status, header, config) {
            });
    };


    $scope.saveAssignMenuFunction = function(assignRole){

        $scope.saveCrudList = true ;
           var data = {
                'role_id':$scope.SelectRoleID ,
                 'data' : assignRole
            }
            var  sendData = data ;
        $http.post($scope.changeRoleURL , sendData)
            .success(function (data, status, headers, config) {

                $scope.saveCrudList = false ;

                $scope.popUPassignRole = !$scope.popUPassignRole;

            })
            .error(function (data, status, header, config) {
            });

    }



    $scope.editCrud = function(assignRole,module_action_role_id){

            var data = {
                'crud':assignRole ,
                'module_action_role_id' : module_action_role_id
            }
            var  sendData = data ;

           $http.post($scope.changeRoleURL+'save_Crud' , sendData)
            .success(function (data, status, headers, config) {
                $scope.popUPassignRole = !$scope.popUPassignRole;
            })
            .error(function (data, status, header, config) {
            });

    }


    $scope.saveZone = function (saveZone) {
             var sendData = angular.toJson(saveZone);

        $http.post($scope.saveNewRoleURL, sendData)
            .success(function (data, status, headers, config) {
                if(data.success){

                    $scope.assignRole.push(data.zone[0]);
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
        $http.post($scope.editRoleULR, sendData)
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

                    var index = $scope.assignRole.indexOf(saveZone);
                    $scope.assignRole.splice(index, 1);

                }else{
                    alert(angular.toJson(data.message));
                }

            })
            .error(function (data, status, header, config) {
                alert("You can't delete this Role");
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

