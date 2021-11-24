// angular js page functionality
var app = angular.module('typeModule', []);
app.controller('typeCtrl', function($scope,$http) {
    $scope.init=function(types,fields,url){
        
        // varialbes
        $scope.sortType     = 'typeSort'; // set the default sort type
        $scope.sortReverse  = false;  // set the default sort order
        $scope.search   = '';     // set the default search/filter term
        
        $scope.loader = false;
        $scope.inner_loader = false;
        
        $scope.typeList = types;
        $scope.fieldList = fields;
        $scope.disabledList = [];
        $scope.disabledSubList = [];
        
        // main question urls
        $scope.addURL = url[0];
        $scope.updateURL = url[1];
        $scope.deleteURL = url[2];
        $scope.viewURL = url[3];
        
        $scope.newValue = "";
        $scope.newSubValue = "";
        $scope.detail_data = "";
        $scope.detail_data_index = "";
        
        
        $scope.selectedType = $scope.typeList[0].medical_history_id;
        for(var i=0;i<$scope.fieldList.length;i++){
            $scope.disabledList.push(false);
        }

        // pagination
        $scope.curPage = 0;
        $scope.pageSize = 10;
        $scope.numberOfPages = function() {
				return Math.ceil($scope.typeList.length / $scope.pageSize);
                    };

    };
    
    // add new type
    $scope.addFieldData = function(){
        if($scope.newValue !== ""){
            $scope.loader = true;
            var param = {
                    "value": $scope.newValue,
                    "history": $scope.selectedType,
                    "parent": 0
                };
            $http.post($scope.addURL,param)
                .then(function(response) {
                    if(response.data === 'false'){
                        alert('Sorry! error in saving');
                        $scope.cancelField();
                    }else{
                        $scope.fieldList.push({
                                medical_detail_field_id : response.data,
                                name : $scope.newValue,
                                medical_history_type_id : $scope.selectedType,
                                parent : 0,
                        });
                        $scope.cancelField();
                    }
            });
            
        }else{
            $scope.cancelField();
        }
    };
    
    // edit type
    $scope.editFieldData = function(index){
        //$scope.disabledList[index] = !$scope.disabledList[index];
        if($scope.fieldList[index].name !== ""){
            $scope.loader = true;
            var param = {
                    "id": $scope.fieldList[index].medical_detail_field_id,
                    "value": $scope.fieldList[index].name
                };
            $http.post($scope.updateURL,param)
                .then(function(response) {
                    if(response.data === 'false'){
                        alert('Sorry! error in saving');
                        $scope.loader = false;
                    }else{
                        $scope.disabledList[index] = !$scope.disabledList[index];
                        $scope.loader = false;
                    }
            });
            
        }else{
            alert('Can\'t empty name field.');
            $scope.loader = false;
        }
    };
    
    // cancel add
    $scope.cancelField = function(){
        $scope.addMore = !$scope.addMore;
        $scope.newValue = '';
        $scope.loader = false;
    };
    
    // cancel add
    $scope.viewSubFields = function(index){

        $scope.loader = true;
        var param = {"id": $scope.fieldList[index].medical_detail_field_id};
        $http.post($scope.viewURL,param)
            .then(function(response) {
                if(response.data === 'false'){
                    alert('Sorry! error in loading');
                    $scope.loader = false;
                }else{
                    $scope.detail_data = response.data;
                    $scope.detail_data_index = index;
                    $scope.disabledSubList = [];
                    for(var i=0;i<$scope.detail_data.length;i++){
                        $scope.disabledSubList.push(false);
                    }
                    $scope.loader = false;
                    $('#fieldDetail').modal('show');
                }
        });
    };
    

    // add new type
    $scope.addSubFieldData = function(){
        if($scope.newSubValue !== ""){
            
            $scope.inner_loader = true;
            var param = {
                    "value": $scope.newSubValue,
                    "history": $scope.selectedType,
                    "parent": $scope.fieldList[$scope.detail_data_index].medical_detail_field_id,
                };
                
            $http.post($scope.addURL,param)
                .then(function(response) {
                    if(response.data === 'false'){
                        alert('Sorry! error in saving');
                        $scope.cancelSubField();
                    }else{
                        $scope.detail_data.push({
                                medical_detail_field_id : response.data,
                                name : $scope.newSubValue,
                                medical_history_type_id : $scope.selectedType,
                                parent : $scope.fieldList[$scope.detail_data_index].medical_detail_field_id,
                        });
                        $scope.cancelSubField();
                    }
            });
        }else{
            $scope.cancelSubField();
        }
    };
    
    // edit type
    $scope.editSubFieldData = function(index){
        //$scope.disabledList[index] = !$scope.disabledList[index];
        if($scope.fieldList[index].name !== ""){
            $scope.inner_loader = true;
            var param = {
                    "id": $scope.detail_data[index].medical_detail_field_id,
                    "value": $scope.detail_data[index].name
                };
            $http.post($scope.updateURL,param)
                .then(function(response) {
                    if(response.data === 'false'){
                        alert('Sorry! error in saving');
                        $scope.inner_loader = false;
                    }else{
                        $scope.disabledSubList[index] = !$scope.disabledSubList[index];
                        $scope.inner_loader = false;
                    }
            });
            
        }else{
            alert('Can\'t empty name field.');
            $scope.inner_loader = false;
        }
    };
    
    // cancel add
    $scope.cancelSubField = function(){
        $scope.addMoreSub = !$scope.addMoreSub;
        $scope.newSubValue = '';
        $scope.inner_loader = false;
    };
    

});

// pagination
angular.module('typeModule').filter('pagination', function()
{
    return function(input, start)
    {
        start = +start;
        return input.slice(start);
    };
});