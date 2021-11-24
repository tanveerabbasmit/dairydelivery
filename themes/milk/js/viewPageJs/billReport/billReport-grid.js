/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('clintManagemaent', ['angularjs-datetime-picker']);
app.controller('clintManagemaent', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function(LableList ,productList ,clientList , getClientLedgherReportURL,oneCustomerAmountListallCustomerList){


        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";
        $scope.LableList = LableList ;
        $scope.clientList = clientList ;
        $scope.getClientLedgherReportURL = getClientLedgherReportURL ;
        $scope.productList  = productList ;

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

        $scope.reportLoader = true ;
        $scope.pageShow = false ;
      //  $scope.getCustomerLedgerReportFunction();
      $scope.SelectedCustomer = 'Select Cutomer'
      $scope.productID = $scope.productList[0].product_id;

     $scope.productName = $scope.productList[0].name;

     $scope.oneCustomerAmountListallCustomerListURL =oneCustomerAmountListallCustomerList;

        $scope.getAllCustomerList();




    }

    $scope.getAllCustomerList = function(){
        $http.post($scope.oneCustomerAmountListallCustomerListURL )
            .success(function(responce){

                $scope.clientList = responce ;
                $scope.reportLoader = false;
            })
            .error(function (responce) {

            });
    }

    $scope.getCustomerLedgerReportFunction = function(){
        if($scope.startDate >$scope.endDate ){
            $scope.taskMessage = 'Start date should be greater then end date';
            document.getElementById("alertMessage").style.display = "block";
            setTimeout(function(){
                    document.getElementById("alertMessage").style.display = 'none';
                },
                1500);
        }else {
            var data ={
                'clientID' :$scope.clientID ,
                'productID' :$scope.productID ,
                'startDate' :  $scope.startDate ,
                'endDate' : $scope.endDate
            }
            if($scope.clientID == ''){
                $scope.taskMessage = 'Please Select Customer';
                document.getElementById("alertMessage").style.display = "block";
                setTimeout(function(){
                        document.getElementById("alertMessage").style.display = 'none';
                    },
                    1500);
            }else {
                $scope.reportLoader = true;
                $scope.pageShow = true ;
                $http.post( $scope.getClientLedgherReportURL , data)
                    .success(function(responce , data){

                        $scope.responceData = responce.finalData ;
                        $scope.productList = responce.productList ;
                        $scope.LableList = responce.headingResult ;
                        $scope.Zone = responce.Zone ;
                        $scope.reportLoader = false;
                        $scope.totalQuantity = 0 ;
                        $scope.totalAmount = 0 ;
                        angular.forEach($scope.responceData , function(value , key){

                            $scope.totalQuantity = Number($scope.totalQuantity) + Number(value[2]);
                            $scope.totalAmount   = Number($scope.totalAmount) + Number(value[3]);
                        })

                    })
                    .error(function(responce , data){

                    });
            }
        }


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
      $scope.address = y.address;
      $scope.cell_no_1 = y.cell_no_1;

  }
  $scope.showDropDownList = function () {
      document.getElementById("serachCustomerBar").focus();

  }


    $scope.printMonthlyRepot = function () {

        var innerContents = document.getElementById('printForm').innerHTML;
        var popupWinindow = window.open('', '_blank', 'width=1200px,height=1000px,scrollbars=no,menubar=no,toolbar=no,location=no,status=no,titlebar=no');
        popupWinindow.document.open();
        popupWinindow.document.write('<html><head><link rel="stylesheet" type="text/css" href="style.css" /></head><body onload="window.print()">' + innerContents + '</html>');
        popupWinindow.document.close();
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

