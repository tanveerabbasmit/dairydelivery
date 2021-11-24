/**
 * Created by Muhammad.Imran on 4/1/2016  .
 */
var app = angular.module('clintManagemaent', ['angularjs-datetime-picker']);
app.controller('clintManagemaent', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (riderList , clientList , oneCustomerAmountListURL ,oneCustomerAmountListallCustomerListURL){

        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";
        $scope.clientList = clientList ;
        $scope.riderList = riderList ;
        $scope.oneCustomerAmountListURL = oneCustomerAmountListURL ;
        $scope.oneCustomerAmountListallCustomerListURL = oneCustomerAmountListallCustomerListURL ;

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

        $scope.startDate = selectYear + '-' + month + '-' +'01';
        $scope.endDate = selectYear + '-' + month + '-' + date;

        $scope.clientID = '';

        $scope.reportLoader = false ;
        $scope.pageShow = false ;
      //  $scope.getCustomerLedgerReportFunction();

        $scope.SelectedCustomer = 'Select Cutomer'

        $scope.showOpeningBalance = false ;

        $scope.sortReverse = true ;
        $scope.loadClientLoader = true;
        $scope.getAllCustomerList();

        $scope.updateForMonth = false;

        $scope.searchPayment ={
            month : month+'',
            year : selectYear+'',
            payment_mode : '0',
        }


    }

    $scope.updatePayment = function(payment ,month ,year){
        $scope.updateMonth =  month ;
        $scope.updateYear =  year ;
        $scope.update_payment_master_id =payment.payment_master_id;
        $scope.updateForMonth =  !$scope.updateForMonth ;
    }

    $scope.changeFormonth = function(updateMonth,updateYear){

         var data={
             month : updateMonth ,
             year : updateYear,
             payment_master_id : $scope.update_payment_master_id
         }

        $http.post($scope.oneCustomerAmountListURL+'_forMonth' ,data )
         .success(function(responce){
             $scope.updateForMonth =  !$scope.updateForMonth ;


            angular.forEach($scope.paymentList ,function (value ,key) {

                if(value.payment_master_id == $scope.update_payment_master_id){
                    value.bill_month_date = responce;
                }
            })
        })
        .error(function (responce) {
         });
    }


    $scope.getOneCustomerPaymentList = function (client_id) {
        $http.post($scope.oneCustomerAmountListURL ,client_id )
            .success(function(responce){
                $scope.paymentList = responce ;

                $scope.total_recived =0;

                angular.forEach($scope.paymentList ,function (value ,key) {

                    $scope.total_recived = Number($scope.total_recived) + Number(value.amount_paid);

                });
            })
            .error(function (responce) {
            });
    }

    $scope.getAllPaymentList = function (data) {

        $scope.reportLoader = true ;
        $http.post($scope.oneCustomerAmountListURL+"allPaymentList" ,data )
            .success(function(responce){
                $scope.paymentList = responce ;
                $scope.total_recived =0;

                angular.forEach($scope.paymentList ,function (value ,key) {

                    $scope.total_recived = Number($scope.total_recived) + Number(value.amount_paid);

                });
                $scope.reportLoader = false ;
            })
            .error(function (responce) {
            });
    }

    $scope.getAllCustomerList = function(){


        $http.post($scope.oneCustomerAmountListallCustomerListURL )
            .success(function(responce){

                $scope.clientList = responce ;
                $scope.loadClientLoader = false;
            })
            .error(function (responce) {

            });
    }



    $scope.changeDateFormate = function (y) {
        function addZero(i) {
            if (i < 10) {
                i = "0" + i;
            }
            return i;
        }
        var d = new Date(y);
        if(addZero(d.getDate())){
            var selectYear = addZero(d.getFullYear());
            var month = addZero(d.getMonth()+1);
            var date = addZero(d.getDate());
            var selectedDate  = date + '-' + month + '-' + selectYear;
        }else {
            var selectedDate = '' ;
        }
        return selectedDate
    }

  $scope.abcd = function (y) {
     $scope.SelectedCustomer = y.fullname;
      $scope.clientID = y.client_id;
      $scope.cell_no_1 = y.cell_no_1;
      $scope.address = y.address;
      $scope.zone_name = y.zone_name;
      $scope.getOneCustomerPaymentList($scope.clientID);

   }
  $scope.showDropDownList = function () {
      document.getElementById("serachCustomerBar").focus();

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

