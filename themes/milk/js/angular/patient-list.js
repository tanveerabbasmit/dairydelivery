// angular js page functionality
var app = angular.module('patientModule', []);
app.controller('patientCtrl', function($scope,$http) {
    $scope.init=function(patients,urls){
        
        // varialbes
        $scope.sortType     = 'patientSort'; // set the default sort type
        $scope.sortReverse  = false;  // set the default sort order
        $scope.search   = '';     // set the default search/filter term
        
        $scope.patientList = patients;
        $scope.section = 1;
        $scope.detailPart = 1;
        $scope.addPatient;
        $scope.resetAddFields();
        
        $scope.addURL = urls[0];
        $scope.updateURL = urls[2];
        $scope.deleteURL = urls[3];
        $scope.visitUrl = urls[4];


        // pagination
        $scope.curPage = 0;
        $scope.pageSize = 10;
        $scope.numberOfPages = function() {
				return Math.ceil($scope.patientList.length / $scope.pageSize);
                    };

    };
    
    // add new patient
    $scope.addPatientData = function(){

        var param = $scope.returnAddFields();
        
        $http.post($scope.addURL,param)
            .then(function(response) {
                if(response.data == 'false'){
                    alert('Sorry! error in saving');
                }else{

                    $scope.section = 1;
                    $scope.addDataToList();
                }
        });
        
        
        
    };
    
    $scope.AddVisit = function(id){
        
        
    }
    
    // add new patient to current list
    $scope.addDataToList = function(){
        $scope.patientList.push({
            patient_first_name : $scope.addPatient.first_name,
            patient_last_name : $scope.addPatient.last_name,
            patient_father_name : $scope.addPatient.father_name,
            patient_gender : $scope.addPatient.gender,
            patient_nic : $scope.addPatient.nic,
            patient_martial_status : $scope.addPatient.martial_status,
            patient_dob : $scope.addPatient.dob,
            patient_phone_mobile : $scope.addPatient.phone_mobile,
            patient_phone_home : $scope.addPatient.phone_home,
            patient_phone_emergency : $scope.addPatient.phone_emergency,
            patient_email : $scope.addPatient.email,
            patient_country : $scope.addPatient.country,
            patient_city : $scope.addPatient.city,
            patient_address : $scope.addPatient.address,
            patient_pic : 'avatar.png'
        });
        $scope.resetAddFields();
    };
    
    // currntly selected patient for detail
    $scope.updateSelectedPatient = function(index){
        $scope.selectedPatient = index;
    };
    
    // reset fields that used in add form
    $scope.resetAddFields = function(){
        $scope.addPatient = {
            "first_name" : "",
            "last_name" : "",
            "father_name" : "",
            "gender" : "Male",
            "cnic" : "",
            "martial_status" : "Single",
            "dob" : "",
            "phone_mobile" : "",
            "phone_home" : "",
            "phone_emergency" : "",
            "email" : "",
            "country" : "",
            "city" : "",
            "address" : "",
            "pic" : ""
        };
    };
    
    // return add field's data
    $scope.returnAddFields = function(){
        var param = {
            "first_name" : $scope.addPatient.first_name,
            "last_name" : $scope.addPatient.last_name,
            "father_name" : $scope.addPatient.father_name,
            "gender" : $scope.addPatient.gender,
            "cnic" : $scope.addPatient.cnic,
            "martial_status" : $scope.addPatient.martial_status,
            "dob" : $scope.addPatient.dob,
            "phone_mobile" : $scope.addPatient.phone_mobile,
            "phone_home" : $scope.addPatient.phone_home,
            "phone_emergency" : $scope.addPatient.phone_emergency,
            "email" : $scope.addPatient.email,
            "country" : $scope.addPatient.country,
            "city" : $scope.addPatient.city,
            "address" : $scope.addPatient.address,
            "pic" : ""
        };
        
        return param;
    };

});

// pagination
angular.module('patientModule').filter('pagination', function()
{
    return function(input, start)
    {
        start = +start;
        return input.slice(start);
    };
});