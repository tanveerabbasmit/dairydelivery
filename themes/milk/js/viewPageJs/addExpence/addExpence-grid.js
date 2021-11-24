/**
 * Created by Muhammad.Imran on 4/1/2016.
 */
var app = angular.module('riderDailyStockGridModule', ['angularjs-datetime-picker']);
app.controller('manageZone', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    //INITIALIZATION ===============================================================
    $scope.init = function (allow_delete,todayDate  ,expencetype , riderList, saveNewRiderURL ,
                            editRiderUrl , deleteURL , getZoneAgainstRider,checkDuplicateRiderUseNameURL) {

        document.getElementById("testContainer").style.display = "block";
        document.getElementById("loaderImage").style.display = "none";



        $scope.allow_delete = allow_delete ;
        $scope.todayDate = todayDate ;

        $scope.riderList = riderList;
        $scope.total = 0;
        angular.forEach($scope.riderList , function (value ,key) {
            $scope.total = $scope.total + Number(value.amount);

        });

        $scope.saveNewRiderURL = saveNewRiderURL ;
        $scope.editRiderUrl = editRiderUrl ;
        $scope.expencetype  = expencetype ;

        $scope.deleteURL = deleteURL ;
        $scope.getZoneAgainstRider = getZoneAgainstRider ;

        $scope.checkDuplicateRiderUseNameURL = checkDuplicateRiderUseNameURL ;

        var date = new Date();
        date.setDate(date.getDate());
        var selectYear = date.getFullYear() ;
        var month = date.getMonth()+1;
        var date = date.getDate();

        if(month<10){
            month = '0'+month
        }

        if(date<10){
            date = '0'+date
        }


        $scope.dateOfBirth = selectYear + '-' + month + '-' + date;



        $scope.riderObject = {'expence_type':'12243' ,'activity':'' ,'date':$scope.dateOfBirth , 'remarks':'', 'amount':''}
        $scope.temporaryRiderObject = $scope.riderObject ;
        $scope.search = ''
        $scope.searchBar = ''

        angular.forEach($scope.ZoneList , function(value ,key){
            value.isselected = false ;
        });

        $scope.riderUserNameCheck = false ;

        $scope.searchObject = {'startDate' :$scope.todayDate.start_date ,'endDate':$scope.todayDate.end_date ,'expenses_type_id':''}

        $scope.imageLoader = false;
        $scope.selecedExpenceName = 'All Type';

        $scope.geexpenceListFunction();


    }




    $scope.addnewRider = function(flag,list){


        angular.forEach($scope.ZoneList , function(value ,key){
            value.isselected = false ;
        });
        $scope.riderObject  =  $scope.temporaryRiderObject  ;
        if(flag==1){
            $scope.title_name = 'Save';
            $scope.riderObject = {'expence_record_id':'0','expence_type':'' ,'activity':'' ,'date':$scope.dateOfBirth , 'remarks':'', 'amount':''}
        }else {
            $scope.title_name = 'Update';
            $scope.riderObject = {'expence_record_id':list.expence_record_id,'expence_type':list.expence_type ,'activity':list.activity ,'date':list.date , 'remarks':list.remarks, 'amount':Number(list.amount)}
        }

        $scope.showAddNewRider = !$scope.showAddNewRider;

    };
    $scope.geexpenceListFunction = function(){
        $scope.imageLoader = true;
        $http.post($scope.saveNewRiderURL+'searchExpense',  $scope.searchObject)
            .success(function (data, status, headers, config) {
                $scope.riderList = data;
                angular.forEach($scope.riderList , function (value,key) {
                    value.update = false;
                })

                $scope.total = 0;
                angular.forEach($scope.riderList , function (value ,key) {
                    $scope.total = $scope.total + Number(value.amount);

                });
                $scope.selecedExpenceName = 'All Type'
                angular.forEach( $scope.expencetype, function (value ,key) {
                    if(value .expence_type == $scope.searchObject.expenses_type_id){
                        $scope.selecedExpenceName = value.type ;
                    }


                });


                $scope.imageLoader = false;
            })
            .error(function (data, status, header, config) {
                alert(data.message);
                subject.showLoader = false;
            });
    }

    $scope.saveRider = function (object) {

        var sendData = angular.toJson(object);

        $http.post($scope.saveNewRiderURL, sendData)
            .success(function (data, status, headers, config) {
                if(data.success){
                    $scope.geexpenceListFunction()
                    // $scope.riderList.push(data.rider[0]);
                    $scope.showAddNewRider = !$scope.showAddNewRider;

                }else{
                    alert(angular.toJson(data.message));
                }
            })
            .error(function (data, status, header, config) {
                alert(data.message);
                subject.showLoader = false;
            });
    }

    $scope.editRider = function(rider){

        $scope.riderUserNameCheck = false ;

        $http.post($scope.getZoneAgainstRider, rider.rider_id)
            .success(function (data, status, headers, config) {
                $scope.showEditRider = !$scope.showEditRider;
                angular.forEach($scope.ZoneList , function(value ,key){
                    value.isselected = false ;
                });

                angular.forEach($scope.ZoneList , function(value1 , key){
                    angular.forEach(data , function(value2 , key){
                        if(value1.zone_id == value2.zone_id){
                            value1.isselected = true ;

                        }
                    })
                });

                rider.zone = $scope.ZoneList ;
                $scope.riderObject = rider;

            })
            .error(function (data, status, header, config) {
                alert(data.message);
                subject.showLoader = false;
            });



    };



    $scope.editRiderFunction = function (object) {

        var sendData = angular.toJson(object);

        $http.post($scope.editRiderUrl, sendData)
            .success(function (data, status, headers, config) {
                if(data.success){

                    $scope.showEditRider = !$scope.showEditRider;

                }else{
                    alert(angular.toJson(data.message));
                }
            })
            .error(function (data, status, header, config) {
                alert(data.message);
                subject.showLoader = false;
            });
    }

    $scope.delete = function (object) {

        var sendData = angular.toJson(object);
        var r = confirm("Are you sure?");
        if (r == true) {
            $http.post($scope.deleteURL , sendData)
                .success(function (data, status, headers, config) {
                    if(data.success){
                        //  object.is_deleted = 1;
                        var index = $scope.riderList.indexOf(object);
                        $scope.riderList.splice(index, 1);

                    }else{
                        alert("Cannot delete or update a parent row");
                    }
                })
                .error(function (data, status, header, config) {
                    alert(data.message);
                    subject.showLoader = false;
                });
        }

    }

    $scope.searchBarFunction = function(search){
        $scope.search = search ;

    }
    $scope.changebarSearch = function(search){
        if(!search){
            $scope.search = '';
        }
    }

    $scope.resetObject = function () {

        angular.forEach($scope.riderObject.zone , function(value ,key){
            value.isselected = false ;
        });
        $scope.riderObject = {'expence_type':'' ,'activity':'' ,'date':$scope.dateOfBirth , 'remarks':'', 'amount':''}
    }
    $scope.changeRiderUserName = function(userName ,id){

        var data = {
            userName :userName,
            rider_id :id
        }

        $http.post($scope.checkDuplicateRiderUseNameURL ,data )
            .success(function (responceData ,status) {
                $scope.riderUserNameCheck = false ;
                if(responceData){
                    $scope.riderUserNameCheck = true ;
                }

            })
            .error(function(responce , status) {

            });
    }

    $scope.deleteexpence = function (list) {

        $http.post($scope.checkDuplicateRiderUseNameURL+"_delete_expence" ,list )
            .success(function (responceData ,status) {
                $scope.geexpenceListFunction();

            })
            .error(function(responce , status) {

            });

    }

}]);

app.directive('modal', function () {
    return {
        template: '<div class="modal fade">' +
            '<div class="modal-dialog">' +
            '<div class="modal-content"  style="width: 70% ; margin-left: 25% ; margin-top: 0% ">' +
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

