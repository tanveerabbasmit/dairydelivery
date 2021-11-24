/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('riderDailyStockGridModule', []);
app.controller('manageZone', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (posShopList , allow_delete ,ZoneList , riderList, saveNewRiderURL ,
                            editRiderUrl , deleteURL , getZoneAgainstRider,checkDuplicateRiderUseNameURL) {
        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";

        $scope.posShopList = posShopList;
        $scope.allow_delete = allow_delete;
            //  alert(allow_delete);
        $scope.riderList = riderList;
        $scope.saveNewRiderURL = saveNewRiderURL ;
        $scope.editRiderUrl = editRiderUrl ;
        $scope.ZoneList  = ZoneList ;
        $scope.deleteURL = deleteURL ;
        $scope.getZoneAgainstRider = getZoneAgainstRider ;

        $scope.checkDuplicateRiderUseNameURL = checkDuplicateRiderUseNameURL ;

         $scope.riderObject = {'fullname':'' ,'userName':'' ,'password':'' , 'father_name':'', 'address':'','cnic':'' ,'cell_no_1':'','cell_no_2':'','residence_phone_no':'','email':'','pos_shop_id':'0','is_active':'1','is_deleted':'1','can_add_payment':'1'}
        $scope.temporaryRiderObject = $scope.riderObject ;
        $scope.search = ''
        $scope.searchBar = ''

        angular.forEach($scope.ZoneList , function(value ,key){
            value.isselected = false ;
        });

        $scope.riderUserNameCheck = false ;

    }




    $scope.addnewRider = function(){

        angular.forEach($scope.ZoneList , function(value ,key){
            value.isselected = false ;
        });
         $scope.riderObject  =  $scope.temporaryRiderObject  ;
        $scope.riderObject = {'fullname':'' , 'father_name':'', 'address':'','cnic':'' ,'cell_no_1':'','cell_no_2':'','residence_phone_no':'','email':'','is_active':'1','is_deleted':'1' ,'zone' : $scope.ZoneList ,'can_add_payment':'1','pos_shop_id':'0','show_customers_in_app':'1'}
        $scope.showAddNewRider = !$scope.showAddNewRider;

    };


    $scope.saveRider = function (object) {

        var sendData = angular.toJson(object);

        $http.post($scope.saveNewRiderURL, sendData)
            .success(function (data, status, headers, config) {
                if(data.success){

                    $scope.riderList.push(data.rider[0]);
                    $scope.showAddNewRider = !$scope.showAddNewRider;

                }else{
                    alert(angular.toJson(data.message));
                }
            })
            .error(function (data, status, header, config) {
                alert(data.message);
                subject.showLoader = false;
            });
    }

    $scope.editRider = function(rider){

        $scope.riderUserNameCheck = false ;

        $http.post($scope.getZoneAgainstRider, rider.rider_id)
            .success(function (data, status, headers, config) {
                $scope.showEditRider = !$scope.showEditRider;
                angular.forEach($scope.ZoneList , function(value ,key){
                    value.isselected = false ;
                });

                  angular.forEach($scope.ZoneList , function(value1 , key){
                      angular.forEach(data , function(value2 , key){
                           if(value1.zone_id == value2.zone_id){
                               value1.isselected = true ;

                           }
                      })
                  });

                rider.zone = $scope.ZoneList ;
                $scope.riderObject = rider;

            })
            .error(function (data, status, header, config) {
                alert(data.message);
                subject.showLoader = false;
            });



    };



    $scope.editRiderFunction = function (object) {

        var sendData = angular.toJson(object);

        $http.post($scope.editRiderUrl, sendData)
            .success(function (data, status, headers, config) {
                if(data.success){

                    $scope.showEditRider = !$scope.showEditRider;

                }else{
                    alert(angular.toJson(data.message));
                }
            })
            .error(function (data, status, header, config) {
                alert(data.message);
                subject.showLoader = false;
            });
    }

    $scope.delete = function (object) {

        var sendData = angular.toJson(object);
        var r = confirm("Are you sure?");
        if (r == true) {
            $http.post($scope.deleteURL , sendData)
                .success(function (data, status, headers, config) {
                    if(data.success){
                        //  object.is_deleted = 1;
                        var index = $scope.riderList.indexOf(object);
                        $scope.riderList.splice(index, 1);

                    }else{
                        alert("Cannot delete or update a parent row");
                    }
                })
                .error(function (data, status, header, config) {
                    alert(data.message);
                    subject.showLoader = false;
                });
        }

    }

    $scope.searchBarFunction = function(search){
      $scope.search = search ;

    }
    $scope.changebarSearch = function(search){
        if(!search){
        $scope.search = '';
        }
    }

    $scope.resetObject = function () {

        angular.forEach($scope.riderObject.zone , function(value ,key){
        value.isselected = false ;
        });
        $scope.riderObject = {'fullname':'' , 'father_name':'', 'address':'','cnic':'' ,'cell_no_1':'','cell_no_2':'','residence_phone_no':'','email':'','is_active':'1','is_deleted':'1' ,'zone' : $scope.ZoneList ,'can_add_payment':'1'}
    }
    $scope.changeRiderUserName = function(userName ,id){

           var data = {
               userName :userName,
                rider_id :id
           }

        $http.post($scope.checkDuplicateRiderUseNameURL ,data )
            .success(function (responceData ,status) {
                $scope.riderUserNameCheck = false ;
                if(responceData){
                    $scope.riderUserNameCheck = true ;
                }

            })
            .error(function(responce , status) {

            });
    }


}]);

app.directive('modal', function () {
    return {
        template: '<div class="modal fade">' +
        '<div class="modal-dialog">' +
        '<div class="modal-content"  style="width: 70% ; margin-left: 25% ; margin-top: 0% ">' +
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

