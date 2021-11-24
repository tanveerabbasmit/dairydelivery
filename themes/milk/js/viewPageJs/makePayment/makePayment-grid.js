/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('clintManagemaent', ['angularjs-datetime-picker']);
app.controller('clintManagemaent', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (required_name ,collectionvault_list , date_object ,disabled_result ,discount_type ,allow_delete ,Currentyear ,todayMonth , company_id ,clientList , paymentMethod , oneCustomerAmontListURL , checkAccountBalnceUrl){


        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";

        $scope.collectionvault_list = collectionvault_list;

        $scope.date_object = date_object;
        $scope.required_name = required_name;
        $scope.disabled_result = disabled_result;
        $scope.security_code ='';
        $scope.selectMonth = todayMonth+'';
        $scope.selectYear = Currentyear+'';
        $scope.discount_type = discount_type ;
        $scope.allow_delete = allow_delete ;
        $scope.todayMonth = todayMonth ;
        $scope.Currentyear = Currentyear ;
        $scope.billMonth =  todayMonth+"/"+Currentyear;
        $scope.clientList = clientList ;
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

        $scope.paymentObject = {'payment_type':'0','collection_vault_id' :'', 'bill_year': $scope.selectYear , 'bill_month': $scope.selectMonth, 'amount_paid':'','trans_ref_no':'','client_id':'','remarks':'2' ,'company_branch_id':company_id,'payment_mode':'3','startDate':$scope.startDate};

        $scope.showBalance = false ;
        $scope.loadClientLoader = true;
        $scope.originalAmount = 0;
        $scope.getAllCustomerList();



        $scope.selected_discount_list =[];
    }

    $scope.changeDiscountType = function(list){
       // var x=list;

        $scope.selected_discount_list.push(list);
    }
    $scope.deleteClientButton = function (payment_master_id) {

        var txt;

        var person = prompt("Security Code:", "");
        if (person=="32147") {

            var remarks = prompt("You can put remarks here.", "");

            var  send_data = {
                payment_master_id:payment_master_id,
                remarks : remarks

            }

            $http.post( $scope.oneCustomerAmontListURL+'delete_master_payment', send_data)
                .success(function (data, status, headers, config) {

                     if(data){
                         $scope.getOneCustomerPaymentList($scope.paymentObject.client_id );
                         $scope.getcheckAccountBalnce($scope.paymentObject.client_id );
                     }else {
                         alert("You can not delete This.");
                     }

                })
                .error(function (data, status, header, config) {
                    alert(data.message);
                    subject.showLoader = false;
                });
        } else {
            alert('Code is Wrong');
        }

    }

    $scope.get_today_payment_list = function () {


        $http.post($scope.oneCustomerAmontListURL+"_today_payment" ,$scope.paymentObject )
            .success(function(responce){

                $scope.OneCustomerPaymentData = responce ;
                angular.forEach($scope.OneCustomerPaymentData ,function (value,key) {
                    value.update = false;
                });
            })
            .error(function (responce) {
            });
    }

    $scope.checkPayemtAllready = function(){


        /* mit ===================================*/

        $scope.imageLoader = true ;
        var send_data = {
            mayment : $scope.paymentObject ,
            discount : $scope.discount_type
        }

        $http.post($scope.paymentMethod ,send_data)
            .success(function(responce){
                $scope.imageLoader = false ;
                   if(responce.success){
                       $scope.paymentObject.amount_paid ='';
                       $scope.paymentObject.trans_ref_no ='';
                       $scope.taskMessage = 'Payment is already made for the selected month. Are you sure to add another payment for the same month?';
                       document.getElementById("alertMessage").style.display = "block";
                       setTimeout(function(){
                               document.getElementById("alertMessage").style.display = 'none';
                           },
                           1500);
                       $scope.SelectedCustomer = 'Select Cutomer'
                       $scope.getcheckAccountBalnce( $scope.new_select_client_id);

                       $scope.paymentObject.client_id=''


                       $scope.getcheckAccountBalnce($scope.paymentObject.client_id);
                       $scope.getOneCustomerPaymentList($scope.paymentObject.client_id);

                   }else {
                       alert(responce.message);
                   }



            })
            .error(function (responce) {
            });

        /* ===================================*/

    }

   $scope.savePaymernt = function() {

     //  var result   =  $scope.checkPayemtAllready($scope.paymentObject);
     //  console.log($scope.paymentObject.client_id);
      if($scope.paymentObject.client_id==''){

          $scope.taskMessage = 'Select customer';
          document.getElementById("alertMessage").style.display = "block";
          setTimeout(function(){
                  document.getElementById("alertMessage").style.display = 'none';
              },
              1500);
        }else {




          $http.post($scope.paymentMethod+'checkPayment' ,$scope.paymentObject)
              .success(function(responce){

                      if(responce){

                          var result = confirm("Payment  have paid already for this month");
                          if (result) {
                              $scope.checkPayemtAllready();

                          }else {
                                 die();
                          }

                      }else {
                          $scope.checkPayemtAllready();
                      }



                     /* ===================================*/



                     /* ===================================*/




              })
              .error(function (responce) {
              });





      }

   }
   $scope.abcd = function (y) {

        $scope.SelectedCustomer = y.fullname;
        $scope.paymentObject.client_id = y.client_id;
       $scope.new_select_client_id = y.client_id;
        $scope.getOneCustomerPaymentList(y.client_id);
        $scope.getcheckAccountBalnce(y.client_id);
        $scope.cell_no_1 = y.cell_no_1;
        $scope.address = y.address;
        $scope.zone_name = y.zone_name;


       $scope.get_payment_term_of_client($scope.paymentObject.client_id);
    }


    $scope.get_payment_term_of_client = function(client_id){
        $http.post($scope.oneCustomerAmontListURL+'_payment_term_of_client' ,client_id )
            .success(function(responce){
                $scope.payment_term = responce;
            })
            .error(function (responce) {

            });
    }

    $scope.getAllCustomerList = function(){


        $http.post($scope.oneCustomerAmontListURL+'allCustomerList_rider_wise'  )
            .success(function(responce){

                $scope.clientList = responce ;
                $scope.loadClientLoader = false;
            })
            .error(function (responce) {

            });
    }
    $scope.getOneCustomerPaymentList = function (client_id) {
        $http.post($scope.oneCustomerAmontListURL ,client_id )
            .success(function(responce){

               $scope.OneCustomerPaymentData = responce ;
               angular.forEach($scope.OneCustomerPaymentData ,function (value,key) {
                   value.update = false;
               });

            })
            .error(function (responce) {

            });
    }


    $scope.security_code_function =function (){


        var number=document.getElementById("security_code").value;

        if (number== "32147") {
            $scope.add_security_code_model =false;
             list = $scope.selected_payment_update_object;

            var remarks = prompt("You can put remarks here.", "");
            list.remarks = remarks;
            $http.post($scope.oneCustomerAmontListURL+"_update_payment" ,list )
                .success(function(responce){
                    if(responce){
                        $scope.getOneCustomerPaymentList($scope.paymentObject.client_id);
                    }else {
                        alert("You can not change");
                    }

                })
                .error(function (responce) {
                });

        }else {
            alert("Code is not correct");
        }
    }

    $scope.update_payment = function(list) {

        $scope.selected_payment_update_object = list;
        $scope.add_security_code_model =true;
       /* var txt;
        var person = prompt("Security Code:", "");
        if (person == "74123") {

            var remarks = prompt("You can put remarks here.", "");
            list.remarks = remarks;
            $http.post($scope.oneCustomerAmontListURL+"_update_payment" ,list )
                .success(function(responce){
                    if(responce){
                        $scope.getOneCustomerPaymentList($scope.paymentObject.client_id);
                    }else {
                        alert("You can not change");
                    }

                })
                .error(function (responce) {
                });

        }else {
            alert("Code is not correct");
        }*/

    }


    $scope.getcheckAccountBalnce = function(client_id) {

       var data = {
          'client_id' :  client_id
        }
        $http.post($scope.checkAccountBalnceUrl ,data )
            .success(function(responce){

                $scope.OneCustomerOustaningBalance = responce.data ;
                $scope.showBalance = true ;
                $scope.get_closing_balnce_of_day();
                $scope.getOneCustomerPaymentList(client_id);
            })
            .error(function (responce) {

            });
    }

    $scope.get_closing_balnce_of_day = function(){
        $scope.paymentObject.client_id = $scope.new_select_client_id
        $http.post($scope.oneCustomerAmontListURL+"_closing_balnce_of_one_day" , $scope.paymentObject)
            .success(function(responce){

               $scope.closing_balance = responce;

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
        $scope.change_date_select();
    }

    $scope.showDropDownList = function () {
        document.getElementById("serachCustomerBar").focus();
    }
    $scope.change_date_select = function(){
        $http.post($scope.oneCustomerAmontListURL+"_change_date_select" , $scope.paymentObject)
            .success(function(responce){
                 if(responce.company_id=='19'){
                     $scope.paymentObject.bill_year =responce.year;
                     $scope.paymentObject.bill_month =responce.month+"";
                 }


            })
            .error(function (responce) {
            });

    }

    $scope.view_discount_amount = function(product){
        $scope.view_discount_model =true;
        $scope.discount_list = product.discount_list.list;

    }
    $scope.change_discount_amount_function =function (){
        var number = prompt("Security Code Please.", "");
        if (number== "32147") {
            $scope.view_discount_model =false;
            $http.post($scope.oneCustomerAmontListURL+"_change_discount_amount_function" ,  $scope.discount_list)
                .success(function(responce){
                    $scope.getOneCustomerPaymentList($scope.new_select_client_id);

                })
                .error(function (responce) {
                });

        }else {
            alert("Code is not correct");
        }

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

