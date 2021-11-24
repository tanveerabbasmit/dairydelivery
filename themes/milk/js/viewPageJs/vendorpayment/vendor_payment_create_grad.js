/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('riderDailyStockGridModule', ['angularjs-datetime-picker']);
app.controller('manageZone', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (allow_delete , zoneList, companyBranchList , base_url ,editZoneURL , deleteURL) {


        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";

        $scope.allow_delete = allow_delete;
        $scope.zoneList = zoneList;

        $scope.base_url = base_url;
        $scope.companyBranchList = companyBranchList ;
        $scope.farm_list = $scope.companyBranchList.farm_list;

        $scope.today_date = $scope.companyBranchList.today_date;

         $scope.main_object_function();

         $scope.save_data = false;


    }
    $scope.main_object_function = function(){
        $scope.button_text ='Save';
        $scope.main_object = {
            'vendor_payment_id':'0',
            'action_date':$scope.today_date,
            'reference_no':'',
            'remarks':'',
            'vendor_id':'',
            'amount':'',
            'payment_mode':'',
            'security_code':'',
            'remarks':'',

        }

    }

     $scope.savePaymernt_for_dairy_farm = function(){

        if($scope.main_object.farm_payment_id>0){

            $scope.main_object.security_code = prompt("Security Code:", "");
            $scope.main_object.remarks = prompt("Remarks:", "");

        }
         $scope.save_data = true;
         $http.post( $scope.base_url+"_save_payment", $scope.main_object)
             .success(function (data, status, headers, config) {
                 if(data.success){
                     $scope.save_data = false;
                     $scope.change_dairy_farm('0');
                 }else {
                     $scope.save_data = false;
                     alert(data.message);
                 }

             })
             .error(function (data, status, header, config) {

             });
     }
     $scope.savePaymernt_for_dairy_farm_update = function(list){



         list.security_code = prompt("Security Code:", "");
         list.remarks = prompt("Remarks:", "");


         $scope.save_data = true;
         $http.post( $scope.base_url+"_save_payment",list)
             .success(function (data, status, headers, config) {
                 if(data.success){
                     $scope.save_data = false;
                     $scope.change_dairy_farm('0');
                     list.false;
                 }else {
                     $scope.save_data = false;
                     alert(data.message);
                 }

             })
             .error(function (data, status, header, config) {

             });
     }
     $scope.savePaymernt_for_dairy_farm_delete = function(list){



         list.security_code = prompt("Security Code:", "");
        // list.remarks = prompt("Remarks:", "");


         $scope.save_data = true;
         $http.post( $scope.base_url+"_delete_payment",list)
             .success(function (data, status, headers, config) {
                 if(data.success){
                     $scope.save_data = false;
                     $scope.change_dairy_farm('0');
                     list.false;
                 }else {
                     $scope.save_data = false;
                     alert(data.message);
                 }

             })
             .error(function (data, status, header, config) {

             });
     }

     $scope.edit_payment = function(list){

        // $scope.button_text ='Update';
         list.update = true;

       /*  $scope.main_object = {
             'farm_payment_id':list.farm_payment_id,
             'action_date':list.action_date,
             'reference_no':list.reference_no,
             'remarks':list.remarks,
             'farm_id':list.farm_id,
             'amount': Number(list.amount),
             'payment_mode':list.payment_mode,

         }*/

     }


    $( "#target_change_payment" ).change(function() {
        var  farm_id =  document.getElementById("target_change_payment").value;

        $scope.main_object.vendor_id = farm_id;
        $scope.change_dairy_farm(farm_id);

    });
     $scope.change_dairy_farm = function (farm_id) {

         $http.post( $scope.base_url+"_get_one_farm_payment", $scope.main_object.vendor_id)
             .success(function (data, status, headers, config) {
                 $scope.save_data = false;
                 $scope.farm_payment = data;
             })
             .error(function (data, status, header, config) {

             });
     }



}]);

app.directive('modal', function () {
    return {
        template: '<div class="modal fade">' +
            '<div class="modal-dialog">' +
            '<div class="modal-content"  style="width: 70% ; margin-left: 25% ; margin-top: 0%">' +
            '<div class="modal-header">' +
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

