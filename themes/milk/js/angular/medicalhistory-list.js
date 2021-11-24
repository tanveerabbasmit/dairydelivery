// angular js page functionality
var app = angular.module('typeModule', []);
app.controller('typeCtrl', function($scope,$http) {
    $scope.init=function(types,add,update){
        
        // varialbes
        $scope.sortType     = 'typeSort'; // set the default sort type
        $scope.sortReverse  = false;  // set the default sort order
        $scope.search   = '';     // set the default search/filter term
        
        $scope.loader = false;
        
        $scope.typeList = types;
        $scope.disabledList = [];
        $scope.addURL = add;
        $scope.updateURL = update;
        $scope.newValue = "";
        for(var i=0;i<$scope.typeList.length;i++){
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
    $scope.addTypeData = function(){
        if($scope.newValue !== ""){
            $scope.loader = true;
            var param = {"value": $scope.newValue};
            $http.post($scope.addURL,param)
                .then(function(response) {
                    if(response.data === 'false'){
                        alert('Sorry! error in saving');
                        $scope.cancelType();
                    }else{
                        $scope.typeList.push({
                                medical_history_id : response.data,
                                name : $scope.newValue
                        });
                        $scope.cancelType();
                    }
            });
        }else{
            $scope.cancelType();
        }
        
    };
    
    // edit type
    $scope.editTypeData = function(index){
        if($scope.typeList[index].name !== ""){
            $scope.loader = true;
            var param = {
                    "id": $scope.typeList[index].medical_history_id,
                    "value": $scope.typeList[index].name
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
    $scope.cancelType = function(){
        $scope.addMore = !$scope.addMore;
        $scope.newValue = '';
        $scope.loader = false;
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