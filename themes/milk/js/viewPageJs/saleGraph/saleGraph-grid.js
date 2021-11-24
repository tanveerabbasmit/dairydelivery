/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('riderDailyStockGridModule', ['ngSanitize', 'toggle-switch']);
app.controller('manageZone', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (customerObject  ,lable , getCustomerDataURL , saveNewZoneURL ,editZoneURL , deleteURL) {


            $scope.getCustomerDataURL = getCustomerDataURL;
            $scope.lable = lable ;
            $scope.DatasetObject = customerObject ;
          //  $scope.DatasetObject.push(customerObject);

            document.getElementById("testContainer").style.display = "block";

            document.getElementById("loaderImage").style.display = "none";



        $scope.GraphFunction();
    }



    $scope.GraphFunction =function () {

        new Chart(document.getElementById("bar-chart-grouped"), {
            type: 'bar',
            data: {
                labels:  $scope.lable,
                datasets: $scope.DatasetObject
            },
            options: {
                title: {
                    display: true,
                    text: 'Sale growth with Month'
                }
            }
        });

    }



}]);


