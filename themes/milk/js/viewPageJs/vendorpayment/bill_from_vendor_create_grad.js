/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('riderDailyStockGridModule', ['angularjs-datetime-picker']);
app.controller('manageZone', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (allow_delete , zoneList, companyBranchList , base_url) {


        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";


        $scope.allow_delete = allow_delete;
        $scope.zoneList = zoneList;

        $scope.base_url = base_url;
        $scope.companyBranchList = companyBranchList ;
        $scope.bill_from_vendor_id = companyBranchList.bill_from_vendor_id ;
        $scope.object = companyBranchList.object ;




        $scope.farm_list = $scope.companyBranchList.farm_list;
        $scope.item_list = $scope.companyBranchList.item_list;
        $scope.button_text = $scope.companyBranchList.button_text;



        $scope.today_date = $scope.companyBranchList.today_date;

        if($scope.bill_from_vendor_id>0){
            $scope.main_object = $scope.object;
            var send_data = {
                'vendor_id':$scope.main_object.vendor_id,
                'page_number':0
            }
            $scope.get_bill_list(send_data);
        }else {
            $scope.main_object_function();
        }
         $scope.save_data = false;

        $scope.page_number =0;
    }
    $scope.main_object_function = function(){

        $scope.main_object = {
            'bill_from_vendor_id':'0',
            'action_date':$scope.today_date,
            'item_id':'',
            'vendor_id':'',
            'price':'',
            'quantity':'',
            'gross_amount':'',
            'tax_amount':'',
            'discount_amount':'',
            'net_amount':'',
            'remarks':'',
        }

    }

    $( "#vendor_id_value" ).change(function() {
        var  vendor_id =  document.getElementById("vendor_id_value").value;

        $scope.main_object.vendor_id = vendor_id;
         var send_data = {
             'vendor_id':vendor_id,
             'page_number':$scope.page_number
         }
        $scope.get_bill_list(send_data);



    });

    $scope.get_bill_list = function (send_data){
        $http.post( $scope.base_url+"_bill_from_vendor_report_view_report", send_data)
            .success(function (data, status, headers, config) {

                $scope.list =data.list;

            })
            .error(function (data, status, header, config) {

            });
    }

     $scope.savePaymernt_for_dairy_farm = function(){
         $scope.main_object.vendor_id=  document.getElementById("vendor_id_value").value;

         $scope.save_loading = true;
         $http.post( $scope.base_url+"_save_payment", $scope.main_object)
             .success(function (data, status, headers, config) {
                 if(data.success){
                     $scope.save_loading = false;
                     if($scope.bill_from_vendor_id==0){
                         $scope.main_object_function();
                     }else {
                        // window.location.replace('bill_from_vendor_create');

                         var send_data = {
                             'vendor_id':$scope.main_object.vendor_id,
                             'page_number': $scope.page_number
                         }

                         $scope.get_bill_list(send_data);
                     }

                 }else {
                     $scope.save_data = false;
                     alert(data.message);
                 }

             })
             .error(function (data, status, header, config) {

             });
     }

     $scope.calculation_function  = function () {
         $scope.main_object.gross_amount = ($scope.main_object.quantity) * ($scope.main_object.price)
         $scope.main_object.net_amount =($scope.main_object.gross_amount)-($scope.main_object.tax_amount) * ($scope.main_object.discount_amount)

     }

     $scope.delete_function =function (list){

         var txt;
         var r = confirm("Are you sure?");
         if (r == true) {

             $http.post( $scope.base_url+"_delete_vendor_bill", list)
                 .success(function (data, status, headers, config) {

                     var send_data = {
                         'vendor_id':$scope.main_object.vendor_id,
                         'page_number': $scope.page_number
                     }
                     debugger ;
                     $scope.get_bill_list(send_data);

                 })
                 .error(function (data, status, header, config) {

                 });

         } else {

         }
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

