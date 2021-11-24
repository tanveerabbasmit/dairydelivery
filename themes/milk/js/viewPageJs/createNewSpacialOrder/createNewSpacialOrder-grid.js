/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('clintManagemaent', ['angularjs-datetime-picker']);
app.controller('clintManagemaent', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (discount_type ,allow_delete ,Currentyear ,todayMonth , company_id ,clientList , paymentMethod , oneCustomerAmontListURL , checkAccountBalnceUrl){

        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";
        $scope.selectMonth = todayMonth+'';
        $scope.selectYear = Currentyear+'';
        $scope.discount_type = discount_type ;
        $scope.allow_delete = allow_delete ;
        $scope.todayMonth = todayMonth ;
        $scope.Currentyear = Currentyear ;
        $scope.billMonth =  todayMonth+"/"+Currentyear;

        $scope.paymentMethod = paymentMethod ;
        $scope.checkAccountBalnceUrl =checkAccountBalnceUrl ;
        $scope.oneCustomerAmontListURL = oneCustomerAmontListURL ;
        $scope.SelectedCustomer = 'Select Cutomer'
        $scope.discount_type_select = '0';
        $scope.imageLoader = false ;
        var date = new Date();
        date.setDate(date.getDate());
        var selectYear = date.getFullYear() ;
        var month = date.getMonth()+1;
        var date = date.getDate();
        if(month<10){
            month = '0'+month
        }

        if(date<10){
            date = '0'+date
        }
        $scope.startDate = selectYear + '-' + month + '-' + date;
        $scope.paymentObject = {'bill_year': $scope.selectYear , 'bill_month': $scope.selectMonth, 'amount_paid':'','trans_ref_no':'','client_id':'','remarks':'2' ,'company_branch_id':company_id,'payment_mode':'3','startDate':$scope.startDate,'endDate':$scope.startDate,'product_id':clientList.product_id};
        $scope.showBalance = false ;
        $scope.loadClientLoader = true;
        $scope.originalAmount = 0;
        $scope.getAllCustomerList();
        $scope.selected_discount_list =[];

        $Scope.clientList =clientList;

        if(clientList.client_id>0){
            $scope.abcd(clientList.client_object);
        }

    }

    $scope.changeDiscountType = function(list){
       // var x=list;

        $scope.selected_discount_list.push(list);
    }
    $scope.deleteClientButton = function (payment_master_id) {

        var txt;
        var person = prompt("Security Code:", "");
        if ( person == "74123") {
            $http.post( $scope.oneCustomerAmontListURL+'delete_master_payment', payment_master_id)
                .success(function (data, status, headers, config) {
                    $scope.getOneCustomerPaymentList($scope.paymentObject.client_id );
                    $scope.getcheckAccountBalnce($scope.paymentObject.client_id );
                })
                .error(function (data, status, header, config) {
                    alert(data.message);
                    subject.showLoader = false;
                });
        } else {
            alert('Code is Wrong');
        }
    }

    $scope.checkPayemtAllready = function(){
        /* ===================================*/
        $scope.imageLoader = true ;
        var send_data = {
            mayment : $scope.paymentObject ,
            discount : $scope.discount_type
        }
        $http.post($scope.paymentMethod ,send_data)
            .success(function(responce){
                $scope.paymentObject.amount_paid ='';
                $scope.paymentObject.trans_ref_no ='';
                $scope.taskMessage = 'Payment is already made for the selected month. Are you sure to add another payment for the same month?';
                document.getElementById("alertMessage").style.display = "block";
                setTimeout(function(){
                        document.getElementById("alertMessage").style.display = 'none';
                    },
                    1500);
                $scope.SelectedCustomer = 'Select Cutomer'
                $scope.imageLoader = false ;
                $scope.getcheckAccountBalnce($scope.paymentObject.client_id);
                $scope.getOneCustomerPaymentList($scope.paymentObject.client_id);
            })
            .error(function (responce) {
            });

        /* ===================================*/

    }

   $scope.savePaymernt = function() {

       $http.post($scope.paymentMethod+'save_spacial_order' ,$scope.paymentObject)
           .success(function(responce){
               window.location="manageSpecialOrder";

           })
           .error(function (responce) {
           });

   }
    $scope.abcd = function (y) {

        $scope.SelectedCustomer = y.fullname;
        $scope.paymentObject.client_id = y.client_id;

       
        $scope.getOneCustomerPaymentList(y.client_id);
       // $scope.getcheckAccountBalnce(y.client_id);

        $scope.cell_no_1 = y.cell_no_1;
        $scope.address = y.address;
        $scope.zone_name = y.zone_name;
    }

    $scope.getAllCustomerList = function(){

        $http.post($scope.oneCustomerAmontListURL+'allCustomerList'  )
            .success(function(responce){
                $scope.clientList = responce ;
                $scope.loadClientLoader = false;
            })
            .error(function (responce) {
        });
    }
    $scope.getOneCustomerPaymentList = function (client_id) {

        $http.post($scope.oneCustomerAmontListURL+"_spacial_order" ,client_id )
            .success(function(responce){
               $scope.OneCustomerPaymentData = responce ;
            })
            .error(function (responce) {

            });
    }


    $scope.getcheckAccountBalnce = function(client_id) {

       var data = {
          'client_id' :  client_id
        }
        $http.post($scope.checkAccountBalnceUrl ,data )
            .success(function(responce){

                $scope.OneCustomerOustaningBalance = responce.data ;
                $scope.showBalance = true ;
            })
            .error(function (responce) {

            });
    }

    $scope.changeDiscount = function(){

        if($scope.originalAmount){

            var paid_amount =  $scope.originalAmount ;
            var sum_discount = 0;
            angular.forEach($scope.discount_type ,function (value ,key) {
                if(value.percentage){
                    var percentage_amount = (value.discount_amount/100)*paid_amount;
                    value.calculated_discount =Math.round(percentage_amount);
                    sum_discount = Math.round(Number(sum_discount) + Number(percentage_amount));
                }else {
                    value.calculated_discount =Math.round(value.discount_amount);
                    sum_discount = Math.round(Number(sum_discount) + Number(value.discount_amount));

                }
                $scope.total_sum_discount =sum_discount ;
            })
           // $scope.paymentObject.amount_paid =Number(paid_amount) - Number(sum_discount);
        }

    }
    $scope.saveOriginalAmount =function(amount) {
        $scope.originalAmount = amount ;
        $scope.total_sum_discount ='';
        angular.forEach($scope.discount_type ,function (value ,key) {
            value.percentage = false ;
            value.discount_amount ='';
            value.calculated_discount ='';

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

