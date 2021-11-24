/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('clintManagemaent', ['angularjs-datetime-picker']);
app.controller('clintManagemaent', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (data){


        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";

        $scope.collectionvault = data.collectionvault;
        $scope.base_url = data.base_url;

        $scope.loading = false;
        $scope.date = data.date
       // $scope.getAllCustomerList();

        $scope.selected_discount_list =[];

        $scope.main_object_function();

    }



    $scope.main_object_function=function (){
        $scope.main_object ={
            'payment_transfer_collection_vaul_to_other_id':'0',
             'collection_vault_id_from':'',
            'collection_vault_id_to':'',
            'action_date':$scope.date,
            'amount':'',
            'remarks':'',
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



            })
            .error(function (responce) {

            });
    }


    $scope.save_payment_function = function (){


        $scope.loading = true;

        $http.post($scope.base_url+'_save_payment_transfer_to_other_vault',  $scope.main_object)
            .success(function(responce){

                if(responce.success){

                    $scope.get_payment_list_function();

                    $scope.loading = false;

                    $scope.main_object.collection_vault_id_to ='';
                    $scope.main_object.amount ='';



                }else {
                    alert(JSON.stringify(responce.message));
                }


            })
            .error(function (responce) {

            });
    }

    $scope.get_payment_list_function =function (){

        $http.post($scope.base_url+'_get_new_payment_transfer_list', $scope.main_object)
            .success(function(responce){

                 $scope.payment_list  = responce;


                 angular.forEach($scope.payment_list ,function(value,key){
                                     value.update =false;
                 })


            })
            .error(function (responce) {

            });
    }

    $scope.delete_new_payment = function (list){
        var r = confirm("Are you sure?");
        if (r == true) {


            $http.post($scope.base_url+'_delete_transfer_amount', list)
                .success(function(responce){

                    $scope.get_payment_list_function();


                })
                .error(function (responce) {

                });
        }

    }

    $scope.edit_payment = function (list){
        list.update= true;
    }
    $scope.save_payment = function (list){


        $http.post($scope.base_url+'_save_payment_transfer_to_other_vault',  list)
            .success(function(responce){
                list.update =false;
               // $scope.get_payment_list_function();


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

