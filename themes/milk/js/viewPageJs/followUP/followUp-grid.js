/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('clintManagemaent', ['angularjs-datetime-picker']);
app.controller('clintManagemaent', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (allow_delete ,dropReasonList , Currentyear ,todayMonth , company_id ,clientList , paymentMethod , oneCustomerAmontListURL , checkAccountBalnceUrl){

        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";
        $scope.selectMonth = todayMonth+'';
        $scope.selectYear = Currentyear+'';
        $scope.allow_delete = allow_delete ;
        $scope.dropReasonList = dropReasonList ;
        $scope.todayMonth = todayMonth ;
        $scope.Currentyear = Currentyear ;
        $scope.billMonth =  todayMonth+"/"+Currentyear;
        $scope.clientList = clientList ;
        $scope.paymentMethod = paymentMethod ;
        $scope.checkAccountBalnceUrl =checkAccountBalnceUrl ;
        $scope.oneCustomerAmontListURL = oneCustomerAmontListURL ;
        $scope.SelectedCustomer = 'Select Cutomer';
        $scope.sample_client_drop_reason_id = '0';
        $scope.selectReasonSection =false ;
        $scope.imageLoader = false ;
        $scope.imageLoader2 = false ;
        $scope.imageLoader3 = false ;
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

        $scope.paymentObject = {'bill_year': $scope.selectYear , 'bill_month': $scope.selectMonth, 'amount_paid':'','trans_ref_no':'','client_id':'','remarks':'2' ,'company_branch_id':company_id,'payment_mode':'','startDate':$scope.startDate};

        $scope.showBalance = false ;
        $scope.addFollowUpCustomerList($scope.oneCustomerAmontListURL);



    }

    $scope.addFollowUpCustomerList =function(url){

        $http.post(url+"_customerListapi" ,"list")
            .success(function(responce){
                $scope.clientList =responce;


            })
            .error(function (responce) {
            });
    }

   $scope.makeDrop = function(flag){


       if($scope.sample_client_drop_reason_id =='0'){

           $scope.taskMessage = 'Select Reason';
           document.getElementById("alertMessage").style.display = "block";
           setTimeout(function(){
                   document.getElementById("alertMessage").style.display = 'none';
               },
               1500);
       }else {

           if($scope.paymentObject.client_id==''){
               $scope.taskMessage = 'Select customer';
               document.getElementById("alertMessage").style.display = "block";
               setTimeout(function(){
                       document.getElementById("alertMessage").style.display = 'none';
                   },
                   1500);
           }else {
               $scope.send_data = {
                   'client_id':$scope.paymentObject.client_id,
                   'flag'    :flag,
                   'sample_client_drop_reason_id'    :$scope.sample_client_drop_reason_id,
               }

               $scope.imageLoader3 = true ;
               $scope.new_path =$scope.paymentMethod+"_meakeDrop";

               $http.post($scope.new_path ,$scope.send_data)
                   .success(function(responce){

                       $scope.paymentObject.amount_paid ='';
                       $scope.paymentObject.trans_ref_no ='';
                       $scope.taskMessage = 'successfully drop';
                       document.getElementById("alertMessage").style.display = "block";
                       setTimeout(function(){
                               document.getElementById("alertMessage").style.display = 'none';
                           },
                           1500);
                       $scope.SelectedCustomer = 'Select Cutomer'
                       $scope.imageLoader3 = false ;
                       $scope.clientList = responce;

                   })
                   .error(function (responce) {
                   });
           }


       }


   }
   $scope.makeRegular = function(flag){


       if($scope.paymentObject.client_id==''){
           $scope.taskMessage = 'Select customer';
           document.getElementById("alertMessage").style.display = "block";
           setTimeout(function(){
                   document.getElementById("alertMessage").style.display = 'none';
               },
               1500);
       }else {
            $scope.send_data = {
                'client_id':$scope.paymentObject.client_id,
                 'flag'    :flag,
            }

           $scope.imageLoader2 = true ;
            $scope.new_path =$scope.paymentMethod+"_meakeRegualr";

           $http.post($scope.new_path ,$scope.send_data)
               .success(function(responce){

                   $scope.paymentObject.amount_paid ='';
                   $scope.paymentObject.trans_ref_no ='';
                   $scope.taskMessage = 'Make Regularly has been processed successfully';
                   document.getElementById("alertMessage").style.display = "block";
                   setTimeout(function(){
                           document.getElementById("alertMessage").style.display = 'none';
                       },
                       1500);
                   $scope.SelectedCustomer = 'Select Cutomer'
                   $scope.imageLoader2 = false ;
                   $scope.clientList = responce;

               })
               .error(function (responce) {
               });
       }
   }
   $scope.savePaymernt = function() {

      if($scope.paymentObject.client_id==''){
          $scope.taskMessage = 'Select customer';
          document.getElementById("alertMessage").style.display = "block";
          setTimeout(function(){
                  document.getElementById("alertMessage").style.display = 'none';
              },
              1500);
      }else {

          $scope.imageLoader = true ;

          $http.post($scope.paymentMethod ,$scope.paymentObject)
              .success(function(responce){

                  $scope.paymentObject.amount_paid ='';
                  $scope.paymentObject.trans_ref_no ='';
                  $scope.taskMessage = 'Your Follow up has been processed successfully';
                  document.getElementById("alertMessage").style.display = "block";
                  setTimeout(function(){
                          document.getElementById("alertMessage").style.display = 'none';
                      },
                      1500);
                  $scope.SelectedCustomer = 'Select Cutomer'
                  $scope.imageLoader = false ;

                  $scope.OneCustomerPaymentData = [];

              })
              .error(function (responce) {
              });
      }

   }
    $scope.abcd = function (y) {

        $scope.SelectedCustomer = y.fullname;
        $scope.paymentObject.client_id = y.client_id;

        $scope.getOneCustomerPaymentList(y.client_id);




        $scope.cell_no_1 = y.cell_no_1;
        $scope.address = y.address;
        $scope.zone_name = y.zone_name;
    }
    $scope.getOneCustomerPaymentList = function (client_id) {


        $http.post($scope.oneCustomerAmontListURL ,client_id )
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
    $scope.dropSection = function(){
        $scope.selectReasonSection =!$scope.selectReasonSection ;
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

