/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('riderDailyStockGridModule', ['ngSanitize', 'toggle-switch']);
app.controller('manageZone', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (allow_delete , riderList ,zoneList  , clientList , SendSMSURL) {


        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";
        $scope.allow_delete = allow_delete ;
        $scope.riderList = riderList ;
        $scope.clientList = clientList ;
        $scope.zoneList = zoneList ;

        angular.forEach( $scope.zoneList ,function (value ,key) {
            value.is_selected = false ;
        });

        $scope.SendSMSURL =SendSMSURL;
        $scope.search = '';
        $scope.searchBar = ''
        $scope.switchStatus = true ;
        $scope.SelectedCustomer = 'Select Client' ;
        $scope.selectOption = '';
        $scope.messageText = ""
        $scope.senedSMS_disabled = false ;
        $scope.selectRiderID = riderList[0].rider_id;

        $scope.selectClientLoading = false;
        $scope.tag_color_id ='-1';
    }
    $scope.sendSms = function(){



        if($scope.selectOption != ''){
            if($scope.OnlectCutomer  || $scope.selectOption ==1|| $scope.selectOption ==2|| $scope.selectOption ==4 || $scope.selectOption ==5|| $scope.selectOption ==6|| $scope.selectOption ==7){
                 var sendData = {
                   customerID :$scope.OnlectCutomer ,
                   optionName : $scope.selectOption ,
                   message : $scope.messageText ,
                   zoneList : $scope.zoneList ,
                   rider_id :  $scope.selectRiderID,
                   tag_color_id :  $scope.tag_color_id
                 }

                $scope.senedSMS_disabled = true ;
                $http.post($scope.SendSMSURL ,sendData )
                    .success(function (responce) {
                        $scope.taskMessage = 'SMS Sended Successfully';
                        $scope.senedSMS_disabled = false ;
                        document.getElementById("alertMessage").style.display = "block";
                        setTimeout(function(){
                                document.getElementById("alertMessage").style.display = 'none';
                            },
                            1500);

                       $scope.selectOption = '';

                        $scope.messageText = ""
                    })
                    .error(function (responce) {

                    });
            }else {
                $scope.taskMessage = 'Select Customer first.';

                document.getElementById("alertMessage").style.display = "block";
                setTimeout(function(){
                        document.getElementById("alertMessage").style.display = 'none';
                    },
                    1500);

            }

        }
        if(!$scope.selectOption){
            $scope.taskMessage = 'Select Option first.';

            document.getElementById("alertMessage").style.display = "block";
            setTimeout(function(){
                    document.getElementById("alertMessage").style.display = 'none';
                },
                1500);
        }
    }
    $scope.selectedClient = function(client){
        $scope.OnlectCutomer = client.client_id ;
        $scope.SelectedCustomer = client.fullname;
    }

    $scope.change_option = function(option){

        $scope.selectClientLoading = true;

        if(option== '3_active' || option== '3_inactive'){

            $http.post($scope.SendSMSURL+"_selectCustomer" ,option )
                .success(function (responce) {

                    $scope.clientList =responce ;

                    $scope.selectClientLoading = false;
                })

        }
        if(option ==7){

            $http.post($scope.SendSMSURL+"_selectCustomer_tagColor" ,option )
                .success(function (responce) {

                    $scope.clientList_color_tag =responce ;
                    debugger  ;

                    $scope.selectClientLoading = false;
                })

        }

    }

}]);

app.directive('modal', function () {
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

