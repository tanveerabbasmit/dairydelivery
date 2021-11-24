/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var riderDailyStockGridModule = angular.module('riderDailyStockGridModule', ['angularjs-datetime-picker']);
riderDailyStockGridModule.controller('riderDailyStockGridCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter , $timeout) {
    //INITIALIZATION ===============================================================
    $scope.init = function (rider_user_list , company_id , riderList , getDialyDeliveryCustomerURL ,googleMapURL , saveDeliveryURL) {

        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";


         $scope.rider_user_list = rider_user_list ;
         $scope.company_id = company_id ;
         $scope.riderList= riderList;
         $scope.getDialyDeliveryCustomerURL = getDialyDeliveryCustomerURL ;
         $scope.selectRiderID ='0';
         $scope.deliveredQuantityShowDive = false;

         $scope.imageLoading = false ;

        $scope.googleMapURL = googleMapURL ;



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
        $scope.todate = selectYear + '-' + month + '-' + date;

        $scope.startDate = $scope.todate ;
        $scope.endDate = $scope.todate ;

        $scope.payment_mode = '0' ;
        $scope.payment_type = '0' ;
        $scope.enter_by = '' ;

        $scope.saveDeliveryURL = saveDeliveryURL ;

        $scope.sortReverse = true ;
        $scope.full_name_rider_print = 'All Rider';
        $scope.payment_mode_name = 'All Mode';

    }
    $scope.payment_mode_change_function =function(mode_id){
        $scope.payment_mode_name = 'All Mode';
        if(mode_id==2){
            $scope.payment_mode_name = 'Cheque';
        }
        if(mode_id==3){
            $scope.payment_mode_name = 'Cash';
        }
        if(mode_id==5){
            $scope.payment_mode_name = 'Bank Transaction';
        }
        if(mode_id==6){
            $scope.payment_mode_name = 'Card Transaction';
        }
    }

    $scope.rider_name_by_id_function = function (rider_id){



        $scope.full_name_rider_print = 'All Rider'
       angular.forEach($scope.riderList,function (value,key){

            if(value.rider_id ==rider_id){

                $scope.full_name_rider_print = value.fullname

            }
       });



    }

     $scope.changeRider = function (riderID) {

         $http.post($scope.getDialyDeliveryCustomerURL, riderID)
             .success(function (data, status, headers, config) {

             })
             .error(function (data, status, header, config) {

             });

     }

     $scope.selectRiderOnChange = function(productID){

         if(productID ==''){
             $scope.todayDeliveryproductList ='';
         }else {
              var data = {
                  'startDate' : $scope.startDate ,
                  'endDate' : $scope.endDate ,
                  'RiderID'  :productID,
                  'payment_mode'  :$scope.payment_mode,
                  'payment_type'  :$scope.payment_type,
                  'enter_by'  :$scope.enter_by
              }
              var RiderObject = angular.toJson(data);

             $scope.imageLoading = true ;
             $http.post($scope.getDialyDeliveryCustomerURL ,RiderObject)
                 .success(function(reponseData , status ){
                    $scope.customerList = reponseData.data ;
                    $scope.count = reponseData.count ;
                    $scope.totol_discount = reponseData.totol_discount ;
                    $scope.totol_net = reponseData.totol_net ;

                     $scope.imageLoading = false ;

                 })
                 .error(function () {
                 });
         }
     }


    $scope.printFunction =function(){
        var divToPrint=document.getElementById("printTalbe");
        newWin= window.open(divToPrint);
        newWin.document.write(divToPrint.outerHTML);
        newWin.print();
        newWin.close();
    }







}]);


riderDailyStockGridModule.directive('modal', function () {
    return {
        template: '<div class="modal fade">' +
        '<div class="modal-dialog">' +
        '<div class="modal-content"  style="width: 70% ; margin-left: 25% ; margin-top: 0%">' +
        '<div class="modal-header"  style="background-color: #D8DCE3">' +
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

riderDailyStockGridModule.factory('Excel',function($window){
    var uri='data:application/vnd.ms-excel;base64,',
        template='<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>',
        base64=function(s){return $window.btoa(unescape(encodeURIComponent(s)));},
        format=function(s,c){return s.replace(/{(\w+)}/g,function(m,p){return c[p];})};
    return {
        tableToExcel:function(tableId,worksheetName){
            var table=$(tableId),
                ctx={worksheet:worksheetName,table:table.html()},
                href=uri+base64(format(template,ctx));
            return href;
        }
    };
})
    .controller('MyCtrl',function(Excel,$timeout){
        $scope.exportToExcel=function(tableId){ // ex: '#my-table'
            $scope.exportHref=Excel.tableToExcel(tableId,'sheet name');
            $timeout(function(){location.href=$scope.fileData.exportHref;},100); // trigger download
        }
    });


