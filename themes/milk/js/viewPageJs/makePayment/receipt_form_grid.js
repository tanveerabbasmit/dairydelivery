/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('clintManagemaent', ['angularjs-datetime-picker']);
app.controller('clintManagemaent', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (data){


        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";

        $scope.base_url = data.base_url;

        $scope.loading = false;

        $scope.date = data.date
        $scope.getAllCustomerList();

        $scope.selected_discount_list =[];

        $scope.main_object_function();

    }

    $scope.main_object_function=function (){
        $scope.main_object ={
            'type':'receipt_vendor',
            'pay_to_party_id':'',
            'payment_type_id':'',
            'date':$scope.date,
            'head':'',
            'payment_mode':'',
            'amount_paid':'',
            'reference_no':'',
        }
    }



    $scope.getAllCustomerList = function(){


        $http.post($scope.base_url+'_master_list'  )
            .success(function(responce){

                $scope.farm_object = responce.farm_object;
                $scope.expense_object = responce.expense_object;
                $scope.vendor_object = responce.vendor_object;
                $scope.employee_object = responce.employee_object;
                $scope.main_list = $scope.vendor_object;
                $scope.other_income_source_object = responce.other_income_source_object;

            })
            .error(function (responce) {

            });
    }

    $scope.change_type_function = function (type){
        if(type==1){

            $scope.main_list = $scope.vendor_object;

            $scope.main_object.pay_to_party_id ='';
        }
        if(type==2){
            $scope.main_list = $scope.other_income_source_object;

            $scope.main_object.pay_to_party_id ='';
        }
        if(type==3){
            $scope.main_list = $scope.expense_object;
            $scope.main_object.pay_to_party_id ='';
        }if(type==4){
            $scope.main_list = $scope.employee_object;
            $scope.main_object.pay_to_party_id ='';
        }
    }

    $scope.change_party_function = function (){


        $http.post($scope.base_url+'_get_payment_list', $scope.main_object)
            .success(function(responce){

                $scope.payment_list =responce;

            })
            .error(function (responce) {

            });
    }

    $scope.save_payment_function = function (){


        $scope.loading = true;

        $http.post($scope.base_url+'_save_payment',  $scope.main_object)
            .success(function(responce){

                if(responce.success){
                    $scope.loading = false;

                    $scope.main_object.amount_paid ='';
                    $scope.main_object.reference_no ='';

                    $scope.change_party_function();

                }else {
                    alert(JSON.stringify(responce.message));
                }


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

