/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var riderDailyStockGridModule = angular.module('riderDailyStockGridModule', ['angularjs-datetime-picker']);
riderDailyStockGridModule.controller('riderDailyStockGridCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter , $timeout) {
    //INITIALIZATION ===============================================================
    $scope.init = function (discount_type , year ,monthNum ,fiveDayAgo ,  company_id , riderList , getDialyDeliveryCustomerURL ,googleMapURL , saveDeliveryURL) {

        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";
        $scope.discount_type  = discount_type ;
        $scope.year = year+'';
        $scope.monthNum = monthNum+'' ;
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

        $scope.startDate =  fiveDayAgo ;
        $scope.endDate =  $scope.todate;
        $scope.saveDeliveryURL = saveDeliveryURL ;
        $scope.sortReverse = true ;
        $scope.selectedRider = 'non';
        $scope.totalcolumn  = Number(discount_type.length) + 5
         angular.forEach($scope.riderList ,function (value,key) {

             value.loading = true ;
             value.nodataFound = false ;
         })
        $scope.totalRider =  Number($scope.riderList.length) -1 ;




    }

    $scope.selectRiderOnChange = function(riderid){
        $scope.grand_total_paid = 0;
        $scope.grand_total_discount = 0;
        angular.forEach($scope.riderList ,function (value,key) {
            value.loading = true ;
            value.nodataFound = false ;
            value.discountList = [] ;
        })


        if(riderid ==0){

            $scope.discountList = $scope.riderList;
            $scope.selectRiderOnChangeALl_test();
            $scope.selectAllRider = true;
        }else {
            $scope.discountList =[];
            angular.forEach($scope.riderList ,function (value,key) {
               if(value.rider_id ==riderid){
                   $scope.discountList.push(value);
               }
            })

            $scope.selectRiderOnChangeALl_test();
            $scope.selectAllRider = false;
        }


    }

    $scope.changeRider = function (riderID) {
        $http.post($scope.getDialyDeliveryCustomerURL, riderID)
            .success(function (data, status, headers, config) {
            })
            .error(function (data, status, header, config) {

            });

    }

    $scope.selectRiderOnChangeALl = function(value){

        if(value.rider_id ==''){
            $scope.todayDeliveryproductList ='';
        }else {

            var data = {
                'year' : $scope.year ,
                'monthNum' : $scope.monthNum ,
                'RiderID'  :value.rider_id
            }
            var RiderObject = angular.toJson(data);
            $scope.imageLoading = true ;
            $http.post($scope.getDialyDeliveryCustomerURL ,RiderObject)
                .success(function(reponseData , status ){
                      var oneObject = {
                         'fullname' : value.fullname,
                         'discountList' : reponseData.report,
                      }
                    $scope.todayDeliveryproductList = reponseData.report ;
                    $scope.total_amount = reponseData.totalamount ;
                    $scope.amount_paid = reponseData.amount_paid ;
                    if(reponseData.report.length >0){
                        $scope.discountList.push(oneObject);
                    }

                    $scope.imageLoading = false;

                })
                .error(function () {
                });
        }
    }

    $scope.selectRiderOnChangeALl_test = function(){

        angular.forEach($scope.discountList ,function (value ,key) {

            var data = {
                'year' : $scope.year ,
                'monthNum' : $scope.monthNum ,
                'RiderID'  :value.rider_id
            }
            var RiderObject = angular.toJson(data);
            $scope.imageLoading = true ;
            $http.post($scope.getDialyDeliveryCustomerURL ,RiderObject)
                .success(function(reponseData , status ){
                    var total_paid = 0 ;
                    var total_discount = 0 ;
                    angular.forEach(reponseData.report ,function (value ,key) {
                        total_paid = Number(total_paid) + Number(value.amount_paid);
                        total_discount = Number(total_discount) + Number(value.total_discount);
                    });
                    value.discountList = reponseData.report ;
                    value.one_rider_discount_wise_sum = reponseData.one_rider_discount_wise_sum ;
                    value.total_paid = total_paid ;
                    value.total_discount = total_discount ;
                    if(reponseData.report.length ==0 ){
                        value.nodataFound = true ;
                    }


                    if($scope.totalRider ==key){
                    $scope.findGrandTotal();
                    }
                    value.loading = false ;
                    $scope.imageLoading = false;
                })
                .error(function () {
                });
        })


    }
    $scope.findGrandTotal = function(){
          $scope.grandDiscount =[];
         angular.forEach( $scope.discount_type ,function (value ,key) {
             $scope.grandDiscount.push(0);


         })

        angular.forEach($scope.discountList ,function (value ,key) {
            var grand_total_object  = value.one_rider_discount_wise_sum ;

            angular.forEach(value.one_rider_discount_wise_sum ,function (value ,key) {

                $scope.grandDiscount[key] =$scope.grandDiscount[key] + value;

            } )
            $scope.grand_total_paid =Number($scope.grand_total_paid) +Number(value.total_paid);
            $scope.grand_total_discount =Number($scope.grand_total_discount) +Number(value.total_discount);
        })
    }
    $scope.selectRiderOnChangeOne = function(riderid){
        $scope.discountList = [];
        if(riderid ==''){
            $scope.todayDeliveryproductList ='';
        }else {
            var data = {
                'year' : $scope.year ,
                'monthNum' : $scope.monthNum ,
                'RiderID'  :riderid
            }
            var RiderObject = angular.toJson(data);

            $scope.imageLoading = true ;
            $http.post($scope.getDialyDeliveryCustomerURL ,RiderObject)
                .success(function(reponseData , status ){

                    var oneObject = {
                        'fullname' : riderid.fullname,
                        'discountList' : reponseData.report,
                    }

                    $scope.todayDeliveryproductList = reponseData.report ;
                    $scope.total_amount = reponseData.totalamount ;
                    $scope.amount_paid = reponseData.amount_paid ;
                    if(reponseData.report.length >0){
                        $scope.discountList.push(oneObject);
                    }


                    $scope.imageLoading = false;

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

    $scope.printFunction =function(){

        var divToPrint=document.getElementById("printTalbe");
        newWin= window.open("");
        newWin.document.write(divToPrint.outerHTML);
        newWin.print();
        newWin.close();
    }
    $scope.changeSelectRider = function(riderId){


        angular.forEach($scope.riderList , function(value ,key){

            if(value.rider_id ==riderId){

                $scope.selectedRider = value.fullname;
            }
        })
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


