/**
 * Created by Muhammad.Imran on 4/1/2016  .
 */
var app = angular.module('clintManagemaent', ['angularjs-datetime-picker']);
app.controller('clintManagemaent', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (productList ,clientList , getClientLedgherReportURL ,oneCustomerAmountListallCustomerListURL){


        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";
        $scope.clientList = clientList ;
        $scope.productList = productList ;
        $scope.getClientLedgherReportURL = getClientLedgherReportURL ;
        $scope.oneCustomerAmountListallCustomerListURL = oneCustomerAmountListallCustomerListURL;
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
        $scope.startDate = selectYear + '-' + month + '-' + date;
        $scope.clientID = '';
        $scope.reportLoader = false ;
        $scope.pageShow = false ;
      //  $scope.getCustomerLedgerReportFunction();
       $scope.SelectedCustomer = 'Select Cutomer'
        $scope.showOpeningBalance = false ;
        $scope.sortReverse = true ;
        $scope.loadClientLoader = true;
        $scope.client_type = '1';
        $scope.getAllCustomerList($scope.client_type);
        $scope.result =[] ;

        $scope.quantity = '';
    }

    $scope.getAllCustomerList = function(client_type){

        $scope.loadClientLoader = true;
        $http.post($scope.oneCustomerAmountListallCustomerListURL+'_active' ,client_type)
            .success(function(responce){
                $scope.clientList = responce ;
                $scope.loadClientLoader = false;
            })
            .error(function (responce) {

            });
    }

    $scope.change_rate_function = function(product_id ,new_rate ){
        var send_data = {
            product_id : product_id,
            new_rate : new_rate,
            result : $scope.result,
        }
        var array_lenght =   $scope.result.length;
        if(array_lenght == 0){
             alert("There is no delivery to change rate");
        }else {
             $scope.reportLoader_rateChange = true ;
            $http.post( $scope.getClientLedgherReportURL+"_change_rate" , send_data)
             .success(function(responce , data){
               $scope.getCustomerLedgerReportFunction();
             })
             .error(function(responce , data){
             });
        }
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
                $scope.totalDelievry = Number(0);
                $scope.totalRecive = Number(0);
                $http.post( $scope.getClientLedgherReportURL , data)
                    .success(function(responce , data){

                        $scope.result = responce ;
                        $scope.reportLoader = false;
                        $scope.reportLoader_rateChange = false;

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
          $scope.cell_no_1 = y.cell_no_1;
          $scope.address = y.address;
          $scope.zone_name = y.zone_name;

    }
    $scope.showDropDownList = function () {
          document.getElementById("serachCustomerBar").focus();

    }

    $scope.selectProduct = function () {
        if($scope.clientID==''){
            alert("Select Customer First");
             $scope.product_id ='';
        }else {
             var sendData ={
                 client_id : $scope.clientID ,
                 product_id : $scope.product_id
             }
            $http.post( $scope.getClientLedgherReportURL+"_select_product_rate" , sendData)
                .success(function(responce , data){
                     $scope.product_rate = responce ;
                })
                .error(function(responce , data){
               });
        }
    }

    $scope.returnSaleFunction = function (saleOption) {
         if( $scope.product_id =='' || $scope.quantity==''){
             alert("Put all value");
         }else{

             var sendData ={
                 client_id : $scope.clientID ,
                 product_id : $scope.product_id,
                 product_rate : $scope.product_rate,
                 startDate : $scope.startDate ,
                 quantity : $scope.quantity,
                 saleOption : saleOption,
             }
             $scope.reportLoader_rateChange = true ;
             $http.post( $scope.getClientLedgherReportURL+"_return_product" , sendData)
                 .success(function(responce , data){
                     $scope.product_rate = responce ;

                     $scope.reportLoader_rateChange = false ;

                     $scope.clientID ='';
                     $scope.product_id ='';
                     $scope.quantity ='';
                     $scope.product_rate ='';
                     $scope.SelectedCustomer = 'Select Cutomer';
                     $scope.address = false ;
                 })
                 .error(function(responce , data){
                 });
        }

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

