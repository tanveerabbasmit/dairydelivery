/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('clintManagemaent', ['angularjs-datetime-picker']);
app.controller('clintManagemaent', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (data , base_url){





        document.getElementById("testContainer").style.display = "block";


        $scope.productList = data.product_list ;
        $scope.start_date = data.start_date ;
        $scope.end_date = data.end_date ;
        $scope.base_url = base_url ;





        try{
            $scope.product_id =    data.product_id+'';


        }catch(err) {

            alert(err);
        }


        $scope.getCustomerLedgerReportFunction();

    }

    $scope.getCustomerLedgerReportFunction = function(){




        if($scope.start_date >$scope.end_date ){
            alert("Start date should be greater then end date");
        }else {

           var data ={

                'start_date' :  $scope.start_date ,
                'end_date' : $scope.end_date,
                'product_id' : $scope.product_id
            }

             $scope.reportLoader =true;
            $http.post( $scope.base_url+"_stock_out_detail_list" , data)
                .success(function(responce , data){
                   $scope.main_list = responce.main_list;
                    $scope.reportLoader =false;
                })
                .error(function(responce , data){
                });
        }
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

