/**
 * Created by Muhammad.Imran on 4/1/2016  .
 */
var app = angular.module('clintManagemaent', ['angularjs-datetime-picker']);
app.controller('clintManagemaent', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (clientList , getClientLedgherReportURL ,oneCustomerAmountListallCustomerListURL){



        $scope.farm_list = clientList.farm_list;
        $scope.copany_object = clientList.copany_object;

        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";
        $scope.clientList = clientList ;
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
        $scope.endDate = selectYear + '-' + month + '-' + date;


        $scope.clientID = '';

        $scope.reportLoader = false ;
        $scope.pageShow = false ;
      //  $scope.getCustomerLedgerReportFunction();

        $scope.SelectedCustomer = 'Select Cutomer'

        $scope.showOpeningBalance = false ;

        $scope.sortReverse = true ;
        $scope.loadClientLoader = true;
        $scope.farm_id = '0';
        $scope.getAllCustomerList($scope.client_type);
        $scope.get_farm_name ='';
        $scope.phone_number ='';
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
    $scope.get_farm_name_function = function (){
        angular.forEach($scope.farm_list , function(value ,key){

            if($scope.farm_id == value.farm_id){

                $scope.get_farm_name =value.farm_name;

            }


        });
    }

    $scope.getCustomerLedgerReportFunction = function(){

        $scope.farm_id =  document.getElementById("farm_id_value").value;


         angular.forEach($scope.farm_list, function (value , key){
             if(value.farm_id == $scope.farm_id){
                 $scope.phone_number = value.phone_number;

             }

         })

        if($scope.startDate >$scope.endDate ){
            $scope.taskMessage = 'Start date should be greater then end date';
            document.getElementById("alertMessage").style.display = "block";
            setTimeout(function(){
                    document.getElementById("alertMessage").style.display = 'none';
                },
                1500);
        }else {
            $scope.get_farm_name_function();
            var data ={
                'farm_id' :$scope.farm_id ,
                'startDate' :  $scope.startDate ,
                'endDate' : $scope.endDate
            }
            if($scope.farm_id == '0'){
                $scope.taskMessage = 'Please Select Farm';
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
                        $scope.responce = responce ;


                        $scope.acountSumery =  responce.sumery;

                        $scope.responceData = responce.ledgerData;
                        $scope.openeningStock = responce.openeningStock ;
                        $scope.totalRemaining = responce.totalRemaining ;

                        $scope.totalDelivery = responce.totalDelivery ;

                        $scope.reportLoader = false;
                        angular.forEach($scope.responceData ,function(value ,key){
                            if(value.discription == 'OPENING BALANCE'){
                                 $scope.TotoalOpeningBalnce = value.delivery;
                                 $scope.OpeningReciveAmount = value.reciveAmount;
                            }else {
                                $scope.totalDelievry =  $scope.totalDelievry +Number(value.delivery);
                                $scope.totalRecive =$scope.totalRecive + Number(value.reciveAmount);
                            }
                        });
                        $scope.showOpeningBalance = true ;
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

    $scope.printFunction =function(){

        var divToPrint=document.getElementById("printTalbe");
        newWin= window.open(divToPrint);
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

