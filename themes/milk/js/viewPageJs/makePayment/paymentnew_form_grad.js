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
        $scope.vendor_type = data.vendor_type;

        $scope.farm_list = data.farm_list;

        $scope.vandor_list = data.vandor_list;
        $scope.form_name = data.form_name;
        $scope.payment_or_receipt = data.payment_or_receipt;
        $scope.get_expence_list = data.get_expence_list;


        $scope.loading = false;

        $scope.date = data.date
       // $scope.getAllCustomerList();
        $scope.page = 0 ;
        $scope.selected_discount_list =[];

        $scope.main_object_function();

    }



    $scope.main_object_function=function (){
        $scope.main_object ={
            'payment_or_receipt': $scope.payment_or_receipt,
            'collection_vault_id':'',
            'vendor_type_id':'',
            'vendor_id':'',
            'date':$scope.date,
            'transaction_type':'',
            'expence_type':'',
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



            })
            .error(function (responce) {

            });
    }



    $scope.change_party_type_function = function (vendor_type_id){
        try {
            $scope.main_object.vendor_id =$scope.page;
            if(vendor_type_id==3){

                if($scope.payment_or_receipt==2){
                    alert("Farm Receipt is not allowed");
                    $scope.main_object.vendor_type_id ='';
                }else {
                    $scope.main_list =  $scope.farm_list
                }

            }else {

                $scope.main_list =$scope.vandor_list[vendor_type_id];
            }

        }
        catch(err) {
           alert(err);
        }
    }

    $scope.save_payment_function = function (){

        var  vendor_id =  document.getElementById("change_party_id").value;

        $scope.main_object.vendor_id = vendor_id;

        $scope.loading = true;

        $http.post($scope.base_url+'_save_payment_new_other',  $scope.main_object)
            .success(function(responce){

                if(responce.success){

                    $scope.get_payment_list_function();

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
    $( "#change_party_id" ).change(function() {
        var  vendor_id =  document.getElementById("change_party_id").value;

        $scope.main_object.vendor_id = vendor_id;

        $scope.get_payment_list_function();

    });
    $scope.get_payment_list_function_next =function (){
        $scope.page++;
        $scope.get_payment_list_function();
    }
    $scope.get_payment_list_function_perious =function (){
        $scope.page--;
        if($scope.page<0){
            alert('NO more data');
            $scope.page=0;
        }
        $scope.get_payment_list_function();
    }
    $scope.get_payment_list_function =function (){
        $scope.main_object.page= $scope.page;
        $http.post($scope.base_url+'_get_new_payment_list', $scope.main_object)
            .success(function(responce){

                  if(Object.keys(responce.list).length==0){
                        alert('NO more data');
                      $scope.page--;
                  }else {
                      $scope.payment_list  = responce.list;
                  }


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

            var send_data ={
                'list' :list,
                'main_object' :$scope.main_object

            }
            $http.post($scope.base_url+'_delete_new_payment', send_data)
                .success(function(responce){

                    debugger;
                    if(responce.success){
                        $scope.get_payment_list_function();
                    }else {
                        alert(responce.messag);
                    }


                })
                .error(function (responce) {

                });
        }

    }

    $scope.edit_payment = function (list){
        list.update= true;
    }
    $scope.save_payment = function (list){
        var send_data ={
            'list' :list,
            'main_object' :$scope.main_object

        }
        $http.post($scope.base_url+'_update_new_payment', send_data)
            .success(function(responce){


                if(responce.success){
                    list.update = false;
                    $scope.get_payment_list_function();
                   // $scope.get_payment_list_function();
                }else {
                    alert(responce.messag);
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

