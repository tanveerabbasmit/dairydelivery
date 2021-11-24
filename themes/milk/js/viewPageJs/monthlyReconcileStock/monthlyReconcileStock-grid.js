/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var riderDailyStockGridModule = angular.module('riderDailyStockGridModule', ['angularjs-datetime-picker']);
riderDailyStockGridModule.controller('riderDailyStockGridCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter , $timeout) {
    //INITIALIZATION ===============================================================
    $scope.init = function (year , todayMonth , lableObject ,productList , todayData , getDialyDeliveryCustomerURL ,googleMapURL , saveDeliveryURL) {

        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";

         $scope.selectYear = year+"";
         $scope.todayMonth = todayMonth+"" ;

       // debugger ;
       // alert($scope.todayMonth);

         $scope.lableObject = lableObject ;
         $scope.productList = productList ;
         $scope.todayData = todayData.report_data ;
         $scope.end_total_sum = todayData.end_total_sum ;


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
        $scope.startDate = selectYear + '-' + month + '-' + date;
        $scope.endDate = selectYear + '-' + month + '-' + date;
        $scope.saveDeliveryURL = saveDeliveryURL ;
        $scope.showProgressBar = true ;
        $scope.riderStockListloading = [];
        $scope.pageLoad = false;
         $scope.riderData3();

        $scope.pervousDate_count = '3';

        


    }
    $scope.riderData2 = function(todate) {
        $scope.todate = todate;

        $scope.showProgressBar = true ;
        $scope.riderStockListloading = [];
        var numberOfLoadRider= 0;
        var startAray = 0;
          var check_increment = 0;
        for(startAray ; startAray<=3;startAray++){

            var sendData = {
                todate: todate ,
                DayNumber  : check_increment,
            }
            $http.post($scope.getDialyDeliveryCustomerURL+'2', sendData)
                .success(function (data, status, headers, config) {

                    $scope.testObject  = data;
                    $scope.riderStockListloading.push(data);
                    $scope.loadPerCentage = ((Number($scope.pervousDate_count)/Number(check_increment)))*100
                    check_increment++;
                     if(check_increment ==check_increment ){
                         $scope.showProgressBar = false;
                     }


                })
                .error(function (data, status, header, config) {

                });
        }




    }
    $scope.SearchNewDate = function (today) {
        $scope.pervousDate_count = '3';
        $scope.riderStockListloading = [] ;
        $scope.riderData3(today ,0);
    }
    $scope.getPreviousData = function () {
        $scope.riderStockListloading = [] ;
        $scope.riderData3($scope.todate ,0);
    }
    $scope.riderData3 = function() {

        $scope.showProgressBar = true;
        var numberOfLoadRider= 0;

            var sendData = {


                startDate:  $scope.startDate ,
                endDate:  $scope.endDate ,
                DayNumber  : 1,
            }
             $scope.pageLoad = true;

            $http.post($scope.getDialyDeliveryCustomerURL+'2_monthly', sendData)
                .success(function (data, status, headers, config) {
                    $scope.testObject  = data;
                    $scope.riderStockListloading = [];
                    $scope.riderStockListloading.push(data);

                    $scope.pageLoad = false;

                })
                .error(function (data, status, header, config) {

                });





    }
    $scope.riderData = function(todate) {
        $scope.todate = todate;

        $scope.showProgressBar = true ;
          var totalRider =  $scope.riderList.length;
        var numberOfLoadRider= 0;
         $scope.riderStockListloading = [];

        angular.forEach($scope.riderList , function (value ,key) {


                var riderID = value.rider_id ;
               var sendData = {
                    todate: todate ,
                    riderID : riderID
                }
            $http.post($scope.getDialyDeliveryCustomerURL, sendData)
                .success(function (data, status, headers, config) {
                    $scope.riderStockListloading.push(data);
                    numberOfLoadRider++;
                    $scope.loadPerCentage = (Number(numberOfLoadRider)/Number(totalRider))*100;

                    if($scope.riderStockListloading.length == totalRider){
                        $scope.showProgressBar = false;
                        $scope.assignData($scope.riderStockListloading);

                        $scope.sortReverse = true ;

                    }

                })
                .error(function (data, status, header, config) {

                });

        })


    }
    $scope.assignData = function (list) {
        $scope.riderStockList =list ;

        $http.post($scope.getDialyDeliveryCustomerURL+'Count_total',  $scope.todate)
            .success(function (data, status, headers, config) {

                  $scope.totalCount = data;
            })

        $http.post($scope.getDialyDeliveryCustomerURL+'previous_date',  $scope.todate)
            .success(function (data, status, headers, config) {
                     $scope.DateObject = data;

            })
    }

     $scope.changeRider = function (riderID) {

         $http.post($scope.getDialyDeliveryCustomerURL, riderID)
             .success(function (data, status, headers, config) {

             })
             .error(function (data, status, header, config) {

             });

     }

     $scope.selectRiderOnChange = function(riderID){

         if(riderID == '0'){
             $scope.todayDeliveryproductList ='';
             $scope.taskMessage = 'Select Rider First';
             document.getElementById("alertMessage").style.display = "block";
             setTimeout(function(){
                     document.getElementById("alertMessage").style.display = 'none';
                 },
                 1500);

         }else {
              var data = {
                  'date' : $scope.todate ,
                  'RiderID'  :$scope.selectRiderID
              }
              var RiderObject = angular.toJson(data);

             $scope.imageLoading = true ;
             $http.post($scope.getDialyDeliveryCustomerURL ,RiderObject)
                 .success(function(reponseData , status ){

                     $scope.todayData = reponseData.list ;
                     $scope.totalSum = reponseData.totalSum ;

                     $scope.imageLoading = false ;

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
                'data':[regularOrderList]
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
    $scope.closeDelivery = function(regularOrderList) {
        regularOrderList.updateMode = false;
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


