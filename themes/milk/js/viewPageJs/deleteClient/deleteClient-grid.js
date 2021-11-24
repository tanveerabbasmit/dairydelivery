/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('clintManagemaent', ['angularjs-datetime-picker']);
app.controller('clintManagemaent', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (companyID , ClientOneObject , preferedTimeList , frequencyList , productCount ,
     clientCount  , zoneList , clientList , saveNewClientURL , EditClientURL ,
     deleteURL , checkAlredyExistClientUrl ,searchClientURL , nextPagePaginationURL
     ,getProductListUrl , PnextPagePaginationURL , selectFrequencyForOrderURL ,
      saveChangedayObjectQuantityURL , removeProductFormSchedualURL , getProductPriceListURL){

        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";


        $scope.clientList = clientList.clientList;


        $scope.curPage = 1;
        $scope.pageSize = 10;
        $scope.clientCount = Number(clientList.count);

        $scope.totalPages = Math.ceil( $scope.clientCount/ $scope.pageSize);



        var i ;
        $scope.switchObject = [];

        for(i= 1 ; i<=$scope.totalPages ; i++){
            var pageObject={
                'pageno':Number(i)
            }
            $scope.switchObject.push(pageObject);

        }

          $scope.productCount = productCount ;
          $scope.temporyPcurPage = 0 ;
        $scope.PcurPage = 0 ;
        $scope.pageSize = 10;
        $scope.PtotalPages = Math.ceil($scope.productCount/ $scope.pageSize);

        $scope.companyID = companyID;

        $scope.preferedTimeList = preferedTimeList ;
        $scope.frequencyList = frequencyList ;
        $scope.zoneList  =  zoneList ;

        $scope.saveNewClientURL = saveNewClientURL ;
        $scope.EditClientURL  = EditClientURL ;
        $scope.deleteURL  = deleteURL ;
        $scope.checkAlredyExistClientUrl = checkAlredyExistClientUrl ;
        $scope.searchClientURL = searchClientURL ;
        $scope.nextPagePaginationURL = nextPagePaginationURL ;
        $scope.getProductListUrl = getProductListUrl ;
        $scope.PnextPagePaginationURL = PnextPagePaginationURL ;
        $scope.selectFrequencyForOrderURL = selectFrequencyForOrderURL ;
        $scope.saveChangedayObjectQuantityURL = saveChangedayObjectQuantityURL ;
        $scope.removeProductFormSchedualURL = removeProductFormSchedualURL ;
        $scope.getProductPriceListURL = getProductPriceListURL ;

        $scope.search = '';
        $scope.ClientObject = {'security':'0','network_id':'0','fullName':'' ,'userName':'' ,'password':'' , 'father_or_husband_name':'', 'date_of_birth':$scope.dateOfBirth,'cnic':'' ,
            'address':'' , 'city':'',   'area':'' , 'cell_no_1':'' ,   'cell_no_2':'' ,
            'residence_phone_no':'' , 'email':'' , 'zone_id':'',  'is_active':'1'
            ,'is_deleted':'0' ,'clientProductObject':'','payment_term':'1','delivery_alert':'1','new_product_alert':'1'}
        $scope.checkAlredyExist = false ;
        $scope.temporaryProductList =   $scope.clientList;
        $scope.hideAndShowPagination = true ;
        angular.forEach( $scope.frequencyList ,function(value ,key) {
            value.slectDayForProducy = false ;
            value.quantity = 0 ;
        });

        $scope.orderStartDate = 0;

        $scope.mainPageSwitch = true;
        $scope.countryCode = '+92'
        $scope.phoneNumberFormate = false ;
        $scope.showHideproductListPrice = false;


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


        $scope.dateOfBirth = selectYear + '-' + month + '-' + date;

        $scope.ClientOneObject = ClientOneObject ;

        $scope.clientOneObject_DaySchedule =  $scope.ClientOneObject.dashBordaction;
        if( $scope.clientOneObject_DaySchedule){
            $scope.scheduleSee( $scope.ClientOneObject.client_name , $scope.ClientOneObject.client_id);
        }


      //  $scope.showDayScheduleFunction();

        $scope.serach_zone_id = '0' ;
        $scope.serach_status_id = '0' ;

    }




    $scope.addNewClient = function(){

          $scope.showAddNewClient = !$scope.showAddNewClient;

        $scope.ClientObject = {'security':0, 'network_id':'0','fullName':'' ,'userName':'' ,'password':'' , 'father_or_husband_name':'', 'date_of_birth':$scope.dateOfBirth,'cnic':'' ,
            'address':'' , 'city':'',   'area':'' , 'cell_no_1':'' ,   'cell_no_2':'' ,
            'residence_phone_no':'' , 'email':'' , 'zone_id':'',  'is_active':'1'
            ,'is_deleted':'0' ,'clientProductObject':'','payment_term':'1','daily_delivery_sms':'1','alert_new_product':'1'}
    };


    $scope.saveClient = function (ClientObject) {

        $scope.checkAlredyExist = false ;
        $http.post($scope.checkAlredyExistClientUrl ,  ClientObject.userName)
            .success(function (responcedata , status, headers, config) {

                if(responcedata){
                    $scope.checkAlredyExist = true ;
                }else {
                    
                     if(ClientObject.cell_no_1.length==13){

                         var sendData = angular.toJson(ClientObject);
                         $http.post($scope.saveNewClientURL, sendData)
                             .success(function (data, status, headers, config) {
                                 if(data.success){
                                     $scope.clientList.push(data.client[0]);
                                     $scope.showAddNewClient = !$scope.showAddNewClient;

                                     $scope.showHideproductListPrice = false ;
                                     $scope.clientCount++ ;
                                 }else{
                                     alert(angular.toJson(data.message));
                                 }
                             })
                             .error(function (data, status, header, config) {
                                 alert(data.message);
                                 subject.showLoader = false;
                             });
                     }else{
                         $scope.phoneNumberFormate = true ;
                     }
                }
            })
            .error(function (data, status, header, config) {
                alert(data.message);
                subject.showLoader = false;
            });
    }

    $scope.editClint = function (clint) {
        $scope.ClientObject = clint;
        $scope.showEditClient = !$scope.showEditClient;
        $scope.checkAlredyExist = false ;
        $scope.showHideproductListPrice = false;

    }
    $scope.editClientFunction = function (saveZone) {
        var sendData = angular.toJson(saveZone);

        $http.post($scope.EditClientURL, sendData)
            .success(function (data, status, headers, config) {
                if(data.success){
                    $scope.showEditClient = !$scope.showEditClient;
                    $scope.showHideproductListPrice = false ;
                }else{
                    alert(angular.toJson(data.message));
                }
            })
            .error(function (data, status, header, config) {
                alert(data.message);
                subject.showLoader = false;
            });
    }

    $scope.deleteClientButton = function (saveZone) {
          var sendData = angular.toJson(saveZone);
        var txt;
        var person = prompt("Security Code:", "");
        if ( person == "74123") {
            $http.post($scope.deleteURL, saveZone.client_id)
                .success(function (data, status, headers, config) {
                    if(data.success){
                        var index = $scope.clientList.indexOf(saveZone);
                        $scope.clientList.splice(index, 1);
                        $scope.clientCount--;
                    }else{
                        alert(angular.toJson(data.message));
                    }
                })
                .error(function (data, status, header, config) {
                    alert(data.message);
                    subject.showLoader = false;
                });
        } else {
                 alert('Code is Wrong');
        }

       /* if (confirm("Are you sure you want to delete this customer with all associated data!") == true) {

            $http.post($scope.deleteURL, saveZone.client_id)
                .success(function (data, status, headers, config) {
                    if(data.success){
                        var index = $scope.clientList.indexOf(saveZone);
                        $scope.clientList.splice(index, 1);
                        $scope.clientCount--;
                    }else{
                        alert(angular.toJson(data.message));
                    }
                })
                .error(function (data, status, header, config) {
                    alert(data.message);
                    subject.showLoader = false;
                });
        } else {

        }*/

    }

    $scope.searchClientFuction = function (search) {


        if(search.length >3){
            var sendData ={
                   'text': search,
                   'zone_id': $scope.serach_zone_id ,
                   'status_id':$scope.serach_status_id ,
            }
            $http.post($scope.searchClientURL ,  angular.toJson(sendData))
                .success(function (clientList , status, headers, config) {


                    $scope.clientList = clientList.clientList;

/*
                    $scope.curPage = 1;
                    $scope.pageSize = 10;
                    $scope.clientCount = Number(clientList.count);

                    $scope.totalPages = Math.ceil( $scope.clientCount/ $scope.pageSize);



                    var i ;
                    $scope.switchObject = [];

                    for(i= 1 ; i<=$scope.totalPages ; i++){
                        var pageObject={
                            'pageno':Number(i)
                        }
                        $scope.switchObject.push(pageObject);

                    }

                    debugger ;*/
                   // $scope.clientList = responcedata ;
                   // $scope.hideAndShowPagination = false ;
                   // $scope.mainPageSwitch = true ;

                })
                .error(function (data, status, header, config) {
                    alert(data.message);
                    subject.showLoader = false;
                });
        }

    }

    $scope.checkAlredyExistClientFunction = function (search , clientID) {
        var sendData ={
            'userName':search ,
             'client_id':clientID
        }
        $scope.checkAlredyExist = false ;
        $http.post($scope.checkAlredyExistClientUrl ,  sendData)
            .success(function (responcedata , status, headers, config) {

                if(responcedata){
                    $scope.checkAlredyExist = true ;
                }
            })
            .error(function (data, status, header, config) {
                alert(data.message);
                subject.showLoader = false;
            });
    }
    $scope.changeSearchBar = function(search) {
        if(!search){
            $scope.clientList =  $scope.temporaryProductList ;
            $scope.hideAndShowPagination = true ;
        }
    }

    $scope.nextPagePagination = function(page) {

          var sendData = {
              page : page ,
              serach_zone_id : $scope.serach_zone_id ,
              serach_status_id : $scope.serach_status_id
          }

        $http.post($scope.nextPagePaginationURL , angular.toJson(sendData))
            .success(function(clientList , status) {


                $scope.clientList = clientList.clientList;





                $scope.temporaryProductList =  clientList.clientList;;

            })
            .error(function (data , status) {

            })
    }

    $scope.onchangeZoneAndStatus = function() {

          var sendData = {
              page : 1 ,
              serach_zone_id : $scope.serach_zone_id ,
              serach_status_id : $scope.serach_status_id
          }
        $scope.hideAndShowPagination = true ;
        $scope.search = '' ;
        $http.post($scope.nextPagePaginationURL , angular.toJson(sendData))
            .success(function(clientList , status) {


                $scope.clientList = clientList.clientList;


                $scope.curPage = 1;
                $scope.pageSize = 10;
                $scope.clientCount = Number(clientList.count);

                $scope.totalPages = Math.ceil( $scope.clientCount/ $scope.pageSize);



                var i ;
                $scope.switchObject = [];

                for(i= 1 ; i<=$scope.totalPages ; i++){
                    var pageObject={
                        'pageno':Number(i)
                    }
                    $scope.switchObject.push(pageObject);

                }



                $scope.temporaryProductList =  clientList.clientList;;

            })
            .error(function (data , status) {

            })
    }

    $scope.PnextPagePagination = function(page) {
         $scope.temporyPcurPage = page ;
              var sendData = {
                  'page': page ,
                   'SelectClientID' : $scope.SelectClientID
              }
            var data =  (angular.toJson(sendData));
        $http.post($scope.PnextPagePaginationURL , data)
            .success(function(responceData , status) {
                $scope.clientOrderList = responceData.order;
                $scope.productList = responceData.product;

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
                var today= selectYear + '-' + month + '-' + date;

                angular.forEach($scope.productList , function (value , key) {
                    value.orderPlace = false ;
                    value.ObjectOFDayWiseQuantity = false ;
                    value.orderStartDate = today ;
                });
                angular.forEach($scope.productList , function (value1 , key) {
                    angular.forEach($scope.clientOrderList , function (value2 , key) {
                        if(value1.product_id == value2.product_id){
                            value1.orderPlace = true ;
                            value1.orderStartDate = value2.orderStartDate ;
                        }
                    });
                });


            })
            .error(function (data , status) {
            })
    }

    /*Start schedule See-------------------------*/

    $scope.scheduleSee = function(fullname ,id) {



          $scope.slectedClient = fullname;
          $scope.temporyPcurPage = 0 ;
          $scope.SelectClientID = id ;
        $http.post($scope.getProductListUrl , id)
            .success(function(responceData , status) {

               /*$scope.showScheduleSee = !$scope.showScheduleSee;*/
                $scope.clientOrderList = responceData.order;
                $scope.productList = responceData.product;

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
                var today= selectYear + '-' + month + '-' + date;
                angular.forEach($scope.productList , function (value , key) {
                    value.orderPlace = false ;
                    value.saveLoading = false ;
                    value.orderStartDate = today ;
                    value.order_type = 0 ;
                });
                angular.forEach($scope.productList , function (value1 , key) {
                    angular.forEach($scope.clientOrderList , function (value2 , key) {
                       if(value1.product_id == value2.product_id){

                           value1.orderPlace = true ;
                           value1.order_type = value2.order_type ;
                           value1.orderStartDate = value2.orderStartDate ;

                       }
                    });
                });
                $scope.mainPageSwitch = false ;
                if($scope.clientOneObject_DaySchedule){
                  //  $scope.showDayScheduleFunction($scope.productList[0]);
                }
            })
            .error(function (data , status) {

            })
    }
    $scope.goBackManPage = function(){

        $scope.mainPageSwitch = true ;
    }

    $scope.showDayScheduleFunction = function(product){



        $scope.selectedProduct = product.name;
        $scope.selectedProductID = product.product_id ;
        $scope.changeStartOrderDate(product.orderStartDate)

           angular.forEach($scope.productList , function(value , key){
                    value.ObjectOFDayWiseQuantity = false;
           });
          $scope.orderStartDate = product.orderStartDate ;
          var sendData = {
              'clientID' : $scope.SelectClientID,
               'productID' :product.product_id
          }

      $scope.showDaySchedule = !$scope.showDaySchedule;

        $http.post($scope.selectFrequencyForOrderURL , sendData)
            .success(function(responceData , status) {

                angular.forEach( $scope.frequencyList ,function(value ,key) {
                    value.slectDayForProducy = false ;
                    value.quantity = 0 ;
                    value.preferred_time_id = '1' ;
                });
                angular.forEach( $scope.frequencyList ,function(value1 ,key) {
                    angular.forEach(responceData ,function(value2 ,key) {
                         $scope.orderStartDate =   value2.orderStartDate;
                       if(value1.frequency_id ==value2.frequency_id){
                           value1.slectDayForProducy = true;
                           value1.quantity = value2.quantity;
                           value1.preferred_time_id = value2.preferred_time_id;
                       }
                    });
                });
            })
           .error(function (data , status) {
            })
        product.ObjectOFDayWiseQuantity = $scope.frequencyList ;

    }

    $scope.showDayScheduleFunction_interval = function(product){
        $scope.selectedProduct = product.name;
        $scope.changeStartOrderDate(product.orderStartDate)

           angular.forEach($scope.productList , function(value , key){
                    value.ObjectOFDayWiseQuantity = false;
           });
          $scope.orderStartDate = product.orderStartDate ;
          var sendData = {
              'clientID' : $scope.SelectClientID,
               'productID' :product.product_id
          }

      $scope.showDaySchedule_interval = !$scope.showDaySchedule_interval;
        var new_url = $scope.selectFrequencyForOrderURL+'_interval';

        $http.post(new_url , sendData)
            .success(function(responceData , status) {

                $scope.intervalScheduleObject =  responceData ;

            })
           .error(function (data , status) {
            })
        product.ObjectOFDayWiseQuantity = $scope.frequencyList ;
    }

    $scope.clientProductScheduleChangeSave =function(product){


        if(product.ObjectOFDayWiseQuantity){
            if(true){
                if($scope.orderStartDate){
                    product.saveLoading = true;
                    var  sendData = {
                        'productID': product.product_id ,
                        'clientID':$scope.SelectClientID ,
                        'orderStartDate':$scope.orderStartDate ,
                        'dayObject' : product.ObjectOFDayWiseQuantity
                    }
                    $http.post($scope.saveChangedayObjectQuantityURL , sendData)
                        .success(function(responceData , status) {
                            product.saveLoading = false;
                            product.ObjectOFDayWiseQuantity = false ;
                            product.orderPlace = true;
                            $scope.orderStartDate = false;
                        })
                        .error(function (data , status) {
                        })
                }else{
                    alert("please select start order Date ");
                }


            }else {
                alert("Select First");
            }
        }else {
            alert("You can,t Save unchange data  ");
        }

    }

    $scope.clientProductScheduleChangeSave2 =function(frequencyList , orderStartDate){


                if($scope.orderStartDate){
                    var  sendData = {
                        'productID':  $scope.selectedProductID ,
                        'clientID':$scope.SelectClientID ,
                        'orderStartDate':orderStartDate ,
                        'dayObject' : frequencyList
                    }
                   //  alert(angular.toJson(sendData));
                   //  alert($scope.SelectClientID);

                    $http.post($scope.saveChangedayObjectQuantityURL , sendData)
                        .success(function(responceData , status) {
                          $scope.showDaySchedule = !$scope.showDaySchedule;


                            angular.forEach($scope.productList , function (value ,key) {
                                if($scope.selectedProductID == value.product_id){
                                    value.orderPlace = true ;
                                }

                            });



                            angular.forEach($scope.productList , function (value , key) {

                               if(value.product_id ==$scope.selectedProductID){
                                   value.order_type =1;
                               }

                            });

                        })
                        .error(function (data , status) {
                        })
                }else{
                    alert("please select start order Date ");
                }





    }

    $scope.clientProductScheduleChangeSave_interval =function(intervalScheduleObject){

                 var    product_id =   intervalScheduleObject.product_id;

                if(intervalScheduleObject.start_interval_scheduler){

                     var new_url = $scope.saveChangedayObjectQuantityURL+'_interval';
                    $http.post(new_url , intervalScheduleObject)
                        .success(function(responceData , status) {
                            $scope.showDaySchedule_interval = !$scope.showDaySchedule_interval;

                            angular.forEach($scope.productList , function (value ,key) {
                                if(product_id == value.product_id){
                                    value.orderPlace = true ;
                                    value.order_type =2;
                                }

                            });
                        })
                        .error(function (data , status) {
                        })
                }else{
                    alert("please select start order Date ");
                }



    }
    $scope.showDayScheduleok_interval = function () {
        $scope.showDaySchedule_interval = !$scope.showDaySchedule_interval;
    }
    $scope.showDayScheduleok = function () {
        $scope.showDaySchedule = !$scope.showDaySchedule
    }

    $scope.showScheduleSeeOK = function () {

        $scope.showScheduleSee = !$scope.showScheduleSee
    }

    $scope.rsetClientObject = function () {
        $scope.ClientObject = {'fullName':'' , 'father_or_husband_name':'', 'date_of_birth':$scope.dateOfBirth, 'cnic':'' ,
            'address':'' , 'city':'',   'area':'' , 'cell_no_1':'+92' ,   'cell_no_2':'' ,
            'residence_phone_no':'' , 'email':'' , 'zone_id':'',  'is_active':'1' ,'is_deleted':'0',
            'clientProductObject':'','payment_term':1}
    }

    $scope.changeStartOrderDate = function (x) {


        $scope.orderStartDate = x;
    }

    $scope.removeProductForSchedual = function(product){

         var sendData = {
             clientID : $scope.SelectClientID,
             productID : product.product_id
         }
        $http.post($scope.removeProductFormSchedualURL , sendData)
            .success(function(responceData , status) {

                angular.forEach($scope.productList , function (value , key) {

                    if(value.product_id ==product.product_id){
                        value.order_type =0;
                    }

                });
                product.orderPlace = false ;
            })
            .error(function (data , status) {
            })
    }
    $scope.changeOnFullName = function(){

        if($scope.ClientObject.fullname){
            $scope.ClientObject.password = $scope.ClientObject.fullname
            var getname= $scope.ClientObject.fullname.replace(/\s/g, '');
            userName =  getname.toLowerCase();
            $scope.ClientObject.userName = userName ;
            $scope.ClientObject.password = userName+'123' ;
        }else {
            $scope.ClientObject.userName = '' ;
            $scope.ClientObject.password = '' ;
        }

    }

    $scope.setProductPrice = function(clientID) {
        $scope.clientProductloaderImage = true ;
        $http.post($scope.getProductPriceListURL ,clientID)
            .success(function(responceData) {
                $scope.ClientObject.clientProductObject = responceData ;
                $scope.clientProductloaderImage = false;

                $scope.showHideproductListPrice = true ;
            })
            .error(function(responceData){

            });
    }
    $scope.hideProductShowTable = function () {
        $scope.showHideproductListPrice = false ;
    }
    $scope.changePhoneNumber = function(phoneNumber){

        if(phoneNumber.length==13){
            $scope.phoneNumberFormate = false ;
        }else{
            $scope.phoneNumberFormate = true ;

        }
    }

    $scope.changeDaysQuantity = function(list){

        if(list.quantity>0){
            list.slectDayForProducy = true;
        }else {
            list.slectDayForProducy = false ;
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
            var h = addZero(d.getHours());
            var m = addZero(d.getMinutes());
            var s = addZero(d.getSeconds());
            var selectedDate  = date + '-' + month + '-' + selectYear+"\t\t\t"+ h + ":" + m ;
        }else {
            var selectedDate = '' ;
        }


        return selectedDate
    }

}]);

app.directive('modal', function () {
    return {
        template: '<div class="modal fade"  >' +
        '<div class="modal-dialog" >' +
        '<div class="modal-content"  style="width: 70% ; margin-left: 25% ; margin-top: 0% ">' +
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

