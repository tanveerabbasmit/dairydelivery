/**
 * Created by Muhammad.Imran on 4/1/2016.
 */

var app = angular.module('productGrid', []);

app.controller('manageProduct', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    $scope.init = function (data) {
        $scope.amount = +data.current_amount;
        $scope.outstanding_balance = data.outstanding_balance;
        $scope.base_url = data.base_url;
        $scope.ru = data.ru;
        $scope.client_id = data.client_id;
        $scope.pp_TxnType = 'MPAY';
        $scope.valid_amount_show = false;
        $scope.check_valid_amount();
    }

    $scope.check_valid_amount = function () {
        $scope.valid_amount_message = 'Please enter valid amount'
        if ($scope.amount > 0) {
            $scope.valid_amount_show = false;
        } else {
            $scope.valid_amount_show = true;
        }
    }

    $scope.proceed_function = function () {
        var url = $scope.base_url + "/tazafarm_payment/confirm_payment?client_id=" + $scope.client_id + "&amount=" + $scope.amount + "&outstanding_balance=" + $scope.outstanding_balance + "&pp_TxnType=" + $scope.pp_TxnType + "&ru=" + $scope.ru;
        window.location.href = url;
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
        replace: true,
        scope: true,
        link: function postLink(scope, element, attrs) {
            scope.title = attrs.title;

            scope.$watch(attrs.visible, function (value) {
                if (value == true)
                    $(element).modal('show');
                else
                    $(element).modal('hide');
            });

            $(element).on('shown.bs.modal', function () {
                scope.$apply(function () {
                    scope.$parent[attrs.visible] = true;
                });
            });

            $(element).on('hidden.bs.modal', function () {
                scope.$apply(function () {
                    scope.$parent[attrs.visible] = false;
                });
            });
        }
    };
});

