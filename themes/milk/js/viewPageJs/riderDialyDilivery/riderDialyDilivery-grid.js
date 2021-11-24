/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var riderDailyStockGridModule = angular.module('riderDailyStockGridModule', ['angularjs-datetime-picker']);
riderDailyStockGridModule.controller('riderDailyStockGridCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter , $timeout) {
    //INITIALIZATION ===============================================================
    $scope.init = function (payment_term ,default_product_id ,product_list,allow_delete,company_id , riderList , getDialyDeliveryCustomerURL ,googleMapURL , saveDeliveryURL) {



        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";
        $scope.allow_delete = allow_delete ;
         $scope.company_id = company_id ;
         $scope.riderList= riderList;

         $scope.product_list = product_list;
         $scope.payment_term = payment_term;


         $scope.getDialyDeliveryCustomerURL = getDialyDeliveryCustomerURL ;
         $scope.selectRiderID ='';
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


        $scope.saveDeliveryURL = saveDeliveryURL ;

        $scope.sortReverse = true ;

        $scope.roderLoadData = false;
        $scope.filter_by ='0';
        $scope.scheduled_customer ='0';
        $scope.payment_term_id ='0';
        $scope.product_id =default_product_id+'';



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
                  'product_id' : $scope.product_id ,
                  'payment_term_id' : $scope.payment_term_id ,
                  'date' : $scope.todate ,
                  'RiderID'  :productID,
                  'filter_by'  :$scope.filter_by,
                  'scheduled_customer'  :$scope.scheduled_customer
              }
              var RiderObject = angular.toJson(data);

             $scope.imageLoading = true ;
             $http.post($scope.getDialyDeliveryCustomerURL ,RiderObject)
                 .success(function(reponseData , status ){
                     $scope.todayDeliveryproductList = reponseData ;

                     $scope.deliveredQuantityShowDive = true;
                     $scope.imageLoading = false ;

                     var x = 0;
                     var y = 0 ;
                     var z= 0;

                     /*regualr Customer*/

                     $scope.regular_x = 0;
                     $scope.regular_y = 0;
                     $scope.regular_z = 0;

                     /*Sample Customer*/
                     $scope.sample_x = 0;
                     $scope.sample_y = 0;
                     $scope.sample_z = 0;


                     angular.forEach($scope.todayDeliveryproductList , function(value , key){

                         x = Number(x) + Number(value.regularQuantity);
                         y = Number(y) + Number(value.totalSpecialQuantity);
                         z = Number(z) + Number(value.deliveredQuantity);
                         value.updateMode = false ;
                         value.new_product_rate = '' ;
                         value.quantity = Number(value.regularQuantity) + Number(value.totalSpecialQuantity);
                         value.makeDeliveryLoader = false;
                         if (value.client_type ==1){
                             $scope.regular_x = Number($scope.regular_x) + Number(value.regularQuantity);
                             $scope.regular_y = Number($scope.regular_y) + Number(value.totalSpecialQuantity);
                             $scope.regular_z = Number($scope.regular_z) + Number(value.deliveredQuantity);
                         }

                         if (value.client_type ==2){
                             $scope.sample_x = Number($scope.sample_x) + Number(value.regularQuantity);
                             $scope.sample_y = Number($scope.sample_y) + Number(value.totalSpecialQuantity);
                             $scope.sample_z = Number($scope.sample_z) + Number(value.deliveredQuantity);
                         }




                     });
                     $scope.regularTotal = x;
                     $scope.specialTotal = y;
                     $scope.deliveryTotal = z ;
                     $scope.roderLoadData = true;
                 })
                 .error(function () {
                 });
         }
     }

     $scope.checkObjectlenght = function (x , y ) {
         var itemsLength = Object.keys(x).length;
           if(itemsLength == 0){
              return false
           }else {
               return true ;
           }
     }


    $scope.showMap=function (lat,long){
        if(long.length == '0'  || long.length =='0' ){
            alert("Latitude And Longitude field Are required");
        }else{
            window.open($scope.googleMapURL+"?lat="+lat+"&lon="+long, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=0,left=0,width=800,height=600");
        }

    }

    $scope.setCompanyLimit = function(regularOrderList){
        regularOrderList.updateMode = true ;
    }
    $scope.SaveDelivery = function(regularOrderList) {
          var  remarks;

         if(regularOrderList.deliveredQuantity>0){
              remarks = prompt("You can put remarks here.", "");
         }
        regularOrderList.makeDeliveryLoader = true ;
          var productObject = {
              'price':regularOrderList.price
          }

         var sendData = {
             'client_id':regularOrderList.client_id,
             'company_branch_id':$scope.company_id,
              'rider_id' :$scope.selectRiderID,
              'selectDate' :$scope.todate,
              'deliveredQuantity' :regularOrderList.deliveredQuantity,
               'lat':'0' ,
               'longi':'0' ,
               'broken':'0' ,
               'perfect':'0' ,
                'data':[regularOrderList],
                 'remarks':remarks
         }

        $http.post($scope.saveDeliveryURL ,sendData)
            .success(function(reponseData , status ){
                  if(reponseData.success){
                     regularOrderList.updateMode = false;
                      regularOrderList.makeDeliveryLoader = false ;
                      regularOrderList.deliveredQuantity = regularOrderList.quantity;
                      regularOrderList.time = reponseData.delivery_time;
                      regularOrderList.reject_delivery = false ;
                  }else {
                      alert(reponseData.message);

                      regularOrderList.updateMode = false;
                      regularOrderList.makeDeliveryLoader = false ;
                  }


            })
            .error(function () {
            });



    }
    $scope.SaveDelivery_delete = function(regularOrderList) {



        var code = prompt("Plz enter code", "");
        var remarks = prompt("You can put remarks here.", "");


       // var r = confirm("Are you Sure ");
        if (true) {


            regularOrderList.makeDeliveryLoader = true ;
            var productObject = {
                'price':regularOrderList.price
            }

            var sendData = {
                'client_id':regularOrderList.client_id,
                'company_branch_id':$scope.company_id,
                'rider_id' :$scope.selectRiderID,
                'selectDate' :$scope.todate,
                'deliveredQuantity' :regularOrderList.deliveredQuantity,
                'lat':'0' ,
                'longi':'0' ,
                'broken':'0' ,
                'perfect':'0' ,
                'data':[regularOrderList],
                 'code':code,
                 'remarks':remarks
            }
            $http.post($scope.saveDeliveryURL+"_delete_delivery" ,sendData)
                .success(function(reponseData , status ){
                    if(reponseData.success){
                        regularOrderList.updateMode = false;
                        regularOrderList.makeDeliveryLoader = false ;
                        regularOrderList.deliveredQuantity = 0;
                        regularOrderList.time = 0;
                        regularOrderList.reject_delivery = false ;
                    }else {
                        alert(reponseData.message);

                        regularOrderList.updateMode = false;
                        regularOrderList.makeDeliveryLoader = false ;
                    }


                })
                .error(function () {
                });
        }





    }
    $scope.closeDelivery = function(regularOrderList) {
        regularOrderList.updateMode = false;
    }

    $scope.updateClietProductRate_model = function(list){
         $scope.updateRateModel = true ;
         $scope.updateRatelistObject =list;
    }

    $scope.save_new_rate = function () {

         var client_id =  $scope.updateRatelistObject.client_id;
         var product_id =  $scope.updateRatelistObject.product_id;

        $http.post($scope.saveDeliveryURL+"_save_new_rate" ,$scope.updateRatelistObject)
            .success(function(reponseData , status ){

                $scope.updateRateModel = false ;

                angular.forEach( $scope.todayDeliveryproductList ,function (value ,key) {

                    if(value.client_id==client_id && value.product_id==product_id){
                        value.price = $scope.updateRatelistObject.new_product_rate ;
                    }
                });

            })
            .error(function () {
        });
    }
    $scope.get_current_balance = function(list){
        $http.post($scope.saveDeliveryURL+"_get_user_currect_balance" ,list.client_id)
            .success(function(reponseData , status ){
                list.current_balance_of_client =reponseData;
            })
            .error(function () {
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


