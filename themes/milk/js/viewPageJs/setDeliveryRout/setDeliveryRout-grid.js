angular.module("demo").controller("SimpleDemoController",['$scope', '$http', '$filter', function($scope, $http, $filter) {
    $scope.init = function (riderList , getClientListURL ,saveOrOrderByLstURL , saverearrangeOrderListURL) {

        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";
        $scope.riderList = riderList;
        $scope.getClientListURL = getClientListURL ;
        $scope.saveOrOrderByLstURL = saveOrOrderByLstURL;
        $scope.saverearrangeOrderListURL = saverearrangeOrderListURL ;
        
        $scope.imageLoading = false ;
        $scope.riderId ='0';

        $scope.order_no= '0';
    }


   $scope.order_by_zone = function(){
       $scope.order_no++;
       $scope.getRiderList($scope.riderId,$scope.order_no)
   }

    $scope.getRiderList = function (riderId,zone_order_by) {
        $scope.imageLoading = true;
        var send_data = {
            riderId : riderId,
            zone_order_by:zone_order_by
        }
        $http.post($scope.getClientListURL, send_data)
            .success(function (data, status, headers, config) {
                $scope.CustomerList = data ;
                $scope.imageLoading = false;


                $scope.models = {
                    selected: null,
                    lists: {"A": []}
                };



                // Model to JSON for demo purpose
                $scope.$watch('models', function(model) {
                    $scope.modelAsJson = angular.toJson(model, true);
                }, true);



                angular.forEach( $scope.CustomerList , function (value , key) {
                    $scope.models.lists.A.push({fullname: value.fullname ,	address :value.address ,zone_name:value.zone_name});
                    value.orderNo = '';
                })




            })
            .error(function (data, status, header, config) {
        });
    }

    $scope.objectAndIndex =function(lable, indexNo) {
        // alert(lable);
        // list.orderNo = indexNo ;
        angular.forEach($scope.CustomerList , function (value ,key) {
            if(value.fullname==lable){
                value.orderNo = indexNo
            }
        });
    }
    $scope.saveOrderList = function(){
        $scope.imageLoading = true;
      $http.post($scope.saverearrangeOrderListURL ,$scope.CustomerList  )
          .success(function(responce){
              $scope.imageLoading = false;
          })
          .error(function (responce) {
              
          })
    }


}]);
