// angular js page functionality
var app = angular.module('visitModule',['ngSanitize']);
app.controller('visitCtrl', function($scope,$http) {
    $scope.init=function(question){
        
        // varialbes
        
        $scope.loader = false;
        
        $scope.questionList = question;
        
        
        $scope.section = 0;
        
        
        $scope.addURL = "";
        $scope.updateURL = "";
        
        
        // pagination
        $scope.curPage = 0;
        $scope.pageSize = 10;
        $scope.numberOfPages = function() {
				return Math.ceil($scope.questionList.length / $scope.pageSize);
                    };

    };
    
    $scope.sectionChange = function(index){
        $scope.section = index;
    };
    
    $scope.addVisitData = function(index){
        //alert($scope.questionList[0].list[0].answer);
    };

    $scope.reportFormat = function(){
                
        for(var i=0;i<$scope.questionList.length;i++){
            $scope.report += '<h4><i class="fa fa-question-circle margin-right-10"></i>'+ $scope.questionList[i].name +'</h4>';
            var list = $scope.questionList[i].list;
            for(var j=0;j<list.length;j++){
                $scope.report += '<p><i class="fa fa-check margin-right-10 margin-left-10"></i>' + list[j].question + ' : ' + list[j].answer + '</p>';
            }
            
            if(list.length == 0){
                $scope.report += '<p>Empty</p>';
            }
        }
        
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
                        $scope.questionList.push({
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
        if($scope.questionList[index].name !== ""){
            $scope.loader = true;
            var param = {
                    "id": $scope.questionList[index].medical_history_id,
                    "value": $scope.questionList[index].name
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
    
    $scope.parentFilter = function(item){
        if(item.parent == 0 && item.medical_history_type_id == $scope.questionList[$scope.section].medical_history_id)
            return true;
        return false;
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