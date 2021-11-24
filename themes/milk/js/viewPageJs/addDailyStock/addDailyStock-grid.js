/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var riderDailyStockGridModule = angular.module('riderDailyStockGridModule', ['angularjs-datetime-picker']);
riderDailyStockGridModule.controller('riderDailyStockGridCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter , $timeout) {
    //INITIALIZATION ===============================================================
    $scope.init = function (farmlist ,company_id , productList , saveNewStock_addURL ,googleMapURL , saveDeliveryURL) {

        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";
        $scope.company_id = company_id ;
        $scope.productList= productList;
        $scope.farmlist = farmlist ;
         angular.forEach($scope.productList ,function (value,key) {
             value.quantity ='';
             value.return_quantity ='';
             value.wastage ='';
             value.description ='';
             value.update_wastage =true;
             value.update_return_quantity =true;
         });
        $scope.saveNewStock_addURL = saveNewStock_addURL ;
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
        $scope.saveDeliveryURL = saveDeliveryURL ;
        $scope.sortReverse = true ;
        $scope.farm_id='0';

        $scope.today_production_quantity_data_laod = false ;

        $scope.todayStock();
        $scope.searchproduct =productList[0].name;
    }

     $scope.todayStock =function(){
         $scope.farm_id =  document.getElementById("farm_id_value").value;



         var sendData ={
             'date': $scope.startDate,
             'farm_id': $scope.farm_id,
         }
         $http.post($scope.saveNewStock_addURL+"_todayStock", sendData)
             .success(function (data, status, headers, config) {

                 $scope.TodayStockstockList =data;
                 angular.forEach($scope.TodayStockstockList,function (value ,key) {
                     value.updateMode= false;
                 });

                 $scope.change_select_all_farm($scope.farm_id);

             })
             .error(function (data, status, header, config) {

         });
     }

     $scope.edit_watageStock = function(list){
        list.updateMode = true;

     }
     $scope.edit_watageStock_save = function(list){

         $http.post($scope.saveNewStock_addURL+"_updatWastageStock", list)
             .success(function (data, status, headers, config) {
                 list.updateMode = false ;
             })
             .error(function (data, status, header, config) {

             });

     }

    $scope.editReturn = function(){
        angular.forEach($scope.productList ,function (value,key) {

            value.update_return_quantity =!value.update_return_quantity;

            value.return_quantity ='';

        });
    }

    $scope.editWastage = function(){
        angular.forEach($scope.productList ,function (value,key) {
            value.update_wastage =!value.update_wastage;
            value.wastage ='';
        });
    }
    $scope.saveStockFunction = function () {
        $scope.farm_id =  document.getElementById("farm_id_value").value;

         var sendData ={
             'farm_id':$scope.farm_id,
             'date':$scope.startDate,
             'productList':$scope.productList
         }
          $scope.imageLoading = true ;
        $http.post($scope.saveNewStock_addURL, sendData)
            .success(function (data, status, headers, config) {
                angular.forEach($scope.productList ,function (value,key) {
                    value.quantity ='';
                    value.return_quantity ='';
                    value.wastage ='';
                    value.description ='';
                });
                $scope.imageLoading = false ;
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
                'RiderID'  :productID
            }
            var RiderObject = angular.toJson(data);

            $scope.imageLoading = true ;
            $http.post($scope.getDialyDeliveryCustomerURL ,RiderObject)
                .success(function(reponseData , status ){
                    $scope.customerList = reponseData.data ;
                    $scope.count = reponseData.count ;
                    $scope.imageLoading = false ;

                })
                .error(function () {
                });
        }
    }

    $scope.delete_daily_stock = function(list){

        $http.post($scope.saveNewStock_addURL+"_delete_daily_stock" ,list.daily_stock_id)
            .success(function(reponseData , status ){
                var index = $scope.TodayStockstockList.indexOf(list);
                $scope.TodayStockstockList.splice(index, 1);
            })
            .error(function () {
            });
    }

    $scope.change_select_all_farm = function(farm_id){

          var send_data ={
              'startDate':$scope.startDate,
              'farm_id':$scope.farm_id,
          }

        $http.post($scope.saveNewStock_addURL+"_getProductionStock", send_data)
            .success(function (data, status, headers, config) {

                $scope.today_production_quantity = data ;

                $scope.today_production_quantity_data_laod = true ;
                 // debugger ;
            })
            .error(function (data, status, header, config) {
            });


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


