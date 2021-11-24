/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('productGrid', []);
app.controller('manageProduct', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (data , allow_delete , productCount , productList , saveNewProductURL , editProductURL ,deleteURL , searchProductURL , checkAlredyExistURL ,nextPageForpaginationURL) {



        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";
        $scope.curPage = 0;

        $scope.pageSize = 10;


        $scope.data = data ;
        $scope.line_1 = data.line_1 ;
        $scope.line_2 = data.line_2 ;
        $scope.line_3 = data.line_3 ;
        $scope.line_4 = data.line_4 ;

        $scope.company_id = data.company_object.company_id;

        $scope.company_logo = data.company_object.company_logo;
        $scope.subdomain = data.company_object.subdomain;


        $scope.allow_delete = allow_delete ;
        $scope.totalPages = Math.ceil(productCount/ $scope.pageSize);
        $scope.productList = productList ;
         angular.forEach($scope.productList ,function (value ,key) {
             value.quantity =1;
             value.total_price =value.price;
             value.quantity_or_price = 1;
         });
        $scope.saveNewProductURL = saveNewProductURL ;
        $scope.editProductURL = editProductURL ;
        $scope.deleteURL  = deleteURL ;
         $scope.searchProductURL  = searchProductURL ;
         $scope.checkAlredyExistURL = checkAlredyExistURL ;
        $scope.nextPageForpaginationURL = nextPageForpaginationURL ;
        $scope.productObject = {'name':'' , 'unit':'' , 'price' : '' , 'is_active':'1' ,'is_deleted':'0' , 'order_type':1}
        $scope.temporary = $scope.productObject ;
        $scope.search = '';
        $scope.temporaryProductList =  $scope.productList ;
        $scope.checkAlredyExist = false ;
        $scope.hideAndShowPagination = true ;

        $scope.selectProduct ='0';
        $scope.customer_name = '';
        $scope.discount_amount = '0';


        $scope.invoiceShow =false ;

        $scope.submitDisabled = true;

        $scope.totalSalePriceOfAllItem_fixed = 0;

    }
    $scope.change_customer = function(customer_name){
        if(customer_name ==''){
            $scope.discount_amount = '0';
        }
    }
    $scope.SaleItemList = [];
    $scope.selectProductfunction = function (product) {
        angular.forEach($scope.productList , function (value , key) {
            if(value.name == product){
                selected_product = value
                $scope.product = '';
            }
        });
        if(selected_product){
          /*  selected_product.quantity = 1;*/
          //  $scope.selectQuantityForSale(selected_product);
            var index = $scope.SaleItemList.indexOf(selected_product);
            if(index< 0){
                $scope.SaleItemList.push(selected_product);
            }
            $scope.saleValueItem =angular.fromJson($scope.SaleItemList);
            item2 = false ;
        }
      $scope.findTotalCount();
    }
    $scope.findTotalCount_first_function = function(product,flag){
        product.quantity_or_price = flag;
        $scope.findTotalCount();
    }
    $scope.findTotalCount = function(){
        $scope.totalSalePriceOfAllItem = 0;
        angular.forEach($scope.saleValueItem ,function (value ,key){
            if(value.quantity_or_price==1){
                value.total_price = Number(value.price) * Number(value.quantity);
            }else {
                value.quantity =(Number(value.total_price)/Number(value.price)).toFixed(2);;
            }

            $scope.totalSalePriceOfAllItem = Number(value.total_price) +  $scope.totalSalePriceOfAllItem;
        })

        $scope.totalSalePriceOfAllItem_fixed =  $scope.totalSalePriceOfAllItem;
        $scope.returnAmount =Number($scope.receivedAmount) - Number($scope.totalSalePriceOfAllItem)
    }
    $scope.removeSaleButton = function (item) {
        var index =  $scope.saleValueItem.indexOf(item);
        $scope.saleValueItem.splice(index , 1);
        $scope.findTotalCount();
    }
    $scope.selectQuantityForSale= function(product){
        product.total_price= Number(product.price) * Number(product.quantity);
    }
    $scope.recivedAmountFunction = function(){

        $scope.totalSalePriceOfAllItem = $scope.totalSalePriceOfAllItem_fixed;
        $scope.totalSalePriceOfAllItem = Number($scope.totalSalePriceOfAllItem) -Number($scope.discount_amount);
        $scope.returnAmount =Number($scope.receivedAmount) - Number($scope.totalSalePriceOfAllItem);

        if(Number($scope.receivedAmount)>0){
            $scope.submitDisabled = false;
        }else {
            $scope.submitDisabled = true;
        }

    }

    $scope.saleProductList =[];
     $scope.addNewProduct =function(selectProduct){

         angular.forEach($scope.productList ,function (value ,key) {
             if(value.product_id ==selectProduct){
                 $scope.saleProductList.push(value);
             }

         })
     }

     $scope.saleItemFunction=function(){
        var d = new Date();
         $scope.inviceNo = d.getTime();
         $scope.invoiceShow =true ;
          var sendData ={
              inviceNo : $scope.inviceNo ,
              receivedAmount : $scope.receivedAmount ,
              saleValueItem : $scope.saleValueItem,
              customer_name : $scope.customer_name,
              discount_amount : $scope.discount_amount
          }



         $http.post($scope.saveNewProductURL, sendData)
             .success(function (data, status, headers, config) {
                 $scope.submitDisabled = true;
                 $scope.taskMessage = 'Save Successfully';



                 $scope.printInvoiceRepot();
                 document.getElementById("alertMessage").style.display = "block";
                 setTimeout(function(){
                         document.getElementById("alertMessage").style.display = 'none';
                     },
                     2500);

             })
             .error(function (data, status, header, config) {
                 alert(data.message);
                 subject.showLoader = false;
             });



     }

     $scope.resetSubmit =function(){

         $scope.SaleItemList =[];
         $scope.saleValueItem =[];
         $scope.returnAmount ='';
         $scope.receivedAmount ='';
         $scope.totalSalePriceOfAllItem ='';

     }

    $scope.printInvoiceRepot = function () {

        if($scope.customer_name ==''){
            document.getElementById("customer_name").style.display = "none";
            document.getElementById("Discount_name").style.display = "none";
        }

        var innerContents = document.getElementById('printInvoice').innerHTML;
        var popupWinindow = window.open('', '_blank', 'width=1200px,height=1000px,scrollbars=no,menubar=no,toolbar=no,location=no,status=no,titlebar=no');
        popupWinindow.document.open();
        popupWinindow.document.write('<html><head><link rel="stylesheet" type="text/css" href="style.css" /></head><body onload="window.print()">' + innerContents + '</html>');
        popupWinindow.document.close();
    }

    $scope.searchInvoiceFunction = function(invoice){

        $http.post($scope.saveNewProductURL+"_getinvoiceData", invoice)
            .success(function (data, status, headers, config) {
                $scope.receivedAmount =  data[0].received_amount;
                $scope.inviceNo =  data[0].invoice;
                $scope.customer_name =  data[0].customer;
                $scope.discount_amount =  data[0].discount;
                $scope.invoiceShow = true ;
                $scope.saleValueItem = data;
                $scope.totalSalePriceOfAllItem =0;
                angular.forEach($scope.saleValueItem ,function (value ,key) {
                    $scope.totalSalePriceOfAllItem= Number($scope.totalSalePriceOfAllItem) +Number(value.total_price);
                });
                $scope.totalSalePriceOfAllItem_fixed =$scope.totalSalePriceOfAllItem ;
                $scope.returnAmount = Number($scope.totalSalePriceOfAllItem)-Number($scope.receivedAmount);
                $scope.recivedAmountFunction();
            })
            .error(function (data, status, header, config) {
                alert(data.message);
                subject.showLoader = false;
            });
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

