/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('riderDailyStockGridModule', []);
app.controller('manageZone', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (posShopList ,allow_delete , rolList , userList , companyBranchList , saveNewZoneURL ,editZoneURL , deleteURL , checkAlredyExist , viewRoleURL) {

        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";
        $scope.posShopList = posShopList ;
        $scope.allow_delete = allow_delete ;
         $scope.rolList = rolList ;
        $scope.userList = userList;
        $scope.companyBranchList = companyBranchList ;
        $scope.saveNewZoneURL = saveNewZoneURL ;
        $scope.editZoneURL = editZoneURL ;
        $scope.deleteURL = deleteURL ;
        $scope.checkAlredyExist = checkAlredyExist ;
        $scope.viewRoleURL = viewRoleURL ;

         $scope.search = '';
        $scope.searchBar = ''
        $scope.checkUserNameAlredy = false;
    }




    $scope.addnewZone = function(){
        $scope.showAddNewZone = !$scope.showAddNewZone;
        $scope.zoneObject = {'full_name':'' , 'user_name':'','phone_number':'' , 'user_role_id':'','email':'' ,'pos_shop_id':'0', 'password':'', 'is_active':'1' ,'is_deleted':'0','allow_delete':'1'}
    };


    $scope.saveZone = function (saveZone) {
             var sendData = angular.toJson(saveZone);
        $http.post($scope.saveNewZoneURL, sendData)
            .success(function (data, status, headers, config) {
                if(data.success){

                    $scope.userList.push(data.zone[0]);
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
        var sendData = angular.toJson(saveZone);
        var r = confirm("Are you sure you want to delete?");
        if (r == true) {

            $http.post($scope.deleteURL, sendData)
                .success(function (data, status, headers, config) {
                    if(data.success){
                       // saveZone.is_deleted =1;
                        var index = $scope.zoneList.indexOf(saveZone);
                         $scope.zoneList.splice(index, 1);

                    }else{
                        alert(angular.toJson(data.message));
                    }

                })
                .error(function (data, status, header, config) {
                    alert("You can't delete this Product");
                });
        }

    }


    $scope.checkAlreadyExistFunction = function(name) {
        $http.post($scope.checkAlredyExist, name)
            .success(function (data, status, headers, config) {
                $scope.checkUserNameAlredy = false;
                if(data == 'yes'){
                    $scope.checkUserNameAlredy = true ;

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
        $scope.zoneObject = {'full_name':'' , 'user_name':'','phone_number':'' , 'user_role_id':'','email':'' , 'password':'','pos_shop_id':'0','is_active':'1' ,'is_deleted':'0'}
    }
    $scope.viewMenuName = function(roleID) {

        if(roleID == ''){
            alert("Select Role First");
        }else {

            $http.post($scope.viewRoleURL, roleID)
                .success(function (data, status, headers, config) {

                     $scope.menuList = data ;


                    $scope.showMenuList = !$scope.showMenuList;

                })
                .error(function (data, status, header, config) {
                    alert("You can't delete this Product");
                });

        }
    }
    $scope.add_rider =function (user){
        $scope.show_rider_right_model =true;
        $scope.selected_user_id = user.user_id;
        $http.post($scope.editZoneURL+"user_wisr_rider", user.user_id)
            .success(function (data, status, headers, config) {
                 $scope.riderlist =   data;
            })
            .error(function (data, status, header, config) {
                alert("You can't delete this Product");
            });
    }

    $scope.save_rider_right = function (){

        var send_date ={
           'riderlist' : $scope.riderlist,
           'user_id' : $scope.selected_user_id
        }
        $http.post($scope.editZoneURL+"user_wisr_ridr_save", send_date)
            .success(function (data, status, headers, config) {
               // $scope.riderlist =   data;
                $scope.show_rider_right_model =false;
            })
            .error(function (data, status, header, config) {
                alert("You can't delete this Product");
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

