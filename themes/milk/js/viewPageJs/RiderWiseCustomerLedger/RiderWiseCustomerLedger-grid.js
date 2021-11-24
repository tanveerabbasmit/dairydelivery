/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var riderDailyStockGridModule = angular.module('riderDailyStockGridModule', ['angularjs-datetime-picker']);
riderDailyStockGridModule.controller('riderDailyStockGridCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter , $timeout) {
    //INITIALIZATION ===============================================================
    $scope.init = function (category_list , year ,monthNum ,fiveDayAgo ,  company_id , riderList , getDialyDeliveryCustomerURL ,googleMapURL , saveDeliveryURL) {



        $scope.category_list = category_list;
        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";
        $scope.year = year+'';
        $scope.monthNum = monthNum+'' ;
        $scope.company_id = company_id ;
        $scope.riderList= riderList;
        $scope.getDialyDeliveryCustomerURL = getDialyDeliveryCustomerURL ;
        $scope.selectRiderID ='0';
        $scope.client_payment_type =['0'];
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
        $scope.customer_category_id ='0';

        $scope.monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];

        $scope.changeMonth();

        $scope.grand_customer_total =0;

    }


    $scope.change_category = function(){
        $scope.selectRiderID ='0';
    }

    $scope.changeMonth = function(){
        $scope.selectedMonthName= $scope.monthNames[Number($scope.monthNum)-1];

    }

    $scope.changeRider = function (riderID) {

        $http.post($scope.getDialyDeliveryCustomerURL, riderID)
            .success(function (data, status, headers, config) {

            })
            .error(function (data, status, header, config) {

            });

    }

    $scope.selectRiderOnChange = function(riderid){



        if(riderid ==''){
            $scope.todayDeliveryproductList ='';
        }else {
            var data = {
                'year' : $scope.year ,
                'monthNum': $scope.monthNum ,
                'RiderID':riderid,
                'customer_category_id':$scope.customer_category_id,
                'client_payment_type':$scope.client_payment_type
            }
            var RiderObject = angular.toJson(data);
            $scope.grand_customer_total =0;
            $scope.imageLoading = true ;
            $http.post($scope.getDialyDeliveryCustomerURL ,RiderObject)
                .success(function(reponseData , status ){

                    $scope.todayDeliveryproductList = reponseData.finalData ;

                    angular.forEach($scope.todayDeliveryproductList ,function (value ,key) {

                         if(value.client_id=='Sub Total'){

                         }else {

                             $scope.grand_customer_total =  $scope.grand_customer_total +1 ;
                         }

                    });

                    $scope.avgRate = reponseData.avgRate ;
                    $scope.quantity = reponseData.quantity ;
                    $scope.grandTotal = reponseData.grandTotal ;

                    $scope.totalOutStandingBalance = 0;
                    $scope.amountPaid = 0;
                    $scope.difference = 0;
                    $scope.endDateBalance = 0;
                    $scope.final_total_amount_opening_sum = 0;
                    $scope.totaldeliverySum_current_sum = 0;

                    angular.forEach($scope.todayDeliveryproductList , function(value ,key){
                         if(value.sum_record =='sum_record'){
                              //debugger ;
                             $scope.totalOutStandingBalance = Number($scope.totalOutStandingBalance) + Number(value.balance);
                             $scope.amountPaid = Number($scope.amountPaid) + Number(value.totalMakePayment);
                             $scope.difference = Number($scope.difference) + Number(value.difference);
                             $scope.endDateBalance = Number($scope.endDateBalance) + Number(value.endDateBalance);
                             $scope.final_total_amount_opening_sum = Number($scope.final_total_amount_opening_sum) + Number(value.final_total_amount_opening);
                             $scope.totaldeliverySum_current_sum = Number($scope.totaldeliverySum_current_sum) + Number(value.totaldeliverySum_current);

                         }

                    });
                    
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

        $scope.customer_category_id ='0';
        angular.forEach($scope.riderList , function(value ,key){

            if(value.rider_id ==riderId){

                $scope.selectedRider = value.fullname;
            }
        })
    }

    $scope.get_rate_list = function(totaldeliverySum_current ,totaldeliverySum_curren) {
        alert(totaldeliverySum_curren);
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


