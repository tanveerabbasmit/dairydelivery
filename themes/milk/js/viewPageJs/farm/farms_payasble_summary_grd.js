/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var riderDailyStockGridModule = angular.module('riderDailyStockGridModule', ['angularjs-datetime-picker']);
riderDailyStockGridModule.controller('riderDailyStockGridCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter , $timeout) {
    //INITIALIZATION ===============================================================
    $scope.init = function (data,base_url) {


         document.getElementById("testContainer").style.display = "block";
         document.getElementById("loaderImage").style.display = "none";
         $scope.startDate = data.five_day_ago;
         $scope.endDate = data.today_date;

         $scope.copany_object = data.copany_object;

         $scope.vendor_list = data.vendor_list;

         $scope.vendor_id = '0';
         $scope.base_url = base_url;



         $scope.selected_vendor ='';
        $scope.select_shop_ledger();
       //  $scope.select_shop_ledger();
    }


     $scope.vendor_name=function (){
        angular.forEach($scope.vendor_list, function(value ,key){
            if(value.vendor_id==$scope.vendor_id){
                $scope.selected_vendor = value.vendor_name;
            }
        });
     }

     $scope.select_shop_ledger = function(){

         if(false){
             alert('Select Vendor First');
         }else {
             var data = {
                 'startDate' : $scope.startDate ,
                 'endDate' : $scope.endDate ,
                 'vendor_id'  :$scope.vendor_id


             }
             $scope.vendor_name();
             // var RiderObject = angular.toJson(data);
             $scope.imageLoading = true ;
             $http.post($scope.base_url+"_get_farms_payasble_summary" ,data)
                 .success(function(responce , status ){
                     $scope.list_data =responce;

                     $scope.imageLoading = false;

                 })
                 .error(function () {
                 });
         }



     }




    $scope.printFunction =function(){

        var divToPrint=document.getElementById("printTalbe");
        newWin= window.open(divToPrint);
        newWin.document.write(divToPrint.outerHTML);
        newWin.print();
        newWin.close();
    }


}]);


riderDailyStockGridModule.directive('modal', function () {
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


