/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('riderDailyStockGridModule', []);
app.controller('manageZone', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (allow_delete, clientComplainCount , statusList , clientComplainList, saveStatusURL ,
                            nextPageForpaginationURL , searchComplainURL ,totalComplainOfOneCustomerURL , getComplainTypeURL) {



        $scope.allow_delete = allow_delete ;
        $scope.pageSize = 10;
        $scope.clientComplainCount = clientComplainCount ;

        $scope.totalPages = Math.ceil(clientComplainCount/ $scope.pageSize);
        $scope.statusList = statusList ;
        $scope.clientComplainList = clientComplainList;
        $scope.saveStatusURL = saveStatusURL ;
        $scope.nextPageForpaginationURL = nextPageForpaginationURL ;
        $scope.searchComplainURL = searchComplainURL ;
        $scope.totalComplainOfOneCustomerURL  = totalComplainOfOneCustomerURL ;
        $scope.getComplainTypeURL = getComplainTypeURL ;

        $scope.temporyClientComplain =  $scope.clientComplainList ;

        $scope.search = '';
        $scope.hideAndShowPagination = true;

        $scope.viewresultComplain = false ;

        $scope.complainType = '1';
        $scope.status_id = '0';

         $scope.imageDataLoader = false ;

        $scope.changeComplaintype(1);
    }




    $scope.addnewZone = function(){
        $scope.showAddNewZone = !$scope.showAddNewZone;
        $scope.zoneObject = {'name':'' , 'companyBranch':'1', 'is_active':'1' ,'is_deleted':'1'}
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

    $scope.complainDetail = function(ComplainObject){
       //     alert(angular.toJson(ComplainObject.client_id));

        $http.post($scope.totalComplainOfOneCustomerURL , ComplainObject.client_id)
            .success(function(responceData , status) {
                      $scope.countComplain =  responceData.count ;
                      $scope.resultComplain =  responceData.result ;


            })
            .error(function (data , status) {

            })
        $scope.ComplainObject = ComplainObject ;
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
        $scope.zoneObject = {'name':'' , 'companyBranch':'1', 'is_active':'1' ,'is_deleted':'1'}
    }

    $scope.saveStatus = function(statusObject){
          var sendData = {
              'statusObject': statusObject ,
               'page' : $scope.curPage ,

          }
          var  data = angular.toJson(sendData);
        $http.post($scope.saveStatusURL , data)
            .success(function (data, status, headers, config) {
                   if(data.success){

                       $scope.clientComplainList = data.message ;
                       $scope.showEditZone = !$scope.showEditZone;

                   }

            })
            .error(function (data, status, header, config) {
                alert(data.message);
                subject.showLoader = false;
            });
    }
    $scope.okComplainDetail = function(){
        $scope.showEditZone = !$scope.showEditZone;

    }

    $scope.nextPagePagination = function(page) {

        $http.post($scope.nextPageForpaginationURL , page)
            .success(function(responceData , status) {

                $scope.clientComplainList = responceData ;
                $scope.temporyClientComplain =  responceData ;

            })
            .error(function (data , status) {

            })
    }

    $scope.searchComplainFunction = function(page) {

        $http.post($scope.searchComplainURL , page)
            .success(function(responceData , status) {

                $scope.clientComplainList = responceData ;
                $scope.temporaryProductList =  responceData ;

            })
            .error(function (data , status) {

            })
    }

    $scope.changeClientComplain = function(search) {
        if(!search){

            $scope.clientComplainList = $scope.temporyClientComplain ;
        }
    }
    $scope.complainTypeDelate = function () {

    }
    $scope.showAllComplain=function () {
        $scope.viewresultComplain = !$scope.viewresultComplain ;
    }

    $scope.changeDateFormate = function (y) {

        function addZero(i) {
            if (i < 10) {
                i = "0" + i;
            }
            return i;
        }

        var d = new Date(y);
        if(addZero(d.getDate())){
            var selectYear = addZero(d.getFullYear());
            var month = addZero(d.getMonth()+1);
            var date = addZero(d.getDate());
            var h = addZero(d.getHours());
            var m = addZero(d.getMinutes());
            var s = addZero(d.getSeconds());
            var selectedDate  = date + '-' + month + '-' + selectYear+"\t\t\t"+ h + ":" + m ;
        }else {
            var selectedDate = '' ;
        }


        return selectedDate
    }

    $scope.changeComplaintype = function(page){
          var sendData = {
              'type': $scope.complainType ,
               'page': page,
               'status_id': $scope.status_id
          }
        if($scope.complainType == 1){
            $scope.selectedTypeComplain = 'Total Complains';
        }else {
            $scope.selectedTypeComplain = 'Total Suggestions';
        }
        $scope.curPage = page;
           $scope.imageDataLoader = true ;
           $http.post($scope.getComplainTypeURL  , sendData)
               .success(function (responceData , status) {
                   $scope.clientComplainList = responceData.data;
                   $scope.clientComplainCount = responceData.totalRecord ;
                   $scope.totalPages = Math.ceil($scope.clientComplainCount/ $scope.pageSize);

                   document.getElementById("testContainer").style.display = "block";
                   document.getElementById("loaderImage").style.display = "none";
                   $scope.imageDataLoader = false;
               })
               .error(function (responceData , status) {

               })

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

