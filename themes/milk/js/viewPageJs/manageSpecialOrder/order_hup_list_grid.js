/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('riderDailyStockGridModule', ['angularjs-datetime-picker']);
app.controller('manageZone', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (spcialOrderCount ,data, nextPageForPaginationURL ,searchDeliveryDateURL , viewAllURL ,nextPagePaginationViewAllURL) {

        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";

        $scope.curPage = 0;
        $scope.pageSize = 10;
       $scope.totalPages = Math.ceil(data.count/ $scope.pageSize);

        $scope.spcialOrderList = data.order;

        $scope.nextPageForPaginationURL = nextPageForPaginationURL ;
        $scope.searchDeliveryDateURL = searchDeliveryDateURL ;
        $scope.viewAllURL = viewAllURL;
        $scope.nextPagePaginationViewAllURL = nextPagePaginationViewAllURL ;

        $scope.temporaryOrderList =  $scope.spcialOrderList ;
        $scope.search = '';
        $scope.hideAndShowPagination = 'true'

        $scope.viewAllDataLoader = false ;


        var date = new Date();

        var selectYear = date.getFullYear() ;
        var month = date.getMonth()+1;
        var date = date.getDate();

        if(month<10){
            month = '0'+month
        }

        if(date<10){
            date = '0'+date
        }
        $scope.today = selectYear + '-' + month + '-' + date;

        $scope.make_delivery = false;
        $scope.loading = false;
    }

    $scope.nextPagePagination = function(page) {

        var sendData = {
            'date':$scope.today ,
            'page':page
        }
        var  data = angular.toJson(sendData);
        $http.post($scope.nextPageForPaginationURL , data)
            .success(function(responceData , status) {

                $scope.totalPages = Math.ceil(responceData.count/ $scope.pageSize);

                $scope.spcialOrderList = responceData.order;

            })
            .error(function (data , status) {

            })
    }

    $scope.nextPagePaginationViewAll = function(page) {



        $http.post($scope.nextPagePaginationViewAllURL , page)
            .success(function(responceData , status) {

                $scope.totalPages = Math.ceil(responceData.count/ $scope.pageSize);

                $scope.spcialOrderList = responceData.order;

            })
            .error(function (data , status) {

            })
    }

    $scope.searchSpicailOrder = function(date) {

          var sendData = {
              'date':$scope.today ,
               'page':0
          }

          var  data = angular.toJson(sendData);
        $http.post($scope.searchDeliveryDateURL , data)
            .success(function(responceData , status) {
                 $scope.order_list = responceData;


            })
            .error(function (data , status) {

            })
    }
    $scope.viewAllDataFunction = function () {
        $scope.viewAllDataLoader = true ;
        $http.post($scope.viewAllURL , 'data')
            .success(function(responceData , status) {
                $scope.today = '';
                $scope.curPage = 0;
                $scope.curPage = 0;
                $scope.totalPages = Math.ceil(responceData.count/ $scope.pageSize);

                $scope.spcialOrderList = responceData.order;
                $scope.viewAllDataLoader = false ;
            })
            .error(function (data , status) {
            })
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

    $scope.changeDateFormateOnlyDate = function (y) {

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
            var selectedDate  = date + '-' + month + '-' + selectYear;
        }else {
            var selectedDate = '' ;
        }


        return selectedDate
    }

    $scope.make_delivery_function = function (list){




        $scope.slect_delivery_list = list;

        $scope.make_delivery = true;

        angular.forEach($scope.slect_delivery_list.prouct_object ,function (value ,key){
             value.selected = true;
        });
    }

    $scope.deliver_order =function (){
        $scope.loading = true;
        $http.post($scope.viewAllURL+"_orderhub_delivery" ,$scope.slect_delivery_list)
            .success(function(responceData , status) {
               $scope.make_delivery = false;
                $scope.loading = false;
            })
            .error(function (data , status) {
            })

    }


}]);

app.directive('modal', function () {
    return {
        template: '<div class="modal fade">' +
        '<div class="modal-dialog">' +
        '<div class="modal-content"  style="width: 70% ; margin-left: 25% ; margin-top: 0px">' +
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

