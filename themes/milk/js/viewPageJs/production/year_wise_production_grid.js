/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('riderDailyStockGridModule', ['angularjs-datetime-picker','ngSanitize', 'toggle-switch']);
app.controller('manageZone', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (allow_delete , zoneList, todaydate , saveNewZoneURL ,editZoneURL , deleteURL) {

        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";


        $scope.allow_delete = allow_delete;

        $scope.todaydate = todaydate ;
        $scope.saveNewZoneURL = saveNewZoneURL ;
        $scope.editZoneURL = editZoneURL ;
        $scope.deleteURL = deleteURL ;
        $scope.zoneObject = {'name':'' , 'companyBranch':'1', 'is_active':'1' ,'is_deleted':'1'}



        $scope.search = '';
        $scope.searchBar = ''
        $scope.switchStatus = true ;
        $scope.editMode = false ;
        $scope.sortReverse =true;
        $scope.orderBy ='number';
        $scope.searchCattleProduction($scope.todaydate);
    }

     $scope.edit_mode_function = function(){
         $scope.editMode = true;
     }
    $scope.mouseoveronimg = function(selectImg){
       $scope.showAddNewZone =true;
       $scope.selectImg = selectImg;

    }

    $scope.closeImg = function(){
       $scope.showAddNewZone =false;
    }

   $scope.editProduct = function(zone){
       zone.update = true ;
   }






    $scope.saveProduction = function () {


          var sendData = {
              production : $scope.zoneList,
              date : $scope.todaydate
          };

        $http.post($scope.saveNewZoneURL+'_saveproduction', sendData)
            .success(function (data, status, headers, config) {

                $scope.editMode = false;

            })
            .error(function (data, status, header, config) {
                alert(data.message);
                subject.showLoader = false;
            });
    }

    $scope.searchCattleProduction = function (today) {


        $http.post($scope.saveNewZoneURL+'_year_wise_production', today)
            .success(function (data, status, headers, config) {
                $scope.zoneList = data;



            })
            .error(function (data, status, header, config) {
                alert(data.message);
                subject.showLoader = false;
            });
    }
    $scope.cangeValue  = function () {
        $scope.morning =0;
        $scope.afternoun =0;
        $scope.evenining =0;

        angular.forEach($scope.zoneList ,function (value ,key) {
            $scope.morning = $scope.morning + Number(value.morning);
            $scope.afternoun = $scope.afternoun + Number(value.afternoun);
            $scope.evenining = $scope.evenining + Number(value.evenining);

            value.total = Number(value.morning) + Number(value.afternoun)+ Number(value.evenining);
          

        });
        $scope.grandTotal = Number($scope.morning) +Number($scope.afternoun) +Number( $scope.evenining);
    }

    $scope.printProduction = function () {
        var innerContents = document.getElementById('printInvoice').innerHTML;
        var popupWinindow = window.open('', '_blank', 'width=1200px,height=1000px,scrollbars=no,menubar=no,toolbar=no,location=no,status=no,titlebar=no');
        popupWinindow.document.open();
        popupWinindow.document.write('<html><head><link rel="stylesheet" type="text/css" href="style.css" /></head><body onload="window.print()">' + innerContents + '</html>');
        popupWinindow.document.close();
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

