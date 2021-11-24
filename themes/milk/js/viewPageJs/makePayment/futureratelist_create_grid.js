/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('clintManagemaent', ['angularjs-datetime-picker']);
app.controller('clintManagemaent', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (data){

        $scope.base_url =data.base_url;
        $scope.start_date =data.start_date;
        $scope.end_date =data.end_date;
        $scope.rate =data.rate;
        $scope.product_list =data.product_list;
        $scope.product_id ='';
        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";
        $scope.SelectedCustomer = 'Select Cutomer'
        $scope.getAllCustomerList();

    }

    $scope.abcd = function (y) {

        $scope.SelectedCustomer = y.fullname;

        $scope.new_select_client_id = y.client_id;

        $scope.cell_no_1 = y.cell_no_1;
        $scope.address = y.address;
        $scope.zone_name = y.zone_name;


        $scope.get_rate_list_function($scope.new_select_client_id);
    }
    $scope.getAllCustomerList = function(){
        $scope.loadClientLoader = true;
        $http.post( $scope.base_url+'_all_customer_list'  )
            .success(function(responce){
                $scope.clientList = responce ;
                $scope.loadClientLoader = false;
            })
            .error(function (responce) {
            });
    }
    $scope.save_future_rate_function =function(){
          $scope.imageLoader =true;

          var send_data ={
            'start_date': $scope.start_date,
             'end_date': $scope.end_date ,
             'rate': $scope.rate ,
             'product_id': $scope.product_id ,
              'client_id': $scope.new_select_client_id

          }
         if($scope.new_select_client_id){
             $http.post( $scope.base_url+'_save_future_rate',send_data  )
                 .success(function(responce){
                     $scope.imageLoader =false;
                     $scope.rate ='';
                     $scope.get_rate_list_function($scope.new_select_client_id );
                 })
                 .error(function (responce) {
                 });
         } else {
             alert('Select Customer First');
         }

    }
    $scope.get_rate_list_function =function (client_id){

        $http.post( $scope.base_url+'_rate_list_function',client_id )
            .success(function(responce){
                $scope.imageLoader =false;
                $scope.rate_list =responce;
            })
            .error(function (responce) {
            });
    }




}]);

app.directive('modal', function () {
    return {
        template: '<div class="modal fade">' +
            '<div class="modal-dialog">' +
            '<div class="modal-content"  style="width: 80% ; margin-left: 10% ; margin-top: 0%">' +
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

