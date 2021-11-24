/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('riderDailyStockGridModule', ['ngSanitize', 'toggle-switch']);
app.controller('manageZone', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function(backgroundColor , customerObject  ,lable , getCustomerDataURL , saveNewZoneURL ,editZoneURL , deleteURL) {


            $scope.backgroundColor = backgroundColor;
            $scope.getCustomerDataURL = getCustomerDataURL;
            $scope.lable = lable ;
            $scope.DatasetObject = customerObject ;
          //  $scope.DatasetObject.push(customerObject);

            document.getElementById("testContainer").style.display = "block";

            document.getElementById("loaderImage").style.display = "none";



        $scope.GraphFunction();
    }



    $scope.GraphFunction =function () {

        new Chart(document.getElementById("bar-chart"), {
            type: 'bar',
            data: {
                labels: $scope.lable,
                datasets: [
                    {
                        label: "Make Total Delivery",
                        backgroundColor: $scope.backgroundColor,
                        data: $scope.DatasetObject
                    }
                ]
            },
            options: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Make Total Delivery'
                }
            }
        });

    }



}]);


