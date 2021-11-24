/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var riderDailyStockGridModule = angular.module('riderDailyStockGridModule', ['angularjs-datetime-picker']);
riderDailyStockGridModule.controller('riderDailyStockGridCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter , $timeout) {
    //INITIALIZATION ===============================================================
    $scope.init = function (zoneList , year ,monthNum ,fiveDayAgo ,  company_id , riderList , getDialyDeliveryCustomerURL ,googleMapURL , saveDeliveryURL) {



        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";

        $scope.zoneList = zoneList ;
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

        $scope.selectedRider = 'non'
        $scope.zone_id ='0';
    }

    $scope.changeRider = function (riderID) {

        $http.post($scope.getDialyDeliveryCustomerURL, riderID)
            .success(function (data, status, headers, config) {

            })
            .error(function (data, status, header, config) {

            });

    }
    $scope.changeSelectZOne = function(){
        $scope.selectRiderID ='0'
    }

    $scope.selectRiderOnChange = function(riderid){

        if(riderid ==''){
            $scope.todayDeliveryproductList ='';
        }else {
            var data = {
                'year' : $scope.year ,
                'monthNum' : $scope.monthNum ,
                'RiderID'  : $scope.selectRiderID,
                'zone_id'  :$scope.zone_id
            }
            var RiderObject = angular.toJson(data);

            $scope.imageLoading = true ;
            $http.post($scope.getDialyDeliveryCustomerURL ,RiderObject)
                .success(function(reponseData , status ){
                    $scope.todayDeliveryproductList = reponseData.report ;
                    $scope.grand = reponseData.grand ;

                    $scope.grand_quantity = reponseData.grand_quantity ;

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

        var divToPrint=document.getElementById("customers");
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


