/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('clintManagemaent', ['angularjs-datetime-picker']);
app.controller('clintManagemaent', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function(data ,base_url){


        $scope.product_list = data.product_list;


        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";

        $scope.SelectedCustomer = 'Select Cutomer'
        $scope.base_url = base_url;
        $scope.client_id ='0';
        $scope.product_id ='0';
        $scope.getAllCustomerList();

        $scope.selected_discount_list =[];
    }






   $scope.getAllCustomerList = function() {
       $http.post($scope.base_url+'_customer_lis' ,$scope.paymentObject)
           .success(function(responce){
               $scope.clientList = responce ;
               $scope.loadClientLoader = false;
           })
           .error(function (responce) {
           });
   }

    $scope.abcd = function (y) {



        if($scope.product_id=='0'){
           alert('Plz select product');
        }else{

            $scope.SelectedCustomer = y.fullname;
            $scope.client_id = y.client_id;

             $scope.get_today_schedual_function(y.client_id);
            // $scope.getcheckAccountBalnce(y.client_id);

            $scope.cell_no_1 = y.cell_no_1;
            $scope.address = y.address;
            $scope.zone_name = y.zone_name;
        }


    }
    $scope.get_today_schedual_function_button = function(){
        $scope.get_today_schedual_function($scope.client_id);
    }

    $scope.get_today_schedual_function = function (client_id) {

        var send_data = {
            'client_id' : client_id,
            'product_id' :$scope.product_id

        }

        $http.post($scope.base_url+'_schedule_list' ,send_data)
            .success(function(responce){
               $scope.schedule_data  = responce;
            })
            .error(function (responce) {
            });
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

