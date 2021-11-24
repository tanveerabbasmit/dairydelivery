/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('clintManagemaent', ['angularjs-datetime-picker']);
app.controller('clintManagemaent', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (data , getClientLedgherReportURL ,oneCustomerAmountListallCustomerList,base_url){


        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";

        $scope.action_date =data.action_date;
        $scope.base_url =base_url;


        $scope.oneCustomerAmountListallCustomerListURL =oneCustomerAmountListallCustomerList

        $scope.SelectedCustomer ='Select Customer'
        $scope.getAllCustomerList();

        $scope.quantity ='';

    }

    $scope.save_sample_schedule =function (){
        if($scope.clientID){
            var send_data ={
                'client_id': $scope.clientID,
                'quantity': $scope.quantity,
                'action_date': $scope.action_date,
            }
            $http.post($scope.base_url+"_save_sample_schedule_function",send_data )
                .success(function(responce){

                    $scope.clientList = responce ;
                    $scope.imageLoader = false;
                })
                .error(function (responce) {

                });
        }else{
            alert();
        }



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
                $scope.taskMessage = 'Please Select  Customer';
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
                        'quantity' : $scope.quantity
                    }

                    $scope.reportLoader = true;
                    $scope.pageShow = true ;
                    $scope.totalDelievry = Number(0);
                    $scope.totalRecive = Number(0);
                    $http.post( $scope.getClientLedgherReportURL+'save_default_quantity' , data)
                        .success(function(responce , data){

                           alert(responce.message);

                            $scope.imageLoader = false ;

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

