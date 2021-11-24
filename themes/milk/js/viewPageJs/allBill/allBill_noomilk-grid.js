/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('clintManagemaent', ['angularjs-datetime-picker']);
app.controller('clintManagemaent', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (company_name, company_id , riderList , clientList , getClientLedgherReportURL ,oneCustomerAmountListallCustomerList){


        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";
        $scope.company_name = company_name;
        $scope.company_id = company_id;
        $scope.clientList = clientList ;
        $scope.riderList = riderList ;
        $scope.getClientLedgherReportURL = getClientLedgherReportURL ;

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

        $scope.SelectedCustomer = 'Select Cutomer';

        $scope.showOpeningBalance = false ;

        $scope.sortReverse = true ;

        $scope.riderClientObject = '0';

        $scope.imageLoader = true ;

        $scope.showprograssBar = false ;
        $scope.x= 0;

        $scope.oneCustomerAmountListallCustomerListURL =oneCustomerAmountListallCustomerList

        $scope.getAllCustomerList();

    }

    $scope.getAllCustomerList = function(){


        $http.post($scope.oneCustomerAmountListallCustomerListURL )
            .success(function(responce){

                $scope.clientList = responce ;
                $scope.imageLoader = false;
            })
            .error(function (responce) {

            });
    }

    $scope.getCustomerLedgerReportFunction = function(){
         $scope.finalOneObject = [];
        if($scope.startDate >$scope.endDate ){
            $scope.taskMessage = 'Start date should be greater then end date';
            document.getElementById("alertMessage").style.display = "block";
            setTimeout(function(){
                    document.getElementById("alertMessage").style.display = 'none';
                },
                1500);
        }else {

            if($scope.riderClientObject == '0' && $scope.SelectedCustomer == 'Select Cutomer'){
                $scope.taskMessage = 'Please Select Rider OR Customer';
                document.getElementById("alertMessage").style.display = "block";
                setTimeout(function(){
                        document.getElementById("alertMessage").style.display = 'none';
                    },
                    1500);
            }else {


                var clientObject = angular.fromJson($scope.riderClientObject);

                  var totalCustomer = clientObject.length;

                    var total_increment = 0;
                $scope.imageLoader = true ;
                $scope.loadPerCentage = 0;
                angular.forEach(clientObject , function (value ,key) {
                    var client_id = value.client_id;
                    $scope.showprograssBar = true ;

                    var data ={
                        'clientID' :client_id ,
                        'startDate' :  $scope.startDate ,
                        'endDate' : $scope.endDate
                    }

                    $scope.reportLoader = true;
                    $scope.pageShow = true ;
                    $scope.totalDelievry = Number(0);
                    $scope.totalRecive = Number(0);
                    $http.post( $scope.getClientLedgherReportURL+'_PrintBill' , data)
                        .success(function(responce , data){

                            var length_count =   responce.sumery.length;

                            total_increment++;
                            $scope.loadPerCentage =  (Number(total_increment)/Number(totalCustomer))*100 ;

                            if(length_count > 0){
                                $scope.finalOneObject.push(responce);
                            }
                            if(total_increment ==totalCustomer){
                                $scope.imageLoader = false;
                            }

                            /* debugger ;
                            $scope.acountSumery =  responce.sumery;

                            $scope.responceData = responce.ledgerData;
                            $scope.openeningStock = responce.openeningStock ;
                            $scope.totalRemaining = responce.totalRemaining ;
                            $scope.reportLoader = false;
                            angular.forEach($scope.responceData ,function(value ,key){
                                if(value.discription == 'OPENING BALANCE'){
                                    $scope.TotoalOpeningBalnce = value.delivery;
                                    $scope.OpeningReciveAmount = value.reciveAmount;
                                }else {
                                    $scope.totalDelievry =  $scope.totalDelievry +Number(value.delivery);
                                    $scope.totalRecive =$scope.totalRecive + Number(value.reciveAmount);
                                }
                            });*/
                            $scope.showOpeningBalance = true ;
                        })
                        .error(function(responce , data){
                        });


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
      $scope.riderClientObject = '0';

      $scope.riderClientObject = [{"client_id":y.client_id}];
   }
   $scope.changeRiderFunction = function(){

       $scope.SelectedCustomer = 'Select Cutomer'
       $scope.address = false;

   }
  $scope.showDropDownList = function () {
      document.getElementById("serachCustomerBar").focus();

  }

  $scope.printFunction =function(){
      $scope.getCustomerLedgerReportFunction();
      var divToPrint=document.getElementById("printTable");
      newWin= window.open("");
      newWin.document.write(divToPrint.outerHTML);
      newWin.print();
      newWin.close();
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

