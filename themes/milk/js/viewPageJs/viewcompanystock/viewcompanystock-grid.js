/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('clintManagemaent', ['angularjs-datetime-picker']);
app.controller('clintManagemaent', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (productList , getClientLedgherReportURL){


        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";

        $scope.productList = productList ;



        try{
            $scope.selectProductID =   $scope.productList[0].product_id;


        }catch(err) {

            alert(err);
        }


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

      $scope.SelectedCustomer = 'Select Cutomer'

        $scope.showOpeningBalance = false ;

        $scope.sortReverse = true ;

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
                'clientID' :$scope.selectProductID ,
                'startDate' :  $scope.startDate ,
                'endDate' : $scope.endDate,
                'product_id' : $scope.selectProductID
            }

            if($scope.selectProductID == ''){
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
                        $scope.responceData = responce ;

                        $scope.reportLoader = false;
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

