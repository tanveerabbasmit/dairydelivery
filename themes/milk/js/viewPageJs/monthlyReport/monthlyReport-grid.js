/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('clintManagemaent', []);
app.controller('clintManagemaent', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function( monthNum , year , reportData  , riderList , getOneMonthlyReport){
        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";


             
        $scope.curPage = 1;
        $scope.pageSize = 20;
        $scope.totalPages = Math.ceil(reportData.recordCount/$scope.pageSize);

         $scope.totalData = reportData.recordCount ;

        var i ;
        $scope.switchObject = [];

        for(i= 1 ; i<=$scope.totalPages ; i++){
            var pageObject={
              'pageno':Number(i)
            }
            $scope.switchObject.push(pageObject);
        }
        $scope.lableList = reportData.lable;
        $scope.riderList = riderList ;
        $scope.data = reportData.data;
        $scope.getOneMonthlyReport = getOneMonthlyReport ;
        $scope.riderId = '0';
        $scope.selectYear = String(year);
        $scope.selectMonth = String(monthNum) ;
        $scope.hideAndShowPagination = true ;
        $scope.reportLoader = false ;
         angular.forEach($scope.switchObject , function(value , ket){
            if(value.pageno != 1){
              $scope.getCustomerFunction(value.pageno);
             }
         })

       /* var totalOnject = [];
        angular.forEach($scope.data[0].row_data ,function(value2 ,key){
            totalOnject.push({"total":0})
        })*/


        angular.forEach( $scope.data ,function (value ,key) {
         //   alert(angular.toJson(value.row_data));
            angular.forEach(value.row_data ,function(value2 ,key){

                 console.log(value2.delivery);
                totalOnject[key].total = Number(totalOnject[key].total)+ Number(value2.delivery);
            })
        });
        /*$scope.totalCountObject =totalOnject ;



        $scope.totalRecord = $scope.data.length;

        $scope.totalPercentage = (Number($scope.totalRecord)/Number($scope.totalData))*100;*/

        $scope.client_type = '0';


    }
    $scope.getCustomerFunctionONClick = function(page){


        $scope.curPage = page ;
        var sendData = {
            riderId : $scope.riderId ,
            selectMonth : $scope.selectMonth ,
            selectYear : $scope.selectYear ,
            client_type : $scope.client_type ,
            page : page
        }

        $scope.reportLoader = true ;
        $http.post( $scope.getOneMonthlyReport , sendData)
            .success(function(reportData , data){
                $scope.totalData = reportData.recordCount ;
                $scope.totalPages = Math.ceil(reportData.recordCount/$scope.pageSize);
                var i ;
                $scope.switchObject = [];

                for(i= 1 ; i<=$scope.totalPages ; i++){
                    var pageObject={
                        'pageno':Number(i)
                    }
                    $scope.switchObject.push(pageObject);

                }
                $scope.lableList = reportData.lable;
                $scope.data = reportData.data;
                $scope.reportLoader = false ;
                angular.forEach($scope.switchObject , function(value , ket){
                    if(value.pageno != 1){
                        $scope.getCustomerFunction(value.pageno);
                    }
                })


                var totalOnject = [];
                angular.forEach($scope.data[0].row_data ,function(value2 ,key){
                    totalOnject.push({"total":0})
                })


                angular.forEach( $scope.data ,function (value ,key) {
                    //   alert(angular.toJson(value.row_data));
                    angular.forEach(value.row_data ,function(value2 ,key){


                        totalOnject[key].total = Number(totalOnject[key].total)+ Number(value2.delivery);
                    })
                });
                $scope.totalCountObject =totalOnject ;

                $scope.totalRecord = $scope.data.length;

                $scope.totalPercentage = (Number($scope.totalRecord)/Number($scope.totalData))*100;


            })
            .error(function(responce , data){

            });

           $scope.totalRecord =  $scope.data.length;



    }


    $scope.getCustomerFunction = function(page){

        $scope.curPage = page ;
              var sendData = {
                  riderId : $scope.riderId ,
                  selectMonth : $scope.selectMonth ,
                  selectYear : $scope.selectYear ,
                  client_type : $scope.client_type ,
                  page : page
              }

                $scope.reportLoader = true ;
                $http.post( $scope.getOneMonthlyReport , sendData)
                    .success(function(reportData , data){

                        $scope.totalPages = Math.ceil(reportData.recordCount/$scope.pageSize);
                        var i ;
                        $scope.switchObject = [];
                        for(i= 1 ; i<=$scope.totalPages ; i++){
                            var pageObject={
                                'pageno':Number(i)
                            }
                            $scope.switchObject.push(pageObject);
                        }
                        $scope.lableList = reportData.lable;
                            var newData  = reportData.data ;

                            $scope.data = $scope.data.concat(newData);

                        $scope.reportLoader = false ;


                        var totalOnject = [];
                        angular.forEach($scope.data[0].row_data ,function(value2 ,key){
                            totalOnject.push({"total":0})
                        })


                        angular.forEach( $scope.data ,function (value ,key) {
                            //   alert(angular.toJson(value.row_data));
                            angular.forEach(value.row_data ,function(value2 ,key){
                                totalOnject[key].total = Number(totalOnject[key].total)+ Number(value2.delivery);
                            })
                        });
                        $scope.totalCountObject =totalOnject ;

                        $scope.totalRecord = $scope.data.length;

                        $scope.totalPercentage = (Number($scope.totalRecord)/Number($scope.totalData))*100;

                    })
                    .error(function(responce , data){

                    });


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


     $scope.testChange = function() {

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

