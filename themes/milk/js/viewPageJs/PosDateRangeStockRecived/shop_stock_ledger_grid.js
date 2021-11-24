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
         $scope.shop_list = data.shop_list;
         $scope.product_list = data.product_list;
         $scope.pos_shop_id = '0';
         $scope.product_id = '0';
         $scope.base_url = base_url;


       //  $scope.select_shop_ledger();
    }



     $scope.select_shop_ledger = function(){

         if($scope.pos_shop_id =='0' || $scope.product_id =='0'){
             alert('Select Shop and Product');
         }else {
             var data = {
                 'startDate' : $scope.startDate ,
                 'endDate' : $scope.endDate ,
                 'pos_shop_id'  :$scope.pos_shop_id,
                 'product_id'  :$scope.product_id

             }
             // var RiderObject = angular.toJson(data);
             $scope.imageLoading = true ;
             $http.post($scope.base_url+"_get_pos_shop_ledger" ,data)
                 .success(function(responce , status ){
                     $scope.list_data =responce.list;
                     $scope.imageLoading = false;

                 })
                 .error(function () {
                 });
         }



     }




    $scope.printFunction =function(){

        var divToPrint=document.getElementById("printTalbe");
        newWin= window.open("");
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


